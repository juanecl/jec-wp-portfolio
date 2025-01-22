<div class="postbox">
    <div class="inside">
        <label for="tax-input-<?php echo $taxonomy; ?>"><?php echo $description; ?></label>
        <div class="tagsdiv" id="<?php echo $taxonomy; ?>_tag">
            <div class="jaxtag">
                <div class="nojs-tags hide-if-js">
                    <?php $terms = wp_get_post_terms($post->ID, $taxonomy, ['fields' => 'names']); ?>
                    <?php $terms_string = implode(',', $terms); ?>
                    <p><textarea name="tax_input[<?php echo $taxonomy; ?>]" rows="3" cols="20" class="the-tags" id="tax-input-<?php echo $taxonomy; ?>" aria-describedby="new-tag-<?php echo $taxonomy; ?>-desc"><?php echo esc_textarea($terms_string); ?></textarea></p>
                </div>
                <div class="ajaxtag hide-if-no-js">
                    <input data-wp-taxonomy="<?php echo $taxonomy; ?>" type="text" id="new-tag-<?php echo $taxonomy; ?>" name="newtag[<?php echo $taxonomy; ?>]" class="newtag form-input-tip ui-autocomplete-input" size="16" autocomplete="off" aria-describedby="new-tag-<?php echo $taxonomy; ?>-desc" value="" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-owns="ui-id-1">
                    <input type="button" class="button tagadd" value="<?php echo __('Add', 'text_domain'); ?>">
                </div>
                <p class="howto" id="new-tag-<?php echo $taxonomy; ?>-desc"><?php echo __('Separate tags with commas', 'text_domain'); ?></p>
            </div>
            <ul class="tagchecklist" role="list">
                <?php if ($terms) : ?>
                    <?php foreach ($terms as $term) : ?>
                        <li><button type="button" class="ntdelbutton"><span class="remove-tag-icon" aria-hidden="true"></span><span class="screen-reader-text"><?php echo __('Remove term:', 'text_domain') . ' ' . esc_html($term); ?></span></button>&nbsp;<?php echo esc_html($term); ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        <p class="hide-if-no-js"><button type="button" class="button-link tagcloud-link" id="link-<?php echo $taxonomy; ?>" aria-expanded="false"><?php echo __('Choose from the most used tags', 'text_domain'); ?></button></p>
    </div>
</div>