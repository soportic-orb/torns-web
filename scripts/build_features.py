#!/usr/bin/env python3
"""Build the features page artefacts from the Markdown master.

    python3 scripts/build_features.py            # every language with a master
    python3 scripts/build_features.py es ca      # some languages

Reads   content/features/funcionalidades.<lang>.md
Writes  content/features/build/
          funcionalidades.<lang>.html   semantic HTML for one Divi Code module
          schema.<lang>.json            JSON-LD @graph (Organization, WebSite,
                                        MobileApplication, WebPage, FAQPage)
          funcionalidades.<lang>.md     the master, served as a static file
          llms.txt, llms-full.txt       (from the Spanish master)

Standard library only. The Markdown subset is the one the master uses:
#/##/### headings, paragraphs, "- " lists, pipe tables, **bold**, [links](url).
"""
from __future__ import annotations

import html
import json
import re
import sys
import unicodedata
from datetime import date
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
SRC = ROOT / "content" / "features"
OUT = SRC / "build"

SITE = "https://www.torns.app"
APP_STORE = "https://apps.apple.com/es/app/torns/id6760354618"
GOOGLE_PLAY = "https://play.google.com/store/apps/details?id=com.tornsapp.android"
WEB_APP = "https://app.torns.app"

# Page URL per language: TranslatePress serves /ca/, /gl/, /eu/ prefixes.
PAGE_PATH = {"es": "/funcionalidades/", "ca": "/ca/funcionalidades/", "gl": "/gl/funcionalidades/", "eu": "/eu/funcionalidades/"}
MD_PATH = {lang: f"/funcionalidades.{lang}.md" if lang != "es" else "/funcionalidades.md" for lang in PAGE_PATH}
IN_LANGUAGE = {"es": "es-ES", "ca": "ca-ES", "gl": "gl-ES", "eu": "eu-ES"}
SUBCATEGORY = {"es": "Gestión y cambio de turnos", "ca": "Gestió i canvi de torns",
               "gl": "Xestión e cambio de quendas", "eu": "Txanden kudeaketa eta aldaketa"}

# Prices from app_config.plans_config.pro_plans (12/10/2026).
OFFERS = [
    {"name": {"es": "Free", "ca": "Free", "gl": "Free", "eu": "Free"}, "price": "0", "unit": None},
    {"name": {"es": "Torns Pro mensual", "ca": "Torns Pro mensual", "gl": "Torns Pro mensual", "eu": "Torns Pro hilekoa"}, "price": "2.99", "unit": "MON"},
    {"name": {"es": "Torns Pro anual", "ca": "Torns Pro anual", "gl": "Torns Pro anual", "eu": "Torns Pro urtekoa"}, "price": "29.90", "unit": "ANN"},
]


# ── Markdown → HTML (the master's subset) ────────────────────────────────

def slug(text: str) -> str:
    t = unicodedata.normalize("NFKD", text).encode("ascii", "ignore").decode()
    t = re.sub(r"[^a-zA-Z0-9]+", "-", t).strip("-").lower()
    return t or "seccion"


def inline(text: str) -> str:
    out = html.escape(text, quote=False)
    out = re.sub(r"\[([^\]]+)\]\((https?://[^)\s]+)\)", lambda m: f'<a href="{html.escape(m.group(2))}">{m.group(1)}</a>', out)
    out = re.sub(r"\*\*([^*]+)\*\*", r"<strong>\1</strong>", out)
    # Bare addresses and emails become links too.
    out = re.sub(r"(?<![\"=>])(https://[A-Za-z0-9./_?=&%-]+[A-Za-z0-9/])", r'<a href="\1">\1</a>', out)
    out = re.sub(r"(?<![\w.:>])([a-z]+@torns\.app)", r'<a href="mailto:\1">\1</a>', out)
    return out


def md_to_html(md: str) -> tuple[str, str]:
    """(h1 text, body html without the h1)."""
    lines = md.splitlines()
    out: list[str] = []
    h1 = ""
    used: set[str] = set()
    i = 0

    def uid(text: str) -> str:
        base = slug(text)
        s, n = base, 2
        while s in used:
            s, n = f"{base}-{n}", n + 1
        used.add(s)
        return s

    while i < len(lines):
        line = lines[i].rstrip()
        if not line:
            i += 1
            continue
        if line.startswith("# "):
            h1 = line[2:].strip()
            i += 1
            continue
        m = re.match(r"^(#{2,3}) (.+)$", line)
        if m:
            level = len(m.group(1))
            text = m.group(2).strip()
            out.append(f'<h{level} id="{uid(text)}">{inline(text)}</h{level}>')
            i += 1
            continue
        if line.startswith("|"):
            rows = []
            while i < len(lines) and lines[i].startswith("|"):
                rows.append([c.strip() for c in lines[i].strip().strip("|").split("|")])
                i += 1
            head, body = rows[0], [r for r in rows[1:] if not all(re.fullmatch(r":?-+:?", c) for c in r)]
            out.append("<table>")
            out.append("<thead><tr>" + "".join(f'<th scope="col">{inline(c)}</th>' for c in head) + "</tr></thead>")
            out.append("<tbody>")
            for r in body:
                out.append("<tr>" + "".join(f"<td>{inline(c)}</td>" for c in r) + "</tr>")
            out.append("</tbody></table>")
            continue
        if line.startswith("- "):
            out.append("<ul>")
            while i < len(lines) and lines[i].startswith("- "):
                out.append(f"<li>{inline(lines[i][2:].strip())}</li>")
                i += 1
            out.append("</ul>")
            continue
        para = [line]
        i += 1
        while i < len(lines) and lines[i].strip() and not re.match(r"^(#|- |\|)", lines[i]):
            para.append(lines[i].strip())
            i += 1
        out.append(f"<p>{inline(' '.join(para))}</p>")
    return h1, "\n".join(out)


