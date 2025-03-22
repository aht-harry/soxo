<?php
class LotteryCronjobManager {
    private static $instance = null;
    private $option_prefix = 'lottery_cronjob_';
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('fetch_lottery_data_event', array($this, 'fetch_lottery_data'));
        add_action('init', array($this, 'check_and_reschedule'));
    }

    public function check_and_reschedule() {
        if (!wp_next_scheduled('fetch_lottery_data_event')) {
            $this->schedule_daily_cronjob();
        }
    }

    public function fetch_lottery_data() {
        $start_time = microtime(true);
        $this->log_execution('Started fetching lottery data');

        $province_config = get_option('province_config', []);
        
        try {
            foreach ($province_config as $key => $provinces) {
                foreach ($provinces as $province => $value) {
                    $data = $this->make_api_request($province);
                    if (!$data || !$data['success']) {
                        throw new Exception('Failed to fetch data for province: ' . $province);
                    }

                    $this->create_or_update_post($data['t'], $province,$key , 29);
                }
            }

            foreach ($provinces as $province) {
                
            }

            $execution_time = round(microtime(true) - $start_time, 2);
            $this->update_execution_history('success', "Completed successfully in {$execution_time}s");
            
        } catch (Exception $e) {
            $this->update_execution_history('error', $e->getMessage());
            error_log("Lottery Cronjob Error: " . $e->getMessage());
        }
    }

    private function make_api_request($province) {
        $url = 'https://xoso188.net/api/front/open/lottery/history/list/30/' . $province;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) WordPress/' . get_bloginfo('version')
        ));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new Exception('cURL error: ' . $error_msg);
        }

        curl_close($ch);

        if ($http_code !== 200) {
            throw new Exception("API returned HTTP code: {$http_code}");
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response from API');
        }

        return $data;
    }

    private function create_or_update_post($lottery_data, $province, $key , $day = 0) {
        
        for ($i = $day; $i >= 0; $i--) {


            $masterData = $lottery_data['issueList'][$i];

            // foreach (array_reverse($lottery_data['issueList'])  as $index => $value) {

                $current_date = date('Y-m-d', strtotime("-$i days"));
                

                $turnNum = $current_date;

                $post_title = 'Kết Quả Xổ Số Ngày ' . $turnNum; // Include date in the title

                // Check if a post with the same title already exists
                $existing_posts = get_posts(array(
                    'title' => $post_title,
                    'post_type' => POST_TYPE,
                    'post_status' => 'publish',
                    'numberposts' => 1
                ));

                if (!empty($existing_posts)) {
                    // Use the existing post
                    $post_id = $existing_posts[0]->ID;
                } else {
                    // Create a new post
                    $post_args = array(
                        'post_title'   => $post_title,
                        'post_content' => 'Kết quả xổ số tổng hợp cho ngày ' . $turnNum . '.',
                        'post_status'  => 'publish',
                        'post_type'    => POST_TYPE,
                    );
                    $post_id = wp_insert_post($post_args);
                    sleep(2);

                    if (is_wp_error($post_id)) {
                        throw new Exception('Failed to create post for province: ' . $province);
                    }
                }

                // Organize data into the same structure as in post_metadata.php
                $loto_data = get_post_meta($post_id, '_loto_data', true);
                if (!$loto_data) {
                    $loto_data = [];
                }

                $loto_data[$key][$province]['result'] = $masterData['detail'];
                $loto_data[$key][$province]['loto_date'] = $masterData['turnNum'];

                update_post_meta($post_id, '_loto_data', $loto_data);
                // 
            // }
        }
        
    }
    public function schedule_daily_cronjob() {
        $time = get_option('cawldata_cron_time', '03:00');
        list($hour, $minute) = explode(':', $time);
        
        // Calculate next run time
        $current_time = current_time('timestamp');
        $scheduled_time = strtotime(date('Y-m-d', $current_time) . " {$hour}:{$minute}:00");
        
        if ($scheduled_time <= $current_time) {
            $scheduled_time = strtotime('+1 day', $scheduled_time);
        }

        // Clear existing schedule
        $this->clear_schedule();

        // Schedule new event
        wp_schedule_event($scheduled_time, 'daily', 'fetch_lottery_data_event');
        
        $this->log_execution("Scheduled next run for: " . date('Y-m-d H:i:s', $scheduled_time));
    }

    public function clear_schedule() {
        wp_clear_scheduled_hook('fetch_lottery_data_event');
    }

    private function update_execution_history($status, $message) {
        $history = get_option($this->option_prefix . 'execution_history', array());
        
        // Add new entry at the beginning
        array_unshift($history, array(
            'time' => current_time('mysql'),
            'status' => $status,
            'message' => $message
        ));

        // Keep only last 50 entries
        $history = array_slice($history, 0, 50);
        
        update_option($this->option_prefix . 'execution_history', $history);
    }

    private function log_execution($message) {
        error_log("Lottery Cronjob: {$message}");
    }

    public static function activate() {
        $instance = self::get_instance();
        $instance->schedule_daily_cronjob();
    }

    public static function deactivate() {
        $instance = self::get_instance();
        $instance->clear_schedule();
    }
}

// Initialize the cronjob manager
add_action('plugins_loaded', array('LotteryCronjobManager', 'get_instance'));

// Register activation/deactivation hooks
register_activation_hook(__FILE__, array('LotteryCronjobManager', 'activate'));
register_deactivation_hook(__FILE__, array('LotteryCronjobManager', 'deactivate'));
