<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UrlRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class McpRedirectController extends Controller
{
    public function index(Request $request)
    {
        $limit = min(100, max(1, (int) $request->query('limit', 50)));
        $items = UrlRedirect::query()->orderByDesc('id')->limit($limit)->get();

        return response()->json([
            'success' => true,
            'count' => $items->count(),
            'redirects' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_path' => 'required|string|max:512',
            'to_url' => 'required|string|max:2048',
            'status_code' => 'nullable|in:301,302,307,308',
            'is_active' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $from = UrlRedirect::normalizePath($request->input('from_path'));
        $to = trim($request->input('to_url'));
        if (!preg_match('#^https?://#i', $to) && str_starts_with($to, '/')) {
            $to = url($to);
        }

        $redirect = UrlRedirect::query()->updateOrCreate(
            ['from_path' => $from],
            [
                'to_url' => $to,
                'status_code' => (int) ($request->input('status_code') ?: 301),
                'is_active' => $request->has('is_active')
                    ? filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN)
                    : true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Redirect saved',
            'redirect' => $redirect,
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $confirm = filter_var($request->input('confirm', false), FILTER_VALIDATE_BOOLEAN)
            || $request->input('confirm') === 1
            || $request->input('confirm') === '1';
        if (!$confirm) {
            return response()->json(['success' => false, 'message' => 'delete_redirect requires confirm=true.'], 422);
        }

        $redirect = UrlRedirect::query()->find($id);
        if (!$redirect) {
            return response()->json(['success' => false, 'message' => 'Redirect not found.'], 404);
        }
        $redirect->delete();

        return response()->json(['success' => true, 'message' => 'Redirect deleted', 'id' => (int) $id]);
    }
}
