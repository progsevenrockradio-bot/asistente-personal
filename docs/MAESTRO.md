# PROMPT MAESTRO — ASISTENTE PERSONAL AUTOMATIZADO (PWA LARAVEL)

Este documento va **íntegro y sin resumir**. Se pega una sola vez a Antigravity y se guarda en el
repositorio como `docs/MAESTRO.md`.

---

## 1. OBJETIVO DEL PROYECTO

Construir una aplicación web progresiva (PWA) de organización personal denominada provisionalmente:

**Mi Asistente Personal**

La aplicación debe funcionar principalmente en teléfonos Android, especialmente en un Samsung Galaxy S24,
pero también debe funcionar correctamente en escritorio.

El objetivo es centralizar en una única aplicación:

- calendario;
- citas;
- reuniones;
- tareas;
- eventos;
- conciertos;
- compromisos;
- comidas;
- tomas;
- preparaciones;
- recordatorios;
- documentos;
- información procedente de Google Drive;
- información importada desde documentos/PDF generados o utilizados con NotebookLM;
- planificación diaria;
- asistencia mediante IA.

La aplicación debe actuar como un asistente personal automatizado, no simplemente como un calendario.

El usuario debe poder introducir información de manera desordenada mediante texto o voz y el sistema debe
transformarla progresivamente en información estructurada.

## 2. PRINCIPIO FUNDAMENTAL

El usuario no debe estar obligado a rellenar formularios complejos.

Debe poder escribir:

"El viernes tengo médico a las 10 y el sábado tengo un concierto."

El sistema debe identificar los posibles elementos y preguntar únicamente por los datos que falten.

Ejemplo:

CITA MÉDICA
Viernes
10:00
Falta lugar
Falta duración

CONCIERTO
Sábado
Falta hora
Falta lugar

Después debe preguntar:

¿Dónde es la cita médica?
¿Cuánto dura?
¿A qué hora es el concierto?
¿Dónde es?

No debe inventar información.

## 3. STACK TECNOLÓGICO

Utilizar Laravel como framework principal.

Si el proyecto comienza desde cero, verificar la versión estable de Laravel disponible en el momento de
implementación y utilizar una versión soportada, salvo que el usuario indique expresamente Laravel 11.

La aplicación debe mantener una arquitectura compatible con Laravel y preparada para evolución futura.

Backend:

- Laravel;
- PHP;
- MySQL/MariaDB;
- Laravel Queue;
- Laravel Scheduler;
- Laravel Notifications;
- almacenamiento seguro.

Frontend:

- Blade + Livewire + Alpine.js + Tailwind CSS, salvo que exista una razón técnica clara para utilizar
  Inertia/Vue;
- diseño responsive;
- interfaz mobile-first.

PWA:

- Web App Manifest;
- Service Worker;
- instalación en Android;
- modo standalone;
- caché de recursos;
- página offline;
- soporte para notificaciones web cuando sea compatible.

## 4. DOMINIO

El proyecto se alojará en un dominio del usuario gestionado mediante Hostinger.

La arquitectura debe permitir posteriormente utilizar un subdominio específico para la aplicación.

Ejemplo:

app.dominio-del-usuario.com

No asumir el dominio real hasta que el usuario lo proporcione.

## 5. AUTENTICACIÓN

Implementar autenticación segura.

El sistema debe permitir:

- registro;
- inicio de sesión;
- cierre de sesión;
- recuperación de contraseña;
- sesiones seguras;
- protección CSRF;
- rate limiting;
- validación;
- protección contra acceso no autorizado.

La aplicación debe estar preparada para un único usuario inicialmente, pero la arquitectura debe permitir
múltiples usuarios en el futuro.

## 6. DASHBOARD PRINCIPAL

La pantalla principal debe mostrar:

HOY
Fecha actual.

AHORA
Actividad actual o próxima actividad.

PRÓXIMO
Siguiente compromiso.

DESPUÉS
Próximas actividades.

TAREAS IMPORTANTES
Tareas prioritarias.

NO OLVIDAR
Recordatorios pendientes.

BANDEJA DE ENTRADA
Información todavía no organizada.

ACCIONES RÁPIDAS
- Añadir cita;
- Añadir tarea;
- Añadir evento;
- Hablar;
- Escribir;
- Importar documento;
- Importar PDF;
- Ver calendario.

La interfaz debe ser limpia y no sobrecargar al usuario.

## 7. BANDEJA DE ENTRADA

Crear una bandeja denominada:

**Inbox / Bandeja de entrada**

Todo texto introducido de forma rápida debe poder almacenarse inicialmente aquí.

