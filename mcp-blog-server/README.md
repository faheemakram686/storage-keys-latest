# StorageKeys MCP Blog Server

Claude/Cursor MCP tools that create and list blogs in the Laravel `blogs` table.

## Cloud attach (production)

1. Deploy branch `storagekyes-mcp-server` to the live server (merge/pull + deploy).
2. On **live** `.env` add:

```env
MCP_BLOG_TOKEN=your-long-random-secret
```

Use the **same** token in local Cursor MCP config.

3. On live server after deploy:

```bash
php artisan config:clear
php artisan route:clear
```

4. Confirm endpoint (should be `401` without token, not `404`):

`GET https://storagekeys.com/api/mcp/blogs`

5. Local Cursor `.mcp.json` / `mcp-blog-server/.env`:

```env
BLOG_API_BASE_URL=https://storagekeys.com/api/mcp
MCP_BLOG_TOKEN=same-as-live
```

6. Restart Cursor / reload MCP, then ask Claude to create a **draft** blog.

## Security

- Never commit real tokens (`.mcp.json` and `mcp-blog-server/.env` are gitignored)
- MCP creates **drafts** by default (`status=0`) — publish from admin after review
- Token required on every request (`Authorization: Bearer ...` or `X-MCP-Token`)

## Local setup (optional)

```bash
cd mcp-blog-server
cp .env.example .env
npm install
npm run test:api
```
