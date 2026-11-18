# Memoria · Gremio de Aventureros

**Código de alumno/a:** 2DAW-ALUMNO (sin datos personales)

## 1. Mi aventurero
- Usuario que he añadido a la tabla: `sombra`
- Comando o pasos que he usado para generar el hash:
  1. Generar el hash bcrypt con el comando de Docker:
     ```bash
     docker compose exec www php -r "echo password_hash('Sombra2026', PASSWORD_DEFAULT), PHP_EOL;"
     ```
  2. En phpMyAdmin (http://localhost:8080), dentro de la base de datos del proyecto, insertar la fila en la tabla `aventureros`:
     - `usuario`: `sombra`
     - `nombre`: `Sombra del Roble`
     - `clase`: `Explorador`
     - `password_hash`: [hash generado con password_hash]

## 2. Parte 1 · match
¿Has puesto `default` en `etiquetaRango()`? ¿Qué pasa si llega el rango `'Z'`? ¿Por qué lo prefieres así?

Sí, he incluido `default => 'Desconocido'`.
En PHP 8, la estructura `match` es exhaustiva: si se evalúa un valor que no tiene correspondencia en ninguno de los brazos y no se define un brazo `default`, PHP lanza una excepción fatal `UnhandledMatchError` que interrumpe la ejecución del script y devuelve un error 500 al usuario.
Prefiero usar `default` por principios de programación defensiva. Si los datos del array o de una futura base de datos contienen un rango no previsto o corrupto (como `'Z'`), la aplicación no se rompe y muestra una etiqueta genérica en lugar de provocar la caída del servicio.

## 3. Parte 2 · Validación de la URL
Escribe a mano en la barra `tablon.php?rango=Z&oro=hola`. ¿Qué ocurre y qué línea de tu código lo evita?

Al escribir `tablon.php?rango=Z&oro=hola`, la página no falla ni lanza errores de tipo:
1. **`rango=Z`**: En `leerRangoFiltro()`, se comprueba `in_array($rango, RANGOS, true)`. Al no existir `'Z'` dentro del array de rangos válidos, la función devuelve `null`. Esto hace que el tablón no aplique ningún filtro por rango y muestre todas las misiones.
2. **`oro=hola`**: En `leerOroMinimo()`, se utiliza `filter_var($get['oro'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 10000, 'default' => 0]])`. Al recibir una cadena no numérica como `'hola'`, la validación falla y devuelve `0`. Esto evita que un valor no entero llegue a las comparaciones numéricas de `filtrarMisiones()` y que `declare(strict_types=1);` lance un `TypeError`.

## 4. Parte 3 · Sesión frente a cookie
*(Correspondiente a la Entrega 2)*
En `$_SESSION` se almacenan en el servidor los datos confidenciales o de estado que no deben ser manipulados por el cliente (datos del aventurero autenticado, visitas, misiones del zurrón). En las cookies del navegador solo se almacenan preferencias no críticas o identificadores (el tema visual claro/oscuro o recordar el nombre de usuario), evitando guardar información sensible en el cliente.

## 5. Parte 3 · Seguridad del login
*(Correspondiente a la Entrega 2)*
- ¿Qué pasa si en el usuario escribes `' OR '1'='1`? ¿Por qué?
  No produce inyección SQL porque la consulta está parametrizada mediante marcadores PDO (`:usuario`), separando la estructura de la consulta de los datos de entrada.
- ¿Para qué sirve `session_regenerate_id(true)`?
  Para prevenir ataques de fijación de sesión (Session Fixation), renovando el identificador de sesión al autenticarse y eliminando el identificador anterior.
- ¿Por qué el mensaje de error es el mismo si falla el usuario o la contraseña?
  Para no dar pistas a posibles atacantes sobre si un usuario existe o no en el sistema (evitar la enumeración de usuarios).

## 6. Pruebas que he hecho
| Prueba | Resultado esperado | Resultado obtenido |
|---|---|---|
| Entrar en tablon.php sin login (Entrega 1) | Acceso permitido como "Invitado", mostrando las 10 misiones con sus tarjetas y estilos según dificultad | Funciona correctamente; se muestran las 10 tarjetas con bordes verde, naranja y rojo |
| Filtrar por rango y oro mínimo | Mostrar únicamente las misiones que cumplan ambos filtros | Filtra con exactitud y actualiza el contador de misiones visibles |
| URL con valores inválidos (`?rango=Z&oro=hola`) | No romper la aplicación y usar valores seguros por defecto | Se restablece rango a null y oro a 0 sin errores |
| Aceptar una misión en el tablón | Se añade al zurrón, muestra aviso flash y el botón cambia a «✔ En tu zurrón» | Funciona correctamente; redirige por POST-Redirect-GET |
| Aceptar la misma misión dos veces | Mensaje de advertencia informando que ya está en el zurrón sin duplicar | Muestra mensaje flash de aviso y no duplica el id en el array |
| Abandonar una misión en zurron.php | Se elimina del zurrón, se reindexa el array y se recalcula el total de oro | Misión eliminada con éxito y total de recompensa actualizado |
| Zurrón vacío | Mostrar mensaje informativo y enlace al tablón | Muestra el mensaje y el enlace correctamente |

## 7. Dificultades y cómo las he resuelto
- **Pluralización de días**: Gestionar correctamente la visualización singular/plural ("1 día" frente a "X días") en la ficha de cada encargo; se resolvió mediante un operador ternario simple antes de pintar el dato.
- **Reindexación de arrays tras eliminación**: Al usar `unset()` sobre un elemento de `$_SESSION['zurron']`, los índices numéricos quedaban desordenados. Se solucionó aplicando `array_values()` tras el `unset` para mantener el array como una lista limpia indexada numéricamente.
- **Estricta tipificación de tipos**: Con `declare(strict_types=1);`, cualquier parámetro de tipo incorrecto desde `$_GET` o `$_POST` provocaría un error fatal. La sanitización con `filter_var` y las comprobaciones con `is_string` aseguraron que siempre se pasen los tipos correctos (`int`, `string` o `null`).
