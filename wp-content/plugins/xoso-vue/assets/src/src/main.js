import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import LotteryResult from './components/LotteryResultTable.vue';
import lotoTab from './components/lotoTab.vue';
import LotteryPredictionTab from './components/LotteryPredictionTab.vue';
createApp(
    lotoTab
).mount('#vue-app')

createApp(
    LotteryPredictionTab
).mount('#LotteryPredictionTab')