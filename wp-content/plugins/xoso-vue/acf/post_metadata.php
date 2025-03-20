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
            echo '<label style="width:10%;" ><b>' . esc_html($title) . ' - ( '. $key .' ) </b></label>';
            echo '<input type="text" name="' . $key . '" value="' . esc_html($ket_qua ) .   '" style="width:70%;" />';
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

// Lưu dữ liệu vào Custom Field