#!/usr/bin/env python3
"""Page-aligned parse of SK_Customers_Details.pdf → JSON (counts only on stdout)."""
from __future__ import annotations

import json
import re
from collections import defaultdict
from pathlib import Path

from pypdf import PdfReader

PDF = Path(r"c:\Users\USER\Downloads\SK_Customers_Details.pdf")
OUT = Path(r"d:\xampp\htdocs\storage-keys-latest\storage\app\sk_customers_import.json")

EMAIL_RE = re.compile(r"[A-Za-z0-9._%+\-]+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}")
BAL_RE = re.compile(r"^-?\d+(?:\.\d+)?$")
SERVICE_LABELS = {
    "storage",
    "moving services",
    "service market (personal moving)",
    "storage services",
    "storage keys",
    "moving done by sk team",
    "moving done by sk team ,",
    "delta",
}


def page_words(page):
    words = []

    def visitor(text, cm, tm, fontDict, fontSize):
        if text is not None and str(text).strip() != "":
            words.append((float(tm[4]), float(tm[5]), str(text)))

    page.extract_text(visitor_text=visitor)
    return words


def cluster_rows(words, y_tol=3.5):
    buckets = defaultdict(list)
    for x, y, t in words:
        key = round(y / y_tol) * y_tol
        buckets[key].append((x, t))
    rows = []
    for y in sorted(buckets.keys(), reverse=True):
        rows.append((y, sorted(buckets[y], key=lambda i: i[0])))
    return rows


def join_col(items, x0, x1):
    parts = [t for x, t in items if x0 <= x < x1]
    return re.sub(r"\s+", " ", " ".join(parts)).strip()


def parse_names_page(page):
    records = []
    for y, items in cluster_rows(page_words(page)):
        name = join_col(items, 0, 250)
        company = join_col(items, 250, 400)
        street = join_col(items, 400, 9999)
        full = f"{name} {company} {street}".strip()
        if not full:
            continue
        if "Company name" in full or "Street Address" in full:
            continue
        if not name:
            if records:
                extra = " ".join(p for p in (company, street) if p).strip()
                if extra and extra.lower() not in {"from (emirate)", "from (address)"}:
                    records[-1]["street_address"] = (
                        (records[-1].get("street_address") or "") + " " + extra
                    ).strip()
                elif extra:
                    # keep From markers out
                    pass
            continue
        if name.lower() in {"from (emirate)", "from (address)"}:
            continue
        records.append(
            {"name": name, "company_name": company or None, "street_address": street or None}
        )
    return records


def parse_geo_page(page):
    records = []
    for y, items in cluster_rows(page_words(page)):
        city = join_col(items, 0, 100)
        state = join_col(items, 100, 180)
        country = join_col(items, 180, 300)
        zipc = join_col(items, 300, 360)
        phone = join_col(items, 360, 9999)
        full = f"{city} {state} {country} {zipc} {phone}".strip()
        if not full:
            continue
        if "Country" in full and "Phone" in full:
            continue
        records.append(
            {
                "city": city or None,
                "state": state or None,
                "country": country or None,
                "zip": zipc or None,
                "phone": phone or None,
            }
        )
    return records


def parse_email_page(page):
    records = []
    pending = []
    for y, items in cluster_rows(page_words(page)):
        left = join_col(items, 0, 200)
        right = join_col(items, 200, 9999)
        blob = f"{left} {right}"
        if "Open balance" in blob or left == "Email":
            continue
        emails = EMAIL_RE.findall(left) + EMAIL_RE.findall(right)
        if emails:
            pending.extend(emails)
        bal = None
        for token in re.split(r"\s+", right.strip()):
            tok = token.replace(",", "")
            if BAL_RE.match(tok):
                try:
                    bal = float(tok)
                except ValueError:
                    bal = None
        if bal is not None:
            records.append({"emails": list(dict.fromkeys(pending)), "open_balance": bal})
            pending = []
    if pending:
        records.append({"emails": list(dict.fromkeys(pending)), "open_balance": 0.0})
    return records


def tokens(s: str):
    return [t for t in re.findall(r"[a-z0-9]{3,}", (s or "").lower())]


def match_score(name_rec, email_rec):
    emails = email_rec.get("emails") or []
    if not emails:
        return 0.0
    local = re.sub(r"[^a-z0-9]", "", emails[0].split("@")[0].lower())
    domain = emails[0].split("@")[-1].lower() if "@" in emails[0] else ""
    blob_tokens = tokens(name_rec.get("name", "")) + tokens(name_rec.get("company_name") or "")
    if not blob_tokens:
        return 0.0
    hits = sum(1 for t in blob_tokens if t in local or t in domain)
    return hits / len(blob_tokens)


