# MCP Expansion Plan — Tools & SEO Skills

**Audience:** Product / SEO partner requesting more Claude.ai MCP tools  
**Site:** StorageKeys (`storagekeys.com`) — Laravel + Blade (not WordPress)  
**Today:** MCP only has `create_blog` + `list_blogs`  
**Date:** Sep 2026

---

## 1. Reality check (important)

StorageKeys is **not** a full CMS like WordPress/Yoast.

| Area | Current state |
|------|----------------|
| Blog posts | Thin table: title, image, description, slug, status |
| SEO meta | Hard-coded in Blade per page; blog meta derived from title + excerpt |
| Schema JSON-LD | Not stored / not rendered from DB |
| Categories / tags | Do not exist for blogs |
| Media library | No; only blog cover uploads |
| Redirects manager | Does not exist |
| GA / GSC API | Verification meta only; no analytics API |
| Pages | Static Blade routes, not DB pages |

**So:** many requested tools need **new database + APIs first**, then MCP wrappers. Claude “skills” (keyword research, audits, etc.) can run in the model **without** tools — but tools are what let Claude **read/write the live site**.

---

## 2. Map: requested tools → feasibility

### Phase 1 — Quick wins (use existing blog CRUD) — ~1–2 weeks

| Tool | Feasibility | How |
|------|-------------|-----|
| `get_blog` | Easy | Return one blog by `id` or `slug` |
| `update_blog` | Easy | Wrap existing `BlogClass::updateBlog` |
| `delete_blog` | Easy | Soft-delete (`is_deleted=1`) |
| `bulk_update_posts` | Easy | Batch status/title/slug updates |
| `get_site_info` | Easy | base URL, timezone, blog URL pattern from config |
| `get_sitemap` | Easy | Proxy `/sitemap.xml` or regenerate list |
| `get_pages` | Easy | Return static marketing URLs (already in SitemapController) |
| `search_content` | Medium | SQL `LIKE` on blog title/description (later full-text) |
| `upload_media` | Medium | Upload/download image → `blog-images` + return URL |
| `list_media` | Medium | List files in `storage/uploads/blog-images/` (or new media table) |

**After Phase 1:** Claude can fully manage blog posts + see site URLs/sitemap. Still no real SEO DB fields.

---

### Phase 2 — SEO data layer (needed for meta/schema/taxonomy) — ~2–4 weeks

**New tables (recommended):**

1. `blog_seo` (or columns on `blogs`)
   - `meta_title`, `meta_description`, `canonical_url`, `robots`, `og_image`
2. `blog_schema` or `schema_json` column
   - JSON-LD blobs (Article, FAQPage, BreadcrumbList…)
3. `blog_categories`, `blog_tags`, pivots
4. `media_assets` (optional proper library)
   - path, alt_text, width, height, mime
5. `url_redirects`
   - from_path, to_url, status_code (301/302)

Then MCP tools:

| Tool | Depends on |
|------|------------|
| `get_seo_meta` / `update_seo_meta` | SEO columns + frontend reading them |
| `get_schema` / `update_schema` | Schema storage + Blade inject JSON-LD |
| `get_categories` / `create_category` | Category tables + UI optional |
| `get_tags` / `create_tag` | Tag tables |
| `get_redirects` / `create_redirect` | Redirects table + middleware |
| `list_media` / `upload_media` (proper) | Media table + alt text |

**Also required:** frontend layout must **output** stored meta/schema (today it mostly ignores DB for SEO).

---

### Phase 3 — Links, audits, technical SEO — ~3–6 weeks

| Tool | How |
|------|-----|
| `get_internal_links` | Crawl stored HTML / sitemap + parse `<a href>` into link graph |
| Broken link checks | Same crawl + HTTP HEAD/GET job queue |
| Cannibalization / thin / duplicate | Content similarity jobs over blogs + static pages |
| Sitemap / robots validation | Read `sitemap.xml` + `robots.txt`, report issues |
| Core Web Vitals / page speed | External APIs (PageSpeed Insights) — optional, rate-limited |
| Content refresh flags | `updated_at` / `last_reviewed_at` column + MCP list “stale” posts |

These are mostly **read/report** tools + background jobs, not simple CRUD.

---

### Phase 4 — Analytics (GSC / GA) — separate credentials

| Tool | Requirement |
|------|-------------|
| `get_analytics_summary` | Google Analytics 4 Data API + service account |
| `get_top_pages` | Same |
| `get_search_queries` | Google Search Console API + verified property |

**Not possible from DB alone.** Needs Google Cloud project, OAuth/service account, and storagekeys.com property access. Store secrets in live `.env` (never in MCP URL).

---

## 3. Skills vs tools (how the partner’s “capabilities” map)

