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

---

## 📁 Estructura del proyecto

```text
gremio/
├── sql/
│   └── gremio.sql           # Script de creación de la tabla aventureros
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
│   ├── login.php            # Entrada al gremio (Entrega 2)
│   ├── logout.php           # Cierre de sesión (Entrega 2)
│   └── preferencias.php     # Selección de tema visual (Entrega 2)
├── LEEME.md                 # Enunciado y especificaciones del profesor
└── MEMORIA.md               # Memoria técnica de la práctica
```
