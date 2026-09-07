# StorageKeys — MCP Blog Cloud Connect (Complete Docs)

Yeh document batata hai ke **MCP ko Claude.ai (cloud) se connect** karne ke liye kya banaya gaya, kaunse tools hain, kaunse steps hue, aur non-tech user rozana kaise use kare.

**Branch:** `storagekyes-mcp-server`  
**Live site:** `https://storagekeys.com`  
**Goal:** Claude.ai chat se draft blog DB (`blogs` table) mein insert ho, bina Cursor / localhost ke.

---

## 1. Architecture (simple)

```text
┌─────────────────┐     HTTPS + token      ┌──────────────────────────────┐     MySQL
│  Claude.ai      │ ─────────────────────► │  Laravel on storagekeys.com  │ ──────────► blogs
│  (cloud)        │   Streamable HTTP MCP  │  /api/mcp  (+ /blogs REST)   │
└─────────────────┘                        └──────────────────────────────┘

Optional (developers only):
┌─────────────────┐     stdio MCP          ┌──────────────────┐     HTTP      ┌─────────────┐
│  Cursor IDE     │ ─────────────────────► │ mcp-blog-server  │ ───────────► │ Local/Live  │
│                 │                        │ (Node, local)    │              │ Laravel API │
└─────────────────┘                        └──────────────────┘              └─────────────┘
```

### Important difference

| Client | Kaise connect hota hai | Localhost? |
|--------|------------------------|------------|
| **Cursor** | Local Node MCP (`stdio`) → Laravel API | Haan (local) ya live URL |
| **Claude.ai cloud** | Direct **remote MCP** URL on live Laravel | **Nahi** — public HTTPS chahiye |

Claude.ai Settings → General mein MCP **nahi** milta. Connector **Customize → Connectors** se add hota hai.

---

## 2. Tools (MCP)

Server name: `storagekeys-blog`

### Tool: `create_blog`

Blog create karta hai `blogs` table mein.

| Parameter | Required | Description |
|-----------|----------|-------------|
| `title` | Yes | Blog title (3–255 chars) |
| `description` | Yes | HTML/text body |
| `status` | No | `0` = draft (default), `1` = published |
| `image_url` | No | Public image URL (server download karta hai) |
| `slug` | No | Custom slug; warna title se auto |

**Default:** hamesha draft (`status=0`) unless user explicitly publish bole.

### Tool: `list_blogs`

Recent blogs list (newest first).

| Parameter | Required | Description |
|-----------|----------|-------------|
| `limit` | No | 1–50, default 10 |

---

## 3. Database schema (`blogs`)

| Column | Type | Notes |
|--------|------|--------|
| `id` | bigint | PK |
| `title` | string | Required |
| `image` | string | Filename under `storage/uploads/blog-images/` ya `empty` |
| `description` | text | HTML body |
| `slug` | text | Unique-ish; duplicates get `-1`, `-2` |
| `status` | bool/int | `0` draft, `1` active |
| `is_deleted` | bool | Soft delete flag |
| `created_at` / `updated_at` | timestamps | |

**Nahi hain:** `author_id`, `category`, `featured_image`, `tags` — cover = `image` field.

Public URL: `https://storagekeys.com/blogs/{slug}`

---

## 4. API endpoints (live)

Auth: `Authorization: Bearer <MCP_BLOG_TOKEN>` **ya** `X-MCP-Token: <token>`  
Path-token option (Claude connector): `/api/mcp/claude/{token}`

| Method | URL | Purpose |
|--------|-----|---------|
| `GET` | `/api/mcp/blogs?limit=10` | REST list |
| `POST` | `/api/mcp/blogs` | REST create |
| `POST` | `/api/mcp` | Streamable HTTP MCP (Claude.ai) |
| `POST` | `/api/mcp/claude/{MCP_BLOG_TOKEN}` | Same MCP, token in URL |

Config: `config/services.php` → `mcp_blog.token` ← `env('MCP_BLOG_TOKEN')`  
Middleware: `mcp.blog` (`VerifyMcpBlogToken`)  
Throttle: 60 requests / minute

### Expected HTTP status