Many items are **Claude skills/prompts** (reasoning), not new MCP endpoints:

| Capability | Needs MCP tool? | Approach |
|------------|-----------------|----------|
| Keyword research / clustering | No (or optional external SEO API) | Claude + optional DataForSEO/etc. |
| Topic calendar / content gaps | Partial | `list_blogs` + `get_pages` + competitor URLs in prompt |
| On-page audit | Yes (read) | `get_blog` + `get_seo_meta` + HTML |
| Internal linking strategy | Yes | `get_internal_links` + `search_content` |
| External link curation | No | Claude research; optional save notes table later |
| Featured image / alt text | Partial | `upload_media` + `update_seo_meta` / media alt |
| Meta title/description gen | Yes (write) | Claude writes → `update_seo_meta` |
| FAQ + schema gen | Yes (write) | Claude writes → `update_schema` |
| Cannibalization / thin content | Yes (read corpus) | `search_content` / audit jobs |
| Redirect management | Yes | `create_redirect` |
| Draft → review → publish | Partial | Extend `status` or add `workflow_status` |
| Local SEO (UAE NAP) | Partial | `get_site_info` + LocalBusiness schema |
| GSC/GA performance loop | Yes (Phase 4) | Analytics tools |
| CWV / page speed | Optional Phase 3 | PageSpeed API tool |

**Pattern:** Claude does strategy; MCP tools **persist and fetch** site truth.

---

## 4. Recommended build order (practical)

```text
Phase 1 — DONE (additive; create_blog / list_blogs unchanged)
  get_blog, update_blog, delete_blog (confirm=true)
  get_site_info, get_pages, get_sitemap
  search_content, upload_media, list_media
  bulk_update_posts (confirm=true)

Phase 2 — DONE (nullable SEO; frontend fallback)
  blogs columns: meta_title, meta_description, canonical_url, robots, schema_json
  get_seo_meta / update_seo_meta / get_schema / update_schema
  Blog detail uses stored meta/schema when set; else old title+excerpt behavior

Next (Phase 3)
  get_internal_links, audit/report tools, refresh flags

Later (Phase 4)
  GSC + GA tools (needs Google API access)
```

Do **not** promise full WordPress-level SEO MCP until Phase 2 schema + frontend rendering ship.

---

## 5. Security / workflow rules (keep for cloud)

- All new tools behind same `MCP_BLOG_TOKEN` (or scoped tokens later).
- Default writes = **draft**; publish only on explicit ask.
- `delete_blog` = soft-delete; hard-delete admin-only.
- Destructive tools (`delete`, `create_redirect`, `bulk_update`) may need confirm flag `confirm=true`.
- Never put Google API keys in Claude connector URL.

---

## 6. What to tell the third person (short reply)

> Today StorageKeys MCP can create/list blog drafts only. The site is Laravel Blade, not WordPress—so most SEO tools (meta DB, schema, categories, redirects, GSC) don’t exist yet.
>
> We can expand in phases:
> 1) Full blog CRUD + sitemap/pages/media/search (fast)
> 2) New SEO tables + MCP meta/schema/taxonomy/redirects (needed for real on-page SEO)
> 3) Link graph / audits
> 4) Google Search Console / Analytics APIs (needs Google access)
>
> Claude can already do keyword research, outlines, and audits as **skills**; MCP tools are for reading/writing the live site. Phase 1 + 2 are the right next investment.

---

## 7. Suggested first implementation sprint (if approved)

**Sprint A (MCP + API only, reuse BlogClass):**

1. `GET /api/mcp/blogs/{id}` → `get_blog`
2. `PATCH /api/mcp/blogs/{id}` → `update_blog`
3. `DELETE /api/mcp/blogs/{id}` → `delete_blog` (soft)
4. `GET /api/mcp/site-info` → `get_site_info`
5. `GET /api/mcp/pages` → `get_pages`
6. `GET /api/mcp/sitemap` → `get_sitemap`
7. Register same tools in `McpStreamController` for Claude.ai

**Sprint B (SEO foundation):**

1. Migration: add `meta_title`, `meta_description`, `schema_json` to `blogs` (simplest start)
2. Blog detail Blade uses those fields when set
3. MCP `get_seo_meta` / `update_seo_meta` / `get_schema` / `update_schema`

---

## 8. Out of scope / alternatives

| Request | Alternative |
|---------|-------------|
| Full WP plugin parity | Not realistic on current stack without large rebuild |
| Competitor crawling at scale | Separate SEO platform / paid API |
| Image “generation” | Claude/Cursor image tools → then `upload_media` |
| Instant GSC without Google setup | Impossible; need property + API credentials |

---

*Related: `docs/MCP-CLOUD-CONNECT.md` (current cloud connector setup).*
