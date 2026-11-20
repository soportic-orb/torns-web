#!/usr/bin/env python3
"""Build the comparison pages' FAQ (JSON-LD) and Markdown from the data.

Source: content/site/comparisons/*.json (features.json, torns.json and one
file per app). Output, committed and shipped by OTA:
- content/site/schema/vs-<app>.<lang>.json: FAQPage, also shown on the page;
- content/site/markdown/vs-<app>.<lang>.md and comparativas.<lang>.md: the
  pages as Markdown for AI assistants (/torns-vs-<app>.md, llms-full.txt).

Run after changing the data: python3 scripts/build-comparisons.py
"""
import json
import pathlib

ROOT = pathlib.Path(__file__).resolve().parent.parent / 'content' / 'site'
DATA = ROOT / 'comparisons'
LANGS = ['es', 'ca', 'gl', 'eu']
BASE = 'https://www.torns.app'

T = {
    'es': {
        'title': 'Torns vs {name}: comparativa de funcionalidades',
        'checked': 'Datos de las fuentes públicas de cada app, revisados el {date}.',
        'feature': 'Funcionalidad', 'yes': 'Sí', 'no': 'No', 'none': 'No consta',
        'sources': 'Fuentes', 'torns_source': 'Torns: funcionalidades, planes y límites',
        'note': 'Comparativa basada solo en lo que publica cada app (web, tiendas de aplicaciones y centro de ayuda) a fecha de la revisión. Si ves un dato desactualizado, escribe a hola@torns.app. {name} es una marca de sus titulares.',
        'faq': 'Preguntas frecuentes',
        'q_diff': '¿Qué diferencia hay entre Torns y {name}?',
        'q_swaps': '¿{name} permite cambiar turnos con compañeros?',
        'q_langs': '¿{name} está en catalán, gallego y euskera?',
        'q_price': '¿Cuánto cuesta {name}?',
        'q_import': '¿{name} importa el cuadrante desde una foto o un PDF?',
        'q_manager': '¿{name} comunica el cambio de turno al gestor?',
        'torns_says': 'En Torns: {text}',
        'hub_title': 'Torns frente a otras apps de turnos',
        'hub_intro': 'Comparativas de funcionalidades entre Torns y otras apps de calendario y cambios de turno que se usan en España, con más de 30 puntos cada una y las fuentes públicas de cada dato.',
    },
    'ca': {
        'title': 'Torns vs {name}: comparativa de funcionalitats',
        'checked': 'Dades de les fonts públiques de cada app, revisades el {date}.',
        'feature': 'Funcionalitat', 'yes': 'Sí', 'no': 'No', 'none': 'No consta',
        'sources': 'Fonts', 'torns_source': 'Torns: funcionalitats, plans i límits',
        'note': 'Comparativa basada només en el que publica cada app (web, botigues d\'aplicacions i centre d\'ajuda) a data de la revisió. Si veus una dada desactualitzada, escriu a hola@torns.app. {name} és una marca dels seus titulars.',
        'faq': 'Preguntes freqüents',
        'q_diff': 'Quina diferència hi ha entre Torns i {name}?',
        'q_swaps': '{name} permet canviar torns amb companys?',
        'q_langs': '{name} està en català, gallec i basc?',
        'q_price': 'Quant costa {name}?',
        'q_import': '{name} importa el quadrant des d\'una foto o un PDF?',
        'q_manager': '{name} comunica el canvi de torn al gestor?',
        'torns_says': 'A Torns: {text}',
        'hub_title': 'Torns davant d\'altres apps de torns',
        'hub_intro': 'Comparatives de funcionalitats entre Torns i altres apps de calendari i canvis de torn que es fan servir a Espanya, amb més de 30 punts cadascuna i les fonts públiques de cada dada.',
    },
    'gl': {
        'title': 'Torns vs {name}: comparativa de funcionalidades',
        'checked': 'Datos das fontes públicas de cada app, revisados o {date}.',
        'feature': 'Funcionalidade', 'yes': 'Si', 'no': 'Non', 'none': 'Non consta',
        'sources': 'Fontes', 'torns_source': 'Torns: funcionalidades, plans e límites',
        'note': 'Comparativa baseada só no que publica cada app (web, tendas de aplicacións e centro de axuda) á data da revisión. Se ves un dato desactualizado, escribe a hola@torns.app. {name} é unha marca dos seus titulares.',
        'faq': 'Preguntas frecuentes',
        'q_diff': 'Que diferenza hai entre Torns e {name}?',
        'q_swaps': '{name} permite cambiar quendas con compañeiros?',
        'q_langs': '{name} está en catalán, galego e éuscaro?',
        'q_price': 'Canto custa {name}?',
        'q_import': '{name} importa o cadro de quendas desde unha foto ou un PDF?',
        'q_manager': '{name} comunica o cambio de quenda ao xestor?',
        'torns_says': 'En Torns: {text}',
        'hub_title': 'Torns fronte a outras apps de quendas',
        'hub_intro': 'Comparativas de funcionalidades entre Torns e outras apps de calendario e cambios de quenda que se usan en España, con máis de 30 puntos cada unha e as fontes públicas de cada dato.',
    },
    'eu': {
        'title': 'Torns vs {name}: funtzioen konparaketa',
        'checked': 'Aplikazio bakoitzaren iturri publikoetako datuak, {date}(e)an berrikusiak.',
        'feature': 'Funtzioa', 'yes': 'Bai', 'no': 'Ez', 'none': 'Ez da ageri',
        'sources': 'Iturriak', 'torns_source': 'Torns: funtzioak, planak eta mugak',
        'note': 'Aplikazio bakoitzak argitaratzen duenean soilik oinarritutako konparaketa (webgunea, aplikazio-dendak eta laguntza-zentroa), berrikuspenaren datan. Datu zaharkiturik ikusten baduzu, idatzi hola@torns.app helbidera. {name} bere titularren marka da.',
        'faq': 'Ohiko galderak',
        'q_diff': 'Zer desberdintasun dago Torns eta {name}-ren artean?',
        'q_swaps': '{name}-k txandak lankideekin aldatzeko aukera ematen du?',
        'q_langs': '{name} katalanez, galizieraz eta euskaraz dago?',
        'q_price': 'Zenbat kostatzen da {name}?',
        'q_import': '{name}-k koadrantea argazki edo PDF batetik inportatzen du?',
        'q_manager': '{name}-k txanda-aldaketa kudeatzaileari jakinarazten dio?',
        'torns_says': 'Torns-en: {text}',
        'hub_title': 'Torns eta txanden beste aplikazioak',
        'hub_intro': 'Torns eta Espainian erabiltzen diren egutegi eta txanda-aldaketako beste aplikazio batzuen arteko funtzio-konparaketak, bakoitza 30 puntu baino gehiagorekin eta datu bakoitzaren iturri publikoekin.',
    },
}


