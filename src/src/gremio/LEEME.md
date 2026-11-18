# ⚔️ Gremio de Aventureros · Kit de partida (UD3 · UD4 · UD5)

0613 DWES · 2.º DAW A · curso 2026-2027

Este kit trae **solo el proyecto**. El servidor ya lo tienes montado: es tu
`docker-lamp` del ejercicio inicial (servicios `www`, `db` y `phpmyadmin`).

## 1. Instalación (5 minutos)

1. Copia la carpeta `gremio/` entera dentro de la carpeta `www/` de tu docker-lamp.
2. Arranca el entorno desde la carpeta de docker-lamp:
   `docker compose up -d`
3. Abre phpMyAdmin (http://localhost:8080), entra en la base de datos `dbname`,
   pestaña **Importar**, y sube `gremio/sql/gremio.sql`.
   Debe aparecer la tabla `aventureros` con 3 filas.
4. Abre http://localhost/gremio/src/ : verás el tablón como «Invitado».
   Las misiones aún no aparecen: eso es la Parte 1.

> Si tu docker-lamp usa otro puerto u otros nombres de base de datos o usuario,
> revisa las variables `MYSQL_*` de tu `docker-compose.yml`: `inc/conexion.php`
> las lee de ahí.

## 2. Usuarios de prueba

| Usuario   | Contraseña   | Clase     |
|-----------|--------------|-----------|
| brunhilda | Martillo2026 | Guerrera  |
| aldric    | Arcano2026   | Hechicero |
| vex       | Sombra2026   | Pícara    |

**Añade tu propio aventurero** (obligatorio). Genera el hash de tu contraseña:

    docker compose exec www php -r "echo password_hash('TuClave', PASSWORD_DEFAULT), PHP_EOL;"

y en phpMyAdmin inserta una fila en `aventureros` pegando ese hash en
`password_hash`. Nunca guardes la contraseña en claro.

## 3. Qué está hecho y qué tienes que hacer

| Archivo | Estado |
|---|---|
| `sql/gremio.sql`, `inc/datos_misiones.php`, `inc/conexion.php` | Dado. No tocar. |
| `inc/cabecera.php`, `inc/pie.php`, `css/estilo.css`, `index.php`, `logout.php` | Dado. No tocar. |
| `inc/funciones.php` | **Parte 1 y 2** · completa los `TODO` |
| `tablon.php`, `zurron.php` | **Parte 1, 2 y 3** · completa los `TODO` |
| `inc/sesion.php`, `login.php`, `preferencias.php` | **Parte 3** · completa los `TODO` |
| `MEMORIA.md` | Rellénala al final |

Cada función tiene un *return provisional*: la web funciona desde el primer día
y va mejorando según completas. No cambies nombres ni firmas de las funciones.

Orden recomendado: `funciones.php` → `tablon.php` (Parte 1) → filtros y botón
Aceptar (Parte 2) → `zurron.php` → `sesion.php` → `login.php` → `preferencias.php`.

## 4. Reglas del gremio

- `declare(strict_types=1);` en todos los archivos (ya viene puesto).
- Todo dato que pintes en HTML pasa por `e()`.
- Consultas a la BD **solo preparadas**. Concatenar variables en SQL = criterio suspenso.
- Contraseñas solo con `password_hash` / `password_verify`.
- IA: solo el cuaderno NotebookLM que comparte el profesor.

## 5. Entrega

Un `.zip` con **solo** la carpeta `gremio/` (sin docker-lamp), con `MEMORIA.md` rellena.
