# PWA «Mi Asistente Personal» — DECISIONES, AVISOS Y PLAN POR TANDAS

## 1. Veredicto sobre el prompt maestro
El maestro está **muy bien escrito** y no hay que reescribirlo: sirve como documento de contexto.
**Pero NO se le pega todo a Antigravity de una vez.** Un prompt tan largo produce código a medias en
todo y funcional en nada. Se trabaja por tandas, como el propio maestro pide en sus puntos 46 y 47.

Regla: **una tanda = un prompt = un push = una comprobación.** No se abre la siguiente sin cerrar la
anterior.

## 2. Decisiones antes de la primera tanda
1. **Dominio de la app:** `asistente.sevenrockradio.com` (subdominio nuevo).
   Alternativa: `app.jmsync.es`.
2. **Proyecto en Google Cloud:** (para Calendar y Drive, Fases 6 a 8). Crear proyecto, activar las dos
   APIs y configurar la pantalla de consentimiento desde la cuenta Google del usuario. Credenciales en
   el `.env` del servidor.
3. **Repositorio nuevo y PRIVADO:** (`asistente-personal`). **Nunca** dentro del repositorio de la radio.

## 3. Avisos técnicos clave (Hostinger compartido)
1. **Hostinger compartido no tiene worker de colas permanente:** La cola se atiende por **cron**
   (`queue:work --stop-when-empty` cada minuto) y el scheduler con `schedule:run` cada minuto.
2. **OAuth de Google caduca:** Si el proyecto queda en modo **Testing**, el permiso expira **cada 7 días**.
   Solución: Publicar el proyecto (botón Publish app) para uso personal estable.
3. **Permisos de Drive:** Empezar pidiendo lo mínimo (`calendar.events` y `drive.file`) y ampliar solo
   si es indispensable.
4. **PWA en Android:** Requiere HTTPS y el Service Worker para la instalación real en Samsung Galaxy S24.

## 4. Plan por tandas (6 tandas)
- [x] **Tanda 1 — Fases 1, 2 y 3**: Base Laravel + autenticación + interfaz móvil + PWA instalable.
- [ ] **Tanda 2 — Fases 4 y 5**: Calendario y tareas locales + bandeja de entrada.
- [ ] **Tanda 3 — Fases 6, 7 y 8**: Google OAuth + Google Calendar + Google Drive.
- [ ] **Tanda 4 — Fases 9 y 10**: Importación de PDF/documentos + motor de IA (proveedor configurable y validación en backend).
- [ ] **Tanda 5 — Fases 11, 12 y 13**: Preguntas interactivas + recordatorios + notificaciones.
- [ ] **Tanda 6 — Fase 14**: Planificación inteligente (Fase 15 Alexa opcional aparte).

## 5. Principios inmutables
- La IA **propone estructuras validadas**; el backend valida y guarda. Servicio `AiAssistantService` desacoplado.
- **Cero secretos en el repositorio**: `.env` en el servidor, `.env.example` en Git.
- Tokens de Google **cifrados**; contraseñas nunca solicitadas ni guardadas.
- Sincronizaciones externas **idempotentes** (IDs externos y hashes).
- **Nunca** crear eventos sensibles desde documentos sin confirmación previa del usuario.