| Situation | Status |
|-----------|--------|
| Token missing in live `.env` | **503** `MCP blog API token is not configured` |
| Token set, request without auth | **401** Unauthorized |
| Valid token + good body | **200** / **201** |
| Wrong URL / not deployed | **404** |

---

## 5. Code files (jo banaye / use hue)

### Laravel (live MCP + REST)

| File | Role |
|------|------|
| `app/Http/Controllers/Api/McpBlogController.php` | REST create/list blogs |
| `app/Http/Controllers/Api/McpStreamController.php` | Streamable HTTP MCP for Claude.ai (`initialize`, `tools/list`, `tools/call`) |
| `app/Http/Middleware/VerifyMcpBlogToken.php` | Bearer / header / path token auth |
| `app/Console/Commands/McpBlogCheckToken.php` | `php artisan mcp-blog:check-token` (SET/EMPTY, token print nahi) |
| `routes/api.php` | `/api/mcp`, `/api/mcp/claude/{token}`, `/api/mcp/blogs` |
| `config/services.php` | `mcp_blog.token` |
| `app/Http/Kernel.php` | alias `mcp.blog` |

### Cursor local MCP (optional)

| File | Role |
|------|------|
| `mcp-blog-server/src/index.js` | Node MCP tools (stdio) for Cursor |
| `mcp-blog-server/.env` | `BLOG_API_BASE_URL` + `MCP_BLOG_TOKEN` (gitignored) |
| `.mcp.json` | Cursor MCP client config (gitignored) |
| `.mcp.json.example` / `mcp-blog-server/.env.example` | Safe examples |

### Frontend (alag work, same branch pe)

| File | Role |
|------|------|
| `resources/views/ui/pages/blogs.blade.php` | Main blogs listing (featured + cards) |
| `public/sk-assets/css/frontend/blogs.css` | Blog card / grid styles |

---

## 6. Step-by-step: Cloud connect (jo actually hua)

### Phase A — Local pe pehle API + Cursor MCP

1. Laravel REST API banayi: `GET/POST /api/mcp/blogs` + token middleware.
2. Node MCP server (`mcp-blog-server`) banaya: tools `create_blog`, `list_blogs`.
3. Local XAMPP (port **8080**) pe test:
   - Base: `http://127.0.0.1:8080/storage-keys-latest/public/api/mcp`
4. Cursor `.mcp.json` se local MCP connect (stdio).
5. Local token sync: Laravel `.env` aur `mcp-blog-server/.env` mein **same** `MCP_BLOG_TOKEN`.

### Phase B — Samajh: cloud localhost nahi chala sakta

6. Claude.ai / cloud chat **127.0.0.1** tak nahi pahunch sakta.
7. Isliye alag **remote MCP** chahiye — public HTTPS.
8. Alag Node host (Railway etc.) ki jagah Laravel pe hi **Streamable HTTP MCP** add kiya taake ek deploy kaafi ho.

### Phase C — Live deploy + token

9. Branch `storagekyes-mcp-server` GitHub pe push.
10. Live server pe deploy (`/home/instamsgs/public_html/storagekeys.com`).
11. Pehle **404** tha (API deploy nahi thi) → deploy ke baad endpoint mila.
12. Phir **503** aaya: token live `.env` mein nahi tha / galat file.
13. Diagnostic: `php artisan mcp-blog:check-token` → dikhaya:
    - `base_path: /home/instamsgs/public_html/storagekeys.com`
    - `.env has MCP_BLOG_TOKEN line: NO`
14. **Sahi file** mein token add:
    `/home/instamsgs/public_html/storagekeys.com/.env`
    ```env
    MCP_BLOG_TOKEN=your-long-secret
    ```
15. `php artisan config:clear`
16. Verify: bina token **401**, token ke sath **200** (blogs + MCP initialize + tools/list).

### Phase D — Claude.ai custom connector

17. Claude.ai → **Customize → Connectors** (Settings → General **nahi**).
18. Add custom connector:
    - Name: `StorageKeys Blog`
    - URL:
      ```text
      https://storagekeys.com/api/mcp/claude/YOUR_MCP_BLOG_TOKEN
      ```
    - Auth: **None** (token URL mein hai)
19. Chat → **+** → Connectors → enable `StorageKeys Blog`.
20. Prompt: draft blog create.