Ejemplos:

"Comprar algo para el concierto."
"Llamar al médico."
"El viernes tengo una cita."
"Preparar documentos."

Cada elemento debe poder tener estado:

- pendiente de analizar;
- analizado;
- necesita información;
- convertido en evento;
- convertido en tarea;
- archivado.

## 8. MOTOR DE INTERPRETACIÓN

Crear una capa de servicio independiente para interpretar lenguaje natural.

Debe poder identificar:

- fechas;
- horas;
- duración;
- lugares;
- personas;
- eventos;
- tareas;
- prioridades;
- desplazamientos;
- recordatorios;
- comidas;
- tomas;
- preparaciones.

No permitir que el proveedor de IA modifique directamente la base de datos.

La IA debe devolver una estructura validable.

El backend debe validar la estructura antes de guardar cualquier información.

## 9. SISTEMA DE PREGUNTAS

Cuando falten datos esenciales, crear preguntas pendientes.

Ejemplo:

EVENTO:
"Cita médica"

Datos:
fecha: conocida
hora: conocida
lugar: desconocido
duración: desconocida

Crear preguntas:

"¿Dónde es la cita?"
"¿Cuánto dura aproximadamente?"

El usuario debe poder contestar desde la interfaz.

Las respuestas deben completar automáticamente la información pendiente.

## 10. CALENDARIO INTERNO

Crear una representación interna de eventos.

Campos mínimos:

- id;
- user_id;
- título;
- descripción;
- fecha;
- hora_inicio;
- hora_fin;
- duración;
- ubicación;
- prioridad;
- estado;
- fuente;
- origen;
- notas;
- recordatorios;
- created_at;
- updated_at.

Debe existir un identificador externo para Google Calendar cuando el evento haya sido sincronizado.

## 11. GOOGLE CALENDAR

Implementar OAuth 2.0 de Google.

Permitir al usuario:

- conectar una cuenta Google;
- seleccionar calendarios;
- consultar eventos;
- crear eventos;
- modificar eventos;
- eliminar eventos;
- sincronizar cambios;
- identificar eventos creados por la aplicación.

No almacenar contraseñas de Google.

Guardar tokens OAuth de forma segura y cifrada.

Preparar la arquitectura para múltiples cuentas Google en el futuro.

No modificar calendarios sin autorización explícita.

Durante la primera versión, toda creación/modificación debe requerir confirmación cuando provenga de una
interpretación de IA.

## 12. SAMSUNG CALENDAR

No crear una integración propietaria específica con Samsung Calendar salvo que posteriormente exista una
API oficial adecuada.

Utilizar Google Calendar como calendario central.

El Samsung Galaxy S24 debe visualizar los eventos mediante la sincronización de la cuenta Google.

La aplicación debe funcionar correctamente independientemente de que el usuario utilice la aplicación
Google Calendar o Samsung Calendar.

## 13. GOOGLE DRIVE

Implementar conexión OAuth con Google Drive.

Permitir conectar una o varias cuentas Google.

Para cada cuenta guardar:

- identificador;
- nombre;
- correo;
- estado de conexión;
- tokens cifrados;
- fecha de última sincronización.

Permitir:

- listar archivos;
- buscar archivos;
- seleccionar carpetas;
- importar archivos;
- descargar archivos necesarios;
- subir archivos;
- asociar documentos a eventos;
- asociar documentos a tareas.

## 14. DOCUMENTOS

Crear una entidad interna para documentos.

Campos:

- id;
- user_id;
- nombre;
- tipo;
- tamaño;
- origen;
- google_drive_file_id;
- google_drive_account_id;
- ubicación;
- categoría;
- etiquetas;
- fecha;
- hash si procede;
- estado de procesamiento.

Tipos iniciales:

- PDF;
- DOCX;
- TXT;
- CSV;
- Markdown;
- imágenes.

## 15. IMPORTACIÓN DE PDF

Crear una función:

**Importar documento**

El usuario podrá:

- seleccionar un archivo del teléfono;
- seleccionar un archivo de Google Drive;
- subir un PDF;
- introducir texto.

El sistema debe extraer texto cuando sea posible.

Después debe analizarlo.

Nunca crear automáticamente eventos sensibles sin mostrar al usuario lo que se ha encontrado.

Ejemplo:

"Se han detectado 4 posibles eventos."

Mostrar:

- evento;
- fecha;
- hora;
- lugar;
- origen del documento;
- nivel de confianza.

Permitir:

[Crear todos]
[Revisar uno por uno]
[Cancelar]

