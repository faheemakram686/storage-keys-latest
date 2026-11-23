<?php

namespace Tests\Feature\Mcp;

use App\Models\ImageUploadLink;
use App\Models\MediaItem;
use App\Services\Mcp\ImageStore;
use Illuminate\Http\UploadedFile;

class ImageToolsTest extends McpTestCase
{
    /** @var list<string> */
    private array $paths = [];

    protected function tearDown(): void
    {
        foreach (MediaItem::query()->whereIn('path', $this->paths)->get() as $m) {
            ImageStore::deleteFiles($m);
        }
        foreach ($this->paths as $p) {
            @unlink(storage_path('app/public/' . $p));
            @unlink(storage_path('app/mcp-trash/' . $p));
        }
        parent::tearDown();
    }

    private function png(int $w = 300, int $h = 120): string
    {
        $im = imagecreatetruecolor($w, $h);
        imagefill($im, 0, 0, imagecolorallocate($im, 30, 120, 200));
        ob_start();
        imagepng($im);
        imagedestroy($im);

        return (string) ob_get_clean();
    }

    private function upload(array $args): array
    {
        $data = $this->ok('upload_image', $args);
        $this->paths[] = ltrim(substr($data['image']['path'], strlen('/storage/')), '/');

        return $data['image'];
    }

    public function test_base64_upload_becomes_resized_webp_with_img_html(): void
    {
        $img = $this->upload(['base64' => 'data:image/png;base64,' . base64_encode($this->png(3000, 600)), 'alt' => 'Blue test banner', 'filename' => 'My Banner.png']);

        $this->assertMatchesRegularExpression('#^/storage/uploads/mcp-images/my-banner-[a-z0-9]{6}\.webp$#', $img['path']);
        $this->assertSame(2000, $img['width']);
        $this->assertSame(400, $img['height']);
        $this->assertStringContainsString('alt="Blue test banner"', $img['html']);
        $file = storage_path('app/public/' . substr($img['path'], 9));
        $this->assertFileExists($file);
        $this->assertSame('image/webp', getimagesize($file)['mime']);
    }

    public function test_upload_validation(): void
    {
        $this->fails('upload_image', ['base64' => base64_encode($this->png())]); // no alt
        $this->fails('upload_image', ['alt' => 'x']); // neither url nor base64
        $this->fails('upload_image', ['alt' => 'x', 'base64' => base64_encode('<svg onload="alert(1)"></svg>')]);
        $this->fails('upload_image', ['alt' => 'x', 'base64' => 'not base64 !!!']);
    }

    public function test_private_addresses_are_refused(): void
    {
        foreach (['http://127.0.0.1/a.png', 'http://localhost/a.png', 'http://169.254.169.254/latest/meta-data', 'http://10.0.0.5/a.png', 'ftp://example.com/a.png'] as $url) {
            $this->fails('upload_image', ['alt' => 'x', 'url' => $url]);
        }
    }

    public function test_update_image_alt_and_title(): void
    {
        $img = $this->upload(['base64' => base64_encode($this->png()), 'alt' => 'Old alt']);
        $this->fails('update_image', ['path' => $img['path'], 'alt' => 'New', 'expected_updated_at' => '2000-01-01 00:00:00']);

        $data = $this->ok('update_image', ['path' => $img['url'], 'alt' => 'New alt', 'title' => 'Facility']);
        $this->assertSame('New alt', $data['image']['alt']);
        $this->assertSame('Facility', $data['image']['title']);
    }

    public function test_delete_refuses_used_image_then_trash_restore_purge(): void
    {
        $img = $this->upload(['base64' => base64_encode($this->png()), 'alt' => 'Used image']);
        $page = $this->makePage(['status' => 1, 'content' => '<p>x</p>' . $img['html']]);
        $file = storage_path('app/public/' . substr($img['path'], 9));

        $refused = $this->fails('delete_image', ['path' => $img['path']]);
        $this->assertContains('page /' . $page->slug, $refused['used_in']);

        $page->content = '<p>x</p>';
        $page->save();
        $this->ok('delete_image', ['path' => $img['path']]);
        $this->assertFileDoesNotExist($file);
        $this->assertContains($img['id'], array_column($this->ok('list_trash', ['type' => 'image'])['items'], 'id'));

        $this->ok('restore_item', ['type' => 'image', 'id' => $img['id']]);
        $this->assertFileExists($file);

        $this->ok('delete_image', ['path' => $img['path']]);
        $this->ok('purge_item', ['type' => 'image', 'id' => $img['id'], 'confirm' => true]);
        $this->assertNull(MediaItem::find($img['id']));
        $this->assertFileDoesNotExist(storage_path('app/mcp-trash/' . substr($img['path'], 9)));
    }

    public function test_force_delete_of_used_image(): void
    {
        $img = $this->upload(['base64' => base64_encode($this->png()), 'alt' => 'Forced']);
        $this->makePage(['content' => $img['html']]);
        $data = $this->ok('delete_image', ['path' => $img['path'], 'force' => true]);
        $this->assertArrayHasKey('warning', $data);
    }

    public function test_one_time_upload_link(): void
    {
        $link = $this->ok('create_image_upload_link', ['purpose' => 'Photos of the Al Quoz facility', 'alt' => 'Al Quoz facility', 'max_files' => 2]);
        $path = parse_url($link['url'], PHP_URL_PATH);

        $this->get($path)->assertOk()->assertSee('Photos of the Al Quoz facility')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/mcp-upload/' . str_repeat('a', 48))->assertNotFound();
        $this->assertSame('waiting', $this->ok('get_image_upload_link', ['link_id' => $link['link_id']])['status']);

        $this->post($path, [
            'images' => [UploadedFile::fake()->image('one.jpg', 800, 600), UploadedFile::fake()->image('two.png', 400, 400)],
            'alts' => ['Front gate', 'Corridor'],
        ])->assertOk()->assertSee('All done');

        $status = $this->ok('get_image_upload_link', ['link_id' => $link['link_id']]);
        $this->assertSame('completed', $status['status']);
        $this->assertSame(['Front gate', 'Corridor'], array_column($status['images'], 'alt'));
        foreach ($status['images'] as $i) {
            $this->paths[] = substr($i['path'], 9);
        }

        // Used up: a further post adds nothing.
        $this->post($path, ['images' => [UploadedFile::fake()->image('three.jpg')], 'alts' => ['x']])->assertOk();
        $this->assertCount(2, ImageUploadLink::find($link['link_id'])->media_ids);
    }
}
