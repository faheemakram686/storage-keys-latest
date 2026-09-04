# StorageKeys MCP Blog Server

Claude/Cursor tools that create and list blogs in the Laravel `blogs` table.

## Claude.ai cloud connect (non-tech friendly)

After the **live site** has this branch deployed and `MCP_BLOG_TOKEN` set in live `.env`:

### 1) Confirm live MCP is up

Open (no token) — must be **401**, not **404**:

`https://storagekeys.com/api/mcp/blogs`

### 2) Add custom connector in Claude.ai

**Do not use Settings → General.** Use:

1. Open [claude.ai](https://claude.ai)
2. Go to **Customize → Connectors** (sometimes under the profile / customize menu, not Settings → General)
3. Click **+** → **Add custom connector**
4. **Name:** `StorageKeys Blog`
5. **Remote MCP server URL** (pick ONE):

**Option A — token in URL (easiest for Free/Pro):**

```text
https://storagekeys.com/api/mcp/claude/YOUR_MCP_BLOG_TOKEN
```

Replace `YOUR_MCP_BLOG_TOKEN` with the same value as live `.env` `MCP_BLOG_TOKEN`.

Set authentication to **None** (token is already in the URL).

**Option B — Bearer header** (if your Claude account shows Request headers):

```text
https://storagekeys.com/api/mcp
```

Auth: **None**, then Request headers → `authorization` = `Bearer YOUR_MCP_BLOG_TOKEN`

6. Click **Add**
7. In a new chat, open **+** → **Connectors** → enable **StorageKeys Blog**
8. Say: `Create a draft blog about climate-controlled storage in the UAE`

Claude will call `create_blog` (draft by default). Review in admin, then publish.

### Non-tech daily use

Only step 7–8. No Cursor, no localhost, no code.

---

## Deploy checklist (developer, once)

1. Deploy branch `storagekyes-mcp-server` to production.
2. Live `.env`:

```env
MCP_BLOG_TOKEN=your-long-random-secret
```

3. Clear config:

```bash
php artisan config:clear
php artisan route:clear
```

4. Confirm `GET https://storagekeys.com/api/mcp/blogs` → **401** (not 404).
5. Give the non-tech person the Claude connector URL from section above.

## Security

- Never commit real tokens
- Prefer drafts (`status=0`); publish from admin after review
- Token-in-URL is shared with Anthropic as the connector URL — treat it like a password; rotate if leaked

## Local Cursor MCP (optional)

```env
BLOG_API_BASE_URL=http://127.0.0.1:8080/storage-keys-latest/public/api/mcp
MCP_BLOG_TOKEN=same-as-local-laravel-env
```

```bash
cd mcp-blog-server
npm install
npm run test:api
```
