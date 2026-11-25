# ⚔️ Gremio de Aventureros · Villa Roble

Proyecto de Desarrollo Web en Entorno Servidor (DWES) · 2.º DAW.

Digitalización del tablón de misiones del Gremio de Aventureros utilizando PHP 8 y arquitectura LAMP con Docker.

---

## 📌 Entrega 1: Funcionalidades implementadas

### Parte 1 · El tablón de misiones
- **Estructuras de control con `match`:**
  - `etiquetaRango()`: mapeo estricto de rangos (`E Novato`, `D Aprendiz`, `C Veterano`, `B Experto`, `A Élite`, `S Leyenda`) con rama de seguridad `default`.
  - `claseRango()`: asignación de clases CSS de dificultad (`facil`, `media`, `dificil`) con agrupación de valores.
- **Formateo de datos:**
  - Función `formatearOro()` para dar formato a las monedas (`5.000 mo`).
  - Control singular/plural en la duración de las misiones (`1 día` / `X días`).
- **Renderizado seguro:**
  - Pintado dinámico de las 10 tarjetas de misiones mediante bucle `foreach`.
  - Escape de datos contra XSS mediante la función auxiliar `e()` (`htmlspecialchars`).

### Parte 2 · Filtros y zurrón
- **Validación y sanitización estricta (`strict_types=1`):**
  - Validación del parámetro GET de rango frente a lista blanca (`RANGOS`).
  - Validación de recompensa mínima con `filter_var` y `FILTER_VALIDATE_INT` (0 a 10.000 mo).
- **Filtrado dinámico:**
  - Función `filtrarMisiones()` con bucle para filtrar por rango y oro mínimo.
- **Gestión del zurrón:**
  - Aceptación de misiones por método `POST` y patrón **PRG** (Post-Redirect-Get) con mensajes flash en sesión.
  - Comprobación de existencia (`buscarMision()`) y prevención de encargos duplicados en el zurrón.
  - Indicador visual (`✔ En tu zurrón`) o botón de aceptación según el estado.
  - Vista `zurron.php` con tabla de encargos aceptados, cálculo acumulado de recompensas (`totalRecompensa()`) y opción de abandonar encargos reindexando el array con `array_values()`.

---

## 📌 Entrega 2: Miembros del gremio

### Parte 3 · Autenticación, sesiones y cookies

#### Autenticación contra la base de datos (CE 4.e.1)
- Función `autenticar()` con consulta preparada (`marcador :usuario`) y verificación del hash con `password_verify()`.
- `login.php` con validación de campos, captura de `PDOException` (detalle al `error_log()`, mensaje genérico al usuario) y `session_regenerate_id(true)` tras login exitoso.
- `requiereLogin()` protege `tablon.php`, `zurron.php` y `preferencias.php`: sin sesión activa, redirige a `login.php?motivo=privado`.
- `cerrarSesion()` destruye la sesión completamente: vacía `$_SESSION`, caduca la cookie de sesión y llama a `session_destroy()`.
- Aventurero propio añadido con hash bcrypt generado por `password_hash()` (INSERT en `sql/mi_aventurero.sql`).

#### Estado con sesiones (CE 4.b)
- Zurrón almacenado en `$_SESSION['zurron']`: persiste entre páginas y se vacía al cerrar sesión.
- Contador de visitas al tablón (`$_SESSION['visitas']`) y hora de entrada (`$_SESSION['entrada']`) visibles en la cabecera del tablón.
- Mensajes flash entre páginas mediante `$_SESSION['flash']`.

#### Cookies (CE 4.c)
- Tema claro/oscuro en `preferencias.php`, almacenado un año en la cookie `gremio_tema`. Validación del valor leído contra la lista `TEMAS`; si se manipula, se revierte a «claro».
- Casilla «Recordar mi usuario»: cookie `gremio_usuario` de 30 días con el nombre de usuario (nunca la contraseña). Al desmarcar, se borra la cookie.
- Todas las cookies configuradas con `path '/'`, `httponly` y `samesite 'Lax'` (array de opciones de `setcookie`).

---

## 🚀 Despliegue con Docker

1. **Arrancar el entorno:**
   ```bash
   docker compose up -d
   ```

2. **Acceso al servicio web:**
   - Tablón de misiones: [http://localhost:8080/gremio/src/](http://localhost:8080/gremio/src/)
   - Gestor de base de datos (phpMyAdmin): [http://localhost:8081](http://localhost:8081)

3. **Base de datos:**
   - Importar `gremio/sql/gremio.sql` en la base de datos del entorno para la tabla `aventureros`.
   - Importar `gremio/sql/mi_aventurero.sql` para añadir el aventurero propio.

---

## 📁 Estructura del proyecto

```text
gremio/
├── sql/
│   ├── gremio.sql           # Script de creación de la tabla aventureros
│   └── mi_aventurero.sql    # INSERT del aventurero propio (con hash)
├── src/
│   ├── css/
│   │   └── estilo.css       # Estilos CSS
│   ├── inc/
│   │   ├── cabecera.php     # Cabecera HTML común
│   │   ├── conexion.php     # Conexión PDO a MySQL
│   │   ├── datos_misiones.php # Array de misiones
│   │   ├── funciones.php    # Lógica de filtrado, rangos y formateo
│   │   ├── pie.php          # Pie de página HTML
│   │   └── sesion.php       # Gestión de sesión y cookies
│   ├── tablon.php           # Vista principal del tablón de misiones
│   ├── zurron.php           # Vista del zurrón del aventurero
│   ├── login.php            # Entrada al gremio
│   ├── logout.php           # Cierre de sesión
│   └── preferencias.php     # Selección de tema visual
├── LEEME.md                 # Enunciado y especificaciones del profesor
└── MEMORIA.md               # Memoria técnica de la práctica
```

---

## Uso de la IA

He usado la IA para el README y en forma de apoyo a lo largo del proyecto.
