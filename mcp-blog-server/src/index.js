#!/usr/bin/env node
import "dotenv/config";
import { McpServer } from "@modelcontextprotocol/sdk/server/mcp.js";
import { StdioServerTransport } from "@modelcontextprotocol/sdk/server/stdio.js";
import { z } from "zod";

const API_BASE = (process.env.BLOG_API_BASE_URL || "").replace(/\/$/, "");
const TOKEN = process.env.MCP_BLOG_TOKEN || "";

function ensureConfig() {
  if (!API_BASE) {
    throw new Error("BLOG_API_BASE_URL is not set (example: http://localhost/storage-keys-latest/public/api/mcp)");
  }
  if (!TOKEN) {
    throw new Error("MCP_BLOG_TOKEN is not set");
  }
}

async function apiRequest(path, options = {}) {
  ensureConfig();
  const url = `${API_BASE}${path}`;
  const res = await fetch(url, {
    ...options,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      Authorization: `Bearer ${TOKEN}`,
      "X-MCP-Token": TOKEN,
      ...(options.headers || {}),
    },
  });

  const text = await res.text();
  let data;
  try {
    data = text ? JSON.parse(text) : {};
  } catch {
    data = { raw: text };
  }

  if (!res.ok) {
    // Soft-fail tools (e.g. analytics stubs) still return JSON for the model.
    if ((res.status === 503 || res.status === 501) && data && typeof data === "object") {
      return data;
    }
    const message = data?.message || data?.raw || res.statusText;
    throw new Error(`API ${res.status}: ${message}`);
  }

  return data;
}

function ok(data) {
  return {
    content: [{ type: "text", text: JSON.stringify(data, null, 2) }],
  };
}

function blogPath(id, slug) {
  if (id != null) return `/blogs/${id}`;
  if (slug) return `/blogs/${encodeURIComponent(slug)}`;
  throw new Error("Provide id or slug");
}

const server = new McpServer({
  name: "storagekeys-blog",
  version: "1.3.0",
});

server.tool(
  "list_blogs",
  "List recent StorageKeys blogs (newest first).",
  {
    limit: z.number().int().min(1).max(50).optional(),
  },
  async ({ limit }) => {
    const qs = limit ? `?limit=${limit}` : "";
    return ok(await apiRequest(`/blogs${qs}`, { method: "GET" }));
  }
);

server.tool(
  "create_blog",
  "Create a blog post. Defaults to draft (status=0).",
  {
    title: z.string().min(3).max(255),
    description: z.string().min(20),
    status: z.union([z.literal(0), z.literal(1)]).optional(),
    image_url: z.string().url().optional(),
    slug: z.string().optional(),
  },
  async ({ title, description, status, image_url, slug }) => {
    const payload = { title, description };
    if (status === 0 || status === 1) payload.status = status;
    if (image_url) payload.image_url = image_url;
    if (slug) payload.slug = slug;
    return ok(await apiRequest("/blogs", { method: "POST", body: JSON.stringify(payload) }));
  }
);

server.tool(
  "get_blog",
  "Get one blog by id or slug (full description).",
  {
    id: z.number().int().optional(),
    slug: z.string().optional(),
  },
  async ({ id, slug }) => ok(await apiRequest(blogPath(id, slug), { method: "GET" }))
);

server.tool(
  "update_blog",
  "Update a blog by id or slug. Only send fields to change.",
  {
    id: z.number().int().optional(),
    slug: z.string().optional(),
    title: z.string().optional(),
    description: z.string().optional(),
    status: z.union([z.literal(0), z.literal(1)]).optional(),
    image_url: z.string().url().optional(),
    new_slug: z.string().optional(),
  },
  async ({ id, slug, title, description, status, image_url, new_slug }) => {
    const payload = {};
    if (title != null) payload.title = title;
    if (description != null) payload.description = description;
    if (status === 0 || status === 1) payload.status = status;
    if (image_url) payload.image_url = image_url;
    if (new_slug) payload.slug = new_slug;
    return ok(
      await apiRequest(blogPath(id, slug), {
        method: "PATCH",
        body: JSON.stringify(payload),
      })
    );
  }
);

server.tool(
  "delete_blog",
  "Soft-delete a blog. Requires confirm=true.",
  {
    id: z.number().int().optional(),
    slug: z.string().optional(),
    confirm: z.boolean(),
  },
  async ({ id, slug, confirm }) => {
    const qs = `?confirm=${confirm ? "true" : "false"}`;
    return ok(await apiRequest(`${blogPath(id, slug)}${qs}`, { method: "DELETE" }));
  }
);

