<?php

namespace Tests\Feature\Mcp;

use App\Models\ContentRevision;
use App\Models\Page;

class SafetyToolsTest extends McpTestCase
{
    public function test_new_tools_are_listed_next_to_the_old_ones(): void
    {
        $names = array_column($this->rpc('tools/list')->json('result.tools'), 'name');

        foreach (['list_blogs', 'create_page', 'update_page', 'activity_log', 'list_trash', 'restore_item', 'purge_item', 'clear_cache'] as $tool) {
            $this->assertContains($tool, $names);
        }
        $this->assertSame(count($names), count(array_unique($names)), 'Tool names must be unique');
    }

    public function test_page_writes_through_old_tools_are_logged_with_before_and_after(): void
    {
        $created = $this->ok('create_page', ['title' => 'Log Test', 'slug' => 'mcp-log-test', 'content' => '<p>First version of content</p>']);
        $id = $created['page']['id'];
        $this->ok('update_page', ['slug' => 'mcp-log-test', 'content' => '<p>Second version of content</p>']);

        $log = $this->ok('activity_log', ['entity_type' => 'page', 'entity_id' => $id]);
        $this->assertSame(['updated', 'created'], array_column($log['entries'], 'event'));
        $update = $log['entries'][0];
        $this->assertSame('mcp', $update['source']);
        $this->assertSame('update_page', $update['tool']);
        $this->assertSame('<p>First version of content</p>', $update['changes']['content']['before']);
        $this->assertSame('<p>Second version of content</p>', $update['changes']['content']['after']);
    }

    public function test_admin_side_saves_are_logged_as_admin_or_console(): void
    {
        $page = $this->makePage();
        $page->title = 'Renamed';
        $page->save();

        $rev = ContentRevision::where('entity_type', 'page')->where('entity_id', $page->id)->latest('id')->first();
        $this->assertSame('updated', $rev->event);
        $this->assertSame(['title'], $rev->changed);
        $this->assertNotSame('mcp', $rev->source);
    }

    public function test_two_writes_in_the_same_second_get_different_updated_at(): void
    {
        $page = $this->makePage();
        $first = (string) $page->fresh()->getRawOriginal('updated_at');
        $page->title = 'Again';
        $page->save();

        $this->assertNotSame($first, (string) $page->fresh()->getRawOriginal('updated_at'));
    }

    public function test_delete_goes_to_trash_and_restore_brings_it_back(): void
    {
        $page = $this->makePage(['status' => 1]);
        $this->ok('delete_page', ['slug' => $page->slug, 'confirm' => true]);

        $trash = $this->ok('list_trash', ['type' => 'page']);
        $this->assertContains($page->id, array_column($trash['items'], 'id'));

        $restored = $this->ok('restore_item', ['type' => 'page', 'id' => $page->id]);
        $this->assertSame($page->id, $restored['id']);
        $fresh = $page->fresh();
        $this->assertSame(0, (int) $fresh->is_deleted);
        $this->assertSame(1, (int) $fresh->getRawOriginal('status'));

        $events = ContentRevision::where('entity_type', 'page')->where('entity_id', $page->id)->orderBy('id')->pluck('event')->all();
        $this->assertSame(['created', 'trashed', 'restored'], $events);
    }

    public function test_restore_refuses_items_not_in_trash(): void
    {
        $page = $this->makePage();
        $data = $this->fails('restore_item', ['type' => 'page', 'id' => $page->id]);
        $this->assertStringContainsString('not in the trash', $data['message']);
    }

    public function test_purge_needs_confirm_and_trash_and_keeps_history(): void
    {
        $page = $this->makePage();

        $this->assertStringContainsString('confirm=true', $this->fails('purge_item', ['type' => 'page', 'id' => $page->id])['message']);
        $this->assertStringContainsString('Only items in the trash', $this->fails('purge_item', ['type' => 'page', 'id' => $page->id, 'confirm' => true])['message']);

        $page->is_deleted = 1;
        $page->save();
        $this->ok('purge_item', ['type' => 'page', 'id' => $page->id, 'confirm' => true]);

        $this->assertNull(Page::find($page->id));
        $purged = ContentRevision::where('entity_type', 'page')->where('entity_id', $page->id)->latest('id')->first();
        $this->assertSame('purged', $purged->event);
        $this->assertSame($page->slug, $purged->before['slug']);
    }

    public function test_unknown_type_is_refused(): void
    {
        $this->assertStringContainsString('Unknown type', $this->fails('list_trash', ['type' => 'nope'])['message']);
    }

    public function test_clear_cache(): void
    {
        $data = $this->ok('clear_cache');
        $this->assertContains('views', $data['cleared']);
        $this->fails('clear_cache', ['url' => 'https://example.com/']);
    }
}
