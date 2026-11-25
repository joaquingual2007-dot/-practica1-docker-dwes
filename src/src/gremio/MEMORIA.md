# Memoria · Gremio de Aventureros

## 1. Mi aventurero
- Usuario que he añadido a la tabla: `roble`
- Comando que he usado para generar el hash:
  ```bash
  docker compose exec www php -r "echo password_hash('Roble2026', PASSWORD_DEFAULT), PHP_EOL;"
  ```
- El hash resultante lo he pegado en phpMyAdmin al insertar la fila, y también lo he guardado en `sql/mi_aventurero.sql`.

## 2. Parte 1 · match
¿Has puesto `default` en `etiquetaRango()`? ¿Qué pasa si llega el rango `'Z'`? ¿Por qué lo prefieres así?

Sí, he incluido `default => 'Desconocido'`.
En PHP 8, `match` es exhaustiva: si el valor no encaja en ningún brazo y no hay `default`, PHP lanza una excepción `UnhandledMatchError` que corta la ejecución del script y devuelve un error 500 al usuario.
Prefiero tener `default` por programación defensiva. Si en el futuro los datos vienen de una base de datos y contienen un rango corrupto o inesperado (como `'Z'`), la aplicación no se rompe: muestra una etiqueta genérica en lugar de caerse.

## 3. Parte 2 · Validación de la URL
Escribe a mano en la barra `tablon.php?rango=Z&oro=hola`. ¿Qué ocurre y qué línea de tu código lo evita?

La página no se rompe y muestra las 10 misiones sin ningún error:
1. **`rango=Z`**: En `leerRangoFiltro()`, la línea `in_array($rango, RANGOS, true)` comprueba que el rango pertenezca a la lista de rangos válidos. `'Z'` no está en `RANGOS`, así que la función devuelve `null` y no se aplica ningún filtro de rango.
2. **`oro=hola`**: En `leerOroMinimo()`, la línea `filter_var($get['oro'], FILTER_VALIDATE_INT, [...])` valida que el valor sea un entero entre 0 y 10000. La cadena `'hola'` no pasa la validación y la función devuelve `0`, que es el mínimo por defecto.

Esto evita tanto errores de tipo (`TypeError` con `strict_types=1`) como inyección de valores no esperados en la lógica de filtrado.

## 4. Parte 3 · Sesión frente a cookie
Explica con tus palabras qué guardas en `$_SESSION` y qué guardas en cookies, y por qué no al revés.

En **`$_SESSION`** (servidor) guardo los datos sensibles y de estado que no deben ser manipulados por el usuario:
- `$_SESSION['aventurero']`: los datos del usuario autenticado (id, nombre, clase). Si estuvieran en una cookie, cualquiera podría editarlos con las herramientas del navegador y hacerse pasar por otro aventurero.
- `$_SESSION['zurron']`: las misiones aceptadas. Es información de estado de la sesión actual.
- `$_SESSION['visitas']` y `$_SESSION['entrada']`: contadores y marcas de tiempo de la sesión.

En **cookies** (navegador) solo guardo preferencias no sensibles que deben sobrevivir al cierre de sesión:
- `gremio_tema`: el tema visual (claro/oscuro). No es información confidencial y conviene que persista aunque el usuario cierre sesión. Si se manipula, se valida contra la lista `TEMAS` y se vuelve al tema claro.
- `gremio_usuario`: el nombre de usuario para rellenar el formulario de login. Nunca la contraseña. Si alguien lo lee, solo ve un nombre público.

No se puede poner al revés porque las cookies las controla el navegador del usuario: podría modificarlas libremente para suplantar identidades, alterar su zurrón o falsear visitas.

## 5. Parte 3 · Seguridad del login
- **¿Qué pasa si en el usuario escribes `' OR '1'='1`? ¿Por qué?**
  No ocurre nada malo. La consulta usa un marcador preparado (`:usuario`) en lugar de concatenar el valor directamente en el SQL. PDO envía la consulta y los datos por separado al motor de base de datos, por lo que el texto `' OR '1'='1` se trata como un nombre de usuario literal, no como código SQL. Simplemente no encuentra ningún usuario con ese nombre y devuelve `null`.