# ── Pieces read from the master ──────────────────────────────────────────

def sections(md: str) -> list[tuple[str, str]]:
    """[(h2 title, h2 body markdown)]"""
    parts = re.split(r"^## (.+)$", md, flags=re.M)
    return [(parts[k].strip(), parts[k + 1]) for k in range(1, len(parts), 2)]


def faq(md: str) -> list[tuple[str, str]]:
    last = sections(md)[-1][1]
    pairs = re.findall(r"^### (.+?)\n+(.+?)(?=\n### |\Z)", last, flags=re.M | re.S)
    return [(q.strip(), re.sub(r"\s+", " ", a).strip()) for q, a in pairs]


# Every language's master has the same 14 H2 sections in the same order:
# 0 key facts, 1–8 feature areas, 9 plans, 10 organisations, 11 associations,
# 12 privacy, 13 FAQ. Sections are found by position, not by their
# (translated) titles.
H2_COUNT = 14
NOT_FEATURES = {0, 9, 12, 13}
ORG_SECTION, ORG_PRICE_H3 = 10, 1  # "Precio de las licencias" is not a feature


def feature_list(md: str) -> list[str]:
    """Every H3 of the feature areas, organisations and associations."""
    secs = sections(md)
    if len(secs) != H2_COUNT:
        raise SystemExit(f"expected {H2_COUNT} H2 sections, found {len(secs)}")
    names: list[str] = []
    for idx, (_title, body) in enumerate(secs):
        if idx in NOT_FEATURES:
            continue
        h3 = [h.strip() for h in re.findall(r"^### (.+)$", body, flags=re.M)]
        if idx == ORG_SECTION:
            h3 = [h for k, h in enumerate(h3) if k != ORG_PRICE_H3]
        names += h3
    return names


def first_paragraph(md: str) -> str:
    return md.split("\n\n")[1].strip()


def updated(md: str) -> str:
    return date.today().isoformat()


# ── Artefacts ────────────────────────────────────────────────────────────

def build_html(lang: str, md: str) -> str:
    h1, body = md_to_html(md)
    return (
        f'<article class="torns-features" lang="{lang}">\n'
        f"<h1>{html.escape(h1)}</h1>\n{body}\n</article>\n"
    )


def build_schema(lang: str, md: str) -> dict:
    h1 = md.splitlines()[0][2:].strip()
    page_url = SITE + PAGE_PATH[lang]
    today = updated(md)
    # Same @id and name as the nodes Yoast SEO already prints on every page
    # (#organization "Torns", #website), so the two graphs merge instead of
    # describing two different organisations.
    org = {
        "@type": "Organization",
        "@id": f"{SITE}/#organization",
        "name": "Torns",
        "legalName": "Soportic",
        "brand": {"@type": "Brand", "name": "Torns"},
        "founder": {"@type": "Person", "name": "Octavi Rodríguez"},
        "url": SITE,
        "email": "hola@torns.app",
        "contactPoint": [
            {"@type": "ContactPoint", "contactType": "customer support", "email": "soporte@torns.app",
             "availableLanguage": ["es", "ca", "gl", "eu"]},
            {"@type": "ContactPoint", "contactType": "sales", "email": "hola@torns.app",
             "availableLanguage": ["es", "ca", "gl", "eu"]},
        ],
    }
    website = {"@type": "WebSite", "@id": f"{SITE}/#website", "url": SITE, "name": "Torns",
               "publisher": {"@id": f"{SITE}/#organization"}, "inLanguage": ["es-ES", "ca-ES", "gl-ES", "eu-ES"]}
    offers = []
    for o in OFFERS:
        offer = {"@type": "Offer", "name": o["name"][lang], "price": o["price"], "priceCurrency": "EUR",
                 "availability": "https://schema.org/InStock"}
        if o["unit"]:
            offer["priceSpecification"] = {"@type": "UnitPriceSpecification", "price": o["price"],
                                           "priceCurrency": "EUR", "unitCode": o["unit"]}
        offers.append(offer)
    app = {
        "@type": "MobileApplication",
        "@id": f"{SITE}/#app",
        "name": "Torns",
        "description": first_paragraph(md),
        "applicationCategory": "BusinessApplication",
        "applicationSubCategory": SUBCATEGORY[lang],
        "operatingSystem": "iOS, iPadOS, watchOS, Android, Web",
        "inLanguage": ["es-ES", "ca-ES", "gl-ES", "eu-ES"],
        "url": page_url,
        "installUrl": [APP_STORE, GOOGLE_PLAY, WEB_APP],
        "sameAs": [APP_STORE, GOOGLE_PLAY],
        "publisher": {"@id": f"{SITE}/#organization"},
        "offers": offers,
        "featureList": feature_list(md),
    }
    page = {
        "@type": "WebPage",
        # Yoast gives the page's WebPage the page URL as its @id.
        "@id": page_url,
        "url": page_url,
        "name": h1,
        "inLanguage": IN_LANGUAGE[lang],
        "isPartOf": {"@id": f"{SITE}/#website"},
        "about": {"@id": f"{SITE}/#app"},
        "dateModified": today,
        "mainEntity": {"@id": page_url + "#faq"},
    }
    faq_page = {
        "@type": "FAQPage",
        "@id": page_url + "#faq",
        "inLanguage": IN_LANGUAGE[lang],
        "mainEntity": [
            {"@type": "Question", "name": q, "acceptedAnswer": {"@type": "Answer", "text": a}}
            for q, a in faq(md)
        ],
    }
    return {"@context": "https://schema.org", "@graph": [org, website, app, page, faq_page]}


