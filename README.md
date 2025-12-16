
 
-##  Descripción General
-Proyecto académico desarrollado como parte del curso **Desarrollo de Software 2**.  
-El objetivo es construir una **plataforma web de crowdfunding** que garantice **transparencia total en la gestión de fondos**, permitiendo que los creadores administren proyectos y los colaboradores auditen en tiempo real la ejecución financiera.
+## Descripción General
+Proyecto académico desarrollado como parte del curso **Desarrollo de Software 2**. El objetivo es construir una **plataforma web de crowdfunding** que garantice **transparencia total en la gestión de fondos**, permitiendo que los creadores administren proyectos y que los colaboradores auditen en tiempo real la ejecución financiera.
 
-El sistema se implementa bajo el **paradigma de Programación Orientada a Objetos (POO)**, aplicando el marco ágil **Scrum**, y utilizando el **framework Laravel**, que sigue la arquitectura **MVC (Modelo–Vista–Controlador)**.  
-Además, se aplican prácticas de **TDD (Test-Driven Development)**, **refactorización continua**, y principios de **modularidad, cohesión alta y bajo acoplamiento**.
+El sistema se implementa con **Laravel 11** y el paradigma **MVC**, bajo prácticas de **POO**, **Scrum** y **TDD**. Las decisiones de diseño priorizan **cohesión alta**, **acoplamiento bajo** y trazabilidad financiera de extremo a extremo.
 
 ---
 
 ## 👥 Equipo de Desarrollo
-| Nombre | 
+| Nombre |
 |---------|
 | **Nicolás Rodríguez** |
 | **Marco Herrera** |
 | **Cristian Maldonado** |
 | **Kevin Restrepo** |
 | **Kevin Libreros** |
 
+---
 
-## 🧩 Entrega 1 – Diseño e Infraestructura
-
-### 🎯 Objetivos
-- Análisis, levantamiento de requerimientos y diseño del sistema.  
-- Configuración de la infraestructura de desarrollo y repositorio.  
-- Creación del backlog y tablero de control en GitHub Projects.  
-
-### 🧱 Componentes entregados
-1. **Diagramas UML y de análisis**
-   - Diagrama de **Casos de uso**.  
-   - Diagrama de **Flujo de Datos (DFD)** niveles 0 y 1.  
-   - Diagrama **Entidad–Relación (ER)** y modelo relacional.  
-   - Diagrama de **Clases**.  
-
-2. **Configuración técnica**
-   - Repositorio GitHub: `crowfunding_2`  
-   - Framework: **Laravel (PHP 8.x)**  
-   - Arquitectura: **MVC**  
-   - Base de datos: **MariaDB**  
-   - Estrategia de ramas: `main`, `develop`, `feature/*`  
-   - Tablero Kanban: **CrowFunding desarrollo 2**  
-   - Gestión ágil con **Scrum**
+## Funcionalidad por rol
+### Roles y paneles
+- **Admin:** métricas globales, gestión de usuarios/roles, configuración de categorías y modelos de financiamiento, verificación KYC, control de proveedores, seguimiento de auditorías y reportes sospechosos, además de exportes financieros (fondos retenidos/liberados y recaudación por periodo o categoría).【F:app/Http/Controllers/AdminController.php†L23-L167】【F:routes/web.php†L16-L124】
+- **Auditor:** revisión y cambio de estado de comprobantes, solicitudes de desembolso y reportes de riesgo; consulta de hitos y proyectos para habilitar o pausar publicaciones.【F:routes/web.php†L84-L148】
+- **Creador:** dashboard con recaudación, colaboradores y avances; creación/edición de proyectos, recompensas y actualizaciones; gestión de proveedores; solicitud de desembolsos y carga de comprobantes; perfil con pasos de verificación y seguimiento de acciones pendientes.【F:app/Http/Controllers/CreatorController.php†L20-L118】【F:routes/web.php†L150-L267】
+- **Colaborador:** exploración de proyectos, aportes (incluye PayPal), calificación de campañas, consulta de recibos e historial, acceso a proveedores asociados y generación de reportes sospechosos propios.【F:routes/web.php†L269-L322】
+
+### Transparencia extendida
+- **Trazabilidad completa de aportes:** cada contribución mantiene relación con su proyecto, recompensa y comprobantes de pago, con estados verificables por rol.【F:app/Http/Controllers/CreatorController.php†L23-L112】【F:routes/web.php†L88-L114】
+- **Auditoría continua:** los auditores pueden bloquear desembolsos o marcar incidencias que se reflejan como acciones pendientes para el creador hasta su resolución.【F:routes/web.php†L84-L148】
+- **Alertas y reportes:** los reportes sospechosos generan un flujo de revisión que involucra auditor y admin para clasificar y cerrar hallazgos.【F:routes/web.php†L38-L74】【F:routes/web.php†L301-L314】
+
+### Gestión de fondos
+- **Ciclo de recaudación:** aportes registrados sobre cada proyecto con seguimiento de metas y recaudación total.【F:app/Http/Controllers/CreatorController.php†L23-L67】
+- **Desembolsos y escrow:** solicitudes de liberación con estados (pendiente, liberado, pagado, gastado) bajo control de Admin y Auditor; cálculo de fondos retenidos vs. liberados.【F:app/Http/Controllers/CreatorController.php†L45-L57】【F:app/Http/Controllers/AdminController.php†L33-L60】
+- **Comprobantes auditables:** cada pago declarado incluye comprobantes; los auditores pueden marcar observaciones o rechazos, y los creadores responden acciones pendientes.【F:app/Http/Controllers/CreatorController.php†L86-L112】【F:routes/web.php†L88-L107】
+- **Reportes de riesgo:** colaboradores y auditores elevan reportes sospechosos; el administrador centraliza su seguimiento y resolución.【F:routes/web.php†L38-L74】【F:routes/web.php†L301-L314】
+
+### Asistente virtual (n8n + Gemini)
+- **Flujo en n8n:** workflow que recibe consultas por webhook, normaliza parámetros y delega la interpretación a **Gemini** de Google para respuestas alineadas al negocio.
+- **Cobertura funcional:** dudas sobre creación de campañas, validación KYC, estados de desembolsos y observaciones de auditoría; guía a colaboradores para emitir reportes sospechosos con evidencia.
+- **Integraciones:** dispara notificaciones por correo o chat interno y devuelve deep-links hacia los paneles según el rol y la tarea solicitada.
+- **Seguridad y contexto:** el prompt restringe acciones según rol, refuerza políticas de transparencia financiera y explica la trazabilidad antes de sugerir pasos.
+
+### Datos semilla y cuentas demo
+La base de datos incluye semillas para roles y configuración inicial:
+- **Roles base:** `ADMIN`, `AUDITOR`, `CREADOR`, `COLABORADOR`.【F:database/seeders/RoleSeeder.php†L12-L22】
+- **Usuarios demo:**
+  - Admin: `admin@app.test` / `secret`
+  - Auditor: `auditor@app.test` / `secret`
+  - Creador: `creador@app.test` / `secret`
+  - Colaborador: `colaborador@app.test` / `secret`
+  Cada usuario se crea verificado y con su rol asociado.【F:database/seeders/AdminSetupSeeder.php†L12-L42】
+- **Configuración de proyectos:** semillas para categorías y modelos de financiamiento disponibles para nuevas campañas.【F:database/seeders/DatabaseSeeder.php†L11-L17】
 
 ---
 
