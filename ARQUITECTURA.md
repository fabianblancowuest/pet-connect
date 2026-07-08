# PetConnect — Arquitectura y Tecnologías

## Arquitectura General

**Monolítica MVC con Livewire** — No hay API REST ni SPA. Todo se renderiza del lado del servidor.

```
Navegador → Ruta (web.php) → Livewire Component → Eloquent Model → SQLite
                                 ↓
                         Blade View (HTML)
```

- **Laravel 13** actúa como framework backend.
- **Livewire 4** reemplaza a los controladores tradicionales: cada ruta apunta a un componente Livewire que maneja estado y renderiza su propia vista Blade.
- **Alpine.js** (incluido con Flux UI) maneja interactividad liviana del lado del cliente.
- **Vite 8** compila los assets (CSS y JS).

---

## Tecnologías

### Backend

| Tecnología | Versión | Propósito |
|---|---|---|
| PHP | ^8.3 | Lenguaje |
| Laravel | ^13.17 | Framework full-stack |
| Laravel Fortify | ^1.37 | Backend de autenticación (login, registro, 2FA, passkeys, verificación email) |
| Livewire | ^4.1 | Framework full-stack para UI dinámica (reemplaza controladores tradicionales) |
| Flux UI | ^2.13 | Biblioteca de componentes UI para Livewire |
| Laravel Tinker | ^3.0 | REPL interactivo |

### Frontend

| Tecnología | Versión | Propósito |
|---|---|---|
| Tailwind CSS | ^4.0 | Framework CSS utility-first |
| Vite | ^8.0 | Bundler y dev server |
| Blade | — | Motor de plantillas PHP |
| Alpine.js | (bundled con Flux) | JavaScript para interactividad en el cliente |
| Instrument Sans | — | Tipografía (Google Fonts via Bunny CDN) |

### Base de Datos

| Tecnología | Propósito |
|---|---|
| SQLite | Base de datos por defecto (archivo local) |
| MySQL / PostgreSQL / MariaDB / SQL Server | Soportados vía configuración |
| Redis | Cache/sesión (opcional) |

### Calidad y Desarrollo

| Tecnología | Versión | Propósito |
|---|---|---|
| Pest PHP | ^4.7 | Framework de testing |
| Larastan (PHPStan) | ^3.9 | Análisis estático (nivel 7) |
| Laravel Pint | ^1.27 | Code style (PSR-12) |
| FakerPHP | ^1.24 | Generación de datos falsos |
| Mockery | ^1.6 | Mocking para tests |
| Laravel Sail | ^1.53 | Entorno Docker (opcional) |
| GitHub Actions | — | CI/CD (tests + lint) |

---

## Estructura del Proyecto

```
/
├── app/
│   ├── Actions/Fortify/      — Lógica de registro y reseteo de contraseña
│   ├── Concerns/             — Traits de validación
│   ├── Http/
│   │   ├── Controllers/      — Controlador base vacío (todo se delega a Livewire)
│   │   └── Middleware/        — CheckRole (autorización por roles)
│   ├── Livewire/             — ★ Lógica principal de la aplicación
│   │   ├── Admin/            — CRUD de desarrolladores, organizaciones, mascotas, usuarios
│   │   ├── Adoption/         — Solicitudes de adopción
│   │   ├── Rescuer/          — Gestión de refugio y mascotas
│   │   ├── Settings/         — Perfil, apariencia, seguridad, 2FA
│   │   ├── PetCatalog.php    — Catálogo público de mascotas
│   │   └── PetDetail.php     — Detalle de mascota
│   ├── Models/               — ★ Modelos Eloquent (9 modelos)
│   ├── Policies/             — Políticas de autorización
│   └── Providers/            — Service providers
├── bootstrap/                — Bootstrap de Laravel
├── config/                   — ★ Configuración de la aplicación
├── database/
│   ├── factories/            — Factories para tests/seeders
│   ├── migrations/           — ★ Schema de base de datos (22 migrations)
│   └── seeders/              — Datos de demo
├── lang/                     — Traducciones (español)
├── resources/
│   ├── css/                  — Tailwind CSS v4 + Flux
│   ├── js/                   — app.js (vacío), passkeys.js (WebAuthn)
│   └── views/                — ★ Vistas Blade
│       ├── livewire/         — Vistas de cada componente Livewire
│       ├── layouts/          — Layouts (app, auth)
│       ├── components/       — Componentes reutilizables
│       └── flux/             — Overrides de Flux UI
├── routes/
│   ├── web.php               — ★ Todas las rutas web
│   ├── settings.php          — Rutas de configuración de usuario
│   └── console.php           — Comandos Artisan
├── tests/                    — Tests (Pest)
│   ├── Feature/              — Tests de funcionalidad
│   └── Unit/                 — Tests unitarios
└── vite.config.js            — Configuración de Vite
```

