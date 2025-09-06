# Prueba Técnica Amplifica IO

**Resumen:**
Este proyecto es una plataforma sencilla de gestión de tiendas, productos y pedidos para Shopify y WooCommerce. Incluye panel de administración con AdminLTE.

Permite a los usuarios registrarse, iniciar sesión y gestionar recursos desde un dashboard intuitivo, integrando buenas prácticas de seguridad y usabilidad.

## Requisitos

- PHP >= 8.2
- Composer
- Node.js y npm
- SQLite (o cualquier base de datos compatible con Laravel)


## Instalación

1. **Clona el repositorio y entra al directorio:**
	```sh
	git clone <REPO_URL>
	cd prueba_tecnica_amplifica_io
	```

2. **Instala dependencias de PHP:**
	```sh
	composer install
	```

3. **Instala dependencias de Node.js:**
	```sh
	npm install
	```

4. **Copia el archivo de variables de entorno y configura:**
	```sh
	cp .env.example .env
	```
	- Configura las variables en `.env` según tu entorno. Por defecto, usa SQLite:
	  ```
	  DB_CONNECTION=sqlite
	  ```
	  Si usas SQLite, asegúrate de que el archivo `database/database.sqlite` exista:
	  ```sh
	  touch database/database.sqlite
	  ```

5. **Genera la clave de la aplicación:**
	```sh
	php artisan key:generate
	```

6. **Ejecuta las migraciones y seeders:**
	```sh
	php artisan migrate --seed
	```

7. **Compila los assets:**
	- Para desarrollo:
	  ```sh
	  npm run dev
	  ```
	- Para producción:
	  ```sh
	  npm run build
	  ```

8. **Inicia el servidor:**
	```sh
	php artisan serve
	```

## Panel de Administración (AdminLTE)


Este proyecto utiliza [jeroennoten/laravel-adminlte](https://github.com/jeroennoten/Laravel-AdminLTE) para el panel de administración.

- **No es necesario instalar AdminLTE manualmente**: se instala automáticamente con Composer (`composer install`).
- La configuración se encuentra en `config/adminlte.php`.
- Los assets de AdminLTE están en `public/vendor/adminlte`.
- Puedes personalizar el menú, logo, colores y plugins desde el archivo de configuración.

## Autenticación

- Formularios de login y registro personalizados en `resources/views/auth/`.
- El login soporta la opción de "Recordarme" (remember me).
- Tras iniciar sesión o registrarse, el usuario es redirigido a `dashboard.index`.
- El logout destruye la sesión y redirige al login.

## Variables de entorno principales

Algunas variables importantes en `.env`:

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
# Otras opciones: mysql, pgsql, etc.

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Usuario de pruebas

Al ejecutar las migraciones y seeders, se crea automáticamente un usuario de pruebas para acceder al sistema:

- **Email:** user@test.com
- **Contraseña:** asdf1234

Puedes usar estas credenciales para iniciar sesión desde el formulario de login.

## Scripts útiles

- `composer dev`: Inicia servidor, cola y Vite en paralelo.
- `npm run dev`: Compila assets en modo desarrollo.
- `npm run build`: Compila assets para producción.

## Estructura de carpetas relevante

- `app/Http/Controllers/`: Controladores de la aplicación.
- `resources/views/`: Vistas Blade (auth, dashboard, etc).
- `routes/web.php`: Rutas web.
- `config/adminlte.php`: Configuración de AdminLTE.
- `public/vendor/adminlte/`: Assets de AdminLTE.


## Autor

- Vladimir Faundez H.

## Créditos

- [Laravel](https://laravel.com/)
- [AdminLTE](https://adminlte.io/)
- [jeroennoten/laravel-adminlte](https://github.com/jeroennoten/Laravel-AdminLTE)