-## Paradigma, Framework y Arquitectura
+## Arquitectura y stack técnico
+- **Framework:** Laravel 11 con Blade, Vite y Tailwind CSS.
+- **Patrón:** MVC; controladores por rol (Admin, Auditor, Creador, Colaborador) y recursos RESTful.
+- **Persistencia:** MariaDB/MySQL; uso de migraciones y seeders para estados iniciales.
+- **Autenticación y autorización:** middleware `auth`, control por roles y redirección contextual en `/dashboard`.【F:routes/web.php†L322-L374】
+- **Integraciones externas:** PayPal para pagos de colaboradores; webhooks hacia n8n para el asistente virtual.
+- **Prácticas de calidad:** TDD con PHPUnit, validaciones de formularios y políticas de acceso por rol.
 
-### Paradigma de Programación
-El proyecto está desarrollado bajo el paradigma de **Programación Orientada a Objetos (POO)**, permitiendo crear un sistema modular y escalable mediante:
-- Clases y objetos reutilizables.  
-- Encapsulamiento de datos.  
-- Herencia y polimorfismo.  
-- Bajo acoplamiento entre componentes.
+---
 
-### Framework y Arquitectura
-- **Framework:** Laravel 11  
-- **Arquitectura:** MVC (Modelo–Vista–Controlador)
+## Estructura del repositorio
+- `app/Http/Controllers`: controladores por rol y módulos de negocio.【F:app/Http/Controllers/AdminController.php†L23-L167】【F:app/Http/Controllers/CreatorController.php†L20-L118】
+- `routes/web.php`: rutas web agrupadas por rol, autenticación y dashboard genérico.【F:routes/web.php†L16-L374】
+- `database/seeders`: seeders para roles, usuarios demo, categorías y modelos de financiamiento.【F:database/seeders/RoleSeeder.php†L12-L22】【F:database/seeders/AdminSetupSeeder.php†L12-L42】
+- `resources/views`: vistas Blade para paneles y formularios.
+- `public/` y `storage/`: assets compilados y archivos subidos (comprobantes, verificaciones, etc.).
 
