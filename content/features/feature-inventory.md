# Inventario de funcionalidades de Torns

Fuente de verdad: el código de `soportic-orb/Torns` (app Expo en `frontend/`, backend FastAPI en `backend/`, panel Next.js en `panel/`) y la tabla `app_config` de producción leída con `SELECT` el 12/10/2026. No se ha modificado nada de la app.

**Cómo leerlo**

- **Nombre en UI (ES)**: texto del fichero de traducciones de la app (`frontend/src/i18n/translations.ts`, bloque `es`) o del panel (`panel/messages/es.json`), con la clave entre paréntesis. La app descarga traducciones del servidor (`GET /translations`) que pueden prevalecer: el texto en producción podría diferir.
- **Plan**: Free / Pro / B2B (centros y organizaciones, panel) / Asociaciones. Los límites citan la clave de `app_config` de la que salen.
- **Estado**: `LIVE` (flag ON o sin flag, en `main`), `FLAG OFF`, `EN RAMA (nombre)`, `PLANIFICADO`. Todo lo que no es inequívocamente `LIVE` está en «❓ Dudas de estado».
- **Plataformas**: iOS · Android · web (escritorio; en el navegador del móvil la web redirige a las tiendas) · panel (web para organizaciones).
- **Idiomas de la app**: ES, CA, GL, EU completos. EN (389 claves), FR y PT (273) son parciales y caen al castellano. Salvo que se diga lo contrario, cada funcionalidad está en los cuatro idiomas completos.

---

## 1. Cuenta, registro y acceso

| Nombre en UI (ES) | Qué es (técnico) | Qué resuelve | Plan · límites | Estado | Plataformas | Fuente |
|---|---|---|---|---|---|---|
| Iniciar sesión con correo | Login con email y contraseña, verificación de correo por código | Acceso seguro a la cuenta | Todos | LIVE | iOS · Android · web | `frontend/app/(auth)/login.tsx`, `backend/routes/auth.py` |
| Continuar con Apple / Google / Microsoft | Login social (Apple solo iOS); vincular cuentas desde Perfil › Integraciones | Entrar sin crear contraseña | Todos · flags `feature_flags.apple_signin_enabled`, `google_signin_enabled`, `microsoft_signin_enabled`, `social_registration_enabled` = ON | LIVE | iOS · Android · web | `frontend/app/(auth)/login.tsx`, `social-onboarding.tsx`, `backend/routes/auth.py` |
| Registro con código de centro (`registerWithCenterCode`) | Alta con un código de centro o de invitación de 8 caracteres; puede dar días de prueba o descuento | Unirse directamente al centro correcto | Todos | LIVE | iOS · Android · web | `frontend/app/(auth)/center-code.tsx` |
| Periodo de prueba | Las cuentas nuevas empiezan en `trial` con acceso Pro | Probar Pro antes de pagar | 30 días · tabla `subscription_config` (`default.trial_days = 30`) | LIVE | todas | `backend/dependencies.py:202` |
| Tu asociación tiene una oferta para ti (`associationOfferTitle`) | Si el email del alta está en la lista de socios de una asociación, ofrece plan patrocinado o con descuento | Pro pagado por la asociación | Asociaciones | ⚠️ ver duda 9 | iOS · Android · web | `frontend/src/components/AssociationOfferSheet.tsx`, `backend/routes/associations.py` |
| Exportar mis datos | Envía por email un ZIP con los datos del usuario | Derecho de acceso/portabilidad (RGPD) | Todos | LIVE · ⚠️ ver duda 15 | iOS · Android · web | `backend/routes/gdpr.py:42` |
| Eliminar mi cuenta | Borrado de la cuenta y sus datos (~40 tablas) | Derecho de supresión (RGPD) | Todos | LIVE · ⚠️ ver duda 15 | iOS · Android · web | `backend/routes/gdpr.py`, `backend/routes/admin.py:571` |

## 2. Calendario de trabajo (`tabCalendar`: Calendario / Calendari / Calendario / Egutegia)

