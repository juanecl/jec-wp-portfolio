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

## Author

**Juan Enrique Chomon Del Campo**  
[https://www.juane.cl](https://www.juane.cl)

## License

This plugin is licensed under the GPL2.