{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($staticPages as $page)
    <url>
        <loc>{{ $base }}{{ $page === '/' ? '/' : $page }}</loc>
    </url>
@endforeach
@foreach ($blogs as $blog)
    <url>
        <loc>{{ $base }}/blogs/{{ $blog->slug }}</loc>
        <lastmod>{{ optional($blog->updated_at ?? $blog->created_at)->toAtomString() }}</lastmod>
    </url>
@endforeach
</urlset>
