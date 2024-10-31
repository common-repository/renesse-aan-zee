<?php
/*
Plugin Name: Renesse Aan Zee
Plugin URI: http://renesseaanzee.nl
Description: A widget plugin with events and activities.
Version: 1.0.1
Author: Web & App Easy B.V.
Author URI: http://webandappeasy.com
License: GPL2
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit; 
}

// Enqueue CSS and JS files
function renesse_widget_plugin_enqueue_scripts() {

    // jQuery
    wp_enqueue_script('jquery');

    // Font awesome
    wp_enqueue_style('fontawesome-css', plugins_url('/inc/fontawesome/all.min.css', __FILE__), array(), '6.5.2');

    // Bootstrap
    wp_enqueue_style('bootstrap-css', plugins_url('/inc/bootstrap/bootstrap.min.css', __FILE__), array(), '5.3.3');
    wp_enqueue_script('bootstrap-script', plugins_url('/inc/bootstrap/bootstrap.bundle.min.js', __FILE__), array('jquery'), '5.3.3', true);

    // Plugin styling
    wp_enqueue_style('renesse-aan-zee-style', plugins_url('/css/style.min.css', __FILE__), array(), get_plugin_data(__FILE__)['Version']);
    wp_enqueue_script('renesse-aan-zee-script', plugins_url('/js/script.js', __FILE__), array('jquery'), get_plugin_data(__FILE__)['Version'], true);
}
add_action('wp_enqueue_scripts', 'renesse_widget_plugin_enqueue_scripts');

// Fetch API data
function renesse_fetch_api_data($url) {
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return [];
    }
    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}

// Add the widget to the footer
function renesse_widget_plugin_add_widget() {

    // Get events
    $events = renesse_fetch_api_data('https://www.renesseaanzee.nl/wp-json/app/v2/events?key=83b6ea44-9765-46b9-b391-8fd55ba49015&inside=1');
    $events = array_slice($events, 0, 3);

    // Get activities
    $activities = renesse_fetch_api_data('https://www.renesseaanzee.nl/wp-json/app/v1/activities?key=83b6ea44-9765-46b9-b391-8fd55ba49015&inside=1');
    $activities = array_slice($activities, 0, 3);

    // Colors array for cycling through colors
    $colors = ['renesse-yellow', 'renesse-green', 'renesse-blue'];

    // Decide widget position default to left
    $position = get_option('renesse_widget_plugin_position', 'left'); 
    $position_class = $position === 'right' ? 'widget-right' : 'widget-left';

    // Echo widget content
    echo '<div id="widget-container" class="' . esc_attr($position_class) . '">
            <div id="widget-content">
                <p class="widget-text">Welkom in Renesse Aan Zee, bekijk de actuele evenementen en activiteiten.</p>
                <div id="events-content" class="widget-hidden">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-10">
                                <h1 class="m-2 pb-0">Evenementen</h1>
                            </div>
                            <div class="col-2">
                                <i class="close-events fa-solid fa-xmark fa-xl"></i>
                            </div>
                        </div>
                    </div> 
                    <div id="events-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">';
    
                        // Loop through events
                        foreach ($events as $index => $event) {
                            $active_class = $index === 0 ? ' active' : '';
                            $color_class = $colors[$index % count($colors)];
                            $event_content = wp_trim_words(wp_strip_all_tags($event['content']), 12, '...');
                            echo '<div class="carousel-item' . esc_html($active_class) . '">
                                    <div class="card" style="">
                                        <div class="date-overlay">
                                            <span class="date-text"><h3 class="text-white pb-0">' . esc_attr(date_i18n('M', strtotime($event['date']))) . '</h3>' . esc_attr(date_i18n('d', strtotime($event['date']))) . '</span>
                                        </div>
                                        <img src="' . esc_url($event['thumbnail']) . '" class="card-img-top img-fluid" alt="' . esc_attr($event['title']) . '">
                                        <div class="card-body ' . esc_attr($color_class) . '">
                                            <h2 class="card-title text-white mt-1 mb-0">' . esc_html($event['title']) . '</h2>
                                            <p class="card-text text-white mb-2">' . esc_html($event_content) . '</p>
                                            <a target="_blank" href="' . esc_url($event['url']) . '" class="btn btn-primary widget-button">Bekijk activiteit</a>
                                        </div>
                                    </div>
                                </div>';
                        }

                        echo '</div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#events-carousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#events-carousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                  </div>
                </div>
                <div id="activities-content" class="widget-hidden">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-10">
                                <h1 class="m-2 pb-0">Activiteiten</h1>
                            </div>
                            <div class="col-2 mt-2">
                                <i class="close-activities fa-solid fa-xmark fa-xl"></i>
                            </div>
                        </div>
                    </div> 
                    <div id="activities-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">';
    
                            // Loop through activities
                            foreach ($activities as $index => $activity) {
                                $active_class = $index === 0 ? ' active' : '';
                                $color_class = $colors[$index % count($colors)];
                                $activity_content = wp_trim_words(wp_strip_all_tags($activity['content']), 12, '...');
                                echo '<div class="carousel-item' . esc_html($active_class) . '">
                                        <div class="card" style="">
                                            <img src="' . esc_url($activity['thumbnail']) . '" class="card-img-top" alt="' . esc_attr($activity['title']) . '">
                                            <div class="card-body ' . esc_attr($color_class) . '">
                                                <h3 class="card-title text-white mt-1 mb-0">' . esc_html($activity['title']) . '</h3>
                                                <p class="card-text text-white mb-2">' . esc_html($activity_content) . '</p>
                                                <a target="_blank" href="' . esc_url($activity['url']) . '" class="btn btn-primary widget-button">Bekijk activiteit</a>
                                            </div>
                                        </div>
                                    </div>';
                            }

                            echo '</div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#activities-carousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#activities-carousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <div id="widget-buttons">
                <button id="show-events-button" class="widget-button">' . esc_attr(strtoupper(__('Evenementen', 'renesse-aan-zee'))) . '</button>
                <button id="show-activities-button" class="widget-button">' . esc_attr(strtoupper(__('Activiteiten', 'renesse-aan-zee'))) . '</button>
                <a target="_blank" href="https://renesseaanzee.nl/"><button class="widget-button">' . esc_attr(strtoupper(__('Renesse Aan Zee', 'renesse-aan-zee'))) . '</button></a>
            </div>
            <button id="renesse-button" class="inactive"></button>
        </div>';
}
add_action('wp_footer', 'renesse_widget_plugin_add_widget');

// Add settings page
function renesse_widget_plugin_menu() {
    add_options_page('Renesse Aan Zee widget instellingen', 'Renesse widget', 'manage_options', 'renesse-widget-plugin', 'renesse_widget_plugin_settings_page');
}
add_action('admin_menu', 'renesse_widget_plugin_menu');

// Register settings
function renesse_widget_plugin_settings() {
    register_setting('renesse-widget-plugin-settings-group', 'renesse_widget_plugin_position');
}
add_action('admin_init', 'renesse_widget_plugin_settings');

// Settings page content
function renesse_widget_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Renesse Aan Zee widget instellingen', 'renesse-aan-zee')?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('renesse-widget-plugin-settings-group'); ?>
            <?php do_settings_sections('renesse-widget-plugin-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Widget positie', 'renesse-aan-zee')?></th>
                    <td>
                        <select name="renesse_widget_plugin_position">
                            <option value="left" <?php echo esc_attr(get_option('renesse_widget_plugin_position', 'left')) == 'left' ? 'selected' : ''; ?>><?php esc_html_e('Links', 'renesse-aan-zee')?></option>
                            <option value="right" <?php echo esc_attr(get_option('renesse_widget_plugin_position', 'left')) == 'right' ? 'selected' : ''; ?>><?php esc_html_e('Rechts', 'renesse-aan-zee')?></option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php esc_attr(submit_button()); ?>
        </form>
    </div>
    <?php
}
?>