### Phase E — Bug fix (validation error)

21. Claude `create_blog` pe “title/description required” error de raha tha.
22. Cause: MCP request ke `Content-Type: application/json` headers copy ho rahe the, body empty → Laravel fields nahi dekh raha tha.
23. Fix: `McpStreamController` mein fresh JSON request banaya (parent headers copy mat karo).
24. Fix push + live redeploy ke baad create kaam karta hai.

### Temporary testing (optional, pehle use hua)

- Cloudflare quick tunnel (`trycloudflare.com`) se local XAMPP temporarily public kiya tha taake deploy se pehle Claude test ho sake.
- Permanent setup = **live** URL only.

---

## 7. Non-tech daily use (sirf yeh)

1. [claude.ai](https://claude.ai) kholo.
2. Connector **StorageKeys Blog** ON ho.
3. Likho, e.g.:

   > Create a draft blog about climate-controlled storage in the UAE. Use HTML paragraphs and H2s. Do not publish (status 0).

4. Claude `create_blog` call karega.
5. Admin panel se review → status Active / publish.
6. Site pe: `https://storagekeys.com/blogs`

Unko code, Cursor, token edit — **rozana nahi** chahiye (token sirf connector setup mein ek baar).

---

## 8. Developer checklist (naya server / rotate token)

```bash
cd /home/instamsgs/public_html/storagekeys.com

# 1) .env
# MCP_BLOG_TOKEN=....

# 2) Clear config
php artisan config:clear
php artisan route:clear

# 3) Diagnose (token print nahi hota)
php artisan mcp-blog:check-token
# Expect: SET, .env line yes

# 4) Smoke test (browser / curl)
# GET https://storagekeys.com/api/mcp/blogs  → 401
```

Claude connector URL update (agar token change ho):

```text
https://storagekeys.com/api/mcp/claude/NEW_TOKEN
```

Purana connector hata ke naya add karo (Claude auth settings edit allow nahi karta easily).

---

## 9. Cursor local (developers)

`mcp-blog-server/.env`:

```env
BLOG_API_BASE_URL=https://storagekeys.com/api/mcp
MCP_BLOG_TOKEN=same-as-live
```

Ya local:

```env
BLOG_API_BASE_URL=http://127.0.0.1:8080/storage-keys-latest/public/api/mcp
MCP_BLOG_TOKEN=same-as-local-laravel
```

```bash
cd mcp-blog-server
npm install
npm run test:api
```

Cursor restart / MCP reload ke baad tools dikhne chahiye.

---

## 10. Security rules

- Real `MCP_BLOG_TOKEN` **kabhi commit mat karo** (`.env`, `.mcp.json` gitignored).
- MCP default **draft** banaye; publish admin se.
- Token-in-URL Anthropic ko connector ke tor pe nazar aata hai — password jaisa treat karo; leak pe rotate.
- Public mein token mat paste karo (chats, screenshots, docs examples).

---

## 11. Troubleshooting

| Symptom | Likely cause | Fix |
|---------|--------------|-----|
| `404` on `/api/mcp/blogs` | Code live pe nahi | Branch deploy |
| `503` token not configured | Wrong `.env` path / blank / config cache | `mcp-blog:check-token` + sahi `.env` + `config:clear` |
| `401` with token | Token mismatch | Live aur connector URL same token |
| Validation title/description required | Old Stream controller bug | Latest `McpStreamController` deploy |
| Claude mein tools nahi | Connector off / Settings pe dhunda | Customize → Connectors; chat + menu ON |
| Cloud localhost fail | Expected | Live HTTPS MCP use karo |

---

## 12. Example Claude prompt (copy-paste)

```text
Use the StorageKeys Blog connector.
Create 1 draft blog (status=0) about "Climate-Controlled Storage in the UAE".
Write SEO-friendly HTML (p, h2, ul) around 800–1000 words.
Then list_blogs(limit=5) and reply with id, slug, and public URL.
Do not set status=1 unless I ask to publish.
```

---

## 13. Related short README

Quick attach notes: `mcp-blog-server/README.md`

---

*Last updated: Sep 2026 — covers Laravel Streamable HTTP MCP + Claude.ai custom connector + Cursor stdio MCP.*