## 16. INTEGRACIÓN CON NOTEBOOKLM

No depender de una API privada o no documentada de NotebookLM.

NotebookLM se utilizará como fuente externa de conocimiento y preparación de información.

La aplicación debe aceptar documentos producidos a partir de NotebookLM.

Flujo:

NotebookLM
↓
PDF/DOCX/TXT/CSV
↓
Google Drive o teléfono
↓
Mi Asistente
↓
Importación
↓
Análisis
↓
Confirmación
↓
Calendario/tareas/recordatorios.

Preparar posteriormente un formato estructurado específico para intercambio de información entre
NotebookLM y la aplicación.

## 17. ASISTENTE IA

Crear una capa independiente denominada:

**AI Assistant Service**

No acoplar toda la aplicación a un proveedor concreto.

Debe poder cambiarse el proveedor de IA mediante configuración.

Funciones:

- interpretar lenguaje natural;
- extraer eventos;
- extraer tareas;
- detectar información faltante;
- generar preguntas;
- resumir agenda;
- detectar conflictos;
- sugerir prioridades;
- generar planificación diaria.

La IA nunca debe tener permiso directo e ilimitado sobre la base de datos.

Todas las acciones deben pasar por servicios y validaciones del backend.

## 18. DETECCIÓN DE CONFLICTOS

Detectar:

- eventos simultáneos;
- desplazamientos incompatibles;
- eventos demasiado próximos;
- falta de preparación;
- tareas con fechas imposibles;
- citas que requieren preparación previa;
- fechas límite incompatibles.

No modificar automáticamente los eventos.

Mostrar el conflicto y proponer alternativas.

## 19. TAREAS

Crear sistema de tareas.

Campos:

- título;
- descripción;
- fecha límite;
- hora;
- prioridad;
- estado;
- duración estimada;
- dependencia;
- categoría;
- recordatorios.

Estados:

- pendiente;
- en proceso;
- completada;
- bloqueada;
- pospuesta.

## 20. PRIORIDADES

Sistema:

- URGENTE
- IMPORTANTE
- NORMAL
- PUEDE ESPERAR

Permitir que la IA sugiera una prioridad, pero permitir al usuario modificarla.

## 21. RECORDATORIOS

Crear sistema de recordatorios.

Tipos:

- diario;
- día anterior;
- dos horas antes;
- hora concreta;
- antes de salir;
- tarea pendiente;
- recordatorio personalizado.

Permitir configurar reglas por usuario.

Ejemplo:

Evento importante:
- 1 día antes;
- 2 horas antes.

Evento normal:
- 2 horas antes.

Resumen diario:
hora configurable.

## 22. RESUMEN DIARIO

Crear una función:

**Mi día**

Debe mostrar:

- citas;
- reuniones;
- tareas;
- comidas;
- tomas;
- desplazamientos;
- eventos;
- preparaciones;
- recordatorios;
- conflictos.

Orden cronológico.

## 23. PLANIFICACIÓN INTELIGENTE

El sistema debe poder responder:

"Organízame mañana."

Y generar:

- agenda;
- tareas;
- desplazamientos;
- preparación;
- recordatorios;
- espacios libres.

No debe llenar automáticamente los espacios libres.

Debe conservar tiempo libre y mostrarlo.

## 24. COMIDAS

Crear módulo opcional de comidas.

Permitir:

- desayuno;
- comida;
- merienda;
- cena;
- otras comidas.

Cada comida puede tener:

- hora;
- contenido;
- notas;
- restricciones;
- recordatorio.

## 25. TOMAS Y MEDICACIÓN

Crear módulo de tomas.

No establecer recomendaciones médicas automáticamente.

El usuario debe proporcionar las instrucciones.

Campos:

- nombre;
- cantidad indicada;
- hora;
- frecuencia;
- duración;
- relación con comidas;
- notas;
- fuente.

La aplicación solamente organiza la información proporcionada.

## 26. INFORMACIÓN MÉDICA

Crear categoría específica para documentos médicos.

Los documentos médicos pueden contener:

- citas;
- instrucciones;
- preparación;
- horarios;
- documentación;
- restricciones.

No permitir que la IA invente instrucciones médicas.

Mostrar siempre la fuente cuando la información proceda de un documento.

## 27. PWA

Implementar:

- manifest.json;
- iconos;
- start_url;
- display standalone;
- theme_color;
- service worker;
- caché de recursos;
- página offline;
- actualización controlada;
- instalación en Android.

Preparar soporte para:

- push notifications;
- compartir archivos;
- selección de archivos;
- integración con el menú compartir de Android cuando sea viable.

