# 🐾 Sistema de Registro de Mascotas

Proyecto web desarrollado con **Laravel**, **Vue.js**, **Vue Router**, **Bootstrap** y **MySQL**, enfocado en la gestión básica de mascotas dentro de un entorno veterinario.

El sistema permite registrar mascotas junto con los datos de su dueño, consultar mascotas mediante el **RUT del propietario**, buscar registros existentes y registrar atenciones veterinarias con información clínica detallada.

## 🚀 Funcionalidades principales

- Registro de mascotas
- Registro de datos del dueño
- Formateo automático y validación de RUT chileno
- Búsqueda de mascotas por RUT del dueño
- Consulta de mascotas registradas
- Registro de atenciones veterinarias
- Selección de mascota para atención
- Ingreso de fecha de atención automática
- Registro de:
  - Motivo de consulta
  - Síntomas
  - Diagnóstico
  - Tratamiento
  - Observaciones
  - Estado de atención (checkbox)
- Navegación por múltiples vistas usando Vue Router

## 🛠️ Tecnologías utilizadas

- **Laravel** (Backend / API REST)
- **Vue.js** (Frontend)
- **Vue Router** (Navegación SPA)
- **Bootstrap 5** (Estilos)
- **MySQL** (Base de datos)
- **Vite** (Compilación de frontend)
- **DBeaver** (Administración de base de datos)

## 📂 Estructura general del sistema

El proyecto está organizado en diferentes vistas para facilitar la navegación:

- **Registro Mascota** → Permite ingresar los datos de la mascota y su dueño.
- **Buscar Mascota** → Permite buscar registros existentes.
- **Registrar Atención** → Permite registrar información clínica de una mascota.
- **Mascotas por RUT** → Permite listar todas las mascotas asociadas a un RUT.

## 🎯 Objetivo del proyecto

El objetivo de este sistema es centralizar el registro de mascotas y sus atenciones veterinarias, facilitando el control de información clínica y la búsqueda de datos mediante el RUT del dueño, aplicando buenas prácticas de desarrollo con Laravel y Vue.

## 📌 Estado del proyecto

Proyecto en desarrollo / académico, enfocado en reforzar conocimientos de:

- Componentes en Vue
- Rutas con Vue Router
- Consumo de APIs con `fetch`
- Validaciones en frontend
- Integración entre Laravel y Vue
- Manejo de base de datos relacional

## ▶️ Ejecución del proyecto

1. Clonar repositorio
2. Instalar dependencias de PHP:
   ```bash
   composer install