---

## Base de Datos (16 tablas)

```
users ──1:N──> organizations
users ──1:N──> pets (como creador)
users ──1:N──> adoption_requests (como adoptante)
users ──1:N──> favorites
users ──1:N──> passkeys (Fortify/WebAuthn)

species ──1:N──> breeds
species ──1:N──> pets

breeds ──1:N──> pets

organization ──1:N──> pets
organization ──1:N──> adoption_requests

pet ──1:N──> pet_images
pet ──1:N──> adoption_requests
pet ──1:N──> favorites
```

Tablas del sistema: `sessions`, `password_reset_tokens`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`

Soft deletes en: `pets`, `organizations`, `adoption_requests`

---

## Roles de Usuario

| Rol | Permisos |
|---|---|
| **adopter** (default) | Navegar mascotas, solicitar adopciones, favoritos, ver sus solicitudes |
| **rescuer** | Gestionar su organización/refugio, CRUD de mascotas propias, procesar solicitudes de adopción |
| **admin** | CRUD completo de desarrolladores, organizaciones, mascotas y usuarios |

---

## Rutas Principales

### Públicas
| Ruta | Funcionalidad |
|---|---|
| `/` | Página de bienvenida |
| `/quienes-somos` | Página "Acerca de" |
| `/mascotas` | Catálogo público con filtros (especie, tamaño, orden) |
| `/mascotas/{pet}` | Detalle de mascota con galería y formulario de adopción |

### Autenticadas (usuario verificado)
| Ruta | Funcionalidad |
|---|---|
| `/dashboard` | Panel principal |
| `/adopciones/mis-solicitudes` | Mis solicitudes de adopción |

### Rescuer/Admin
| Ruta | Funcionalidad |
|---|---|
| `/rescuer/solicitudes` | Gestionar solicitudes recibidas |
| `/rescuer/mascotas` | CRUD de mascotas del refugio |
| `/rescuer/refugio` | Configuración del refugio |

### Admin
| Ruta | Funcionalidad |
|---|---|
| `/admin/desarrolladores` | CRUD de desarrolladores (equipo) |
| `/admin/refugios` | CRUD de organizaciones/refugios |
| `/admin/mascotas` | CRUD de mascotas (global) |
| `/admin/usuarios` | CRUD de usuarios |

### Configuración
| Ruta | Funcionalidad |
|---|---|
| `/settings/profile` | Editar perfil |
| `/settings/appearance` | Tema claro/oscuro |
| `/settings/security` | Contraseña, 2FA, passkeys |

---

## Funcionalidades Clave

- **Catálogo público** con búsqueda y filtros (especie, tamaño, ordenamiento)
- **Sistema de adopción**: flujo pendiente → en_progreso → aprobado/rechazado con comunicación entre adoptante y rescatista
- **Autenticación completa**: registro, login, verificación email, reseteo de contraseña, 2FA (TOTP), passkeys (WebAuthn)
- **Favoritos**: los adoptantes pueden guardar mascotas
- **Internacionalización**: interfaz completamente en español
- **Modo oscuro**: soporte completo con clase `.dark`
- **Panel admin**: gestión completa del sistema
- **CI/CD**: GitHub Actions con tests en PHP 8.3/8.4/8.5 y linting
