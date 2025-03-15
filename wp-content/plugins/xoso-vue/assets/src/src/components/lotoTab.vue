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
      <div>
        <a @click="handler('Form cannot be submitted yet.', $event)" href=""> Ngay 1</a>
        <a @click="handler('Form cannot be submitted yet 2.', $event)" href=""> Ngay 2</a>
      </div>
      <div v-if="activeRegion" class="region-content">
        <h2>{{ regionNames[activeRegion] }}</h2>
        <select v-if="provinces[activeRegion]" v-model="selectedProvinces[activeRegion]" @change="updateData(activeRegion)">
          <option v-for="(name, key) in provinces[activeRegion]" :key="key" :value="key">
            {{ name }}
          </option>
        </select>
        <div v-if="lotteryData[activeRegion]">
          <h3>Kết quả xổ số:</h3>
          <LotteryResults :activeRegion :loading :prizeCodes="lotteryData[activeRegion].prizeCodes" :prizes="lotteryData[activeRegion].prizes" >
            
          </LotteryResults>
          
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import LotteryResults from './LotteryResults.vue';
  
  export default {
    components: {
      LotteryResults
    },
    data() {
      return {
        selectedProvinces: {},
        lotteryData: {},
        provinces: {},
        data: {},
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
      async fetchData( url = 'latest-posts' ) {
        this.loading = true;
        try {
          
          const response = await axios.get('http://localhost/soxo/wp-json/custom/v1/' + url);
          if (response.data.length > 0) {
            this.data = response.data[0].data[0];
            this.provinces = response.data[0].provinces;
            // Chọn giá trị mặc định đầu tiên
            Object.keys(this.provinces).forEach(region => {
              const firstProvinceKey = Object.keys(this.provinces[region])[0];
              if (firstProvinceKey) {
                this.selectedProvinces[region] = firstProvinceKey;
                this.updateData(region);
              }
            });
          }
        } catch (error) {
          console.error("Error fetching data:", error);
        } finally {
          this.loading = false; // Kết thúc loading
        }
      },
      handler(message, event){
        event.preventDefault();
        this.fetchData('last-posts');

      },
      updateData(region) {
        const provinceKey = this.selectedProvinces[region];
        
        if (provinceKey && this.data[region] && this.data[region][provinceKey]) {
          const rawData = this.data[region][provinceKey];
          const parsedData = JSON.parse(rawData);
          this.lotteryData[region] = {
            prizeCodes: parsedData[0] || [],
            prizes: parsedData.slice(1) || []
          };
        } else {
          this.lotteryData[region] = { prizeCodes: [], prizes: [] };
        }
      }
    },
    mounted() {
      this.fetchData();
    }
  };
  </script>
  
  <style scoped>
  .loto-tabs { display: flex; margin-bottom: 10px; }
  .loto-tab { padding: 10px 20px; cursor: pointer; border: 1px solid #ccc; background: #f1f1f1; }
  .loto-tab.active { background: #fff; font-weight: bold; border-bottom: none; }
  .loto-content { display: none; padding: 10px; border: 1px solid #ccc; }
  .loto-content.active { display: block; }
  </style>
  