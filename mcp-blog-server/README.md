# StorageKeys MCP Blog Server

Claude/Cursor MCP tools that create and list blogs in the Laravel `blogs` table.

## Setup

1. Add to Laravel `.env`:

```env
MCP_BLOG_TOKEN=your-long-random-secret
```

2. Copy env for this server:

```bash
cd mcp-blog-server
cp .env.example .env
```

Edit `.env`:

```env
BLOG_API_BASE_URL=http://localhost/storage-keys-latest/public/api/mcp
MCP_BLOG_TOKEN=your-long-random-secret
```

3. Install deps:

```bash
npm install
```

4. Project root `.mcp.json` is already configured for Cursor.

5. Restart Cursor / reload MCP, then ask:

> Create a draft blog about climate-controlled storage in the UAE

## Tools

- `create_blog` — title, description, optional status/image_url/slug
- `list_blogs` — recent blogs

Drafts use `status=0` by default.