server.tool(
  "bulk_update_posts",
  "Bulk update blog status. Requires confirm=true.",
  {
    ids: z.array(z.number().int()).min(1).max(50),
    status: z.union([z.literal(0), z.literal(1)]),
    confirm: z.boolean(),
  },
  async ({ ids, status, confirm }) =>
    ok(
      await apiRequest("/blogs/bulk-update", {
        method: "POST",
        body: JSON.stringify({ ids, status, confirm }),
      })
    )
);

server.tool(
  "search_content",
  "Search blogs by title, slug, or description.",
  {
    q: z.string().min(2),
    limit: z.number().int().min(1).max(50).optional(),
  },
  async ({ q, limit }) => {
    const params = new URLSearchParams({ q });
    if (limit) params.set("limit", String(limit));
    return ok(await apiRequest(`/blogs/search?${params}`, { method: "GET" }));
  }
);

server.tool("get_site_info", "Base URL, timezone, permalink pattern, sitemap URL.", {}, async () =>
  ok(await apiRequest("/site-info", { method: "GET" }))
);

server.tool("get_pages", "List known static marketing page URLs.", {}, async () =>
  ok(await apiRequest("/pages", { method: "GET" }))
);

server.tool("get_sitemap", "JSON sitemap entries plus sitemap.xml URL.", {}, async () =>
  ok(await apiRequest("/sitemap", { method: "GET" }))
);

server.tool(
  "list_media",
  "List recent blog-images media files.",
  {
    limit: z.number().int().min(1).max(100).optional(),
  },
  async ({ limit }) => {
    const qs = limit ? `?limit=${limit}` : "";
    return ok(await apiRequest(`/media${qs}`, { method: "GET" }));
  }
);

server.tool(
  "upload_media",
  "Download an image URL into blog-images storage.",
  {
    image_url: z.string().url(),
  },
  async ({ image_url }) =>
    ok(
      await apiRequest("/media", {
        method: "POST",
        body: JSON.stringify({ image_url }),
      })
    )
);

server.tool(
  "get_seo_meta",
  "Get SEO meta for a blog (stored + resolved fallbacks).",
  {
    id: z.number().int().optional(),
    slug: z.string().optional(),
  },
  async ({ id, slug }) => ok(await apiRequest(`${blogPath(id, slug)}/seo`, { method: "GET" }))
);

server.tool(
  "update_seo_meta",
  "Update SEO meta fields. Empty string clears a field.",
  {
    id: z.number().int().optional(),
    slug: z.string().optional(),
    meta_title: z.string().optional(),
    meta_description: z.string().optional(),
    canonical_url: z.string().url().optional(),
    robots: z.string().optional(),
  },
  async ({ id, slug, meta_title, meta_description, canonical_url, robots }) => {
    const payload = {};
    if (meta_title !== undefined) payload.meta_title = meta_title;
    if (meta_description !== undefined) payload.meta_description = meta_description;
    if (canonical_url !== undefined) payload.canonical_url = canonical_url;
    if (robots !== undefined) payload.robots = robots;
    return ok(
      await apiRequest(`${blogPath(id, slug)}/seo`, {
        method: "PATCH",
        body: JSON.stringify(payload),
      })
    );
  }
);

server.tool(
  "get_schema",
  "Get JSON-LD schema for a blog.",
  {
    id: z.number().int().optional(),
    slug: z.string().optional(),
  },
  async ({ id, slug }) => ok(await apiRequest(`${blogPath(id, slug)}/schema`, { method: "GET" }))
);

server.tool(
  "update_schema",
  "Set JSON-LD schema object (or schema_json string). Pass schema null to clear.",
  {
    id: z.number().int().optional(),
    slug: z.string().optional(),
    schema: z.any().optional(),
    schema_json: z.string().optional(),
  },
  async ({ id, slug, schema, schema_json }) => {
    const payload = {};
    if (schema !== undefined) payload.schema = schema;
    if (schema_json !== undefined) payload.schema_json = schema_json;
    return ok(
      await apiRequest(`${blogPath(id, slug)}/schema`, {
        method: "PATCH",
        body: JSON.stringify(payload),
      })
    );
  }
);