| Nombre en UI (ES) | Qué es (técnico) | Qué resuelve | Plan · límites | Estado | Plataformas | Fuente |
|---|---|---|---|---|---|---|
| Vista mensual / diaria / lista / anual (`calViewMonth`, `calViewDay`, `calViewList`, `calViewYear`) | Calendario personal de turnos con cuatro vistas (no hay vista semanal) | Ver de un vistazo todos los turnos | Todos · `feature_flags.work_calendar_enabled` = ON | LIVE | iOS · Android · web | `frontend/app/(tabs)/calendar.tsx`, `backend/routes/work_calendar.py` |
| Tipos de turno: Mañana, Tarde, Noche, Día, 24h, Localizable | Cada turno con tipo, color e icono | Distinguir turnos rápidamente | Todos | LIVE | todas | `frontend/src/utils/shiftTypeConfig.tsx` |
| Añadir un turno (`addOneShift`) | Alta de turno: centro, unidad, área, tipo, horario, notas, avisos, **Hora extraordinaria / JAC** (`overtimeShift`) | Llevar el registro propio de turnos | Todos | LIVE | todas | `frontend/app/create-work-shift.tsx` |
| Añadir varios turnos (`addMultipleShifts`) | Modo para poner un lugar habitual en muchos días con toques | Cargar un mes en segundos | Todos | LIVE | todas | `frontend/app/(tabs)/calendar.tsx` |
| Añadir vacaciones / no disponible (`addVacation`) | Marca días como no disponibles | Que nadie te proponga cambios esos días | Todos | LIVE | todas | `backend/routes/unavailability.py` |
| Lugares habituales (`habitualPlaces`) | Plantillas de centro, unidad, área, tipo y horario | No reescribir los mismos datos | Todos | LIVE | todas | `frontend/app/habitual-places.tsx`, `backend/routes/habitual_places.py` |
| Rotaciones (`patternsTitle`; menú «Crear rotación de turnos», `patternMenuOption`) | Ciclo de N semanas o N días que rellena el calendario, con vista previa; puede saltarse festivos y días no disponibles | Cargar una rotación fija de una vez | **Pro** · `app_config.shift_patterns_enabled` = ON · `plans_config.free_tier.has_shift_patterns` ausente → Free bloqueado · máx. 10 rotaciones, 800 turnos, 750 días | LIVE | todas | `frontend/app/shift-patterns.tsx`, `backend/routes/shift_patterns.py`, `docs/shift-patterns.md` |
| Importar cuadrante (`scanMenuOption`) | Foto, PDF, captura u hoja de cálculo del cuadrante → la IA extrae solo tus turnos → revisas línea a línea | Pasar el cuadrante de papel al calendario | Free: 2 lecturas en total · Pro: 30 al mes · `app_config.schedule_scanner.free_lifetime_limit` / `pro_monthly_limit` · archivo ≤ 10 MB, ≤ 4 páginas · `schedule_scanner_enabled` = ON | LIVE | todas | `frontend/app/schedule-import.tsx`, `backend/routes/schedule_import.py`, `docs/schedule-scanner.md` |
| Festivos | Festivos nacionales, autonómicos y locales en el calendario | Saber qué días son festivos al planificar | Todos | LIVE | todas | `backend/routes/holidays.py`, `frontend/src/offline/calendarStore.ts` |
| Tiempo | Previsión meteorológica en los días del calendario (Open-Meteo) | Planificar desplazamientos | Todos · `feature_flags.weather_enabled` = ON · requiere ubicación en el perfil | LIVE | todas | `backend/routes/weather.py` |
| Calendario sin conexión (`offlineBanner`) | El calendario propio funciona sin red (3 meses atrás, 12 adelante) y sincroniza al volver | Consultar y editar turnos sin cobertura | Todos | LIVE | iOS · Android | `frontend/src/offline/*`, `docs/offline-calendar.md` |
| Mostrar turnos publicados en el Muro (`showWallShifts`) | Superpone en el calendario los turnos que ofrecen los compañeros | Ver qué cambios encajan en tu mes | Todos | LIVE | todas | `frontend/app/(tabs)/calendar.tsx` |
| Libranza de turno por permiso (`markLeaveBtn`) | Marca un turno como permiso sin borrarlo | Llevar el control de permisos | Todos | LIVE | todas | `frontend/app/work-shift-detail/[id].tsx` |
| Compartir calendario (`shareCalendarTitle`) | Enlace público de solo lectura con contraseña opcional; exportación ICS | Que familia o pareja vean tus turnos | **Pro** · `plans_config.free_tier.has_public_calendar` = false | LIVE | todas | `frontend/app/share-calendar.tsx`, `backend/routes/calendar_share.py` |
| Imprimir / Exportar a PDF (`printCalendar`, `exportToPdf`) | Impresión o PDF en formato lista, mes o año | Tener el calendario en papel | **Pro** · `has_calendar_print` = false (solo comprobado en la app) | LIVE | todas | `frontend/app/print-calendar.tsx` |
| Sincronización con el calendario del móvil | Exporta los turnos al calendario de iOS/Android elegido por centro, con alarmas; un solo evento por turno | Ver los turnos en el calendario habitual | Todos | LIVE | iOS · Android | `frontend/src/utils/calendar.ts` |
| Google Calendar (`googleCalendar`) | Vincula una cuenta de Google para detectar conflictos y guardar turnos | Integrar Torns con Google | Todos · `feature_flags.google_calendar_enabled` = ON | LIVE | todas | `frontend/app/google-calendar.tsx`, `backend/routes/google_calendar.py` |
| Sincronización bidireccional (`biSyncTitle`) | Detecta en Google o en el calendario del móvil eventos que parecen turnos y propone importarlos | No duplicar el trabajo de apuntar turnos | **Pro** · `has_calendar_import` = false | LIVE | todas | `frontend/app/calendar-import.tsx`, `backend/routes/calendar_import.py` |
| Mi disponibilidad (`myAvailability`) | Días y tipos en los que estás libre para cambiar; puede importar días libres del calendario | Recibir solo propuestas que te encajan | Todos | LIVE | todas | `frontend/app/(tabs)/availability.tsx`, `backend/routes/availability.py` |