El diseño debe ser mobile-first.

## 28. NOTIFICACIONES

Crear sistema preparado para:

- notificaciones web;
- notificaciones internas;
- correo opcional;
- futuras integraciones.

No depender exclusivamente de las notificaciones del navegador.

Registrar estado de cada notificación:

- programada;
- enviada;
- entregada si el canal lo permite;
- fallida;
- cancelada.

## 29. COLAS Y TAREAS EN SEGUNDO PLANO

Utilizar Laravel Queue para:

- procesamiento de documentos;
- sincronización Google;
- análisis IA;
- generación de recordatorios;
- notificaciones;
- sincronizaciones periódicas.

Utilizar Laravel Scheduler para tareas programadas.

La aplicación debe registrar errores sin exponer información sensible.

## 30. ALEXA

No convertir Alexa en una dependencia crítica.

Diseñar una capa de integración independiente.

Primera prioridad:
Aplicación → Google Calendar → teléfono/Samsung.

Segunda prioridad:
Aplicación → notificaciones PWA.

Tercera prioridad:
Aplicación → integración Alexa.

Antes de implementar una integración concreta con Alexa, comprobar las capacidades y APIs disponibles
actualmente.

No asumir que una integración antigua sigue disponible.

## 31. SEGURIDAD

Aplicar:

- HTTPS;
- OAuth;
- cifrado de tokens;
- CSRF;
- XSS protection;
- validación;
- autorización;
- rate limiting;
- protección de archivos;
- límites de subida;
- control de MIME;
- protección de rutas;
- logs sin secretos.

Nunca guardar:

- contraseñas de Google;
- tokens en texto plano;
- claves API en código;
- secretos en Git.

Utilizar variables de entorno.

## 32. PRIVACIDAD

La aplicación contiene información potencialmente sensible.

Implementar:

- eliminación de cuenta;
- desconexión de Google;
- eliminación de tokens;
- eliminación de documentos;
- eliminación de datos;
- auditoría básica;
- política clara de privacidad.

No enviar documentos completos a proveedores de IA salvo que sea necesario y esté autorizado por el
usuario.

Siempre minimizar los datos enviados.

## 33. AUDITORÍA

Registrar acciones importantes:

- evento creado;
- evento modificado;
- evento eliminado;
- documento importado;
- cuenta Google conectada;
- cuenta Google desconectada;
- sincronización ejecutada;
- acción de IA confirmada.

La auditoría no debe almacenar contenido sensible innecesario.

## 34. INTERFAZ

La aplicación debe ser:

- limpia;
- rápida;
- sencilla;
- mobile-first;
- legible;
- accesible;
- sin exceso de elementos;
- con botones grandes para acciones frecuentes.

No convertirla en un dashboard empresarial.

Debe sentirse como una aplicación personal.

## 35. NAVEGACIÓN

Navegación principal:

- HOY
- CALENDARIO
- TAREAS
- BANDEJA
- DOCUMENTOS
- ASISTENTE
- AJUSTES

Acciones rápidas:

🎙
📄

## 36. AJUSTES

Crear:

- Cuenta: perfil.
- Google: cuentas conectadas.
- Calendarios: calendarios sincronizados.
- Drive: cuentas y carpetas.
- Recordatorios: reglas.
- Notificaciones: configuración.
- IA: proveedor y preferencias.
- PWA: información de instalación.
- Privacidad: datos y conexiones.

## 37. ARQUITECTURA

Mantener separación clara entre:

- Controllers;
- Services;
- Actions;
- Jobs;
- Notifications;
- Models;
- Repositories cuando realmente sean necesarios;
- Integrations;
- AI;
- Google;
- Documents;
- Calendar;
- Tasks.

No introducir arquitectura excesivamente compleja sin necesidad.

## 38. INTEGRACIONES

Crear interfaces desacopladas para:

- GoogleCalendarService
- GoogleDriveService
- AiAssistantService
- NotificationService
- DocumentParserService
- ReminderService

Esto permitirá sustituir proveedores posteriormente.

## 39. GOOGLE OAUTH

No hardcodear:

- client ID;
- client secret;
- redirect URI;
- API keys.

Utilizar variables de entorno.

Crear documentación para configurar Google Cloud Console.

Documentar:

- proyecto;
- OAuth consent screen;
- credenciales;
- APIs necesarias;
- scopes;
- redirect URLs;
- producción;
- desarrollo.

No asumir que la aplicación ya tiene las credenciales.

## 40. CONFIGURACIÓN

Toda configuración externa debe estar en:

.env

No incluir secretos en el repositorio.

