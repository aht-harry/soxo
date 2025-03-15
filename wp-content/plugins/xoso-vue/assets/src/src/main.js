import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import LotteryResult from './components/LotteryResultTable.vue';
import lotoTab from './components/lotoTab.vue';
createApp(
    lotoTab
).mount('#vue-app')
