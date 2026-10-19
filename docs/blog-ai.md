# IA para el blog

El editor de publicaciones (Blog → Publicaciones) puede redactar con IA el
cuerpo, el título y el resumen de una noticia, y crear su imagen destacada.
Es el mismo planteamiento que el editor de Mailing del Tauler de iSEM.

## Activarla

**Ajustes → Otros → IA para el blog** (solo superadministradores):

- Interruptor «Activar la IA en el editor del blog». Está **apagado** por
  defecto; mientras lo esté, el editor no muestra nada.
- Clave de Anthropic (Claude) y/o de OpenAI (ChatGPT e imágenes). Se guardan
  cifradas con la `APP_KEY`. Vacía = se mantiene la actual; «-» = se borra.
- Modelos (por defecto `claude-opus-5-5`, `gpt-5` y `gpt-image-1`) y el
  servicio de texto por defecto.
- «Probar la conexión» hace una llamada mínima a cada servicio configurado.

También se pueden dar por `.env` (los ajustes del panel tienen prioridad):
`TORNS_AI_ENABLED`, `TORNS_AI_DEFAULT_TEXT`, `TORNS_ANTHROPIC_API_KEY`,
`TORNS_CLAUDE_MODEL`, `TORNS_OPENAI_API_KEY`, `TORNS_OPENAI_TEXT_MODEL`,
`TORNS_OPENAI_IMAGE_MODEL`.

## En el editor

Encima del contenido aparecen dos botones:

- **✨ Escribir con IA**: petición libre, servicio (Claude o ChatGPT), qué
  escribir (artículo completo, título o resumen), idioma (por defecto el de
  la versión que se edita: es, ca, gl o eu), tono y extensión. Con «Usar el
  texto que ya hay» reescribe o resume el contenido actual. El resultado se
  previsualiza y se puede **sustituir el contenido**, **añadir al final**,
  usarlo como **título** o **descripción**, o **volver a generar**.
- **✨ Crear la imagen destacada con IA**: descripción, formato (horizontal
  1536×1024 o cuadrada) y estilo (fotografía, ilustración o dibujo plano con
  los colores de Torns). La imagen se guarda en Medios → `blog-ia` y, con
  «Usar como imagen destacada», queda puesta en el campo Imagen (hay que
  guardar la publicación).

Nada se publica solo: la persona que edita revisa, ajusta y guarda.

## Qué sabe la IA de Torns

El prompt de sistema (`App\Ai\BlogAi::FACTS`) lleva una ficha con lo que se
puede afirmar de la app (funciones, planes, precios, idiomas) y prohíbe
atribuirle cualquier otra cosa, inventar cifras, fechas o normas (pone
`[dato]` o `[fuente]`), comparar Torns con otras apps por su nombre y dar
consejos médicos. **Mantén la ficha al día** cuando cambien funciones o
precios (las mismas fuentes que `content/site`).

## Límites y registro

- 20 textos y 10 imágenes por minuto y persona.
- Pueden usarla quienes tienen permiso para crear o editar publicaciones.
- Cada llamada se anota en el log de Laravel (`Torns blog AI`) con la
  persona, el servicio, el modelo y los tokens de entrada y salida.