server.tool("get_categories", "List blog categories.", {}, async () =>
  ok(await apiRequest("/categories", { method: "GET" }))
);
server.tool(
  "create_category",
  "Create a blog category.",
  { name: z.string(), slug: z.string().optional(), description: z.string().optional() },
  async (args) => ok(await apiRequest("/categories", { method: "POST", body: JSON.stringify(args) }))
);
server.tool("get_tags", "List blog tags.", {}, async () => ok(await apiRequest("/tags", { method: "GET" })));
server.tool(
  "create_tag",
  "Create a blog tag.",
  { name: z.string(), slug: z.string().optional() },
  async (args) => ok(await apiRequest("/tags", { method: "POST", body: JSON.stringify(args) }))
);
server.tool(
  "set_blog_taxonomies",
  "Assign categories/tags to a blog.",
  {
    id: z.number().int().optional(),
    slug: z.string().optional(),
    category_ids: z.array(z.number().int()).optional(),
    tag_ids: z.array(z.number().int()).optional(),
    category_slugs: z.array(z.string()).optional(),
    tag_slugs: z.array(z.string()).optional(),
  },
  async ({ id, slug, ...rest }) =>
    ok(await apiRequest(`${blogPath(id, slug)}/taxonomies`, { method: "POST", body: JSON.stringify(rest) }))
);

server.tool(
  "get_redirects",
  "List URL redirects.",
  { limit: z.number().int().min(1).max(100).optional() },
  async ({ limit }) => {
    const qs = limit ? `?limit=${limit}` : "";
    return ok(await apiRequest(`/redirects${qs}`, { method: "GET" }));
  }
);
server.tool(
  "create_redirect",
  "Create/update URL redirect.",
  {
    from_path: z.string(),
    to_url: z.string(),
    status_code: z.union([z.literal(301), z.literal(302), z.literal(307), z.literal(308)]).optional(),
    is_active: z.boolean().optional(),
  },
  async (args) => ok(await apiRequest("/redirects", { method: "POST", body: JSON.stringify(args) }))
);
server.tool(
  "delete_redirect",
  "Delete redirect by id. Requires confirm=true.",
  { id: z.number().int(), confirm: z.boolean() },
  async ({ id, confirm }) =>
    ok(await apiRequest(`/redirects/${id}?confirm=${confirm ? "true" : "false"}`, { method: "DELETE" }))
);

server.tool(
  "get_internal_links",
  "Internal link edges + orphan blogs from blog HTML.",
  { limit: z.number().int().optional() },
  async ({ limit }) => {
    const qs = limit ? `?limit=${limit}` : "";
    return ok(await apiRequest(`/internal-links${qs}`, { method: "GET" }));
  }
);
server.tool(
  "check_broken_links",
  "Check a limited sample of outbound/internal links.",
  { limit: z.number().int().min(1).max(30).optional() },
  async ({ limit }) =>
    ok(await apiRequest("/broken-links", { method: "POST", body: JSON.stringify({ limit: limit ?? 15 }) }))
);
server.tool("audit_content", "Thin/missing-meta/duplicate/cannibalization audit.", {}, async () =>
  ok(await apiRequest("/content-audit", { method: "GET" }))
);
server.tool("validate_sitemap_robots", "Validate sitemap.xml and robots.txt.", {}, async () =>
  ok(await apiRequest("/validate-sitemap-robots", { method: "GET" }))
);
server.tool(
  "list_stale_posts",
  "Blogs not reviewed within N days.",
  { days: z.number().int().min(30).max(730).optional() },
  async ({ days }) => {
    const qs = days ? `?days=${days}` : "";
    return ok(await apiRequest(`/stale-posts${qs}`, { method: "GET" }));
  }
);
server.tool(
  "mark_blog_reviewed",
  "Set last_reviewed_at=now.",
  { id: z.number().int().optional(), slug: z.string().optional() },
  async ({ id, slug }) =>
    ok(await apiRequest(`${blogPath(id, slug)}/mark-reviewed`, { method: "POST", body: "{}" }))
);

server.tool(
  "get_analytics_summary",
  "GA4 summary (503 until configured).",
  { period: z.string().optional() },
  async ({ period }) => {
    const qs = period ? `?period=${encodeURIComponent(period)}` : "";
    return ok(await apiRequest(`/analytics/summary${qs}`, { method: "GET" }));
  }
);
server.tool(
  "get_top_pages",
  "GA4 top pages (503 until configured).",
  { limit: z.number().int().optional() },
  async ({ limit }) => {
    const qs = limit ? `?limit=${limit}` : "";
    return ok(await apiRequest(`/analytics/top-pages${qs}`, { method: "GET" }));
  }
);
server.tool(
  "get_search_queries",
  "GSC queries (503 until configured).",
  { limit: z.number().int().optional() },
  async ({ limit }) => {
    const qs = limit ? `?limit=${limit}` : "";
    return ok(await apiRequest(`/analytics/search-queries${qs}`, { method: "GET" }));
  }
);

const transport = new StdioServerTransport();
await server.connect(transport);
