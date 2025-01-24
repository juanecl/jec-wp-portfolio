<?php
require_once plugin_dir_path(__FILE__) . 'meta-boxes/index.php';
/**
 * Abstract class for managing meta boxes.
 * 
 * This class provides a common interface for rendering different types of meta boxes.
 * It includes the necessary meta box classes and provides a method to render them.
 */
abstract class AbstractMetaBoxRenderer {
    /**
     * Array of meta box instances.
     *
     * @var array
     */
    protected $meta_boxes = [];

    /**
     * Constructor.
     * 
     * Initializes the meta box instances.
     */
    public function __construct() {
        $this->meta_boxes['text'] = new TextMetaBox();
        $this->meta_boxes['textarea'] = new TextareaMetaBox();
        $this->meta_boxes['checkbox'] = new CheckboxMetaBox();
        $this->meta_boxes['date'] = new DateMetaBox();
        $this->meta_boxes['select'] = new SelectMetaBox();
        $this->meta_boxes['multiselect'] = new MultiSelectMetaBox();
        $this->meta_boxes['file'] = new FileMetaBox();
        $this->meta_boxes['url'] = new UrlMetaBox();
    }

    /**
     * Renders a meta box.
     * 
     * @param string $type The type of the meta box.
     * @param WP_Post $post The post object.
     * @param string $field The field name.
     * @param string $title The title of the meta box.
     * @param string $description The description of the meta box.
     * @param array $additional_params Additional parameters for the meta box.
     * 
     * @throws Exception If the meta box type is not found.
     */
    protected function render_meta_box($type, $post, $field, $title, $description, $additional_params = []) {
        if (isset($this->meta_boxes[$type])) {
            $this->meta_boxes[$type]->render($post, $field, $title, $description, $additional_params);
        } else {
            throw new Exception(sprintf(__('Meta box type not found: %s', 'jec-portfolio'), $type));
        }
    }
}