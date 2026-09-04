<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class McpBlogCheckToken extends Command
{
    protected $signature = 'mcp-blog:check-token';

    protected $description = 'Diagnose whether MCP_BLOG_TOKEN is visible to Laravel (does not print the token)';

    public function handle(): int
    {
        $envPath = base_path('.env');
        $this->line('base_path: ' . base_path());
        $this->line('.env exists: ' . (is_file($envPath) ? 'yes' : 'NO'));

        $servicesHasKey = array_key_exists('mcp_blog', config('services', []));
        $this->line('config/services.php mcp_blog key: ' . ($servicesHasKey ? 'yes' : 'MISSING — deploy latest branch'));

        $fromConfig = trim((string) config('services.mcp_blog.token', ''));
        $fromEnvHelper = trim((string) (env('MCP_BLOG_TOKEN') ?? ''));
        $fromGetenv = trim((string) (getenv('MCP_BLOG_TOKEN') ?: ''));
        $fromEnvSuperglobal = trim((string) ($_ENV['MCP_BLOG_TOKEN'] ?? ''));

        $lineInDotEnv = false;
        $lineBlank = false;
        if (is_file($envPath) && is_readable($envPath)) {
            foreach (file($envPath, FILE_IGNORE_NEW_LINES) as $line) {
                if (preg_match('/^\s*MCP_BLOG_TOKEN\s*=\s*(.*)$/', $line, $m)) {
                    $lineInDotEnv = true;
                    $val = trim($m[1], " \t\"'");
                    $lineBlank = ($val === '');
                    break;
                }
            }
        }

        $this->line('.env has MCP_BLOG_TOKEN line: ' . ($lineInDotEnv ? 'yes' : 'NO'));
        if ($lineInDotEnv) {
            $this->line('.env MCP_BLOG_TOKEN value blank: ' . ($lineBlank ? 'YES (problem)' : 'no'));
        }

        $this->line('config(services.mcp_blog.token): ' . ($fromConfig !== '' ? 'SET' : 'EMPTY'));
        $this->line('env(MCP_BLOG_TOKEN): ' . ($fromEnvHelper !== '' ? 'SET' : 'EMPTY'));
        $this->line('getenv(MCP_BLOG_TOKEN): ' . ($fromGetenv !== '' ? 'SET' : 'EMPTY'));
        $this->line('$_ENV[MCP_BLOG_TOKEN]: ' . ($fromEnvSuperglobal !== '' ? 'SET' : 'EMPTY'));

        $cached = is_file(base_path('bootstrap/cache/config.php'));
        $this->line('bootstrap/cache/config.php: ' . ($cached ? 'EXISTS (run php artisan config:clear)' : 'absent'));

        if ($lineInDotEnv && !$lineBlank && $fromConfig === '') {
            $this->warn('Token is in .env but config is EMPTY — run: php artisan config:clear');
        }

        if (!$lineInDotEnv) {
            $this->error('Add this line to the .env in base_path above, then config:clear:');
            $this->line('MCP_BLOG_TOKEN=your-long-random-secret');
        }

        return ($fromConfig !== '' || $fromGetenv !== '' || $fromEnvSuperglobal !== '') ? self::SUCCESS : self::FAILURE;
    }
}
