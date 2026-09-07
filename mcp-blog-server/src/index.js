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
  version: "1.1.0",
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

const transport = new StdioServerTransport();
await server.connect(transport);
