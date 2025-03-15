<?php

function setup_default_province_config() {
    $province_config = [
        'mien_bac' => [
            'miba' => 'Miền Bắc',
            'hano' => 'Hà Nội',
            'haph' => 'Hải Phòng',
            'nadi' => 'Nam Định',
        ],
        'mien_trung' => [
            'dana' => 'Đà Nẵng',
            'qung' => 'Quảng Ngãi',
        ],
        'mien_nam' => [
            'tphc' => 'TP.HCM',
            'loan' => 'Long An',
        ],
    ];

    $existing_config = get_option('province_config', null);

    if ($existing_config === null) {
        update_option('province_config', $province_config);
    }
}
add_action('after_switch_theme', 'setup_default_province_config');
