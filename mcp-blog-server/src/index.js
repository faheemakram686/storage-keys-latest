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

const server = new McpServer({
  name: "storagekeys-blog",
  version: "1.0.0",
});

server.tool(
  "list_blogs",
  "List recent StorageKeys blogs from the database (newest first).",
  {
    limit: z.number().int().min(1).max(50).optional().describe("How many blogs to return (default 10)"),
  },
  async ({ limit }) => {
    const qs = limit ? `?limit=${limit}` : "";
    const data = await apiRequest(`/blogs${qs}`, { method: "GET" });
    return {
      content: [
        {
          type: "text",
          text: JSON.stringify(data, null, 2),
        },
      ],
    };
  }
);

server.tool(
  "create_blog",
  "Create a blog post in the StorageKeys database via the Laravel MCP API. Defaults to draft (status=0) unless status=1 is passed.",
  {
    title: z.string().min(3).max(255).describe("Blog title"),
    description: z.string().min(20).describe("Blog HTML or text body"),
    status: z
      .union([z.literal(0), z.literal(1)])
      .optional()
      .describe("0 = draft (default), 1 = published/active"),
    image_url: z.string().url().optional().describe("Optional public image URL to download as blog cover"),
    slug: z.string().optional().describe("Optional custom slug; auto-generated from title if omitted"),
  },
  async ({ title, description, status, image_url, slug }) => {
    const payload = {
      title,
      description,
    };
    if (status === 0 || status === 1) payload.status = status;
    if (image_url) payload.image_url = image_url;
    if (slug) payload.slug = slug;

    const data = await apiRequest("/blogs", {
      method: "POST",
      body: JSON.stringify(payload),
    });

    return {
      content: [
        {
          type: "text",
          text: JSON.stringify(data, null, 2),
        },
      ],
    };
  }
);

const transport = new StdioServerTransport();
await server.connect(transport);
