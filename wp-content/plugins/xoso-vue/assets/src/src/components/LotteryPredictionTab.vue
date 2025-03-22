<template>
  <div>
    <div class="loto-tabs">
      <div 
        v-for="(name, key) in regionNames" 
        :key="key" 
        class="loto-tab" 
        :class="{ active: activeRegion === key }"
        @click="activeRegion = key"
      >
        {{ name }}
      </div>
    </div>

    <div v-if="activeRegion" class="region-content">
      <div class="filter-search">
        <select v-if="provinces[activeRegion]" v-model="selectedProvinces[activeRegion]">
          <option v-for="(name, key) in provinces[activeRegion]" :key="key" :value="key">
            {{ name }}
          </option>
        </select>
      </div>

      <div class="prediction-container">
        <div class="prediction-header">
          <h3>Dự Đoán Kết Quả Xổ Số</h3>
          <p class="update-time">Ngày: {{ formatDate(date) }}</p>
        </div>

        <div class="prediction-numbers">
          <div v-for="(number, index) in getPredictionNumbers(activeRegion)" 
               :key="index" 
               class="number-item">
            {{ number }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LotteryPredictionTab',
  data() {
    return {
      selectedProvinces: {},
      predictionData: {},
      provinces: {},
      date: '',
      loading: true,
      activeRegion: 'mien_bac',
      regionNames: {
        mien_bac: "Miền Bắc",
        mien_trung: "Miền Trung",
        mien_nam: "Miền Nam"
      }
    };
  },
  methods: {
    async fetchData() {
      this.loading = true;
      try {
        const response = await axios.get('http://localhost/soxo/wp-json/custom/v1/prediction/');
        
        console.log('API Response:', response.data);
        
        if (response.data && response.data.length > 0) {
          const data = response.data[0];
          console.log('Prediction Data:', data);
          
          this.provinces = data.provinces;
          this.predictionData = data.prediction[0]; // Lấy phần tử đầu tiên của mảng prediction
          this.date = data.date;

          // Chọn giá trị mặc định đầu tiên
          Object.keys(this.provinces).forEach(region => {
            const firstProvinceKey = Object.keys(this.provinces[region])[0];
            if (firstProvinceKey) {
              this.selectedProvinces[region] = firstProvinceKey;
            }
          });
        }
      } catch (error) {
        console.error("Error fetching prediction data:", error);
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
      });
    },
    getPredictionNumbers(region) {
      console.log('getPredictionNumbers called for region:', region);
      console.log('selectedProvinces:', this.selectedProvinces);
      console.log('predictionData:', this.predictionData);
      
      const provinceKey = this.selectedProvinces[region];
      console.log('provinceKey:', provinceKey);
      
      if (!provinceKey || !this.predictionData[region] || !this.predictionData[region][provinceKey]) {
        console.log('No data found for:', { region, provinceKey });
        return [];
      }
      
      const numbers = this.predictionData[region][provinceKey].split(', ').map(num => num.padStart(2, '0'));
      console.log('Numbers found:', numbers);
      return numbers;
    }
  },
  mounted() {
    this.fetchData();
  }
};
</script>

<style scoped>
.loto-tabs { 
  display: flex; 
  margin-bottom: 10px; 
}
.loto-tab { 
  padding: 10px 20px; 
  cursor: pointer; 
  border: 1px solid #ccc; 
  background: #f1f1f1; 
}
.loto-tab.active { 
  background: #fff; 
  font-weight: bold; 
  border-bottom: none; 
}

.region-content {
  padding: 20px;
  border: 1px solid #ccc;
  border-top: none;
}

.filter-search {
  margin-bottom: 20px;
}

.filter-search select {
  padding: 8px;
  border-radius: 4px;
  border: 1px solid #ddd;
  min-width: 200px;
}

.prediction-container {
  background: #fff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.prediction-header {
  text-align: center;
  margin-bottom: 20px;
}

.prediction-header h3 {
  margin: 0;
  color: #333;
  font-size: 24px;
}

.update-time {
  color: #666;
  font-size: 14px;
  margin: 5px 0 0;
}

.prediction-numbers {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  justify-content: center;
}

.number-item {
  background: #f8f9fa;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 8px 12px;
  font-size: 16px;
  color: #333;
  min-width: 40px;
  text-align: center;
  transition: all 0.3s ease;
}

.number-item:hover {
  background: #e9ecef;
  transform: translateY(-2px);
}

@media (max-width: 768px) {
  .loto-tabs {
    flex-wrap: wrap;
  }
  
  .loto-tab {
    flex: 1;
    text-align: center;
  }
  
  .prediction-numbers {
    gap: 8px;
  }
  
  .number-item {
    min-width: 35px;
    padding: 6px 10px;
    font-size: 14px;
  }
}
</style> 