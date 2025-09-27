<template>
  <button type="button" :class="['block text-[6vmin] cursor-pointer', colorClasses]">
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  theme: {
    type: String,
    default: 'gray',
  }
})
const colorClasses = computed(() => {
  const config = {
    gray: 'bg-gray-500 hover:bg-gray-600',
    orange: 'bg-orange-500 hover:bg-orange-600',
    teal: 'bg-teal-500 hover:bg-teal-600',
    purple: 'bg-purple-500 hover:bg-purple-600',
  }

  return config[props.theme] || config.gray
})
</script>
