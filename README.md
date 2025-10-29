# 🌸 SmellElegance - Backend API

> API RESTful para E-commerce de Perfumes desarrollada con Laravel 11 + MySQL

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tests](https://img.shields.io/badge/Tests-22%20passing-success.svg)](https://phpunit.de)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📋 Tabla de Contenidos

- [Descripción](#-descripción)
- [Características](#-características)
- [Tecnologías](#️-tecnologías)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Configuración](#️-configuración)
- [Uso](#-uso)
- [Testing](#-testing)
- [API Endpoints](#-api-endpoints)
- [Arquitectura](#️-arquitectura)
- [Roadmap](#-roadmap)
- [Contribución](#-contribución)
- [Licencia](#-licencia)

---

## 🎯 Descripción

**SmellElegance Backend** es una API RESTful completa para gestionar un catálogo de perfumes, con autenticación basada en tokens, autorización por roles y operaciones CRUD protegidas.

### **Funcionalidades Principales:**

- ✅ Catálogo público de productos y marcas
- ✅ Autenticación con Laravel Sanctum (token-based)
- ✅ Sistema de roles (Admin/Client)
- ✅ CRUD completo de productos (solo Admin)
- ✅ Upload de imágenes con validación
- ✅ Service Layer + DTO Pattern (Clean Architecture)
- ✅ 22 tests automatizados (Unit + Feature)

---

## ✨ Características

### **Seguridad**
- 🔐 Autenticación con tokens (Sanctum)
- 🛡️ Autorización basada en roles
- ✅ Validación de formularios robusta
- 🔒 Protección contra SQL injection (Eloquent ORM)
- 📁 Validación de archivos subidos (tipo, tamaño)

### **Arquitectura**
- 🏗️ Service Layer Pattern
- 📦 Data Transfer Objects (DTO)
- 🧪 Testing automatizado (PHPUnit)
- 🔄 API Resources para serialización
- 📊 Database Seeders con datos de prueba

### **API**
- 🌐 RESTful endpoints
- 📄 Paginación automática
- 🔍 Búsqueda y filtros
- 📸 Manejo de imágenes
- 📝 Documentación clara

---

## 🛠️ Tecnologías

| Categoría | Tecnología |
|-----------|------------|
| **Framework** | Laravel 11.x |
| **Lenguaje** | PHP 8.2+ |
| **Base de Datos** | MySQL 8.0 |
| **Autenticación** | Laravel Sanctum |
| **Testing** | PHPUnit 10.x |
| **Dev Environment** | Laravel Sail (Docker) |
| **ORM** | Eloquent |
| **Versionado** | Git + GitHub |

---

## 📦 Requisitos

- **Docker** + **Docker Compose** (para Sail)
- **Git**
- Navegador web moderno
- Cliente API (Postman, Insomnia, o cURL)

---

## 🚀 Instalación

### **1. Clonar el repositorio**

```bash
git clone https://github.com/RamzaF/smellelegance-backend-api.git
cd smellelegance-backend-api
```

### **2. Instalar dependencias**

```bash
# Copiar archivo de entorno
cp .env.example .env

# Iniciar contenedores Docker
./vendor/bin/sail up -d

# Instalar dependencias PHP
./vendor/bin/sail composer install

# Generar key de aplicación
./vendor/bin/sail artisan key:generate
```

### **3. Configurar base de datos**

```bash
# Ejecutar migraciones
./vendor/bin/sail artisan migrate

# Poblar con datos de prueba
./vendor/bin/sail artisan db:seed
```

### **4. Configurar storage**

```bash
# Crear link simbólico para imágenes
./vendor/bin/sail artisan storage:link
```

---

## ⚙️ Configuración

### **Variables de Entorno (.env)**

```env
APP_NAME="SmellElegance API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=smellelegance
DB_USERNAME=sail
DB_PASSWORD=password

SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000
```

---

## 💻 Uso

### **Iniciar el servidor**

```bash
./vendor/bin/sail up -d
```

La API estará disponible en: `http://localhost/api/v1`

### **Detener el servidor**

```bash
./vendor/bin/sail down
```

### **Ver logs**

```bash
./vendor/bin/sail logs -f
```

---

## 🧪 Testing

### **Ejecutar todos los tests**

```bash
./vendor/bin/sail artisan test
```

### **Ejecutar solo tests unitarios**

```bash
./vendor/bin/sail artisan test --testsuite=Unit
```

### **Ejecutar solo tests de feature**

```bash
./vendor/bin/sail artisan test --testsuite=Feature
```

### **Tests con cobertura**

```bash
./vendor/bin/sail test --coverage
```

**Resultado esperado:**
```
Tests:  22 passed (79 assertions)
Duration: ~16s
```

---

## 📚 API Endpoints

### **Públicos (Sin autenticación)**

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `POST` | `/api/v1/auth/login` | Login de usuario |
| `GET` | `/api/v1/brands` | Listar marcas |
| `GET` | `/api/v1/products` | Listar productos (paginado) |
| `GET` | `/api/v1/products/{id}` | Detalle de producto |

### **Protegidos (Requieren autenticación + rol Admin)**

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `POST` | `/api/v1/products` | Crear producto |
| `PUT` | `/api/v1/products/{id}` | Actualizar producto |
| `DELETE` | `/api/v1/products/{id}` | Eliminar producto (soft delete) |

### **Ejemplo de uso (cURL)**

```bash
# Login
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Crear producto (con token)
curl -X POST http://localhost/api/v1/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: multipart/form-data" \
  -F "brand_id=1" \
  -F "category_id=1" \
  -F "name=Perfume Test" \
  -F "price=99.99" \
  -F "stock_available=50" \
  -F "image=@/path/to/image.jpg"
```

---

## 🏗️ Arquitectura

```
app/
├── DTOs/              # Data Transfer Objects
│   └── ProductData.php
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── V1/
│   │   │       ├── AuthController.php
│   │   │       └── CatalogController.php
│   ├── Middleware/
│   │   └── CheckAdminRole.php
│   ├── Requests/      # Form Requests (Validación)
│   │   ├── StoreProductRequest.php
│   │   └── UpdateProductRequest.php
│   └── Resources/     # API Resources (Serialización)
│       ├── BrandResource.php
│       ├── CategoryResource.php
│       └── ProductResource.php
├── Models/
│   ├── Brand.php
│   ├── Category.php
│   ├── Product.php
│   └── User.php
└── Services/          # Lógica de Negocio
    └── ProductService.php

database/
├── factories/         # Factories para testing
│   ├── BrandFactory.php
│   ├── CategoryFactory.php
│   └── ProductFactory.php
├── migrations/        # Migraciones de DB
└── seeders/           # Datos de prueba
    ├── BrandSeeder.php
    ├── CategorySeeder.php
    ├── ProductSeeder.php
    └── UserSeeder.php

tests/
├── Feature/           # Tests de integración
│   └── ProductFeatureTest.php
└── Unit/              # Tests unitarios
    └── ProductServiceTest.php
```

---

## 🗺️ Roadmap

### **Fase 1: MVP Backend** ✅ (Completado)
- [x] Modelos y migraciones
- [x] Seeders con datos
- [x] API Resources
- [x] Autenticación (Sanctum)
- [x] Endpoints de catálogo
- [x] CRUD de productos
- [x] Sistema de roles
- [x] Upload de imágenes
- [x] Service Layer + DTO
- [x] Testing automatizado

### **Fase 2: Frontend** 🚧 (En progreso)
- [ ] SPA con React/Vue
- [ ] Integración con API
- [ ] Carrito de compras
- [ ] Checkout

### **Fase 3: Features Avanzados** ⏳ (Planificado)
- [ ] Sistema de pedidos
- [ ] Pasarela de pagos
- [ ] Panel de administración
- [ ] Notificaciones por email
- [ ] Sistema de reviews
- [ ] Búsqueda avanzada

---

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add: AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### **Convenciones de commits:**
- `feat:` Nueva funcionalidad
- `fix:` Corrección de bug
- `docs:` Cambios en documentación
- `test:` Agregar/modificar tests
- `refactor:` Refactorización de código
- `style:` Formateo de código

---

## 👥 Autores

- **RamzaF** - *Desarrollo inicial* - [GitHub](https://github.com/RamzaF)

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

## 📞 Contacto

**Proyecto:** [SmellElegance Backend API](https://github.com/RamzaF/smellelegance-backend-api)

**Desarrollado con ❤️ usando Laravel**

---

## 🙏 Agradecimientos

- [Laravel](https://laravel.com) - El framework PHP para artesanos web
- [Laravel Sail](https://laravel.com/docs/sail) - Entorno de desarrollo Docker
- [Sanctum](https://laravel.com/docs/sanctum) - Sistema de autenticación
- Comunidad de Laravel

---

<p align="center">
  <strong>⭐ Si te gusta el proyecto, dale una estrella en GitHub ⭐</strong>
</p>