def text(value, lang):
    if isinstance(value, dict):
        return value.get(lang) or value.get('es') or ''
    return value or ''


def cell(row, lang):
    value = text(row.get('value', '-'), lang)
    word = {'yes': T[lang]['yes'], 'no': T[lang]['no'], '-': T[lang]['none']}.get(value, value)
    detail = text(row.get('detail', ''), lang)
    return f'{word}. {detail}' if detail and value in ('yes', 'no', '-') else (f'{word}: {detail}' if detail else word)


def prefix(lang):
    return BASE + ('' if lang == 'es' else f'/{lang}')


def faq(app, torns, lang):
    t, name, rows, trows = T[lang], app['name'], app['rows'], torns['rows']
    pairs = [
        (t['q_diff'], f"{text(app['summary'], lang)} {t['torns_says'].format(text=text(trows['focus']['detail'], lang))}"),
        (t['q_swaps'], f"{text(rows['swaps_between_colleagues']['detail'], lang)} {t['torns_says'].format(text=text(trows['swaps_between_colleagues']['detail'], lang))}"),
        (t['q_langs'], f"{text(rows['languages']['detail'], lang)} {t['torns_says'].format(text=text(trows['languages']['detail'], lang))}"),
        (t['q_price'], f"{text(rows['price']['detail'], lang)} {t['torns_says'].format(text=text(trows['price']['detail'], lang))}"),
        (t['q_import'], f"{text(rows['import_roster']['detail'], lang)} {t['torns_says'].format(text=text(trows['import_roster']['detail'], lang))}"),
        (t['q_manager'], f"{text(rows['manager_notification']['detail'], lang)} {t['torns_says'].format(text=text(trows['manager_notification']['detail'], lang))}"),
    ]
    return [(q.format(name=name), a.strip()) for q, a in pairs]


def main():
    features = json.loads((DATA / 'features.json').read_text())
    torns = json.loads((DATA / 'torns.json').read_text())
    apps = {p.stem: json.loads(p.read_text()) for p in sorted(DATA.glob('*.json')) if p.stem not in ('features', 'torns')}

    for slug, app in apps.items():
        name = app['name']
        y, m, d = app['checked_on'].split('-')
        for lang in LANGS:
            t = T[lang]
            pairs = faq(app, torns, lang)
            schema = {'@type': 'FAQPage', 'mainEntity': [
                {'@type': 'Question', 'name': q, 'acceptedAnswer': {'@type': 'Answer', 'text': a}} for q, a in pairs]}
            (ROOT / 'schema' / f'vs-{slug}.{lang}.json').write_text(json.dumps(schema, ensure_ascii=False, indent=2) + '\n')

            md = [f"# {t['title'].format(name=name)}", '', t['checked'].format(date=f'{d}/{m}/{y}'), '',
                  f"- **Torns**: {text(torns['rows']['focus']['detail'], lang)}",
                  f"- **{name}**: {text(app['summary'], lang)}", '']
            groups = {}
            for f in features['features']:
                groups.setdefault(f['group'], []).append(f)
            for group, items in groups.items():
                md += [f"## {text(features['groups'][group], lang)}", '', f"| {t['feature']} | Torns | {name} |", '|---|---|---|']
                for f in items:
                    row_t, row_o = torns['rows'].get(f['key'], {}), app['rows'].get(f['key'], {})
                    md.append('| ' + ' | '.join(s.replace('|', '/') for s in [text(f['label'], lang), cell(row_t, lang), cell(row_o, lang)]) + ' |')
                md.append('')
            md += [f"## {t['faq']}", '']
            for q, a in pairs:
                md += [f'### {q}', '', a, '']
            md += [f"## {t['sources']}", '', f"- [{t['torns_source']}]({prefix(lang)}/funcionalidades)"]
            md += [f"- [{s.get('title') or s['url']}]({s['url']})" for s in app.get('sources', [])]
            md += ['', t['note'].format(name=name), '']
            (ROOT / 'markdown' / f'vs-{slug}.{lang}.md').write_text('\n'.join(md))

    for lang in LANGS:
        t = T[lang]
        md = [f"# {t['hub_title']}", '', t['hub_intro'], '']
        for slug, app in apps.items():
            md.append(f"- [Torns vs {app['name']}]({prefix(lang)}/torns-vs-{slug}): {text(app['summary'], lang)}")
        (ROOT / 'markdown' / f'comparativas.{lang}.md').write_text('\n'.join(md) + '\n')
    print(f'{len(apps)} apps × {len(LANGS)} languages')


if __name__ == '__main__':
    main()