- **¿Para qué sirve `session_regenerate_id(true)`?**
  Previene ataques de fijación de sesión (Session Fixation). Un atacante podría conocer el identificador de sesión antes del login (por ejemplo, inyectándolo en la URL). Al regenerar el ID tras la autenticación exitosa y destruir el anterior (`true`), el atacante pierde acceso porque el identificador que conocía ya no es válido.

- **¿Por qué el mensaje de error es el mismo si falla el usuario o la contraseña?**
  Para evitar la enumeración de usuarios. Si el mensaje fuera distinto ("usuario no existe" vs "contraseña incorrecta"), un atacante podría descubrir qué usuarios están registrados en el sistema. Con un mensaje genérico ("Usuario o contraseña incorrectos"), no sabe si ha fallado el nombre, la contraseña o ambos.

## 6. Pruebas que he hecho
| Prueba | Resultado esperado | Resultado obtenido |
|---|---|---|
| Abrir tablon.php sin haber iniciado sesión | Redirige al login con aviso «solo para miembros» | Redirige a `login.php?motivo=privado` y muestra el aviso correctamente |
| Login con contraseña incorrecta | «Usuario o contraseña incorrectos.» | Muestra el mensaje de error genérico sin revelar qué campo falló |
| Usuario `' OR '1'='1` y cualquier contraseña | Mismo mensaje de error: no entra | No entra, la consulta preparada lo trata como texto literal |
| Login correcto con «Recordar» marcado | Entra; al salir, el login aparece con el usuario relleno | Funciona: la cookie `gremio_usuario` persiste y rellena el campo |
| Aceptar la misma misión dos veces / pulsar F5 | No se duplica; F5 no reenvía el formulario | No duplica gracias al `in_array` y PRG (Post-Redirect-Get) |
| Recargar el tablón varias veces | El contador de visitas sube | El contador incrementa en cada recarga mostrando el valor correcto |
| Cambiar el tema a oscuro y cerrar sesión | El tema se mantiene; el zurrón se vacía | El tema oscuro persiste (cookie); el zurrón se pierde (sesión destruida) |
| Editar a mano la cookie `gremio_tema` con texto raro | La página usa el tema claro y no pinta el texto | `temaActual()` valida contra `TEMAS` y devuelve `'claro'` como fallback |
| `tablon.php?rango=Z&oro=hola` | Muestra las 10 misiones sin errores | Muestra todas las misiones: rango inválido → null, oro inválido → 0 |
| Entrar con mi propio aventurero (`roble` / `Roble2026`) | Funciona | Login exitoso, muestra nombre y clase correctos en la cabecera |

## 7. Dificultades y cómo las he resuelto
- **`$_COOKIE` no se actualiza en la misma petición**: Al hacer `setcookie()` en `guardarTema()`, el valor no aparece inmediatamente en `$_COOKIE` porque las cookies viajan en la respuesta HTTP y solo están disponibles en la siguiente petición. Lo resolví redirigiendo con `header('Location: preferencias.php')` tras guardar, de modo que la siguiente carga ya lee el valor actualizado.
- **Reindexación del zurrón**: Al abandonar una misión con `unset()`, los índices del array quedan salteados (ej. 0, 2, 3). Esto podría causar problemas al recorrerlo o serializarlo en la sesión. Se resolvió con `array_values()` para reindexar el array desde 0.
- **Cierre de sesión completo**: No basta con `session_destroy()`. Hay que vaciar `$_SESSION`, caducar la cookie de sesión con `setcookie()` usando los mismos parámetros (`session_get_cookie_params()`) y una fecha en el pasado, y finalmente destruir la sesión. Si no se hace todo, el navegador podría seguir enviando la cookie antigua.
- **Tipado estricto con datos de formulario**: Con `declare(strict_types=1)`, los valores de `$_POST` y `$_GET` llegan como `string`, lo que puede provocar `TypeError` si se pasan directamente a funciones que esperan `int`. La sanitización con `filter_var`, `trim`, `is_string` y los casteos explícitos lo previenen.
