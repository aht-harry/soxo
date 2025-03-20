<?php
class XoSoPostType {
    private static $instance = null;
    private $post_type = POST_TYPE;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('init', [$this, 'register_post_type']);
        add_action('save_post', [$this, 'save_post_metadata']);
    }

    public function register_post_type() {
        $args = [
            'labels' => [
                'name'          => 'Xổ Số',
                'singular_name' => 'Xổ Số',
                'menu_name'     => 'Xổ Số',
                'add_new'       => 'Thêm KQXS',
                'add_new_item'  => 'Thêm kết quả mới',
                'edit_item'     => 'Chỉnh sửa kết quả',
                'view_item'     => 'Xem kết quả',
                'all_items'     => 'Tất cả kết quả',
            ],
            'public'             => true,
            'has_archive'        => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_position'      => 5,
            'supports'           => ['title', 'editor', 'thumbnail'],
            'menu_icon'          => 'dashicons-tickets-alt',
        ];
        register_post_type($this->post_type, $args);
    }


    function save_post_metadata($post_id) {
        // Kiểm tra quyền và autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        
        $tinh_thanh = get_option('province_config', []);
    
        $result = [
            "mien_bac"  => array_keys(!empty($tinh_thanh["mien_bac"]) && is_array($tinh_thanh["mien_bac"]) ? $tinh_thanh["mien_bac"] : []),
            "mien_trung" => array_keys(!empty($tinh_thanh["mien_trung"]) && is_array($tinh_thanh["mien_trung"]) ? $tinh_thanh["mien_trung"] : []),
            "mien_nam"   => array_keys(!empty($tinh_thanh["mien_nam"]) && is_array($tinh_thanh["mien_nam"]) ? $tinh_thanh["mien_nam"] : []),
        ];
        
        $loto_data = [];
        
        foreach ($result as $mien => $tinh_list) {
            foreach ($tinh_list as $key) {
                if (isset($_POST[$key])) {
                    $loto_data[$mien][$key]['result'] = sanitize_text_field($_POST[$key]);
                    $loto_data[$mien][$key]['loto_date'] = sanitize_text_field($_POST[$key . '_date']);
                }
            }
        }
        update_post_meta($post_id, '_loto_data', $loto_data);
    }


    public static function activate() {
        self::get_instance()->register_post_type();
        flush_rewrite_rules();
    }

    public static function deactivate() {
        flush_rewrite_rules();
    }
}

// Khởi chạy class
add_action('plugins_loaded', ['XoSoPostType', 'get_instance']);

// Hook kích hoạt & hủy kích hoạt
register_activation_hook(__FILE__, ['XoSoPostType', 'activate']);
register_deactivation_hook(__FILE__, ['XoSoPostType', 'deactivate']);