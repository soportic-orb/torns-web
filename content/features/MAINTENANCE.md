# Mantenimiento de la página «Funcionalidades de Torns»

La página solo puede afirmar lo que existe y está activo en producción. La fuente de verdad es el código de `soportic-orb/Torns` y la tabla `app_config`.

## Ficheros

| Fichero | Qué es |
|---|---|
| `content/features/feature-inventory.md` | Inventario con estado, plan, límites (y su clave de `app_config`) y archivos fuente de cada funcionalidad. Incluye las decisiones tomadas sobre qué entra en la página. |
| `content/features/funcionalidades.{es,ca,gl,eu}.md` | Texto maestro de la página, uno por idioma. El castellano es el de referencia. |
| `content/features/published-facts.json` | Los valores de configuración que afirma la página (flags, límites, precios, pruebas), en formato comparable. |
| `scripts/build_features.py` | Genera `content/features/build/` (HTML, JSON-LD, `.md`, `llms.txt`, `llms-full.txt`) a partir de los maestros. |
| `scripts/features_diff.py` | Compara `published-facts.json` con producción (solo lectura) y lista las diferencias. |
| `content/features/build/DEPLOY-WORDPRESS.md` | Checklist para publicar en WordPress/Divi. |

## Cuándo actualizar

- **Antes de cada release de Torns:** ejecuta `scripts/features_diff.py`. Si sale alguna diferencia, la página está desactualizada.
- **Un flag pasa a ON** (un módulo nuevo se activa en `app_config` o en `feature_flags`): decide si entra en la página; si entra, añádelo al inventario, a los cuatro maestros y a `published-facts.json`.
- **Un flag pasa a OFF o se retira una funcionalidad:** quítala de los cuatro maestros (sección, tabla Free × Pro, FAQ si la menciona) y de `published-facts.json`.
- **Cambia un precio o un límite** (`plans_config`, `schedule_scanner`, `legal_assistant`, `social_wall`, `panel_license_tiers`, `panel_trial_defaults`, `subscription_config`): actualiza la cifra en los cuatro maestros (búscala en «Datos clave», en la sección, en la tabla resumen y en la FAQ) y en `published-facts.json`.
- **Nueva release con funcionalidades nuevas** aunque no tengan flag: revisa el inventario contra el código (pantallas nuevas en `frontend/app/`, routers nuevos en `backend/routes/`).
- **Cambia el nombre de una funcionalidad en la app** (`frontend/src/i18n/translations.ts`): usa el nombre nuevo en los cuatro idiomas.

## Cómo comprobar antes de cada release

```bash
# Solo lectura: SELECT de app_config y subscription_config por la API REST de Supabase.
SUPABASE_URL=https://… SUPABASE_SERVICE_KEY=… python3 scripts/features_diff.py
```

- Sale `✓ Every published value matches production.` y código 0: la página está al día.
- Sale `✗ …` y código 1: cada línea dice qué afirma la página y qué tiene producción. Actualiza los maestros y `published-facts.json`.
- Sale `? feature_flags.…` o `? …_enabled`: hay un interruptor encendido en producción que la página no cubre. Puede ser una funcionalidad nueva: decide si entra (y, si es interna, añádela a `IGNORED_FLAGS` o `IGNORED_KEYS` del script).

La base de datos es compartida entre producción y desarrollo: el script nunca escribe, y no hay que modificarlo para que escriba.

## Cómo publicar un cambio

1. Edita los maestros (`funcionalidades.es.md` primero; luego `.ca`, `.gl`, `.eu` con la terminología de la app) y `published-facts.json`.
2. **Actualiza la fecha visible** «Última actualización» en «Datos clave» de los cuatro maestros, y `published_on` en `published-facts.json`.
3. `python3 scripts/build_features.py` — regenera todo `build/`. El `dateModified` del JSON-LD se pone solo con la fecha del día de la compilación: **compila el mismo día que publicas**.
4. `python3 scripts/features_diff.py` debe salir sin diferencias.
5. Sigue `build/DEPLOY-WORDPRESS.md`: vuelve a pegar el HTML y el JSON-LD de cada idioma, sube los `.md`, `llms.txt` y `llms-full.txt`, purga la caché y valida con Rich Results Test.
6. Commit en una rama y PR; nunca push directo a `main`.

## Reglas del contenido

- Solo funcionalidades activas en producción. Nada en ramas, a medias o con el flag apagado.
- Sin afirmaciones comparativas sobre otras apps (Ley de Competencia Desleal, art. 10).
- Sin superlativos; datos concretos. Cada párrafo se entiende solo y nombra «Torns».
- Privacidad: no afirmar «se borra todo» ni «exportación completa» mientras el código no lo cubra (hoy `copilot_logs` sobrevive al borrado de la cuenta y la exportación no incluye el calendario de trabajo, el muro, Copilot, las cadenas ni las rotaciones).

## Migración a Botble

Los maestros en Markdown son el contenido para la nueva web en Botble (`soportic-orb/torns-web`): se reutilizan tal cual. El JSON-LD se puede servir desde la plantilla de la página y los `.md`, `llms.txt` y `llms-full.txt` desde `public/`.
