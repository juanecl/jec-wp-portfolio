# jec-portfolio

## Description

The jec-portfolio is a WordPress plugin designed to manage portfolios. It allows you to create and manage different types of content related to portfolios, such as positions, projects, profiles, and companies.

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
│       ├── portfolio.js
│       ├── position.js
│       └── profile.js
├── includes
│   ├── helpers.php
│   ├── private
│   │   ├── classes
│   │   │   ├── meta-box-renderer.php
│   │   │   └── meta-boxes
│   │   │       ├── abstract.php
│   │   │       ├── checkbox.php
│   │   │       ├── date.php
│   │   │       ├── file.php
│   │   │       ├── init.php
│   │   │       ├── interface.php
│   │   │       ├── multiselect.php
│   │   │       ├── select.php
│   │   │       ├── text.php
│   │   │       ├── textarea.php
│   │   │       └── url.php
│   │   ├── post-types
│   │   │   ├── company.php
│   │   │   ├── init.php
│   │   │   ├── position.php
│   │   │   ├── profile.php
│   │   │   └── project.php
│   │   └── templates
│   │       └── meta-boxes
│   │           ├── checkbox.php
│   │           ├── date.php
│   │           ├── file.php
│   │           ├── multiselect.php
│   │           ├── select.php
│   │           ├── text.php
│   │           ├── textarea.php
│   │           └── url.php
│   └── public
│       ├── classes
│       │   ├── init.php
│       │   └── render
│       │       ├── position
│       │       │   ├── index.php
│       │       │   └── query.php
│       │       └── profile
│       │           └── index.php
│       ├── templates
│       │   ├── position
│       │   │   ├── content-position.php
│       │   │   ├── index.php
│       │   │   └── single-position.php
│       │   ├── profile
│       │   │   ├── content-profile.php
│       │   │   └── single-profile.php
│       │   └── project
│       │       ├── index.php
│       │       └── single-project.php
│       └── widgets
│           ├── init.php
│           ├── position
│           │   └── index.php
│           └── profile
│               └── index.php
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
