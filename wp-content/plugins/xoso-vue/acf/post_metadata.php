<?php
function loto_add_meta_box() {
    add_meta_box(
        'loto_meta_box',
        'Kết Quả Xổ Số',
        'loto_meta_box_callback',
        'xo_so',
        'normal',
        'high'
    );

    add_meta_box(
        'loto_prediction_meta_box',
        'Dự Đoán Kết Quả Xổ Số',
        'loto_prediction_meta_box_callback',
        'xo_so',
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
    
    // echo '<pre>';
    // print_r($loto_data);

    ?>

    <style>
        .loto-tabs { display: flex; margin-bottom: 10px; }
        .loto-tab { padding: 10px 20px; cursor: pointer; border: 1px solid #ccc; background: #f1f1f1; }
        .loto-tab.active { background: #fff; font-weight: bold; border-bottom: none; }
        .loto-content { display: none; padding: 10px; border: 1px solid #ccc; }
        .loto-content.active { display: block; }
        .loto-content .item{display: flex; justify-content: space-between; padding: .5em 0;}
    </style>

    <div class="loto-tabs">
        <div class="loto-tab active" data-tab="mien_bac">Miền Bắc</div>
        <div class="loto-tab" data-tab="mien_trung">Miền Trung</div>
        <div class="loto-tab" data-tab="mien_nam">Miền Nam</div>
    </div>

    <?php foreach ($tinh_thanh as $mien => $tinh_list) {
        echo '<div class="loto-content ' . ($mien === 'mien_bac' ? 'active' : '') . '" id="' . $mien . '">';
    
        foreach ($tinh_list as $key => $title) {

            $ket_qua  = isset( $loto_data[$mien][$key]['result']) ? $loto_data[$mien][$key]['result']: '';

            $ngay = isset(  $loto_data[$mien][$key]['loto_date']  ) ?  $loto_data[$mien][$key]['loto_date']  : ''; 
            
            if (!empty($ngay)) {
                $dateObj = DateTime::createFromFormat('d/m/Y', $ngay);
                $ngay = $dateObj ? $dateObj->format('Y-m-d') : ''; // Đổi thành YYYY-MM-DD
            }

            echo '<div class="item">';
            echo '<label style="width:15%;" ><b>' . esc_html($title) . ' - ( '. $key .' ) </b></label>';
            echo '<input type="text" name="' . $key . '" value="' . esc_html($ket_qua ) .   '" style="width:65%;" />';
            echo '<input type="date" name="' . $key . '_date" value="' . ($ngay) . '" class="loto-date" style="width:20%;" readonly  />';
            echo '</div>';
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

function loto_prediction_meta_box_callback($post) {
    $loto_prediction = get_post_meta($post->ID, '_loto_prediction', true);
    if (!$loto_prediction) {
        $loto_prediction = [];
    }
    $tinh_thanh = get_option('province_config', []);
    ?>

    <style>
        .loto-prediction-tabs { display: flex; margin-bottom: 10px; }
        .loto-prediction-tab { padding: 10px 20px; cursor: pointer; border: 1px solid #ccc; background: #f1f1f1; }
        .loto-prediction-tab.active { background: #fff; font-weight: bold; border-bottom: none; }
        .loto-prediction-content { display: none; padding: 10px; border: 1px solid #ccc; }
        .loto-prediction-content.active { display: block; }
        .loto-prediction-content .item{display: flex; justify-content: space-between; padding: .5em 0;}
    </style>

    <div class="loto-prediction-tabs">
        <div class="loto-prediction-tab active" data-tab="mien_bac">Miền Bắc</div>
        <div class="loto-prediction-tab" data-tab="mien_trung">Miền Trung</div>
        <div class="loto-prediction-tab" data-tab="mien_nam">Miền Nam</div>
    </div>

    <?php foreach ($tinh_thanh as $mien => $tinh_list) {
        echo '<div class="loto-prediction-content ' . ($mien === 'mien_bac' ? 'active' : '') . '" id="prediction_' . $mien . '">';
    
        foreach ($tinh_list as $key => $title) {
            $du_doan = isset($loto_prediction[$mien][$key]) ? $loto_prediction[$mien][$key] : '';

            echo '<div class="item">';
            echo '<label style="width:20%;" ><b>' . esc_html($title) . ' - ( '. $key .' ) </b></label>';
            echo '<input type="text" name="prediction_' . $key . '" value="' . esc_html($du_doan) . '" style="width:80%;" />';
            echo '</div>';
        }

        echo '</div>'; 
    }?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".loto-prediction-tab").forEach(tab => {
                tab.addEventListener("click", function() {
                    document.querySelectorAll(".loto-prediction-tab").forEach(t => t.classList.remove("active"));
                    document.querySelectorAll(".loto-prediction-content").forEach(c => c.classList.remove("active"));
                    
                    this.classList.add("active");
                    document.getElementById("prediction_" + this.dataset.tab).classList.add("active");
                });
            });
        });
    </script>
    <?php
}

// Lưu dữ liệu vào Custom Field
function loto_save_meta_box_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!current_user_can('edit_post', $post_id)) return;

    // Lưu kết quả xổ số
    $loto_data = [];
    $tinh_thanh = get_option('province_config', []);
    
    foreach ($tinh_thanh as $mien => $tinh_list) {
        foreach ($tinh_list as $key => $title) {
            if (isset($_POST[$key])) {
                $loto_data[$mien][$key]['result'] = sanitize_text_field($_POST[$key]);
            }
            if (isset($_POST[$key . '_date'])) {
                $loto_data[$mien][$key]['loto_date'] = sanitize_text_field($_POST[$key . '_date']);
            }
        }
    }
    update_post_meta($post_id, '_loto_data', $loto_data);

    // Lưu dự đoán kết quả
    $loto_prediction = [];
    foreach ($tinh_thanh as $mien => $tinh_list) {
        foreach ($tinh_list as $key => $title) {
            if (isset($_POST['prediction_' . $key])) {
                $loto_prediction[$mien][$key] = sanitize_text_field($_POST['prediction_' . $key]);
            }
        }
    }
    update_post_meta($post_id, '_loto_prediction', $loto_prediction);
}
add_action('save_post', 'loto_save_meta_box_data');