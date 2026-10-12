#!/usr/bin/env python3
"""Compare what the features page states with the Torns production config.

Read-only: it only SELECTs `app_config` and `subscription_config` through
the Supabase REST API (the database is shared by prod and dev; nothing is
written). Run it before every Torns release:

    SUPABASE_URL=… SUPABASE_SERVICE_KEY=… python3 scripts/features_diff.py

It lists every value in content/features/published-facts.json that the
database no longer has, and exits 1 when there is any, 0 when the page is
up to date. It also lists the feature flags that are ON in production but
not covered by the published facts (possibly a new feature to add).

Standard library only.
"""
from __future__ import annotations

import json
import os
import re
import sys
import urllib.request
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
FACTS = ROOT / "content" / "features" / "published-facts.json"

# Flags that are deliberately not on the page: not features, or internal.
IGNORED_FLAGS = {
    "analytics_enabled", "floating_tab_bar_enabled", "show_center_member_count",
    "social_registration_enabled", "invoice_iva_enabled", "invoice_irpf_enabled",
    "verifactu_test_mode", "maps_admin_preview_enabled",
}
IGNORED_KEYS = {
    "marketing_advisor_enabled", "store_reviews_enabled", "mailing_visual_editor_enabled",
    "sos_custom_sound_enabled",
}


def fetch(table: str) -> list[dict]:
    url = os.environ.get("SUPABASE_URL", "").rstrip("/")
    key = os.environ.get("SUPABASE_SERVICE_KEY") or os.environ.get("SUPABASE_ANON_KEY") or ""
    if not url or not key:
        sys.exit("Set SUPABASE_URL and SUPABASE_SERVICE_KEY (read-only use).")
    req = urllib.request.Request(
        f"{url}/rest/v1/{table}?select=*",
        headers={"apikey": key, "Authorization": f"Bearer {key}", "Accept": "application/json"},
    )
    with urllib.request.urlopen(req, timeout=30) as res:  # GET only
        return json.load(res)


def as_bool_if_flag(value):
    """app_config stores some switches as the string "true"."""
    if isinstance(value, str) and value.lower() in ("true", "false"):
        return value.lower() == "true"
    return value


def lookup(root: dict, path: str):
    """`key.sub.list[field=value].leaf` inside a dict. Missing → None."""
    node = root
    for part in path.split("."):
        m = re.fullmatch(r"([^\[]+)\[([^=]+)=([^\]]+)\]", part)
        if m:
            name, field, wanted = m.groups()
            items = node.get(name) if isinstance(node, dict) else None
            node = next((it for it in (items or []) if str(it.get(field)) == wanted), None)
        else:
            node = node.get(part) if isinstance(node, dict) else None
        if node is None:
            return None
    return as_bool_if_flag(node)


def same(a, b) -> bool:
    if isinstance(a, float) or isinstance(b, float):
        try:
            return abs(float(a) - float(b)) < 1e-9
        except (TypeError, ValueError):
            return False
    return a == b


def main() -> int:
    facts = json.loads(FACTS.read_text(encoding="utf-8"))
    app_config = {row["key"]: row["value"] for row in fetch("app_config")}
    sub_config = {row["key"]: row for row in fetch("subscription_config")}

    problems: list[str] = []
    for path, expected in facts["app_config"].items():
        actual = lookup(app_config, path)
        if not same(actual, expected):
            problems.append(f"app_config  {path}: page says {json.dumps(expected)}, production has {json.dumps(actual)}")
    for path, expected in facts["subscription_config"].items():
        actual = lookup(sub_config, path)
        if not same(actual, expected):
            problems.append(f"subscription_config  {path}: page says {json.dumps(expected)}, production has {json.dumps(actual)}")

    covered = {p.split(".", 1)[1] for p in facts["app_config"] if p.startswith("feature_flags.")}
    new_flags = sorted(
        k for k, v in (app_config.get("feature_flags") or {}).items()
        if as_bool_if_flag(v) is True and k not in covered and k not in IGNORED_FLAGS
    )
    top_level = {p.split(".", 1)[0] for p in facts["app_config"]}
    new_keys = sorted(
        k for k, v in app_config.items()
        if k.endswith("_enabled") and as_bool_if_flag(v) is True and k not in top_level and k not in IGNORED_KEYS
    )

    print(f"Published facts: {FACTS.relative_to(ROOT)} (published on {facts.get('published_on')})")
    if problems:
        print(f"\n{len(problems)} difference(s): update the page and published-facts.json\n")
        for p in problems:
            print("  ✗", p)
    else:
        print("\n✓ Every published value matches production.")
    if new_flags or new_keys:
        print("\nSwitched ON in production but not on the page (new feature?):")
        for k in new_flags:
            print("  ?", f"feature_flags.{k}")
        for k in new_keys:
            print("  ?", k)
    return 1 if problems else 0


if __name__ == "__main__":
    sys.exit(main())