## 3. Cambios de turno (`tabShifts`: Cambios / Canvis / Cambios / Aldaketak)

| Nombre en UI (ES) | Qué es (técnico) | Qué resuelve | Plan · límites | Estado | Plataformas | Fuente |
|---|---|---|---|---|---|---|
| Crear turno a cambiar / Publicar cambio (`createShiftToExchange`, `publishChange`) | Publica un turno para cambiarlo; opción **Ocultar mi identidad** (`hideMyIdentity`); marca automática **Turno urgente** si empieza en < 48 h | Encontrar quién te cubra sin grupos de WhatsApp | Todos | LIVE | todas | `frontend/app/create-shift.tsx`, `backend/routes/shifts.py` |
| Muro (`home`) | Feed de turnos publicados en tus centros, filtrado por centro, tipo, área y fecha; solo misma categoría profesional | Ver todos los cambios disponibles en un sitio | Todos | LIVE | todas | `frontend/app/(tabs)/index.tsx`, `GET /shifts/available` |
| Turnos compatibles (`compatibleShifts`) | Filtro del Muro según tu disponibilidad | Ver solo lo que puedes coger | Todos | LIVE | todas | `frontend/app/(tabs)/index.tsx` |
| Proponer cambio / Me interesa (`makeOffer`, `interestedInShift`) | Propone uno de tus turnos a cambio; 24h exige 24h o dos de 12h | Negociar el cambio dentro de Torns | Todos | LIVE | todas | `frontend/app/shift/[id].tsx`, `backend/routes/offers.py` |
| Propuestas recibidas / Aceptar / Rechazar (`offersReceived`, `acceptOffer`, `rejectOffer`) | Gestión de propuestas; al aceptar, los dos turnos cambian de titular en ambos calendarios | Cerrar el cambio con un toque | **Free: 3 cambios al mes** (`plans_config.free_tier.max_swaps_per_month` = 3; se reinicia el día 1) · Pro ilimitado | LIVE | todas | `backend/routes/offers.py:320` |
| Detalle del cambio / Eliminar el cambio (`exchangeDetail`, `undoExchange`) | Ficha del cambio hecho; deshacerlo requiere el acuerdo de ambos | Corregir un cambio por mutuo acuerdo | Todos | LIVE | todas | `frontend/app/exchange-detail/[id].tsx` |
| Comunicar al gestor de planillas (`notifyScheduleManager`) | Envía el cambio por email al gestor (automático o desde tu correo); si el centro usa el panel, lo ve el gestor y la app muestra **El gestor ha visualizado el cambio** | Formalizar el cambio con quien hace el cuadrante | **Pro** · `has_schedule_manager_notify` = false (solo en la app) | LIVE | todas | `frontend/app/schedule-managers.tsx`, `backend/routes/schedule_managers.py`, `panel_cambios.py` |
| Cadenas de cambios (`swapChainsTitle`) | El servidor detecta cambios circulares entre 3 o 4 personas cada 15 min; cada una acepta; chat de grupo | Cambios imposibles entre dos | **Pro** · `has_swap_chains` = false | LIVE · ⚠️ ver duda 3 | todas | `frontend/app/swap-chains/*`, `backend/routes/swap_chains.py` |
| Registrar cambio manual (`manualSwapTitle`) | Registra un cambio pactado fuera de Torns, aunque el otro no use la app | Mantener el calendario al día | Todos · `feature_flags.external_swaps_enabled` = ON | LIVE | todas | `frontend/app/register-manual-swap/[entryId].tsx` |
| Modo SOS / Lanzar SOS (`sosTitle`, `sosLaunchButton`) | Pone el turno arriba del Muro 24 h, envía push urgente y lo reflota en el muro social; turno en < 7 días, una vez por turno, un SOS activo por usuario, máx. 3 por centro y área | Cubrir un turno de última hora | **Pro** · `has_sos` = false · `sos_config.enabled` = ON | LIVE | todas | `frontend/app/sos-shift/[id].tsx`, `backend/routes/shifts.py:911` |
| Turno a cubrir / Apuntarme al turno (`coverageDetailTitle`, `coverageApply`) | Turnos que publica el centro desde el panel; los profesionales se apuntan | Cubrir vacantes del centro | Todos (el centro necesita B2B) | LIVE | todas | `frontend/app/coverage-shift/[id].tsx`, `backend/routes/coverage.py` |
| Historial (`shiftHistory`) | Turnos publicados ya pasados | Consultar lo que ya se cambió | Todos | LIVE | todas | `frontend/app/shift-history.tsx` |

