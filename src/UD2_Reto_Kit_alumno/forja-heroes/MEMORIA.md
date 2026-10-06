# Memoria · Reto UD2 «Forja de Héroes»

**Alumno/a:** Joaquín Gutiérrez Almagro · **Variante:** A (Guerrera) · **Fecha:** 06/10/2026

> Las capturas van en la carpeta `capturas/` y se enlazan así: `![descripción](capturas/nombre.png)`

## 1. Comprobación de resultados (CE 2.e)

Rellena la tabla con los valores que muestra TU ficha y compáralos con la tabla de comprobación del enunciado.

| Dato | Valor esperado (enunciado) | Valor de mi ficha | ¿Coincide? |
|---|---|---|---|
| Nivel | 6 | 6 | Sí |
| Vida máxima | 320 | 320 | Sí |
| % de vida | 36,9 % | 36,9 % | Sí |
| Daño | 40,9 | 40,9 | Sí |
| Estadística especial | 6 % | 6 % | Sí |
| Poder | 245 | 245 | Sí |
| Resultado frente al rival | 1 → Ventaja: ¡a la carga! | 1 → Ventaja: ¡a la carga! | Sí |

---

## 2. El código fuente y el documento resultante (CE 2.e · CE 2.c)

- Captura de `ficha.php` en el editor (parte de cálculos): `![Cálculos en el editor](capturas/calculos_editor.png)`
- Captura del **Ctrl+U** de la página: `![Código fuente en el navegador (Ctrl+U)](capturas/codigo_fuente_ctrl_u.png)`

### Explicación de tres diferencias entre ambas:

1. **Desaparición total del código PHP:** En el editor (`ficha.php`) encontramos la etiqueta `<?php`, la directiva `declare(strict_types=1);`, la llamada `require_once`, comentarios de código y todas las declaraciones de variables (`$fuerza`, `$danio`, `$poder`, etc.). En cambio, al ver el código fuente en el navegador (Ctrl+U), no queda ni una sola línea de PHP: el servidor ha interpretado el script y ha enviado únicamente código HTML5 plano al cliente.
2. **Sustitución de etiquetas cortas por valores calculados:** En el archivo fuente del editor figuran marcas de interpolación como `<?= $nivel ?>`, `<?= $barraVida ?>` o `<?= $danioFormateado ?>`. En el documento devuelto al navegador (Ctrl+U), esas etiquetas han sido sustituidas por sus valores finales ya evaluados (por ejemplo, el número `6`, la barra `███████░░░░░░░░░░░░░` o el texto `40,9`).
3. **Ocultamiento de la lógica de negocio y fórmulas:** En el editor se pueden leer claramente las operaciones aritméticas (como `($fuerza ** 2) / 10 + $nivel * 2`), los operadores de comparación de nave espacial (`<=>`) y las condiciones ternarias. En el navegador, el usuario únicamente recibe el resultado final («Ventaja: ¡a la carga!»). Esto demuestra que la lógica de cálculo permanece en el servidor y nunca se expone al cliente.

### ¿Qué ha pasado con el nombre del héroe en el Ctrl+U? ¿Por qué?
En `inc/heroe.php`, el nombre del héroe está definido como `$nombreHeroe = 'Brunhilda <Martillo de Hierro>';`. Al mostrarlo en `ficha.php`, se ha procesado con la función `htmlspecialchars($nombreHeroe)`.
En el Ctrl+U del navegador se observa que los caracteres `<` y `>` se han transformado en las entidades HTML seguras `&lt;` y `&gt;`, dando como resultado: `Brunhilda &lt;Martillo de Hierro&gt;`.
Esto es imprescindible para garantizar la seguridad frente a ataques de inyección de código (XSS, *Cross-Site Scripting*). Si se hubiera impreso sin escapar, el navegador web habría interpretado `<Martillo de Hierro>` como una etiqueta HTML propia (y posiblemente no reconocida o maliciosa), corrompiendo la estructura del documento.

---

## 3. Experimento: cambio un dato y predigo el efecto (CE 2.e)

1. **Dato que cambio en `inc/heroe.php` (valor antiguo → valor nuevo):**
   Modifico la constitución base: `$constitucionTxt = '15';` pasa a ser `$constitucionTxt = '20';`.

2. **Predicción (antes de recargar):**
   - **Vida máxima:** Al cambiar la constitución a 20 manteniendo el nivel en 6, la fórmula `$vidaMax = VIDA_BASE + constitución × nivel × MULT_VIDA` pasa a ser: $50 + (20 \times 6 \times 3) = 50 + 360 = 410$ (antes valía 320).
   - **Porcentaje de vida:** Con la vida actual inalterada en 118, el nuevo porcentaje será: $(118 / 410) \times 100 \approx 28,78\% \rightarrow 28,8\%$ (antes era 36,9%).
   - **Estado:** Al cumplirse que $28,78\% < 35\%$, el ternario evaluará el estado a **«En peligro»** (antes estaba «En pie»).
   - **Barra de vida:** Los bloques llenos pasarán a ser `(int) round(28.78 / 100 * 20) = round(5.756) = 6` bloques `█` (antes eran 7).
   - Las demás estadísticas (nivel, daño, crítico, poder y rival) permanecerán exactamente iguales porque no dependen de la constitución.

