<?php
/**
 * Plugin Name: Xổ Số Vue
 * Plugin URI: https://example.com
 * Description: Plugin hiển thị kết quả xổ số bằng Vue.jsx.
 * Version: 1.0
 * Author: Harry
 * Author URI: fb.com/harry250298
 */

// Chặn truy cập trực tiếp
if (!defined('ABSPATH')) {
    exit;
}


// Định nghĩa đường dẫn plugin

require_once 'config.php';
require_once 'api/list-post.php';
require_once 'acf/post_metadata.php';
require_once 'acf/custom_type.php';
require_once 'acf/setup.php';
require_once 'cawldata/admin-menu.php';
require_once 'cawldata/cronjob.php';
require_once 'acf/setup.php';

// Thêm shortcode để hiển thị Vue app
function vue_demo_shortcode() {
    ob_start();
    ?>
    <div id="LotteryPredictionTab"></div>
    <div id="vue-app"></div>
    <?php
    return ob_get_clean();
}
add_shortcode('vue_demo', 'vue_demo_shortcode');

// Load các file Vue khi shortcode được gọi
function vue_demo_enqueue_scripts() {
    wp_enqueue_script('vue-app', XOSO_PLUGIN_DIR . 'assets/dist/vue-app.js', [], null, true);
    wp_enqueue_style('vue-style', XOSO_PLUGIN_DIR . 'assets/dist/vue-style.css');
}
add_action('wp_enqueue_scripts', 'vue_demo_enqueue_scripts');
