# UD2 · Caso guiado «Estación orbital ARES-7»

0613 Desarrollo Web en Entorno Servidor · 2.º DAW A · curso 2026-2027
RA 2: Escribe sentencias ejecutables por un servidor web reconociendo y aplicando procedimientos de integración del código en lenguajes de marcas.

## La historia

Somos el equipo de software de la estación orbital ARES-7. El ordenador de a bordo, **ORBIT-OS**, genera en el servidor el *parte de estado* que la tripulación consulta desde el navegador.

ORBIT-OS tiene un problema: **amnesia**. Con cada petición, el programa arranca de cero, genera el HTML y muere. Esa es la idea central de la unidad: *PHP no recuerda nada entre peticiones*.

## Arranque del entorno

```bash
docker compose up -d        # levanta Nginx (web) + PHP-FPM (php)
# abrir http://localhost:8080
docker compose restart php  # tras cambiar php/ares7.ini
docker compose logs php     # ver el log de errores de PHP
docker compose down         # parar
```

| Fichero | Qué es |
|---|---|
| `docker-compose.yml` | Dos contenedores separados: `web` (Nginx) y `php` (PHP 8.4 FPM) |
| `nginx/default.conf` | Pasa los `.php` a PHP-FPM y bloquea `/inc/` |
| `php/ares7.ini` | **Directivas** del entorno de desarrollo (CE 2.f) |
| `src/` | Código del caso guiado (la raíz web) |

## Secuencia de pasos y sesiones

| Sesión | Pasos | CE | Contenido |
|---|---|---|---|
| **S1** · vie 25/09 · 13:00–14:30 | arrancar Docker, 01, 02 | a, b, c, e | Etiquetas `<?php ?>` y `<?= ?>`. «Ver código fuente». La amnesia. |
| **S2** · lun 28/09 · 09:30–11:00 | 03, 04, 05 (06 si da tiempo) | d, e, g | Tipos, conversiones, cadenas, `htmlspecialchars`, operadores. |
| **S3** · mar 29/09 · 11:30–13:00 | (06), 07, 07b, 08, 09 | f, g, h | Constantes, directivas, `strict_types`, ámbito. Presentación del entregable. |

**Rutina en cada paso:** abrir la página → Ctrl+U (ver código fuente) → leer el `.php` en el editor → modificar algo → recargar.

## Qué enseña cada fichero

| Paso | Fichero | Idea clave |
|---|---|---|
| 01 | `01-hola-estacion.php` | El navegador no recibe el PHP, sino lo que el PHP escribe. Trampa: PHP dentro de un comentario HTML se ejecuta. |
| 02 | `02-amnesia.php` | El contador siempre vale 1: cada petición es un programa nuevo. Comparación Java/PHP. |
| 03 | `03-tipos.php` | Tipado dinámico, `var_dump`, `get_debug_type`, *casting*, *type juggling*, valores falsos. |
| 04 | `04-cadenas.php` | Comillas simples y dobles, heredoc, funciones `mb_*` con acentos, `htmlspecialchars` (XSS). |
| 05 | `05-operadores.php` | `/` frente a `intdiv`, `**`, `==` frente a `===`, `<=>`, trampa de `and`, `?:`, `??`, `??=`. |
| 06 | `06-constantes.php` | `const` frente a `define`, constantes predefinidas y mágicas, los float no son exactos. |
| 07 | `07-directivas.php` | Niveles de configuración: php.ini, `ini_get`, `ini_set`, `ini_restore`, `error_reporting`, `strict_types`. |
| 07b | `07b-error-strict.php` | **Falla a propósito**: `TypeError` con `strict_types`. Cómo se lee un error. `display_errors` en producción. |
| 08 | `08-ambito.php` | Global, local, `static` (que también muere con la petición), `$GLOBALS`, superglobal `$_SERVER`. |
| 09 | `09-parte-de-estado.php` + `inc/config.php` | Todo junto: calcular arriba y pintar abajo. **Modelo del entregable.** |
| — | `phpinfo.php` | Configuración completa. Solo para desarrollo, nunca en producción. |

## Requisitos probados

- Todo el código se ha ejecutado con PHP 8.4 (compatible con 8.3 y 8.5), sin avisos ni *deprecations* con `error_reporting = E_ALL`.
- No se usan estructuras de control (`if`, bucles) ni arrays propios: llegan en la UD3. Las únicas excepciones son la lectura de `$_SERVER` y una función mínima en el paso 08 para ver el ámbito.
