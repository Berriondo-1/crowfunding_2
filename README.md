# Plataforma de Crowdfunding con Transparencia

## Descripción General
Proyecto académico desarrollado como parte del curso **Desarrollo de Software 2**. El objetivo es construir una **plataforma web de crowdfunding** que garantice **transparencia total en la gestión de fondos**, permitiendo que los creadores administren proyectos y que los colaboradores auditen en tiempo real la ejecución financiera.

El sistema se implementa bajo el **paradigma de Programación Orientada a Objetos (POO)**, aplicando el marco ágil **Scrum** y utilizando el **framework Laravel** (arquitectura **MVC**). También se practica **TDD**, refactorización continua y principios de **modularidad, cohesión alta y bajo acoplamiento**.

---

## 👥 Equipo de Desarrollo
| Nombre |
|---------|
| **Nicolás Rodríguez** |
| **Marco Herrera** |
| **Cristian Maldonado** |
| **Kevin Restrepo** |
| **Kevin Libreros** |

---

## Funcionalidad actual
### Roles y paneles
- **Admin:** tablero con métricas globales (usuarios verificados, estados de proyectos y finanzas), gestión de roles y usuarios, categorías y modelos de financiamiento, verificación KYC, control de proveedores, seguimiento de auditorías, reportes sospechosos y exportes financieros (fondos retenidos/liberados y recaudación por periodo o categoría).【F:app/Http/Controllers/AdminController.php†L23-L167】【F:routes/web.php†L16-L124】
- **Auditor:** revisión y cambio de estado de comprobantes de pago, solicitudes de desembolso y reportes de riesgo; consulta de hitos y proyectos para habilitar o pausar publicaciones.【F:routes/web.php†L84-L148】
- **Creador:** dashboard con recaudación, colaboradores y avances de meta; creación/edición de proyectos, recompensas y actualizaciones; gestión de proveedores; solicitud de desembolsos y carga de comprobantes; perfil con pasos de verificación y seguimiento de acciones pendientes.【F:app/Http/Controllers/CreatorController.php†L20-L118】【F:routes/web.php†L150-L267】
- **Colaborador:** exploración de proyectos, aportes (incluye flujo PayPal), calificación de campañas, consulta de recibos e historial, acceso a proveedores asociados y generación de reportes sospechosos propios.【F:routes/web.php†L269-L322】

### Gestión de fondos y transparencia
- **Ciclo de recaudación:** aportes registrados sobre cada proyecto con seguimiento de metas y recaudación total.【F:app/Http/Controllers/CreatorController.php†L23-L67】
- **Desembolsos y escrow:** los creadores solicitan liberaciones de fondos; los administradores y auditores supervisan estados (pendiente, liberado, pagado o gastado) y mantienen el cálculo de fondos retenidos vs. liberados.【F:app/Http/Controllers/CreatorController.php†L45-L57】【F:app/Http/Controllers/AdminController.php†L33-L60】
- **Comprobantes y auditoría:** cada pago declarado incluye comprobantes auditables; los auditores pueden marcar observaciones o rechazos, y los creadores mantienen acciones pendientes para responder observaciones.【F:app/Http/Controllers/CreatorController.php†L86-L112】【F:routes/web.php†L88-L107】
- **Reportes de riesgo:** colaboradores y auditores pueden generar reportes sospechosos para proyectos; el administrador centraliza su seguimiento y resolución.【F:routes/web.php†L38-L74】【F:routes/web.php†L301-L314】

### Datos semilla y cuentas demo
La base de datos incluye semillas para roles y configuración inicial:
- **Roles base:** `ADMIN`, `AUDITOR`, `CREADOR`, `COLABORADOR`.【F:database/seeders/RoleSeeder.php†L12-L22】
- **Usuarios demo:**
  - Admin: `admin@app.test` / `secret`
  - Auditor: `auditor@app.test` / `secret`
  - Creador: `creador@app.test` / `secret`
  - Colaborador: `colaborador@app.test` / `secret`
  Cada usuario se crea verificado y con su rol asociado.【F:database/seeders/AdminSetupSeeder.php†L12-L42】
- **Configuración de proyectos:** semillas para categorías y modelos de financiamiento disponibles para nuevas campañas.【F:database/seeders/DatabaseSeeder.php†L11-L17】

---

## Arquitectura y paradigma
- **Framework:** Laravel 11
- **Arquitectura:** MVC (Modelo–Vista–Controlador)
- **Paradigma:** Programación Orientada a Objetos
- **Bases de datos:** MariaDB con tablas normalizadas, integridad referencial y trazabilidad de aportes, recompensas, desembolsos y auditoría.

---

## Cómo ejecutar el proyecto localmente
1. **Requisitos previos:** PHP 8.2+, Composer, Node.js 18+, npm y MariaDB/MySQL en ejecución.
2. **Instalación de dependencias:**
   ```bash
   composer install
   npm install
   ```
3. **Configurar entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Actualiza las credenciales de base de datos en `.env`.
4. **Migraciones y datos semilla:**
   ```bash
   php artisan migrate --seed
   ```
5. **Servir la aplicación y assets:**
   ```bash
   php artisan serve
   npm run dev
   ```
   Accede a `http://localhost:8000` y usa las cuentas demo para explorar los paneles.

---

## Diagramas de Diseño
| Tipo | Enlace |
|------|--------------------|
| Diagrama de flujo de datos (DFD) niveles 0 y 1 | [Ver en Google Drive](https://drive.google.com/file/d/1ZOymZqTG-Ta6wRwX3JkgrnLvZstWIeWG/view?usp=drive_link) |
| Modelo Entidad-Relación (ER) | [Ver en diagrams.net](https://app.diagrams.net/?libs=general;er#G1U6v0B8HN7QTI8B-7y3L-S5haXQ99Tv2m#%7B%22pageId%22%3A%22XPA24Rqfg-Av8ghFqx-V%22%7D) |