-**Estructura del sistema:**
-- **Modelo (M):** gestiona la lógica de negocio y conexión con la base de datos.  
-- **Vista (V):** interfaz de usuario, desarrollada con Blade Templates.  
-- **Controlador (C):** coordina la comunicación entre modelo y vista.  
+---
 
-Laravel provee un entorno seguro, modular y escalable con manejo de rutas, controladores, migraciones y Eloquent ORM.
+## Cómo ejecutar el proyecto localmente
+1. **Requisitos previos:** PHP 8.2+, Composer, Node.js 18+, npm y MariaDB/MySQL en ejecución.
+2. **Instalación de dependencias:**
+   ```bash
+   composer install
+   npm install
+   ```
+3. **Configurar entorno:**
+   ```bash
+   cp .env.example .env
+   php artisan key:generate
+   ```
+   Actualiza las credenciales de base de datos en `.env` (usuario, contraseña, host y puerto).
+4. **Migraciones y datos semilla:**
+   ```bash
+   php artisan migrate --seed
+   ```
+5. **Servir la aplicación y assets:**
+   ```bash
+   php artisan serve
+   npm run dev
+   ```
+   Accede a `http://localhost:8000` y usa las cuentas demo para explorar los paneles.
+
+### Opción con Docker
+1. **Variables de entorno:** copia `.env.example` a `.env` y define `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
+2. **Levantar servicios:**
+   ```bash
+   docker compose up -d
+   ```
+   Esto crea contenedores para aplicación, base de datos y frontend.
+3. **Migrar y sembrar datos:**
+   ```bash
+   docker compose exec app php artisan migrate --seed
+   ```
+4. **Acceso:** la app queda disponible en `http://localhost:8000`.
+
+### Scripts útiles
+- **Compilar assets en producción:** `npm run build`
+- **Linter Tailwind/Prettier (si aplica):** `npm run lint`
+- **Limpiar cachés de Laravel:** `php artisan optimize:clear`
 
+---
 
-##  Base de Datos
+## Testing y calidad
+- **Pruebas unitarias/feature:**
+  ```bash
+  php artisan test
+  ```
+- **Recomendaciones:** ejecutar pruebas antes de crear ramas de entrega; revisar logs en `storage/logs/laravel.log` para diagnosticar fallos de permisos o consultas.
 
-- **Gestor:** MariaDB  
-- **Modelo:** relacional  
-- **Características principales:**
-  - Tablas normalizadas (3FN).  
-  - Relaciones entre usuarios, proyectos, recompensas y transacciones.  
-  - Integridad referencial mediante claves foráneas.  
-  - Seguridad en transacciones de fondos.
+---
 
+## Despliegue activo y demo
+- **Producción:** `https://crowdfunding.eliteacademyfx.com/`
+- **Acceso sugerido:** iniciar sesión con las cuentas demo para validar flujos de paneles, trazabilidad de aportes y revisión de comprobantes.
 
+---
 
-📎 **Referencias:**
-- [ Diagrama de flujo de datos (Google Drive)](https://drive.google.com/file/d/1E74EZBCtWtK_KlUEYS71jvZyvDmcCK-Y/view?usp=drive_link)
-- [ Modelo Entidad-Relación y físico (diagrams.net)](https://app.diagrams.net/?libs=general;er#G1U6v0B8HN7QTI8B-7y3L-S5haXQ99Tv2m#%7B%22pageId%22%3A%22XPA24Rqfg-Av8ghFqx-V%22%7D)
+## Roadmap sugerido
+- Completar cobertura de pruebas para flujos de aportes y PayPal.
+- Añadir monitoreo de integridad de comprobantes y logs de auditoría más granular.
+- Publicar especificación de API y webhooks del asistente virtual (n8n + Gemini).
+- Incorporar CI para linting y pruebas automáticas en cada commit.
 
 ---
 
 ## Diagramas de Diseño
-
-Los diagramas elaborados para esta fase incluyen:
-
 | Tipo | Enlace |
 |------|--------------------|
 | Diagrama de flujo de datos (DFD) niveles 0 y 1 | [Ver en Google Drive](https://drive.google.com/file/d/1ZOymZqTG-Ta6wRwX3JkgrnLvZstWIeWG/view?usp=drive_link) |
 | Modelo Entidad-Relación (ER) | [Ver en diagrams.net](https://app.diagrams.net/?libs=general;er#G1U6v0B8HN7QTI8B-7y3L-S5haXQ99Tv2m#%7B%22pageId%22%3A%22XPA24Rqfg-Av8ghFqx-V%22%7D) |
-
-


