<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Phase 4 stubs: return clear "not configured" until GA4/GSC credentials exist.
 * Does not affect frontend. Safe to deploy without Google setup.
 */
class McpAnalyticsController extends Controller
{
    public function summary(Request $request)
    {
        return $this->notConfigured('get_analytics_summary', [
            'needed' => ['GA4_PROPERTY_ID', 'GOOGLE_APPLICATION_CREDENTIALS or GA service account JSON'],
            'period' => $request->query('period', '28d'),
        ]);
    }

    public function topPages(Request $request)
    {
        return $this->notConfigured('get_top_pages', [
            'needed' => ['GA4_PROPERTY_ID', 'Google service account with Analytics read'],
            'limit' => (int) $request->query('limit', 10),
        ]);
    }

    public function searchQueries(Request $request)
    {
        return $this->notConfigured('get_search_queries', [
            'needed' => ['GSC_SITE_URL (e.g. https://storagekeys.com/)', 'Google Search Console API access'],
            'limit' => (int) $request->query('limit', 25),
        ]);
    }

    private function notConfigured(string $tool, array $extra = [])
    {
        $configured = $this->isConfigured();

        if ($configured) {
            // Placeholder for future real implementation — keep contract stable.
            return response()->json(array_merge([
                'success' => false,
                'tool' => $tool,
                'message' => 'Analytics credentials detected but live GA/GSC fetch is not wired yet. Contact developer to enable API clients.',
                'configured' => true,
            ], $extra), 501);
        }

        return response()->json(array_merge([
            'success' => false,
            'tool' => $tool,
            'message' => 'Analytics not configured. Add Google credentials on the server to enable this tool later.',
            'configured' => false,
            'setup_hint' => 'Set GA4_PROPERTY_ID / GSC_SITE_URL and a service account in live .env, then extend McpAnalyticsController.',
        ], $extra), 503);
    }

    private function isConfigured(): bool
    {
        return (bool) (
            env('GA4_PROPERTY_ID')
            || env('GSC_SITE_URL')
            || env('GOOGLE_APPLICATION_CREDENTIALS')
        );
    }
}
