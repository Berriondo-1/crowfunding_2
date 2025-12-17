# Plataforma de Crowdfunding Transparente

## 📌 Descripción General

Proyecto académico desarrollado como parte del curso **Desarrollo de Software 2**.

El objetivo es construir una **plataforma web de crowdfunding** que garantice **transparencia total en la gestión de fondos**, permitiendo que los creadores administren proyectos y que los colaboradores y auditores puedan **verificar en tiempo real** la ejecución financiera.

El sistema está desarrollado con **Laravel 11**, siguiendo la arquitectura **MVC**, bajo el paradigma de **Programación Orientada a Objetos (POO)** y el marco ágil **Scrum**. Se aplican prácticas de **TDD**, **refactorización continua**, y principios de **alta cohesión** y **bajo acoplamiento**, priorizando la trazabilidad financiera de extremo a extremo.

---

## 👥 Equipo de Desarrollo

| Nombre                 |
| ---------------------- |
| **Nicolás Rodríguez**  |
| **Marco Herrera**      |
| **Cristian Maldonado** |
| **Kevin Restrepo**     |
| **Kevin Libreros**     |

---

## 🧩 Funcionalidad por Rol

### 🔐 Roles y Paneles

* **Admin**
  Métricas globales, gestión de usuarios y roles, configuración de categorías y modelos de financiamiento, verificación KYC, control de proveedores, seguimiento de auditorías y reportes sospechosos, además de exportes financieros por periodo o categoría.

* **Auditor**
  Revisión y cambio de estado de comprobantes, solicitudes de desembolso y reportes de riesgo. Puede habilitar o pausar proyectos y validar hitos financieros.

* **Creador**
  Dashboard con recaudación, colaboradores y avances. Permite crear y editar proyectos, recompensas y actualizaciones, gestionar proveedores, solicitar desembolsos y cargar comprobantes auditables.

* **Colaborador**
  Exploración de proyectos, aportes (incluye PayPal), consulta de recibos e historial, calificación de campañas y generación de reportes sospechosos.

---

## 🔎 Transparencia y Control

* **Trazabilidad completa de aportes**: cada contribución está vinculada a su proyecto, recompensa y comprobante.
* **Auditoría continua**: los auditores pueden bloquear desembolsos y generar observaciones visibles para el creador.
* **Alertas y reportes**: flujo de revisión de reportes sospechosos entre auditor y administrador.

---

## 💰 Gestión de Fondos

* **Ciclo de recaudación**: seguimiento de metas y recaudación total por proyecto.
* **Escrow y desembolsos**: estados de fondos (*pendiente, liberado, pagado, gastado*).
* **Comprobantes auditables**: cada gasto debe contar con evidencia verificable.
* **Reportes de riesgo**: escalamiento y resolución centralizada.

---

## 🤖 Asistente Virtual (n8n + Gemini)

* **Workflow en n8n** con webhook y procesamiento en **Gemini (Google)**.
* Resuelve dudas sobre campañas, KYC, estados de desembolsos y auditorías.
* Genera enlaces directos a paneles según rol.
* Refuerza políticas de seguridad y transparencia financiera.

---

## 🗂️ Datos Semilla y Cuentas Demo

La base de datos incluye información inicial para pruebas:

### Roles

* `ADMIN`
* `AUDITOR`
* `CREADOR`
* `COLABORADOR`

### Usuarios Demo

| Rol         | Email                                               | Password |
| ----------- | --------------------------------------------------- | -------- |
| Admin       | [admin@app.test](mailto:admin@app.test)             | secret   |
| Auditor     | [auditor@app.test](mailto:auditor@app.test)         | secret   |
| Creador     | [creador@app.test](mailto:creador@app.test)         | secret   |
| Colaborador | [colaborador@app.test](mailto:colaborador@app.test) | secret   |

---

## 🏗️ Arquitectura y Stack Técnico

* **Framework:** Laravel 11
* **Frontend:** Blade + Vite + Tailwind CSS
* **Arquitectura:** MVC
* **Base de datos:** MariaDB / MySQL
* **Autenticación:** middleware `auth` y control por roles
* **Pagos:** PayPal
* **Automatización:** Webhooks hacia n8n
* **Calidad:** PHPUnit, validaciones y políticas por rol

---

## 📁 Estructura del Repositorio

```
app/Http/Controllers   # Controladores por rol
routes/web.php         # Rutas agrupadas por rol
database/seeders       # Datos iniciales
resources/views        # Vistas Blade
public/ & storage/     # Assets y archivos subidos
```

---

## ▶️ Ejecución Local

### Requisitos

* PHP 8.2+
* Composer
* Node.js 18+
* MariaDB / MySQL

### Instalación

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

Accede a: **[http://localhost:8000](http://localhost:8000)**

---

## 🐳 Opción con Docker

```bash
docker compose up -d
docker compose exec app php artisan migrate --seed
```

---

## 🧪 Testing y Calidad

```bash
php artisan test
```

Revisar logs en:

```
storage/logs/laravel.log
```

---

## 🚀 Despliegue Activo

* **Producción:** [https://crowdfunding.eliteacademyfx.com/](https://crowdfunding.eliteacademyfx.com/)
* Se recomienda acceder con cuentas demo para validar los flujos completos.

---

## 🛣️ Roadmap

* Aumentar cobertura de pruebas en pagos y PayPal
* Mejorar logs y auditoría granular
* Documentar API y webhooks del asistente virtual
* Integrar CI/CD para pruebas automáticas

---

## 📊 Diagramas de Diseño

| Tipo                | Enlace                                                                              |
| ------------------- | ----------------------------------------------------------------------------------- |
| DFD (niveles 0 y 1) | [Ver](https://drive.google.com/file/d/1ZOymZqTG-Ta6wRwX3JkgrnLvZstWIeWG/view)       |
| Modelo ER           | [Ver](https://app.diagrams.net/?libs=general;er#G1U6v0B8HN7QTI8B-7y3L-S5haXQ99Tv2m) |