def build_llms(md: str) -> str:
    return (
        "# Torns\n\n"
        f"> {first_paragraph(md)}\n\n"
        "Torns es una app de Soportic para gestionar el calendario de turnos y cambiar turnos entre "
        "profesionales sanitarios de España. Disponible en castellano, català, galego y euskara.\n\n"
        "## Funcionalidades\n\n"
        f"- [Funcionalidades de Torns]({SITE}{PAGE_PATH['es']}): todas las funcionalidades, planes, límites y preguntas frecuentes.\n"
        f"- [Funcionalidades en Markdown]({SITE}{MD_PATH['es']}): la misma página en texto plano.\n"
        f"- [Texto completo para LLM]({SITE}/llms-full.txt)\n\n"
        "## Otros idiomas\n\n"
        f"- [Funcionalitats (català)]({SITE}{PAGE_PATH['ca']}) · [Markdown]({SITE}{MD_PATH['ca']})\n"
        f"- [Funcionalidades (galego)]({SITE}{PAGE_PATH['gl']}) · [Markdown]({SITE}{MD_PATH['gl']})\n"
        f"- [Funtzioak (euskara)]({SITE}{PAGE_PATH['eu']}) · [Markdown]({SITE}{MD_PATH['eu']})\n\n"
        "## Planes y precios\n\n"
        f"- [Planes y precios]({SITE}{PAGE_PATH['es']}#planes-y-precios): Free gratuito; Torns Pro 2,99 €/mes o 29,90 €/año; 30 días de prueba.\n"
        f"- [Organizaciones]({SITE}{PAGE_PATH['es']}#organizaciones-panel-de-torns): licencias de 0,39 € a 0,99 € por usuario y mes, sin IVA.\n\n"
        "## Preguntas frecuentes\n\n"
        f"- [Preguntas frecuentes]({SITE}{PAGE_PATH['es']}#preguntas-frecuentes)\n\n"
        "## Legal y contacto\n\n"
        f"- [Privacidad]({SITE}/privacidad)\n"
        f"- [Contacto]({SITE}/contacta/): soporte@torns.app (usuarios) · hola@torns.app (organizaciones)\n\n"
        "## Descargas\n\n"
        f"- [App Store]({APP_STORE})\n"
        f"- [Google Play]({GOOGLE_PLAY})\n"
        f"- [Versión web]({WEB_APP})\n"
    )


def main(langs: list[str]) -> None:
    OUT.mkdir(parents=True, exist_ok=True)
    for lang in langs:
        src = SRC / f"funcionalidades.{lang}.md"
        if not src.exists():
            print(f"skip {lang}: no master")
            continue
        md = src.read_text(encoding="utf-8")
        (OUT / f"funcionalidades.{lang}.html").write_text(build_html(lang, md), encoding="utf-8")
        schema = build_schema(lang, md)
        text = json.dumps(schema, ensure_ascii=False, indent=2)
        json.loads(text)  # syntax check
        (OUT / f"schema.{lang}.json").write_text(text + "\n", encoding="utf-8")
        (OUT / f"funcionalidades.{lang}.md").write_text(md, encoding="utf-8")
        if lang == "es":
            (OUT / "llms.txt").write_text(build_llms(md), encoding="utf-8")
            (OUT / "llms-full.txt").write_text(md, encoding="utf-8")
        print(f"built {lang}: {len(feature_list(md))} features, {len(faq(md))} FAQ")


if __name__ == "__main__":
    main(sys.argv[1:] or [p.stem.split(".")[1] for p in sorted(SRC.glob("funcionalidades.*.md"))])