def align(names, emails):
    """Align two sequences with small length differences via greedy banded matching."""
    if len(names) == len(emails):
        return list(zip(names, emails))
    # Prefer email as master; pick best nearby name
    used = set()
    pairs = []
    ni = 0
    for ei, em in enumerate(emails):
        best_j = None
        best_s = -1.0
        # search window around expected index
        lo = max(0, ni - 2)
        hi = min(len(names), ni + 6)
        for j in range(lo, hi):
            if j in used:
                continue
            s = match_score(names[j], em)
            # slight preference for order
            s -= abs(j - ni) * 0.01
            if s > best_s:
                best_s = s
                best_j = j
        if best_j is None:
            # fallback sequential
            while ni in used and ni < len(names):
                ni += 1
            if ni >= len(names):
                break
            best_j = ni
        used.add(best_j)
        pairs.append((names[best_j], em))
        ni = best_j + 1
    return pairs


def split_name(full: str):
    full = re.sub(r"\s+", " ", full).strip(" ,")
    parts = full.split(" ")
    if not parts:
        return "Customer", "Customer"
    if len(parts) == 1:
        return parts[0], parts[0]
    return parts[0], " ".join(parts[1:])


def guess_type(name: str, company: str | None):
    markers = ("LLC", "L.L.C", "LTD", "FZE", "FZCO", "TRADING", "COMPANY", "HOTEL", "SALON", "GROUP")
    blob = f"{name} {company or ''}".upper()
    if any(m in blob for m in markers):
        return "company"
    return "individual"


def normalize_phone(phone: str | None):
    if not phone:
        return None
    phone = re.sub(r"[^\d+]", "", phone)
    return phone[:32] if phone else None


def main():
    reader = PdfReader(str(PDF))
    assert len(reader.pages) == 228

    out = []
    stats = {
        "pages": 76,
        "pairs": 0,
        "geo_attached": 0,
        "geo_skipped_pages": 0,
        "unique_emails": 0,
    }

    for i in range(76):
        names = parse_names_page(reader.pages[i])
        geos = parse_geo_page(reader.pages[i + 76])
        emails = parse_email_page(reader.pages[i + 152])
        pairs = align(names, emails)
        attach_geo = len(geos) == len(emails)
        if not attach_geo:
            stats["geo_skipped_pages"] += 1

        for idx, (nm, em) in enumerate(pairs):
            email_list = em.get("emails") or []
            if not email_list:
                continue
            primary = email_list[0].lower().strip()
            ctype = guess_type(nm["name"], nm.get("company_name"))
            company = nm.get("company_name")
            if company and company.strip().lower() in SERVICE_LABELS:
                company = None
            if ctype == "company" and not company:
                company = nm["name"]
            first, last = split_name(nm["name"])
            geo = geos[idx] if attach_geo and idx < len(geos) else {}
            if attach_geo:
                stats["geo_attached"] += 1
            address = " ".join(
                p for p in [nm.get("street_address"), geo.get("zip")] if p
            ) or None
            out.append(
                {
                    "customer_type": ctype,
                    "customer_name": nm["name"],
                    "company_name": company,
                    "email": primary,
                    "extra_emails": [e.lower() for e in email_list[1:]],
                    "phone": normalize_phone(geo.get("phone")),
                    "address": address,
                    "city": geo.get("city"),
                    "state": geo.get("state"),
                    "country": geo.get("country") or "United Arab Emirates",
                    "open_balance": em.get("open_balance", 0),
                    "first_name": first,
                    "last_name": last,
                }
            )
            stats["pairs"] += 1

    # de-dupe by email keep first
    seen = set()
    deduped = []
    for row in out:
        if row["email"] in seen:
            continue
        seen.add(row["email"])
        deduped.append(row)
    stats["unique_emails"] = len(deduped)
    stats["dup_emails_dropped"] = len(out) - len(deduped)

    OUT.parent.mkdir(parents=True, exist_ok=True)
    OUT.write_text(json.dumps(deduped, ensure_ascii=False), encoding="utf-8")
    print(json.dumps({"wrote": str(OUT), **stats, "records": len(deduped)}))


if __name__ == "__main__":
    main()
