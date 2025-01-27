# Portfolio Plugin

## Description

The Portfolio Plugin is a WordPress plugin designed to manage portfolios. It allows you to create and manage different types of content related to portfolios, such as positions, projects, profiles, and companies.

## Installation

1. Download the plugin and unzip it into your WordPress plugins directory (`/wp-content/plugins/`).
2. Activate the plugin from the WordPress plugins menu.

## Usage

The plugin registers several custom post types and taxonomies to manage the portfolio. You can access these post types from the WordPress admin panel.

## Functions

- **portfolio_plugin_activate**: Function that runs during the plugin activation.
- **portfolio_plugin_deactivate**: Function that runs during the plugin deactivation.

## Plugin Structure
```
├── README.md
├── assets
│   ├── css
│   │   └── profile.css
│   └── js
│       ├── admin.js
│       ├── position.js
│       └── profile.js
├── includes
│   ├── classes
│   │   ├── meta-box-renderer.php
│   │   ├── meta-boxes
│   │   │   ├── abstract.php
│   │   │   ├── checkbox.php
│   │   │   ├── date.php
│   │   │   ├── file.php
│   │   │   ├── index.php
│   │   │   ├── interface.php
│   │   │   ├── multiselect.php
│   │   │   ├── select.php
│   │   │   ├── text.php
│   │   │   ├── textarea.php
│   │   │   └── url.php
│   │   ├── position-query.php
│   │   ├── position-renderer.php
│   │   └── profile-renderer.php
│   ├── helpers.php
│   ├── partials
│   │   └── meta-boxes
│   │       ├── checkbox.php
│   │       ├── date.php
│   │       ├── file.php
│   │       ├── multiselect.php
│   │       ├── select.php
│   │       ├── text.php
│   │       ├── textarea.php
│   │       └── url.php
│   ├── post-types
│   │   ├── company.php
│   │   ├── index.php
│   │   ├── position.php
│   │   ├── profile.php
│   │   └── project.php
│   ├── templates
│   │   ├── position.php
│   │   ├── positions-loop.php
│   │   ├── positions.php
│   │   ├── profile.php
│   │   └── projects.php
│   └── widgets
│       ├── position-display.php
│       ├── position-widget.php
│       └── profile-widget.php
├── index.php
└── languages
    ├── jec-portfolio-en_US.mo
    ├── jec-portfolio-en_US.po
    ├── jec-portfolio-es_ES.mo
    └── jec-portfolio-es_ES.po
```

## Author

**Juan Enrique Chomon Del Campo**  
[https://www.juane.cl](https://www.juane.cl)

## License

This plugin is licensed under the GPL2.
