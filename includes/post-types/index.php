<?php

function include_all_php_files($directory) {
    foreach (glob("{$directory}/*.php") as $file) {
        if ($file !== __FILE__) {
            require_once $file;
        }
    }
}

include_all_php_files(plugin_dir_path(__FILE__));