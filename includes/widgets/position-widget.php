<?php

/**
 * Class Position_Widget
 *
 * This class defines a custom widget to display position information along with associated company and projects.
 */
require_once plugin_dir_path(__FILE__) . '../classes/position-renderer.php';
class Position_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'position_widget', // Base ID
            __('Position Widget', 'jec-portfolio'), // Name
            ['description' => __('A widget to display position information with associated company and projects', 'jec-portfolio'),] // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        if (!empty($instance['position_id'])) {
            $position_id = $instance['position_id'];
            $post = get_post($position_id);
            if ($post && $post->post_type === 'position') {
                echo isset($args['before_widget']) ? $args['before_widget'] : '';
                // Pass the post object to the template
                include plugin_dir_path(__FILE__) . '../templates/position.php';
                echo isset($args['after_widget']) ? $args['after_widget'] : '';
            }
        }
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $position_id = !empty($instance['position_id']) ? $instance['position_id'] : '';
        $positions = get_posts(['post_type' => 'position', 'numberposts' => -1]);

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('position_id')); ?>"><?php _e('Select Position:', 'jec-portfolio'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('position_id')); ?>" name="<?php echo esc_attr($this->get_field_name('position_id')); ?>">
                <option value=""><?php _e('Select a position', 'jec-portfolio'); ?></option>
                <?php foreach ($positions as $position): ?>
                    <option value="<?php echo esc_attr($position->ID); ?>" <?php selected($position_id, $position->ID); ?>><?php echo esc_html($position->post_title); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['position_id'] = (!empty($new_instance['position_id'])) ? strip_tags($new_instance['position_id']) : '';
        return $instance;
    }
}

/**
 * Register Position_Widget widget.
 */
function register_position_widget() {
    register_widget('Position_Widget');
}
add_action('widgets_init', 'register_position_widget');