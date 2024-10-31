<?php
// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Option name
$option_name = 'renesse_widget_plugin_position';

// For Single site
delete_option($option_name);

// For Multisite
if (is_multisite()) {
    
    $blog_ids = get_sites();
    foreach ($blog_ids as $id) {
        switch_to_blog($id->blog_id);
        delete_option($option_name);
        restore_current_blog();
    }
}