## 4. Muro social y mensajes

| Nombre en UI (ES) | Qué es (técnico) | Qué resuelve | Plan · límites | Estado | Plataformas | Fuente |
|---|---|---|---|---|---|---|
| Muro › Social (`wallViewSocial`) | Muro de cada centro: publicaciones de turnos y mensajes, comentarios, reacciones, lugar a nivel de municipio, reportar, bloquear; solo misma categoría profesional; moderación con IA antes de publicar; mensajes visibles 30 días | Sustituir el grupo de WhatsApp del centro | Comentar y reaccionar: todos · 400 caracteres, 20 mensajes y 60 comentarios/día (`app_config.social_wall`) · publicar mensajes: ⚠️ ver duda 1 · `social_wall_enabled` = ON | LIVE (confirmado) | todas | `frontend/src/components/SocialWallView.tsx`, `backend/routes/wall.py`, `docs/social-wall.md` |
| Compartir fuera de Torns (`wallShare`) | Enlace público a tu publicación | Difundir un turno fuera de la app | Todos · `social_wall_sharing_enabled` = ON | LIVE | todas | `backend/routes/share.py` |
| Mensajes (`messages`) | Chats 1 a 1 y de grupo en tiempo real, reacciones, editar y borrar | Hablar del cambio sin dar el teléfono | Todos · `can_use_chat` = true | LIVE | todas | `frontend/app/(tabs)/messages.tsx`, `backend/routes/messaging.py` |
| Actividad (`activity`) | Historial de propuestas, SOS, comunicados, coberturas y resumen de Copilot | No perderse nada de lo que pasa | Todos | LIVE | todas | `frontend/app/activity.tsx` |

## 5. Asistentes de IA

| Nombre en UI (ES) | Qué es (técnico) | Qué resuelve | Plan · límites | Estado | Plataformas | Fuente |
|---|---|---|---|---|---|---|
| Copilot Torns (`aiPickerCopilotTitle`) | Chat con IA (Anthropic Claude Haiku 4.5 / Sonnet 4.6) que gestiona el calendario, busca turnos compatibles, ejecuta cambios, escribe a compañeros, lee fotos o PDF adjuntos | Hacer en una frase lo que serían varios pasos | **Pro y prueba** · `has_copilot` = false · `copilot_config.enabled` = ON | LIVE | todas (voz por micrófono solo web) | `frontend/app/copilot-chat.tsx`, `backend/routes/copilot.py`, `services/copilot/*` |
| Resumen diario de Copilot (`copilotDigestLabel`) | Push hacia las 21:00 con turnos del Muro que encajan con tu calendario y disponibilidad; sin IA | Enterarse de oportunidades sin mirar | **Pro** · `copilot_config.daily_digest_enabled` = ON · desactivable | LIVE | iOS · Android | `docs/copilot-daily-digest.md` |
| Consultor legal (`legalAssistant`) | Respuestas sobre convenios, Estatuto Marco, EBEP, ET y normativa, solo con el corpus curado y citando documento, artículo y vigencia; filtra por comunidad, relación laboral, centro y sector | Resolver dudas laborales con fuente | Free: 2 consultas en total · Pro: 20 al día · `app_config.legal_assistant.free_lifetime_queries` / `pro_daily_queries` · 8 turnos por conversación, 5 adjuntos de 10 MB · `legal_assistant_enabled` = ON | LIVE | todas | `frontend/app/legal-*.tsx`, `backend/routes/legal.py`, `docs/legal-assistant/` |

