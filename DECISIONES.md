# Decisiones técnicas

> Algunas cosas me habría gustado agregar pero por el tiempo y que no eran requeridas descarte son modo worker con FrankenPHP, deploy en vercel.

## Estructura del proyecto

Se separa el backend en `api/` y el frontend en `public/` porque el proyecto tiene solo una API en PHP y un frontend sumamente sencillo (me parece que no hace falta tratarlos como dos aplicaciones independientes) `api/` permite organizar el backend con ADR, mientras que `public/` concentra lo que sirve el navegador.

Y con FrankenPHP se levantan los 2 en el mismo puerto

## Arquitectura: ADR (Action-Domain-Responder)

En un principio la idea era partir de un MVC simple para el backend, pero la verdad que me parece que ADR encaja mucho mejor con una API REST.
Si bien MVC es más conocido, ADR está pensado para acciones concretas y resulta más directo para una API (cada endpoint es una operación que recibe una petición y devuelve una respuesta)

La idea era separar responsabilidades para que cada parte sea más fácil de entender, mantener y probar.

No creo que ADR sea siempre mejor que MVC. Para este proyecto me pareció la opción adecuada.

*Nota: ADR me parece tambien mejor para generar casos de uso, pruebas unitarias y escalar.*

## PSR-7 para mensajes HTTP

Quería usar **PSR-7** como estándar para requests y responses.

*Nota: al final no se usó PSR-7. El responder y router propios me parece que son suficientes para este proyecto.*

## Frontend: `public/`

Cubre:
- Listar productos con precios en ARS y USD
- Crear productos
- Editar productos existentes
- Eliminar productos
- Mostrar mensajes de éxito o error

La comunicación con el backend usa `fetch`. La conversión a USD la hace la API.

## Entorno: FrankenPHP con Docker

**FrankenPHP** porque la verdad me gusta y simplifica el entorno local no solo en docker.

## Seguridad, errores y mantenibilidad

Pequeño detalles si bien se mantuvo todo minimalista, se tuvieron en cuenta:

- Consultas SQL parametrizadas para evitar inyección
- Validación de datos en la API
- Respuestas JSON consistentes con códigos HTTP correctos
- Manejo controlado de errores, sin filtrar credenciales ni detalles internos
- Variables de entorno para configuración
- Separación de responsabilidades y estructura que permita probar cada parte
