# Challenge PHP - Gestión de Productos

API REST en PHP nativo para gestionar productos, conversión ARS→USD, MySQL y frontend en HTML/CSS/JS puro.

## Requisitos

- Docker y Docker Compose instalados
- Puertos 8080 y 3306 disponibles

## Configuracion

Copiar el archivo de ejemplo y ajustar la cotizacion del dolar:

```bash
cp .env.example .env
```

Editar `.env`:

```env
PRECIO_USD=1000
```

`PRECIO_USD` representa cuantos pesos argentinos equivalen a 1 dolar. La API calcula `precio_usd` como `precio / PRECIO_USD`.

## Ejecucion

Levantar los servicios:

```bash
docker-compose up -d --build
```

Detener:

```bash
docker compose down
```

Ver logs:

```bash
docker compose logs -f
```