## 6. Avisos y notificaciones

| Nombre en UI (ES) | Qué es (técnico) | Qué resuelve | Plan · límites | Estado | Plataformas | Fuente |
|---|---|---|---|---|---|---|
| Notificaciones push | Propuestas, cambios, mensajes, muro, SOS, cadenas, coberturas, soporte, cuenta | Reaccionar a tiempo | Todos | LIVE | iOS · Android | `backend/services/notifications.py` |
| Notificaciones en este navegador (`webPushTitle`) | Push en el navegador de escritorio | Avisos también en el ordenador | Todos | LIVE | web | `backend/routes/web_push.py` |
| Configuración de alertas (`alertSettingsTitle`) | Avisos por centro y área, cadenas, coberturas, email, resumen de Copilot | Recibir solo lo que interesa | Todos | LIVE | todas | `frontend/app/alert-settings.tsx` |
| Recordatorios de turnos (`shiftRemindersTitle`) | Turnos de 06:00–16:59 se recuerdan a las 20:00 del día anterior; los de 17:00–05:59, a las 10:00 del mismo día (hora de España); hay que activarlo | No olvidar un turno | **Todos** · `has_shift_reminders` = true en Free | LIVE | iOS · Android | `backend/tasks/background.py:558` |

## 7. Perfil, estadísticas y otros

| Nombre en UI (ES) | Qué es (técnico) | Qué resuelve | Plan · límites | Estado | Plataformas | Fuente |
|---|---|---|---|---|---|---|
| Mis centros de trabajo / Unirse al centro | Centros a los que perteneces | Ver turnos de tus centros | Free: 1 centro (`max_centers` = 1) · Pro: varios | LIVE | todas | `backend/routes/centers.py:415` |
| Estadísticas de trabajo (`workStats`) | Por mes o año: turnos, horas, horas extra, sábados, domingos, festivos, por tipo, área y día; cambios y compañeros | Saber cuánto has trabajado | **Pro** · `has_work_stats` = false | LIVE | todas | `frontend/app/work-stats.tsx`, `backend/routes/work_stats.py` |
| Perfil público (`publicProfile`) | Perfil visible a compañeros con turnos publicados y centros en común; opción **Mostrar calendario de turnos** (Pro) | Que te encuentren para cambiar | Todos (calendario: Pro) | LIVE | todas | `frontend/app/public-profile/[id].tsx` |
| Pantalla principal (`homeScreen`) | Abrir la app en Muro o en Calendario | Ir directo a lo que más usas | Todos | LIVE | todas | `frontend/app/(tabs)/profile.tsx` |
| Mis puntos / Clasificación general (`gamificationMyPoints`, `gamificationRankingLabel`) | Puntos por turnos publicados e invitaciones que se activan; ranking global y por centro; voluntario | Reconocer a quien hace crecer la comunidad | Todos · `feature_flags.gamification_enabled` = ON | LIVE · ⚠️ ver duda 7 | todas | `frontend/app/leaderboard.tsx`, `backend/routes/gamification.py` |
| Invitar compañeros (`inviteColleagues`) | Código por centro como enlace, código o cartel | Traer al equipo a Torns | Todos · `invitations_enabled` = ON | LIVE | todas | `frontend/app/invite-companions.tsx` |
| Noticias y Novedades (`newsAndUpdates`) | Noticias publicadas por Torns con enlaces a pantallas de la app | Enterarse de cambios y novedades | Todos | LIVE | todas | `frontend/app/news/*`, `backend/routes/news.py` |
| Contacta con el Soporte / Portal de Soporte | Tickets con adjuntos y centro de ayuda con búsqueda | Resolver dudas | Todos | LIVE | todas | `frontend/app/support/*`, `backend/routes/support.py` |
| Juego T-Rex (`trexGameTitle`) | Minijuego con puntuaciones y retos entre compañeros | Entretenimiento | Todos | LIVE · ⚠️ ver duda 8 | todas | `frontend/app/trex-game.tsx` |
| Tema e Idioma | Claro / oscuro / automático; ES, CA, GL, EU | Usar Torns en tu idioma | Todos | LIVE | todas | `frontend/src/store/languageStore.ts` |

## 8. Widgets y Apple Watch (`widgetsAndWatch`) — confirmado

