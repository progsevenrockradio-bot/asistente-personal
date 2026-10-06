# Mi Asistente Personal (PWA)

## Descripción
"Mi Asistente Personal" es una Progressive Web App (PWA) desarrollada en Laravel 11, Livewire v3 y Tailwind CSS v4, diseñada principalmente para uso móvil (orientada a Samsung Galaxy S24, pero funcional en escritorio).

Centraliza la gestión personal integrando:
- Calendario, citas y reuniones.
- Tareas, eventos y compromisos.
- Gestión documental e integración futura con Google Drive.
- QuickCapture para captura rápida de pensamientos.
- Asistencia mediante IA (planificada para futuras fases).

## Requisitos del Servidor
- **PHP:** 8.3+
- **Base de datos:** MySQL / MariaDB (o SQLite para entorno local/testing).
- **Entorno Hostinger Compartido:** Al no disponer de workers persistentes, el sistema de colas y comandos programados se ejecuta mediante `cron` (`queue:work --stop-when-empty` y `schedule:run`).

## Instalación

1. Clona el repositorio y accede al directorio:
   ```bash
   git clone <tu-repositorio>
   cd AsistenteJM
   ```

2. Instala las dependencias de PHP y Node.js:
   ```bash
   composer install
   npm install
   ```

3. Configura el entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   **Nota:** Actualiza los valores de la base de datos en el archivo `.env`.

4. Ejecuta las migraciones y los seeders:
   ```bash
   php artisan migrate --seed
   ```

5. Compila los assets (Vite):
   ```bash
   npm run build
   ```

6. Inicia el servidor local:
   ```bash
   php artisan serve
   ```

## Testing
El proyecto cuenta con una suite de pruebas (Feature tests) que cubren el núcleo de la aplicación.
Para ejecutar los tests:
```bash
php artisan test
```

## Plan de Desarrollo
El proyecto se desarrolla en *tandas* (fases), documentadas en `docs/PLAN_TANDAS.md`.
El prompt maestro completo se encuentra en `docs/MAESTRO.md`.

## Licencia
Proyecto privado.
