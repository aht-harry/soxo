<?php
function loto_add_meta_box() {
    add_meta_box(
        'loto_meta_box',
        'Kết Quả Xổ Số',
        'loto_meta_box_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'loto_add_meta_box');

function loto_meta_box_callback($post) {
    $loto_data = get_post_meta($post->ID, '_loto_data', true);
    if (!$loto_data) {
        $loto_data = [];
    }
    $tinh_thanh = get_option('province_config', []);
    
    ?>

    <style>
        .loto-tabs { display: flex; margin-bottom: 10px; }
        .loto-tab { padding: 10px 20px; cursor: pointer; border: 1px solid #ccc; background: #f1f1f1; }
        .loto-tab.active { background: #fff; font-weight: bold; border-bottom: none; }
        .loto-content { display: none; padding: 10px; border: 1px solid #ccc; }
        .loto-content.active { display: block; }
    </style>

    <div class="loto-tabs">
        <div class="loto-tab active" data-tab="mien_bac">Miền Bắc</div>
        <div class="loto-tab" data-tab="mien_trung">Miền Trung</div>
        <div class="loto-tab" data-tab="mien_nam">Miền Nam</div>
    </div>

    <?php foreach ($tinh_thanh as $mien => $tinh_list) {
        echo '<div class="loto-content ' . ($mien === 'mien_bac' ? 'active' : '') . '" id="' . $mien . '">';
    
        foreach ($tinh_list as $key => $title) {

            $ket_qua  = isset( $loto_data[$mien][$key]) ?  $loto_data[$mien][$key] : '';

            echo '<label><b>' . esc_html($title) . ' - ( '. $key .' ) </b></label>';
            echo '<input type="text" name="' . $key . '" value="' . esc_html($ket_qua ) .   '" style="width:100%;" />';
            echo '<br><br>';
        }

        echo '</div>'; 
    }?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".loto-tab").forEach(tab => {
                tab.addEventListener("click", function() {
                    document.querySelectorAll(".loto-tab").forEach(t => t.classList.remove("active"));
                    document.querySelectorAll(".loto-content").forEach(c => c.classList.remove("active"));
                    
                    this.classList.add("active");
                    document.getElementById(this.dataset.tab).classList.add("active");
                });
            });
        });
    </script>
    <?php
}

// Lưu dữ liệu vào Custom Field

function loto_save_meta_box($post_id) {
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
                $loto_data[$mien][$key] = sanitize_text_field($_POST[$key]);
            }
        }
    }
    update_post_meta($post_id, '_loto_data', $loto_data);
}
add_action('save_post', 'loto_save_meta_box');