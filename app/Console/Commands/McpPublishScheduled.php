<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Services\Mcp\McpContext;
use Illuminate\Console\Command;

/**
 * Publishes blog drafts whose publish_at has passed (set with the MCP schedule_blog tool).
 * Runs every minute from the scheduler.
 */
class McpPublishScheduled extends Command
{
    protected $signature = 'mcp:publish-scheduled';

    protected $description = 'Publish blog posts scheduled with schedule_blog';

    public function handle(): int
    {
        $due = Blog::query()
            ->where('is_deleted', 0)
            ->where('status', 0)
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now())
            ->get();

        foreach ($due as $blog) {
            McpContext::as('schedule_blog', function () use ($blog) {
                McpContext::$source = 'scheduler';
                $blog->status = 1;
                $blog->publish_at = null;
                $blog->save();
            });
            $this->info('Published: ' . $blog->slug);
        }

        return self::SUCCESS;
    }
}
