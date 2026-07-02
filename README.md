# PetConnect 🐾

Plataforma web de adopción y rescate de mascotas. Construida con Laravel, Livewire y Flux UI.

## Requisitos

- PHP ^8.3
- Composer
- Node.js 22+
- SQLite (por defecto) o MySQL/PostgreSQL

## Instalación

```bash
git clone <repo-url>
cd pet-connect
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

## Funcionalidades

- **Catálogo público** de mascotas con búsqueda y filtros
- **Autenticación** con Laravel Fortify (registro, login, 2FA, passkeys)
- **Roles**: adoptante, rescatista, administrador
- **Organizaciones/Refugios**: registro y gestión de entidades
- **Mascotas**: fichas completas con imágenes, características y estado
- **Solicitudes de adopción**: flujo de postulación y seguimiento
- **Favoritos**: mascotas guardadas por usuarios

## Tecnologías

- [Laravel 13](https://laravel.com)
- [Livewire 4](https://livewire.laravel.com)
- [Flux UI 2](https://fluxui.dev)
- [Tailwind CSS 4](https://tailwindcss.com)
- [Laravel Fortify](https://laravel.com/docs/fortify)

## Base de datos

| Tabla | Descripción |
|-------|-------------|
| `species` | Especies (perro, gato, conejo, etc.) |
| `breeds` | Razas vinculadas a especie |
| `organizations` | Refugios y organizaciones rescatistas |
| `pets` | Mascotas disponibles para adopción |
| `pet_images` | Galería de imágenes por mascota |
| `adoption_requests` | Solicitudes de adopción |
| `favorites` | Mascotas favoritas por usuario |

## Tests

```bash
php artisan test
```