| Nombre en UI (ES) | Qué es (técnico) | Qué resuelve | Plan · límites | Estado | Plataformas | Fuente |
|---|---|---|---|---|---|---|
| Widgets: Mis próximos turnos · Propuestas · Último turno publicado · Mis cambios | 4 widgets de pantalla de inicio (pequeño y mediano) | Ver turnos sin abrir la app | Todos (no se ha encontrado flag ni gating) | LIVE (confirmado) | iOS 15.1+ · Android | `targets/torns-widget`, `frontend/src/widgets/androidWidgets.tsx` |
| App de Apple Watch y complicaciones «Próximo turno», «Propuestas» | Próximos turnos y tus cambios en el reloj | Consultar el turno desde la muñeca | Todos (sin flag ni gating) | LIVE (confirmado) | watchOS 9+ | `targets/torns-watch`, `targets/torns-watch-widget` |

## 9. Planes y pago (usuarios de la app)

| Nombre en UI (ES) | Qué es (técnico) | Plan · límites | Estado | Fuente |
|---|---|---|---|---|
| Free | Plan gratuito tras la prueba | 3 cambios/mes, 1 centro, chat, publicar turnos, recordatorios; sin SOS, Copilot, cadenas, estadísticas, calendario público, importación, impresión, aviso al gestor, rotaciones · `plans_config.free_tier` · `free_tier_enabled` = ON | LIVE | `backend/dependencies.py:714` |
| Pro Mensual | Compra integrada (App Store / Google Play) | 2,99 €/mes · `plans_config.pro_plans[0]` (`com.tornsios.pro.monthly`) | LIVE | `backend/routes/payments.py` |
| Pro Anual | Compra integrada | 29,90 €/año («Dos meses gratis con el plan anual») · `plans_config.pro_plans[1]` (`com.tornsios.pro.yearly`) | LIVE | ídem |
| Restaurar · Cupones · Gestionar suscripción | Restaurar compras, códigos promocionales (ofertas de Apple/Google), enlace a la gestión de la tienda | — | LIVE | `frontend/app/subscription.tsx` |

## 10. Entidades (B2B, panel `panel.torns.app`)

Roles: propietario y responsable (panel completo), gestor (centros asignados). Módulos activables por organización.

| Nombre en UI (ES) | Qué es (técnico) | Plan · límites | Estado | Fuente |
|---|---|---|---|---|
| Panel de Cliente / Dashboard | Usuarios activos, uso de licencias, centros, prueba, próxima factura | B2B | LIVE | `panel/src/app/(dashboard)/dashboard/page.tsx` |
| Usuarios panel | Invitar responsables y gestores, asignar centros | B2B | LIVE | `users/page.tsx` |
| Trabajadores | Personal unido con el código del centro; sus coberturas, cambios y calendario | B2B | LIVE | `workers/*` |
| Turnos a cubrir | Publicar vacantes que ven los profesionales en la app; asignar, recordar, mensajear | B2B · `coverage_shifts_enabled` (por organización, ON por defecto) | LIVE | `coverage/*`, `backend/routes/panel_coverage.py` |
| Cambios | Bandeja de cambios hechos en la app por el personal; marcar como tramitado | B2B · `cambios_enabled` (por organización, OFF por defecto) | LIVE · ⚠️ ver duda 11 | `cambios/page.tsx` |
| Mensajes | Chat entre gestores y profesionales | B2B · `messaging_enabled` (ON por defecto) | LIVE | `messages/page.tsx` |
| Campañas de correo + Automatizaciones | Campañas al propio personal por centro y categoría, programación, estadísticas, traducción automática; flujos automáticos | B2B · `mailing_enabled` (por organización, lo activa Torns) | LIVE · ⚠️ ver duda 11 | `mailing/*`, `components/automations/FlowBuilder.tsx` |
| Editor visual de correos | Editor de bloques con IA | B2B · `app_config.mailing_visual_editor_enabled` = **OFF** | FLAG OFF | `panel/src/components/mailing-editor/*` |
| Centros y códigos / Lugares de trabajo | Centros, áreas y códigos de acceso; estadísticas por centro | B2B | LIVE | `centers/*`, `units/page.tsx` |
| Licencias / Facturación | Simulador de precio, pago con Stripe, firma de contrato, facturas en PDF | Ver «Hechos de producto» | LIVE | `licenses/page.tsx`, `billing/page.tsx` |
| Soporte | Tickets y base de conocimiento | B2B | LIVE | `support/*` |
| Mi cuenta | Datos, contraseña, doble factor (2FA), módulos | B2B | LIVE | `account/page.tsx` |

## 11. Asociaciones (`Panel de Asociación`)

