<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Biến lưu trữ dữ liệu xổ số
const latestPost = ref(null);

// Hàm gọi API bằng axios
const fetchLatestPost = async () => {
    try {
        const response = await axios.get('http://localhost/soxo/wp-json/custom/v1/latest-posts/');
        latestPost.value = response.data[0] || null; // Lấy bài viết đầu tiên
    } catch (error) {
        console.error('Lỗi khi lấy dữ liệu:', error);
    }
};

// Gọi API khi component được mounted
onMounted(fetchLatestPost);
</script>

<template>
    <div v-if="latestPost">
        <h2>Kết quả xổ số hôm nay</h2>
        <p><strong>Ngày:</strong> {{ latestPost.date }}</p>
        <p><strong>Tiêu đề:</strong> {{ latestPost.title }}</p>
        <img :src="latestPost.thumbnail" alt="Thumbnail" v-if="latestPost.thumbnail" />

        <!-- Kiểm tra nếu có dữ liệu xổ số -->
        <div v-if="latestPost.data">
            <h3>Chi tiết kết quả:</h3>
            <pre>{{ latestPost.data }}</pre> 
        </div>
        
        <a :href="latestPost.link" target="_blank">Xem chi tiết</a>
    </div>
    <div v-else>
        <p>Đang tải kết quả xổ số...</p>
    </div>
</template>
