import "dotenv/config";

const API_BASE = (process.env.BLOG_API_BASE_URL || "").replace(/\/$/, "");
const TOKEN = process.env.MCP_BLOG_TOKEN || "";

if (!API_BASE || !TOKEN) {
  console.error("Set BLOG_API_BASE_URL and MCP_BLOG_TOKEN in mcp-blog-server/.env");
  process.exit(1);
}

async function main() {
  const listRes = await fetch(`${API_BASE}/blogs?limit=3`, {
    headers: {
      Accept: "application/json",
      Authorization: `Bearer ${TOKEN}`,
      "X-MCP-Token": TOKEN,
    },
  });
  const listText = await listRes.text();
  console.log("GET /blogs =>", listRes.status, listText.slice(0, 500));

  if (!listRes.ok) process.exit(1);

  const createRes = await fetch(`${API_BASE}/blogs`, {
    method: "POST",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      Authorization: `Bearer ${TOKEN}`,
      "X-MCP-Token": TOKEN,
    },
    body: JSON.stringify({
      title: "MCP Test Blog " + new Date().toISOString(),
      description: "<p>This is an automated MCP API connectivity test blog. Safe to delete.</p>",
      status: 0,
    }),
  });
  const createText = await createRes.text();
  console.log("POST /blogs =>", createRes.status, createText.slice(0, 800));
  process.exit(createRes.ok ? 0 : 1);
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
