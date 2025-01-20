<?php

// Enqueue frontend styles
function rewardpoint_enqueue_styles() {
    wp_enqueue_style('rewardpoint-main-style', plugin_dir_url(__FILE__) . 'css/main-css.css');
}
add_action('wp_enqueue_scripts', 'rewardpoint_enqueue_styles');

// Enqueue admin styles and scripts
function rewardpoint_enqueue_admin_styles_and_scripts() {
    // Enqueue admin styles
    wp_enqueue_style('rewardpoint-admin-style', plugin_dir_url(__FILE__) . 'css/custom-modal.css');

    // Enqueue Chart.js and Luxon for admin reports
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', [], null, true);
    wp_enqueue_script('luxon', 'https://cdn.jsdelivr.net/npm/luxon@3.0.1/build/global/luxon.min.js', [], null, true);
    wp_enqueue_script('chartjs-adapter-luxon', 'https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon', [], null, true);
}
add_action('admin_enqueue_scripts', 'rewardpoint_enqueue_admin_styles_and_scripts');

// Enqueue point adjustment script for admin
function rewardpoint_enqueue_point_adjust_script() {
    if (isset($_GET['page']) && $_GET['page'] === 'points-rewards' && isset($_GET['tab']) && $_GET['tab'] === 'point-settings') {
        wp_enqueue_script(
            'rewardpoint-point-adjust',
            plugin_dir_url(__FILE__) . 'js/admin-point-adjust.js',
            ['jquery'],
            null,
            true
        );

        wp_localize_script('rewardpoint-point-adjust', 'adminPointAdjustData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'verificationCode' => esc_js(get_option('verification_code', '')),
        ]);
    }
}
add_action('admin_enqueue_scripts', 'rewardpoint_enqueue_point_adjust_script');

// Enqueue frontend scripts with localized variables
function rewardpoint_enqueue_scripts() {
    wp_enqueue_script(
        'rewardpoint-custom-script',
        plugin_dir_url(__FILE__) . 'js/custom-script.js',
        ['jquery'],
        '1.0',
        true
    );

    // Localize script with variables
    wp_localize_script('rewardpoint-custom-script', 'custom_script_params', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'redemption_rate' => get_option('point_conversation_rate_taka', ''),
        'nonce' => wp_create_nonce('apply_points_redemption_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'rewardpoint_enqueue_scripts');

// Enqueue Chosen for admin
function rewardpoint_enqueue_chosen() {
    wp_enqueue_style('chosen-css', 'https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css');
    wp_enqueue_script('chosen-js', 'https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js', ['jquery'], '1.8.7', true);

    // Enqueue custom script to initialize Chosen
    wp_enqueue_script('custom-chosen-select', plugin_dir_url(__FILE__) . 'js/custom-chosen-select.js', ['jquery', 'chosen-js'], null, true);

    wp_localize_script('custom-chosen-select', 'ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('fetch_categories_nonce'),
    ]);
}
add_action('admin_enqueue_scripts', 'rewardpoint_enqueue_chosen');