3. **Resultado real (captura):**
   `![Experimento de mutación](capturas/experimento_mutacion.png)`

4. **¿Acertaste? Si no, ¿por qué?**
   Sí, acerté en todos los valores calculados: la vida máxima se incrementó a 410, el porcentaje bajó a 28,8%, el estado pasó automáticamente a «En peligro» y la barra visual se redujo a 6 bloques llenos, demostrando el dinamismo de PHP al recalcular todo desde cero en cada petición.

---

## 4. Experimento con strict_types (CE 2.f)

1. **Quita el `(int)` del cálculo de los bloques de la barra de vida. Recarga. Captura del error:**
   `![Error strict_types](capturas/error_strict_types.png)`

2. **Explica qué dice el mensaje (tipo de error, función, argumento, línea):**
   El motor de PHP detiene la ejecución y arroja una excepción fatal de tipo **TypeError**:
   `Fatal error: Uncaught TypeError: str_repeat(): Argument #2 ($times) must be of type int, float given, called in /var/www/html/ficha.php on line 73`
   - **Tipo de error:** `TypeError` (error fatal en tiempo de ejecución por discrepancia de tipo).
   - **Función afectada:** `str_repeat()`.
   - **Argumento:** Argumento nº 2 (`$times`), que define la cantidad de repeticiones.
   - **Causa:** La función espera obligatoriamente un entero (`int`), pero la función `round()` devuelve un número con coma flotante (`float`). Al haber quitado el casting explícito `(int)`, se le pasó un float a una función estricta.

3. **Comenta la línea `declare(strict_types=1);` (manteniendo el cambio anterior) y recarga. ¿Qué pasa? ¿Por qué?**
   Al comentar `declare(strict_types=1);`, la página web vuelve a cargar perfectamente y no muestra ningún error. Esto ocurre porque PHP activa por defecto el modo coercitivo (*type juggling*), en el cual el intérprete convierte de forma automática y silenciosa el `float` a `int` para que coincida con el tipo esperado por `str_repeat()`.

4. **Deja el código como estaba. ¿Qué opción prefieres para un proyecto real y por qué?**
   Para un entorno profesional y proyectos reales, prefiero sin duda activar siempre `declare(strict_types=1);`. El tipado estricto previene comportamientos inesperados, pérdida de precisión numérica o conversiones erróneas que podrían pasar desapercibidas y provocar fallos críticos en producción. Nos ayuda a detectar incoherencias en fase de desarrollo de forma inmediata.

---

## 5. Directivas (CE 2.f)

- **Captura de `diagnostico.php`:** `![Panel de diagnóstico](capturas/diagnostico.png)`
- **¿Qué valor pondrías en `display_errors` en un servidor de producción? ¿Por qué?**
  En un servidor de producción se debe configurar obligatoriamente `display_errors = Off` (o `0`). Mostrar los errores de ejecución directamente en el navegador del usuario final es una vulnerabilidad grave de revelación de información sensible (*Information Disclosure*), ya que expone rutas absolutas del servidor, detalles del sistema operativo, versiones de software y consultas internas que pueden ser explotadas por atacantes. En producción, los errores deben guardarse en ficheros de registro internos del sistema (`log_errors = On`) y al usuario se le debe mostrar una página de error genérica y amigable.

---

## 6. Uso de IA (obligatorio declararlo)

**¿Has usado alguna IA? ¿Para qué?**
Sí, he utilizado el asistente de IA para comprender los requisitos del reto, consultar la sintaxis del operador ternario anidado, repasar la función `htmlspecialchars()` para prevenir ataques XSS, y entender el funcionamiento de las directivas en ficheros `.ini` y su ámbito de modificación (`PHP_INI_ALL` vs `PHP_INI_PERDIR`).

**Copia una respuesta que te diera y explica si era correcta o qué tuviste que corregir:**
> *Respuesta de la IA:* «Para calcular si el héroe está en peligro o en pie, puedes usar un `if-else` que compruebe si el porcentaje de vida es menor a 35...»
>
> *Explicación y corrección:* La respuesta utilizaba la estructura `if-else`, lo cual era conceptualmente válido en PHP general, pero **está estrictamente prohibido en este reto de la UD2**, donde no podemos usar `if`, `else`, `switch` ni bucles. Tuve que descartar el `if` y resolver la lógica mediante un operador ternario anidado:  
> `$estado = ($vidaActual > 0) ? (($pctVida < 35) ? 'En peligro' : 'En pie') : 'K.O.';`
