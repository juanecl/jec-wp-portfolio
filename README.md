# Portfolio Plugin

## Descripción

El Portfolio Plugin es un plugin de WordPress diseñado para gestionar el portafolio. Permite crear y administrar diferentes tipos de contenido relacionados con el portafolio, como posiciones, proyectos, perfiles y empresas.

## Instalación

1. Descarga el plugin y descomprímelo en tu directorio de plugins de WordPress (`/wp-content/plugins/`).
2. Activa el plugin desde el menú de plugins de WordPress.

## Uso

El plugin registra varios tipos de contenido personalizados y taxonomías para gestionar el portafolio. Puedes acceder a estos tipos de contenido desde el panel de administración de WordPress.

## Funciones

- **portfolio_plugin_activate**: Función que se ejecuta durante la activación del plugin.
- **portfolio_plugin_deactivate**: Función que se ejecuta durante la desactivación del plugin.

## Estructura del Plugin

├── assets
│   └── js
│       └── admin.js
├── includes
│   ├── helpers.php
│   ├── front
│   │   ├── profile-display.php
│   │   └── profile-widget.php
│   ├── partials
│   │   ├── abstract-meta-box.php
│   │   ├── checkbox-meta-box.php
│   │   ├── date-meta-box.php
│   │   ├── file-meta-box.php
│   │   ├── multi-select-meta-box.php
│   │   ├── select-meta-box.php
│   │   ├── taxonomy-meta-box.php
│   │   ├── text-meta-box.php
│   │   ├── textarea-meta-box.php
│   │   └── url-meta-box.php
│   └── post_types
│       ├── company.php
│       ├── position.php
│       ├── profile.php
│       └── project.php
└── index.php


## Autor

**Juan Enrique Chomon Del Campo**  
[https://www.juane.cl](https://www.juane.cl)

## Licencia

Este plugin está licenciado bajo la GPL2.