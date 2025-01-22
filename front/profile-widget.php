<?php

class Profile_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'profile_widget', // Base ID
            __('Profile Widget', 'text_domain'), // Name
            ['description' => __('A widget to display profile information', 'text_domain'),] // Args
        );
    }

    public function widget($args, $instance) {
        if (!empty($instance['profile_id'])) {
            $post_id = $instance['profile_id'];
            $post = get_post($post_id);
            if ($post && $post->post_type === 'profile') {
                echo $args['before_widget'];
                include get_template_directory() . '/portfolio/front/profile-display.php';
                echo $args['after_widget'];
            }
        }
    }

    public function form($instance) {
        $profile_id = !empty($instance['profile_id']) ? $instance['profile_id'] : '';
        $profiles = get_posts(['post_type' => 'profile', 'numberposts' => -1]);

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('profile_id')); ?>"><?php _e('Select Profile:', 'text_domain'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('profile_id')); ?>" name="<?php echo esc_attr($this->get_field_name('profile_id')); ?>">
                <option value=""><?php _e('Select a profile', 'text_domain'); ?></option>
                <?php foreach ($profiles as $profile): ?>
                    <option value="<?php echo esc_attr($profile->ID); ?>" <?php selected($profile_id, $profile->ID); ?>><?php echo esc_html($profile->post_title); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['profile_id'] = (!empty($new_instance['profile_id'])) ? strip_tags($new_instance['profile_id']) : '';
        return $instance;
    }
}

function register_profile_widget() {
    register_widget('Profile_Widget');
}
add_action('widgets_init', 'register_profile_widget');