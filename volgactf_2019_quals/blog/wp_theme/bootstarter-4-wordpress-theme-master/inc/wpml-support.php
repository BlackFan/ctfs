<?php
// Remove Multilingual Content Setup metabox on every post type edit page
add_action('admin_head', 'disable_icl_metabox');
function disable_icl_metabox() {
    $screen = get_current_screen();
    remove_meta_box('icl_div_config',$screen->post_type,'normal');
}

// Disabling WPML’s CSS
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
