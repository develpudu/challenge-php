# Challenge PHP - Gestión de Productos

API REST en PHP nativo para gestionar productos, conversión ARS→USD, MySQL y frontend en HTML/CSS/JS puro.

> Respecto a las decisiones técnicas ver [DECISIONES.md](./DECISIONES.md) para más detalles.

## Requisitos

- Docker y Docker Compose instalados
- Puertos 8080 y 3306 disponibles

## Configuración

1. Clonar el repositorio
2. Copiar `.env.example` a `.env` y ajustar valores a gusto

```bash
cp .env.example .env
```

3. Variables de entorno:

| Variable | Descripción | Default |
|----------|-------------|---------|
| `PRECIO_USD` | Cotización del dólar en pesos argentinos | `1000` |
| `DB_HOST` | Host de MySQL | `db` |
| `DB_PORT` | Puerto de MySQL | `3306` |
| `DB_NAME` | Nombre de la base de datos | `productos` |
| `DB_USER` | Usuario de MySQL | `root` |
| `DB_PASS` | Contraseña de MySQL | `rootpassword` |

## Ejecución

Levantar los servicios:

```bash
docker compose up -d
```

Detener:

```bash
docker compose down
```

Ver logs:

```bash
docker compose logs -f
```

## Backend

Se puede acceder a los endpoints de la API en:

| URL | Método | Endpoint | Qué hace |
|--------|--------|----------|----------|
| `http://localhost:8080` | GET | `/productos` | Lista todos los productos |
| `http://localhost:8080` | GET | `/productos/{id}` | Obtiene un producto por ID |
| `http://localhost:8080` | POST | `/productos` | Crea un producto |
| `http://localhost:8080` | PUT | `/productos/{id}` | Modifica un producto |
| `http://localhost:8080` | DELETE | `/productos/{id}` | Elimina un producto |

## Probar la API

### Nuevo producto

```bash
curl -X POST http://localhost:8080/productos \
  -H "Content-Type: application/json" \
  -d '{"nombre": "Producto de prueba", "descripcion": "Este es un producto de prueba desde curl.", "precio": 150000.00}'
```

### Listar todos los productos

```bash
curl http://localhost:8080/productos
```

### Actualizar un producto

```bash
curl -X PUT http://localhost:8080/productos/5 \
  -H "Content-Type: application/json" \
  -d '{"nombre": "Producto de prueba - Actualizado", "descripcion": "Se actualizó el producto desde curl.", "precio": 160000.00}'
```

### Eliminar producto

```bash
curl -X DELETE http://localhost:8080/productos/5
```

## Frontend

Se puede acceder al frontend en `http://localhost:8080`

El frontend permite:
- Listar productos y mostrar precios en ARS y USD.
- Crear productos.
- Editar productos existentes.
- Eliminar productos.
- Mostrar mensajes de éxito o error.

## Estructura

```
challenge-php/
├── api/
│   ├── Action/
│   │   ├── CreateProductAction.php
│   │   ├── DeleteProductAction.php
│   │   ├── GetProductAction.php
│   │   ├── ListProductsAction.php
│   │   └── UpdateProductAction.php
│   ├── Config/
│   │   └── Config.php
│   ├── Database/
│   │   └── Connection.php
│   ├── Domain/
│   │   ├── Product.php
│   │   └── ProductRepository.php
│   ├── Responder/
│   │   └── JsonResponse.php
│   ├── Router.php
│   └── public/
│       └── index.php
├── public/
│   ├── index.html
│   ├── css/
│   │   └── styles.css
│   └── js/
│       └── app.js
├── database/
│   └── init.sql
├── docker-compose.yml
├── .env.example
├── .gitignore
├── README.md
└── DECISIONES.md
```
