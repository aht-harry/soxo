<?php


function province_config_page()  {
    $tinh_thanh = get_option('province_config', [
        'mien_bac' => [],
        'mien_trung' => [],
        'mien_nam' => []
    ]);

    // Xử lý thêm hoặc cập nhật tỉnh
    if (isset($_POST['save_tinh'])) {
        check_admin_referer('tinh_thanh_nonce');

        $region = sanitize_text_field($_POST['region']);
        $old_key = sanitize_text_field($_POST['old_key']); // Key cũ (nếu sửa)
        $new_key = sanitize_text_field($_POST['new_key']);
        $name = sanitize_text_field($_POST['name']);

        if (!empty($region) && !empty($new_key) && !empty($name)) {
            // Nếu sửa key, xóa key cũ
            if (!empty($old_key) && $old_key !== $new_key) {
                unset($tinh_thanh[$region][$old_key]);
            }

            // Lưu dữ liệu mới
            $tinh_thanh[$region][$new_key] = $name;
            update_option('province_config', $tinh_thanh);
            wp_redirect(admin_url('admin.php?page=province-config')); // Reload form
            exit;
        }
    }

    // Xử lý xóa tỉnh
    if (isset($_POST['delete_tinh'])) {
        check_admin_referer('tinh_thanh_nonce');

        $region = sanitize_text_field($_POST['region']);
        $key = sanitize_text_field($_POST['key']);

        if (isset($tinh_thanh[$region][$key])) {
            unset($tinh_thanh[$region][$key]);
            update_option('province_config', $tinh_thanh);
            wp_redirect(admin_url('admin.php?page=province-config')); // Reload form
            exit;
        }
    }

    // Lấy dữ liệu để sửa
    $edit_region = isset($_GET['edit_region']) ? sanitize_text_field($_GET['edit_region']) : '';
    $edit_key = isset($_GET['edit_key']) ? sanitize_text_field($_GET['edit_key']) : '';
    $edit_name = ($edit_region && $edit_key && isset($tinh_thanh[$edit_region][$edit_key])) ? $tinh_thanh[$edit_region][$edit_key] : '';

    ?>

    <div class="wrap">
        <h1>Quản lý Tỉnh Thành</h1>

        <h2><?php echo $edit_key ? 'Chỉnh sửa Tỉnh Thành' : 'Thêm Tỉnh Thành'; ?></h2>
        <form method="post">
            <?php wp_nonce_field('tinh_thanh_nonce'); ?>
            <select name="region" required>
                <option value="mien_bac" <?php selected($edit_region, 'mien_bac'); ?>>Miền Bắc</option>
                <option value="mien_trung" <?php selected($edit_region, 'mien_trung'); ?>>Miền Trung</option>
                <option value="mien_nam" <?php selected($edit_region, 'mien_nam'); ?>>Miền Nam</option>
            </select>
            <input type="hidden" name="old_key" value="<?php echo esc_attr($edit_key); ?>">
            <input type="text" name="new_key" placeholder="Mã tỉnh (VD: hano)" value="<?php echo esc_attr($edit_key); ?>" required>
            <input type="text" name="name" placeholder="Tên tỉnh (VD: Hà Nội)" value="<?php echo esc_attr($edit_name); ?>" required>
            <input type="submit" name="save_tinh" class="button-primary" value="<?php echo $edit_key ? 'Cập nhật' : 'Thêm'; ?>">
        </form>

        <h2>Danh Sách Tỉnh Thành</h2>
        <?php foreach ($tinh_thanh as $region => $provinces): ?>
            <h3><?php echo ucfirst(str_replace('_', ' ', $region)); ?></h3>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Mã tỉnh</th>
                        <th>Tên tỉnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($provinces as $key => $name): ?>
                        <tr>
                            <td><?php echo esc_html($key); ?></td>
                            <td><?php echo esc_html($name); ?></td>
                            <td>
                                <a href="<?php echo esc_url(admin_url("admin.php?page=province-config&edit_region=$region&edit_key=$key")); ?>" class="button button-secondary">Sửa</a>
                                <form method="post" style="display:inline;">
                                    <?php wp_nonce_field('tinh_thanh_nonce'); ?>
                                    <input type="hidden" name="region" value="<?php echo esc_attr($region); ?>">
                                    <input type="hidden" name="key" value="<?php echo esc_attr($key); ?>">
                                    <input type="submit" name="delete_tinh" class="button button-danger" value="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
    <?php
}
