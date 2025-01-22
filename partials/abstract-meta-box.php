<?php

abstract class AbstractMetaBox {
    protected function render_text_meta_box($post, $field, $title, $description) {
        load_partial('text-meta-box', compact('post', 'field', 'title', 'description'));
    }
    
    protected function render_textarea_meta_box($post, $field, $title, $description) {
        load_partial('textarea-meta-box', compact('post', 'field', 'title', 'description'));
    }

    protected function render_checkbox_meta_box($post, $field, $title, $description) {
        load_partial('checkbox-meta-box', compact('post', 'field', 'title', 'description'));
    }

    protected function render_date_meta_box($post, $field, $title, $description) {
        load_partial('date-meta-box', compact('post', 'field', 'title', 'description'));
    }

    protected function render_select_meta_box($post, $field, $title, $description, $post_type, $meta_key) {
        $items = get_posts(['post_type' => $post_type, 'numberposts' => -1]);
        load_partial('select-meta-box', compact('post', 'field', 'title', 'description', 'items', 'meta_key', 'post_type'));
    }

    protected function render_multi_select_meta_box($post, $field, $title, $description, $post_type, $meta_key) {
        $items = get_posts(['post_type' => $post_type, 'numberposts' => -1]);
        load_partial('multi-select-meta-box', compact('post', 'field', 'title', 'description', 'items', 'meta_key', 'post_type'));
    }
    
    protected function render_file_meta_box($post, $field, $title, $description) {
        $value = get_post_meta($post->ID, 'wpcf-' . $field, true);
        load_partial('file-meta-box', compact('post', 'field', 'title', 'description', 'value'));
    }

    protected function render_url_meta_box($post, $field, $title, $description) {
        $value = get_post_meta($post->ID, 'wpcf-' . $field, true);
        load_partial('url-meta-box', compact('post', 'field', 'title', 'description', 'value'));
    }
}