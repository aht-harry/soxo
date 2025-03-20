<template>
    <div class="calendar-wrapper">
      <input type="date" v-model="selectedDate" :max="maxDate" @change="updateDate" />
    </div>
  </template>
  
  <script setup>
  import { ref, defineEmits } from 'vue';
  
  const emit = defineEmits(['update:modelValue']);
  
  const today = new Date();
  const maxDate = today.toISOString().split('T')[0];
  const selectedDate = ref(maxDate);
  
  const updateDate = () => {
    emit('update:modelValue', selectedDate.value);
  };
  </script>
  
  <style scoped>
  .calendar-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
  }
  </style>
  