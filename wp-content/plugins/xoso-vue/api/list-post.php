<?php

include 'function/get_provinces.php';

function get_latest_posts() {
    $args = array(
        'post_type'      => POST_TYPE,
        'posts_per_page' => 1, // Số bài viết muốn lấy
        'orderby'        => 'title',
        'order'          => 'DESC'
    );
    $date = isset($_GET['date'])? $_GET['date'] : '';
    
    if (!empty($date)) {
        
        $dateObj = DateTime::createFromFormat('d/m/Y', $date);
        $date = $dateObj ? $dateObj->format('Y-m-d') : $date; // Đổi thành YYYY-MM-DD
    
        $text = 'Kết Quả Xổ Số Ngày ' . $date;
        
        $args['s'] = $text; // Tìm bài viết theo tiêu đề
    }
    

    $query = new WP_Query($args);

    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $posts[] = [
                'id'        => $post_id,
                'title'     => get_the_title(),
                'excerpt'   => get_the_excerpt(),
                'link'      => get_permalink(),
                'thumbnail' => get_the_post_thumbnail_url($post_id, 'medium'),
                'date'      => get_the_date('Y-m-d H:i:s'),
                'data'      => get_post_meta($post_id, '_loto_data',null ),
                'provinces' => get_provinces()
            ];
        }
        wp_reset_postdata();
    }

    return rest_ensure_response($posts);
}

function get_last() {
    $args = array(
        'post_type'      => POST_TYPE,
        'posts_per_page' => 1, // Số bài viết muốn lấy
        'orderby'        => 'date',
        'order'          => 'ASC'
    );

    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $posts[] = [
                'id'        => $post_id,
                'title'     => get_the_title(),
                'excerpt'   => get_the_excerpt(),
                'link'      => get_permalink(),
                'thumbnail' => get_the_post_thumbnail_url($post_id, 'medium'),
                'date'      => get_the_date('Y-m-d H:i:s'),
                'data'      => get_post_meta($post_id, '_loto_data',null ),
                'provinces' => get_provinces()
            ];
        }
        wp_reset_postdata();
    }

    return rest_ensure_response($posts);
}

function get_prediction() {
    $args = array(
        'post_type'      => POST_TYPE,
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $posts[] = [
                'date'      => get_the_date('Y-m-d'),
                'prediction' => get_post_meta($post_id, '_loto_prediction', null),
                'provinces' => get_provinces()
            ];
        }
        wp_reset_postdata();
    }

    return rest_ensure_response($posts);
}

// Đăng ký REST API route
function register_latest_posts_api() {
    register_rest_route('custom/v1', '/latest-posts/', array(
        'methods'  => 'GET',
        'callback' => 'get_latest_posts',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('custom/v1', '/last-posts/', array(
        'methods'  => 'GET',
        'callback' => 'get_last',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('custom/v1', '/provinces/', array(
        'methods'  => 'GET',
        'callback' => 'get_provinces',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('custom/v1', '/prediction/', array(
        'methods'  => 'GET',
        'callback' => 'get_prediction',
        'permission_callback' => '__return_true',
    ));
}

add_action('rest_api_init', 'register_latest_posts_api');
