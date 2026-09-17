# NODA

Sistema de reservas y gestión de usuarios para centros educativos.

## Requisitos

- PHP 7.4+ o compatible
- MySQL / MariaDB
- Apache o servidor web compatible con PHP
- WAMP, XAMPP o similar para desarrollo local

## Configuración rápida

1. Clona el proyecto.
2. Crea un archivo `.env` basado en `.env.example`.
3. Ajusta tus credenciales de base de datos.
4. Importa la base de datos o ejecuta la migración necesaria.
5. Levanta el proyecto desde la raíz del repositorio con tu servidor local.

## Variables de entorno

Copia `.env.example` a `.env` y modifica los valores:

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=novosis_noda
APP_ENV=development
```

## Base de datos y migraciones

El proyecto usa dos tipos de archivos SQL:

- `Novosis_NODA.sql`: esquema y datos iniciales de la aplicación.
- `migrations/`: cambios posteriores que se aplican sobre una base ya creada.

Para instalar una base desde cero:

1. Crear una base de datos llamada `novosis_noda`.
2. Importar `Novosis_NODA.sql`.
3. Ejecutar cada migración pendiente una sola vez y en orden.

La migración `001_add_primaria_secundaria.sql` agrega las columnas `primaria` y `secundaria` a `usuario`. Estas columnas indican a qué centro pertenece cada funcionario y permiten filtrar usuarios por centro.

Para ejecutarla manualmente:

```sql
ALTER TABLE usuario
	ADD COLUMN primaria TINYINT(1) NOT NULL DEFAULT 0,
	ADD COLUMN secundaria TINYINT(1) NOT NULL DEFAULT 0;
```

También podés ejecutarla desde la consola de MySQL:

```bash
mysql -u root -p novosis_noda < migrations/001_add_primaria_secundaria.sql
```

Para verificar que se aplicó correctamente:

```sql
SHOW COLUMNS FROM usuario;
SELECT email, primaria, secundaria FROM usuario LIMIT 20;
```

No ejecutes la misma migración dos veces sobre la misma base: fallará si las columnas ya existen.

Para una base ya existente, no vuelvas a importar `Novosis_NODA.sql`; ejecutá solamente las migraciones pendientes.

## Ejecutar localmente

Con WAMP/XAMPP, ubica la carpeta del proyecto dentro de la raíz web y accede desde el navegador:

```text
http://localhost/NODA/
```

## Estructura principal

- `Controlador/` : controladores y lógica de acceso a datos
- `modelo/` : modelos y consultas
- `vista/` : vistas, CSS y JS
- `migrations/` : migraciones de base de datos

## Convenciones de nombres

El proyecto conserva algunos nombres históricos para mantener compatibles sus rutas actuales. En particular, los archivos con sufijo `P` corresponden a variantes de determinadas vistas o menús.

Antes de renombrar un archivo PHP, hay que actualizar todas sus referencias en PHP, JavaScript y enlaces HTML. En sistemas Linux las mayúsculas y minúsculas sí importan, por lo que los nombres deben coincidir exactamente con las rutas utilizadas.

La normalización completa de nombres se debe hacer en un cambio separado, verificando primero cada referencia y probando todos los menús.

## Nota

Este repositorio está preparado para usar variables de entorno y no hardcodear credenciales en código. Nunca subas un `.env` real al repositorio.
