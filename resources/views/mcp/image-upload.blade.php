{{-- One-time image upload page (MCP create_image_upload_link). Standalone: no site header, not indexed. --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Upload images | StorageKeys</title>
    <style>
        :root { --bg: #f5f6f8; --card: #fff; --text: #1d2433; --muted: #5d6678; --line: #dde1e8; --brand: #0b5ed7; --ok: #157347; --bad: #b02a37; }
        @media (prefers-color-scheme: dark) { :root { --bg: #14171c; --card: #1d2128; --text: #e9ecf1; --muted: #a4acb9; --line: #343a45; --brand: #5b9cff; --ok: #4cc38a; --bad: #ff6b78; } }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--bg); color: var(--text); font: 16px/1.5 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; }
        main { max-width: 560px; margin: 0 auto; padding: 32px 16px; }
        .card { background: var(--card); border: 1px solid var(--line); border-radius: 12px; padding: 24px; }
        h1 { font-size: 1.35rem; margin: 0 0 4px; }
        p { margin: 0 0 16px; color: var(--muted); }
        label { display: block; font-weight: 600; margin: 16px 0 6px; }
        input[type=text], input[type=file] { width: 100%; padding: 10px; border: 1px solid var(--line); border-radius: 8px; background: var(--bg); color: var(--text); font: inherit; }
        button { margin-top: 20px; width: 100%; padding: 12px; border: 0; border-radius: 8px; background: var(--brand); color: #fff; font: inherit; font-weight: 600; cursor: pointer; }
        .msg { padding: 10px 12px; border-radius: 8px; margin-bottom: 12px; border: 1px solid; }
        .ok { color: var(--ok); border-color: var(--ok); }
        .bad { color: var(--bad); border-color: var(--bad); }
        .hint { font-size: .9rem; color: var(--muted); margin-top: 4px; }
    </style>
</head>
<body>
<main>
    <div class="card">
        <h1>Upload images</h1>
        <p>{{ $link->purpose }}</p>

        @foreach ($done as $name)
            <div class="msg ok">Uploaded: {{ $name }}</div>
        @endforeach
        @foreach ($problems as $problem)
            <div class="msg bad">{{ $problem }}</div>
        @endforeach

        @if ($link->status() === 'completed')
            <div class="msg ok">All done. You can close this page.</div>
        @elseif ($link->status() === 'expired')
            <div class="msg bad">This upload link has expired. Ask for a new one.</div>
        @else
            <form method="post" enctype="multipart/form-data" action="{{ url('/mcp-upload/' . $token) }}">
                @csrf
                @for ($i = 0; $i < $link->remaining(); $i++)
                    <label for="image-{{ $i }}">Image {{ $link->remaining() > 1 ? $i + 1 : '' }}</label>
                    <input id="image-{{ $i }}" type="file" name="images[{{ $i }}]" accept="image/jpeg,image/png,image/webp,image/gif" {{ $i === 0 ? 'required' : '' }}>
                    <label for="alt-{{ $i }}">What does it show?</label>
                    <input id="alt-{{ $i }}" type="text" name="alts[{{ $i }}]" value="{{ $link->alt }}" maxlength="255" {{ $i === 0 ? 'required' : '' }}>
                    <div class="hint">A short description, e.g. "Climate-controlled storage units in Al Quoz".</div>
                @endfor
                <button type="submit">Upload</button>
            </form>
            <p class="hint" style="margin-top:12px">JPEG, PNG, WebP or GIF up to 10 MB each. Images are converted to WebP.</p>
        @endif
    </div>
</main>
</body>
</html>
