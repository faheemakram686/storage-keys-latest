from pathlib import Path

p = Path(r"d:/xampp/htdocs/storage-keys-latest/public/js/core.js")
text = p.read_text(encoding="utf-8")
replacements = [
    ("getAppUrl('images/core.png')", "getAppUrl('sk-assets/assets/images/frontend/front-logo.png')"),
    ("/images/core.png", "/sk-assets/assets/images/frontend/front-logo.png"),
    ("'/images/logo.png'", "'/sk-assets/assets/images/frontend/front-logo.png'"),
    ('"/images/logo.png"', '"/sk-assets/assets/images/frontend/front-logo.png"'),
    ("/images/logo/default-logo.png", "/sk-assets/assets/images/frontend/front-logo.png"),
]
total = 0
for old, new in replacements:
    count = text.count(old)
    if count:
        text = text.replace(old, new)
        total += count
        print(f"{count} x {old}")
p.write_text(text, encoding="utf-8")
print("total", total)
