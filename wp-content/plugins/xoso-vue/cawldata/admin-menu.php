<?php

require_once plugin_dir_path(__FILE__) . 'province_config.php';



function cawldata_add_admin_menu() {
    add_menu_page(
        'Quản lý Cronjob',
        'Cronjob Manager',
        'manage_options',
        'cawldata-cronjob',
        'cawldata_cronjob_page',
        'dashicons-clock',
        25
    );

    // Add submenu for province configuration
    add_submenu_page(
        'cawldata-cronjob',
        'Cấu hình Tỉnh',
        'Cấu hình Tỉnh',
        'manage_options',
        'province-config',
        'province_config_page'
    );
}
add_action('admin_menu', 'cawldata_add_admin_menu');

function cawldata_cronjob_page() {
    // Handle form submissions
    if (isset($_POST['update_cron_schedule'])) {
        check_admin_referer('update_cron_schedule');
        $new_time = sanitize_text_field($_POST['cron_time']);
        update_option('cawldata_cron_time', $new_time);
        LotteryCronjobManager::get_instance()->schedule_daily_cronjob();
        add_settings_error('cawldata_messages', 'cawldata_message', 'Đã cập nhật lịch chạy Cronjob.', 'updated');
    }

    if (isset($_POST['run_cronjob'])) {
        check_admin_referer('run_cronjob');
        do_action('fetch_lottery_data_event');
        add_settings_error('cawldata_messages', 'cawldata_message', 'Đã kích hoạt Cronjob thủ công.', 'updated');
    }

    if (isset($_POST['clear_cronjob'])) {
        check_admin_referer('clear_cronjob');
        LotteryCronjobManager::get_instance()->clear_schedule();
        add_settings_error('cawldata_messages', 'cawldata_message', 'Đã xóa lịch Cronjob.', 'updated');
    }

    // Display settings errors
    settings_errors('cawldata_messages');

    // Get current settings and status
    $current_time = get_option('cawldata_cron_time', '03:00');
    $timestamp = wp_next_scheduled('fetch_lottery_data_event');
    $history = get_option('lottery_cronjob_execution_history', []);
    $last_update = get_option('lottery_cronjob_last_update');

    // Calculate countdown
    $time_remaining = $timestamp ? $timestamp - time() : 0;
    $hours = floor($time_remaining / 3600);
    $minutes = floor(($time_remaining % 3600) / 60);
    $seconds = $time_remaining % 60;
    $countdown_text = ($time_remaining > 0) 
        ? sprintf("%02d giờ %02d phút %02d giây", $hours, $minutes, $seconds)
        : "Sắp chạy!";
    ?>
    <div class="wrap">
        <h1>Quản lý Cronjob Xổ Số</h1>
        
        <div class="card">
            <h2 class="title">Trạng thái</h2>
            <p><strong>Trạng thái cronjob:</strong> <?php echo $timestamp ? '<span style="color: green;">Đã lên lịch</span>' : '<span style="color: red;">Chưa lên lịch</span>'; ?></p>
            <p><strong>Lần cập nhật cuối:</strong> <?php echo $last_update ? date_i18n('d/m/Y H:i:s', strtotime($last_update)) : 'Chưa có'; ?></p>
            <p><strong>Thời gian chạy tiếp theo:</strong> <?php echo $timestamp ? date_i18n('d/m/Y H:i:s', $timestamp) : 'N/A'; ?></p>
            <p><strong>Còn lại:</strong> <span id="cron-countdown"><?php echo esc_html($countdown_text); ?></span></p>
        </div>

        <div class="card">
            <h2 class="title">Cài đặt thời gian chạy</h2>
            <form method="post">
                <?php wp_nonce_field('update_cron_schedule'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="cron_time">Thời gian chạy hàng ngày</label></th>
                        <td>
                            <input type="time" id="cron_time" name="cron_time" value="<?php echo esc_attr($current_time); ?>">
                            <p class="description">Định dạng 24 giờ (VD: 03:00 = 3 giờ sáng)</p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="update_cron_schedule" class="button button-primary" value="Lưu cài đặt">
                </p>
            </form>
        </div>

        <div class="card">
            <h2 class="title">Hành động</h2>
            <form method="post" style="display: inline;">
                <?php wp_nonce_field('run_cronjob'); ?>
                <input type="submit" name="run_cronjob" value="Chạy Cronjob Ngay" class="button button-primary">
            </form>
            <form method="post" style="display: inline; margin-left: 10px;">
                <?php wp_nonce_field('clear_cronjob'); ?>
                <input type="submit" name="clear_cronjob" value="Xóa Cronjob" class="button button-secondary" onclick="return confirm('Bạn có chắc chắn muốn xóa lịch Cronjob?');">
            </form>
        </div>

        <div class="card">
            <h2 class="title">Lịch sử chạy Cronjob </h2>
            <table class="widefat">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($history)) : ?>
                        <?php foreach ($history as $entry) : ?>
                            <tr>
                                <td><?php echo date_i18n('d/m/Y H:i:s', strtotime($entry['time'])); ?></td>
                                <td>
                                    <span class="status-<?php echo esc_attr($entry['status']); ?>">
                                        <?php echo $entry['status'] === 'success' ? '✅ Thành công' : '❌ Lỗi'; ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($entry['message']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3">Chưa có lịch sử chạy.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .card {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            margin-top: 20px;
            padding: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .card .title {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .status-success { color: green; }
        .status-error { color: red; }
        #cron-countdown {
            font-weight: bold;
            color: #0073aa;
        }
    </style>

    <script>
        function updateCountdown() {
            let countdownElement = document.getElementById("cron-countdown");
            let timeLeft = <?php echo $time_remaining; ?>;
            
            if (timeLeft > 0) {
                setInterval(function () {
                    timeLeft--;
                    if (timeLeft >= 0) {
                        let hours = Math.floor(timeLeft / 3600);
                        let minutes = Math.floor((timeLeft % 3600) / 60);
                        let seconds = timeLeft % 60;
                        countdownElement.innerText = 
                            String(hours).padStart(2, '0') + " giờ " + 
                            String(minutes).padStart(2, '0') + " phút " + 
                            String(seconds).padStart(2, '0') + " giây";
                    }
                }, 1000);
            }
        }
        document.addEventListener('DOMContentLoaded', updateCountdown);
    </script>
    <?php
}
