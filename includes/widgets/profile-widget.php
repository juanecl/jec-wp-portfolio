<?php

/**
 * Class Profile_Widget
 *
 * This class defines a custom widget to display profile information.
 */
class Profile_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'profile_widget', // Base ID
            __('Profile Widget', 'jec-portfolio'), // Name
            ['description' => __('A widget to display profile information', 'jec-portfolio'),] // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        if (!empty($instance['profile_id'])) {
            $post_id = $instance['profile_id'];
            $post = get_post($post_id);
            if ($post && $post->post_type === 'profile') {
                echo $args['before_widget'];
                include plugin_dir_path(__FILE__) . '../templates/profile.php';
                echo $args['after_widget'];
            }
        }
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $profile_id = !empty($instance['profile_id']) ? $instance['profile_id'] : '';
        $profiles = get_posts(['post_type' => 'profile', 'numberposts' => -1]);

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('profile_id')); ?>"><?php _e('Select Profile:', 'jec-portfolio'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('profile_id')); ?>" name="<?php echo esc_attr($this->get_field_name('profile_id')); ?>">
                <option value=""><?php _e('Select a profile', 'jec-portfolio'); ?></option>
                <?php foreach ($profiles as $profile): ?>
                    <option value="<?php echo esc_attr($profile->ID); ?>" <?php selected($profile_id, $profile->ID); ?>><?php echo esc_html($profile->post_title); ?></option>
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
        $instance['profile_id'] = (!empty($new_instance['profile_id'])) ? strip_tags($new_instance['profile_id']) : '';
        return $instance;
    }
}

/**
 * Register Profile_Widget widget.
 */
function register_profile_widget() {
    register_widget('Profile_Widget');
}
add_action('widgets_init', 'register_profile_widget');