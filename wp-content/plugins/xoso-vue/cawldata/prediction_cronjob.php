<?php

class LotteryPredictionCronjob {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('fetch_lottery_prediction_event', array($this, 'run_prediction'));
        add_action('init', array($this, 'schedule_daily_prediction'));
    }

    public function schedule_daily_prediction() {
        if (!wp_next_scheduled('fetch_lottery_prediction_event')) {
            wp_schedule_event(time(), 'daily', 'fetch_lottery_prediction_event');
        }
    }

    public function clear_schedule() {
        wp_clear_scheduled_hook('fetch_lottery_prediction_event');
    }

    public function run_prediction() {
        try {
            // Lấy bài viết mới nhất
            $latest_post = get_posts(array(
                'post_type' => 'xo_so',
                'posts_per_page' => 1,
                'orderby' => 'date',
                'order' => 'DESC'
            ));

            if (empty($latest_post)) {
                throw new Exception('Không tìm thấy bài viết xổ số nào.');
            }

            $latest_post_id = $latest_post[0]->ID;

            // Lấy dữ liệu 7 ngày gần nhất
            $posts = get_posts(array(
                'post_type' => 'xo_so',
                'posts_per_page' => 7,
                'orderby' => 'date',
                'order' => 'DESC'
            ));

            if (empty($posts)) {
                throw new Exception('Không đủ dữ liệu để dự đoán.');
            }

            $historical_data = array();
            foreach ($posts as $post) {
                $loto_data = get_post_meta($post->ID, '_loto_data', true);
                if (!empty($loto_data)) {
                    $historical_data[] = $loto_data;
                }
            }

            // Thực hiện dự đoán
            $predictions = $this->analyze_and_predict($historical_data);

            // Lưu dự đoán vào bài viết mới nhất
            update_post_meta($latest_post_id, '_loto_prediction', $predictions);

            // Cập nhật lịch sử
            $this->update_prediction_history(true, 'Dự đoán kết quả xổ số thành công.');

        } catch (Exception $e) {
            $this->update_prediction_history(false, $e->getMessage());
        }
    }

    private function analyze_and_predict($historical_data) {
        $predictions = array();
        $tinh_thanh = get_option('province_config', array());

        foreach ($tinh_thanh as $mien => $tinh_list) {
            foreach ($tinh_list as $key => $title) {
                $numbers = array();
                
                // Thu thập các số xuất hiện trong 7 ngày
                foreach ($historical_data as $day_data) {
                    if (isset($day_data[$mien][$key]['result'])) {
                        $result = $day_data[$mien][$key]['result'];
                        // Tách các số từ kết quả
                        $nums = preg_split('/[,\s]+/', $result);
                        foreach ($nums as $num) {
                            if (is_numeric($num)) {
                                // Chỉ lấy 2 chữ số cuối
                                $num = intval($num) % 100;
                                $numbers[] = $num;
                            }
                        }
                    }
                }

                if (!empty($numbers)) {
                    // Phân tích tần suất xuất hiện
                    $frequency = array_count_values($numbers);
                    arsort($frequency);

                    // Lấy các số có tần suất cao nhất
                    $top_numbers = array_keys($frequency);
                    
                    // Tạo 20 kết quả dự đoán
                    $prediction_numbers = array();
                    
                    // Thêm các số có tần suất cao
                    foreach ($top_numbers as $num) {
                        if (count($prediction_numbers) < 20) {
                            $prediction_numbers[] = $num;
                        }
                    }
                    
                    // Nếu chưa đủ 20 số, thêm các số ngẫu nhiên
                    while (count($prediction_numbers) < 20) {
                        $random_number = rand(0, 99);
                        if (!in_array($random_number, $prediction_numbers)) {
                            $prediction_numbers[] = $random_number;
                        }
                    }
                    
                    // Sắp xếp lại các số
                    sort($prediction_numbers);
                    
                    // Lưu dự đoán
                    $predictions[$mien][$key] = implode(', ', $prediction_numbers);
                }
            }
        }

        return $predictions;
    }

    private function update_prediction_history($status, $message) {
        $history = get_option('lottery_prediction_history', array());
        array_unshift($history, array(
            'time' => current_time('mysql'),
            'status' => $status ? 'success' : 'error',
            'message' => $message
        ));

        // Giữ tối đa 50 bản ghi
        $history = array_slice($history, 0, 50);
        update_option('lottery_prediction_history', $history);
    }
}

// Khởi tạo instance
LotteryPredictionCronjob::get_instance(); 