| Nombre en UI (ES) | Qué es (técnico) | Plan · límites | Estado | Fuente |
|---|---|---|---|---|
| Resumen · Contactos · Socios · Códigos · Contrato · Soporte | La asociación patrocina total o parcialmente el plan de sus socios mediante códigos y listas de emails; contrato anual | Asociaciones · patrocinio total = Pro sin coste para el socio (`subscription_status = sponsored`) · 15 días de gracia si caduca | LIVE · ⚠️ ver duda 9 | `panel/src/app/(dashboard)/asociacion/*`, `backend/routes/association_panel.py`, `services/association_lifecycle.py` |

---

## Hechos de producto verificables

| Hecho | Valor | Fuente |
|---|---|---|
| Empresa responsable | Soportic (Octavi Rodríguez) | Confirmado por Octavi |
| Público | Profesionales sanitarios en España: enfermera/o, médico/a, TES, TCAE, psicólogo/a, fisioterapeuta, administrativo/a, matrón/a, odontólogo/a, podólogo/a | `app_config.professional_categories` |
| Plataformas | iOS (iPhone y iPad), Android, web de escritorio, Apple Watch (watchOS 9+), widgets iOS 15.1+ y Android; panel web para organizaciones | `frontend/app.json`, `targets/*` |
| App Store | https://apps.apple.com/es/app/torns/id6760354618 | código de la app |
| Google Play | https://play.google.com/store/apps/details?id=com.tornsapp.android | código de la app |
| Idiomas de la app | Castellano, catalán, gallego y euskera (completos) | `translations.ts`, tabla `languages` |
| Idiomas del panel | Castellano, catalán, inglés | `panel/messages/*` |
| Prueba (usuarios) | 30 días con funciones Pro | `subscription_config.default.trial_days` |
| Pro | 2,99 €/mes · 29,90 €/año | `plans_config.pro_plans` |
| Free | 3 cambios/mes, 1 centro | `plans_config.free_tier` |
| Licencias B2B (por usuario activo y mes) | 1–50: 0,99 € · 51–100: 0,89 € · 101–500: 0,79 € · 501–1000: 0,69 € · 1001–2000: 0,59 € · 2001–3000: 0,49 € · 3001+: 0,39 € · anual = 10 meses | `app_config.panel_license_tiers`, `backend/routes/panel.py:1309` |
| Prueba B2B | 30 días, hasta 50 licencias | `app_config.panel_trial_defaults` (⚠️ duda 12) |
| Datos tratados | Datos personales y laborales estándar (identificación, contacto, categoría, centros, turnos, mensajes). **No se han encontrado datos clínicos** en ninguna tabla; la moderación del muro bloquea datos de pacientes | `supabase_schema.sql`, `services/wall_moderation.py` |
| Escáner de cuadrantes | El archivo no se guarda: se procesa en memoria y se descarta; solo se guardan las líneas que confirmas | `docs/schedule-scanner.md`, `services/schedule_scanner/files.py` |
| Consultor legal | Responde solo con el corpus curado y cita la fuente; los adjuntos no se usan como fuente | `docs/legal-assistant/README.md` |
| IA | Anthropic Claude (Copilot, Consultor legal, escáner, moderación del muro) | `backend/services/llm.py` |
| Derechos RGPD | Exportar mis datos y Eliminar mi cuenta desde la app | `backend/routes/gdpr.py` |
| Contacto | soporte@torns.app · hola@torns.app | ⚠️ duda 14 |

---

## ❓ Dudas de estado

