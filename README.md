# Fidexa CRM

Fidexa es una aplicación CRM construida con Laravel 10, Jetstream y Livewire. Permite gestionar clientes, registrar interacciones y realizar canjes o compras de puntos.

## Requisitos

- PHP >= 8.1
- Composer
- Node.js y npm
- Una base de datos MySQL u otra compatible

## Instalación

1. Clona el repositorio y entra al proyecto.
2. Ejecuta `composer install` para instalar las dependencias de PHP.
3. Copia `.env.example` a `.env` y configura la conexión a la base de datos.
4. Genera la clave de aplicación con `php artisan key:generate`.
5. Ejecuta las migraciones: `php artisan migrate`.
6. Instala los paquetes de Node y compila los assets:
   ```bash
   npm install
   npm run build
   php artisan livewire:publish --assets
   ```
7. Inicia el servidor con `php artisan serve` y abre `http://localhost:8000`.

## Generar assets nuevamente

Los directorios `public/build` y `public/vendor/livewire` no están versionados. Si no existen al clonar, puedes generarlos con los comandos del paso 6.

## Pruebas

Ejecuta las pruebas de la aplicación con:

```bash
php artisan test
```

## Licencia

El proyecto se publica bajo la licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más información.