Crear:

.env.example

con variables claramente explicadas.

## 41. TESTS

Implementar tests para:

- autenticación;
- creación de tareas;
- creación de eventos;
- detección de conflictos;
- importación de documentos;
- validación de eventos IA;
- Google Calendar;
- Google Drive;
- recordatorios;
- permisos;
- seguridad.

Crear mocks para APIs externas.

No realizar llamadas reales a Google durante tests unitarios.

## 42. MANEJO DE ERRORES

Cuando falle una integración:

No romper la aplicación.

Mostrar:

"Google Calendar no está disponible. Tus datos locales siguen guardados."

Registrar el error.

Permitir reintento.

Las operaciones externas deben poder reintentarse.

## 43. SINCRONIZACIÓN

La sincronización debe ser idempotente.

Evitar:

- duplicados;
- eventos repetidos;
- documentos duplicados;
- notificaciones duplicadas.

Utilizar IDs externos y hashes cuando corresponda.

## 44. IMPORTACIÓN INTELIGENTE

Al importar un documento:

- guardar archivo;
- extraer contenido;
- identificar posibles datos;
- clasificar;
- mostrar resultados;
- pedir confirmación;
- guardar;
- sincronizar si corresponde.

Nunca saltarse la confirmación para operaciones potencialmente destructivas.

## 45. PRIMERA VERSIÓN FUNCIONAL

La primera versión debe conseguir:

- Iniciar sesión.
- Instalarse como PWA.
- Crear tareas.
- Crear eventos.
- Mostrar agenda.
- Tener bandeja de entrada.
- Introducir texto natural.
- Detectar datos faltantes.
- Crear eventos mediante confirmación.
- Conectar Google Calendar.
- Sincronizar eventos.
- Importar PDF.
- Mostrar información extraída.
- Crear eventos desde PDF mediante confirmación.

No implementar Alexa todavía.

## 46. ORDEN DE IMPLEMENTACIÓN

Implementar en este orden:

FASE 1
Base Laravel + autenticación + base de datos.

FASE 2
Interfaz mobile-first.

FASE 3
PWA.

FASE 4
Calendario y tareas locales.

FASE 5
Bandeja de entrada.

FASE 6
Google OAuth.

FASE 7
Google Calendar.

FASE 8
Google Drive.

FASE 9
Importación PDF/documentos.

FASE 10
Motor IA.

FASE 11
Preguntas interactivas.

FASE 12
Recordatorios.

FASE 13
Notificaciones.

FASE 14
Planificación inteligente.

FASE 15
Alexa.

## 47. REGLA PARA ANTIGRAVITY

No intentar construir todo de una sola vez sin comprobar cada fase.

Trabajar de forma incremental.

Antes de modificar arquitectura existente:

- inspeccionar el proyecto;
- identificar versión Laravel;
- identificar PHP;
- identificar base de datos;
- identificar frontend;
- identificar hosting;
- identificar configuración;
- identificar dependencias.

Si el proyecto ya contiene código, NO eliminar ni sustituir funcionalidades existentes sin autorización.

Si existe un proyecto vacío, construir la arquitectura desde cero.

## 48. REGLA DE SEGURIDAD

Nunca solicitar al usuario:

- contraseña de Google;
- contraseña de correo;
- tokens privados;
- claves secretas mediante el chat.

Indicar al usuario dónde configurar las credenciales de forma segura.

## 49. REGLA DE DECISIÓN

Cuando exista más de una forma técnicamente válida de implementar una característica:

- elegir la opción más sencilla;
- elegir la que tenga menor mantenimiento;
- elegir la que funcione mejor en Android;
- elegir la que reduzca dependencias externas;
- mantener Laravel como núcleo.

No introducir tecnologías innecesarias.

## 50. RESULTADO FINAL ESPERADO

El resultado debe sentirse como:

"Tengo una sola aplicación donde puedo poner todo lo que tengo en la cabeza y ella me ayuda a
organizarlo."

Debe poder:

- recibir información desordenada;
- hacer preguntas;
- organizar;
- detectar conflictos;
- consultar documentos;
- consultar Google Drive;
- sincronizar Google Calendar;
- funcionar en Samsung S24;
- instalarse como PWA;
- crear recordatorios;
- mostrar mi día;
- preparar mi día siguiente;
- importar información desde documentos y PDF;
- utilizar información preparada con NotebookLM;
- mantener los datos bajo control del usuario;
- y posteriormente comunicarse con Alexa.

NO debe ser simplemente otro calendario.

Debe ser un ASISTENTE PERSONAL AUTOMATIZADO.