1. **Muro social — publicar mensajes.** `app_config.social_wall.free_can_post_message = true` (el servidor permite a Free publicar mensajes), pero la app muestra «Publicar mensajes es de Pro» (`can_post_message`). ¿Publicar mensajes en el muro social es Free o Pro?
2. **Límite de cambios en Free.** Producción tiene `max_swaps_per_month = 3`; el valor por defecto del código es 2. Publicaré **3**. ¿Correcto?
3. **Cadenas de cambios: ver sin ser Pro.** `can_view_swap_chains = true` para Free, pero la app bloquea la lista entera a Free. ¿Publico «Cadenas de cambios: Pro» sin matices? ¿Y cómo quieres llamarlas en la página: «Cadenas de cambios» (nombre de la UI) o «Canvis a 3»?
4. **Mapas.** `feature_flags.maps_enabled = OFF` (ubicación de centros y lugares en mapa). ¿Excluir?
5. **Editor visual de correos (panel).** Flag `mailing_visual_editor_enabled = OFF`, recién fusionado. ¿Excluir hasta activarlo?
6. **Alta pública de asociaciones.** `feature_flags.associations_enabled` no existe (= OFF): solo controla la pantalla pública de comprobación de email. Los códigos de asociación y el patrocinio sí funcionan. ¿Incluyo «Asociaciones» como LIVE?
7. **Puntos y clasificación.** Flag ON. El banner del sorteo Smartbox está caducado (cierre 30/07/2026). ¿Incluyo Puntos y Clasificación sin mencionar sorteos?
8. **Juego T-Rex.** Está LIVE. ¿Lo incluimos en una página de funcionalidades «seria» o lo dejamos fuera?
9. **Asociaciones en la página.** ¿Quieres una sección «Asociaciones» pública (patrocinio del plan Pro a los socios)? Hay una propuesta comercial en `docs/propuesta_modulo_asociaciones.md`: ¿hay precios publicables o se dice «bajo presupuesto»?
10. **Versión web.** Existe una web de escritorio (en el móvil redirige a las tiendas). ¿La anunciamos como plataforma? ¿Con qué URL (app.torns.app u otra)?
11. **Módulos B2B por organización.** «Cambios» (OFF por defecto) y «Campañas de correo» (lo activa Torns) dependen de cada organización. ¿Los presento como «disponibles bajo activación» o como parte estándar del panel?
12. **Prueba B2B: 30 o 15 días.** `panel_trial_defaults.trial_days = 30`, pero el checkout de Stripe aplica 15 días y el texto del panel dice «prueba de 15 días». ¿Qué cifra publico?
13. **IVA en las licencias B2B.** Los precios de Stripe se crean con `tax_behavior: inclusive`. ¿Las tarifas de 0,99 €… son con IVA incluido o sin IVA?
14. **Contactos públicos.** En el código aparece `soporte@torns.app`; `hola@torns.app` solo está en la web; el formulario de contacto va a otra dirección. ¿Publico soporte@torns.app (usuarios) y hola@torns.app (entidades)?
15. **Afirmaciones de privacidad.** (a) `copilot_logs` conserva email, nombre y texto de Copilot tras eliminar la cuenta; (b) la exportación de datos no incluye calendario de trabajo, muro ni Copilot; (c) la ubicación de los datos apunta a un Supabase autoalojado en Clouding (España) pero no está documentado. Propongo **no** afirmar «se borra todo», «exportación completa» ni «datos en España» hasta confirmarlo. ¿De acuerdo, o puedes confirmar la ubicación de los servidores?
16. **Resumen diario de Copilot.** Flag ON, pero un comentario del código dice que está «deliberadamente sin anunciar». ¿Lo incluimos?
17. **Copilot por voz.** El dictado solo funciona en la web (desactivado en iOS/Android). Propongo no mencionarlo. ¿De acuerdo?
18. **Inglés, francés, portugués.** La app tiene traducciones parciales (caen al castellano). Propongo anunciar solo ES, CA, GL y EU. ¿De acuerdo?

---

## ✅ Respuestas de Octavi (12/10/2026)

1. Muro social: publicar mensajes es **Free**.
2. Free: **3 cambios al mes**.
3. Cadenas de cambios: **Pro y periodo de prueba** (30 días). Nombre: «Cadenas de cambios».
4. Mapas: función anulada. **No se muestra.**
5. Editor visual del panel: existe, pero **no se menciona**.
6. Asociaciones: **sí**, LIVE.
7. Puntos y clasificación: **sí**, sin mencionar sorteos.
8. Juego T-Rex: **sí**.
9. Sección Asociaciones: **sí**, precio «bajo presupuesto».
10. Versión web: **sí**, https://app.torns.app.
11. Módulos B2B (Cambios, Campañas de correo): se presentan como **estándar** del panel.
12. Prueba B2B: **30 días**.
13. Licencias B2B: precios **sin IVA**.
14. Contactos: **soporte@torns.app** (usuarios) y **hola@torns.app** (organizaciones).
15. Privacidad: (a) se borra todo, (b) la exportación es completa, (c) **los datos están en España**.
    - Nota del código (12/10/2026): `copilot_logs` sobrevive al borrado de la cuenta y la exportación no incluye calendario de trabajo, muro, Copilot, cadenas ni rotaciones. Hasta que el código lo cubra, la página dice «exportar tus datos» y «eliminar tu cuenta y tus datos», sin «todo» ni «completa».
16. Copilot y resumen diario: **sí**, resumen diario para Pro.
17. Copilot por voz: **no se menciona**.
18. Idiomas: **castellano, català, galego, euskara**.
