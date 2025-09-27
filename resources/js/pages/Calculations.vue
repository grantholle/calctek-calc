<template>
  <InfiniteScroll data="calculations">
    <div class="pb-24">
      <p v-if="message" class="p-2 text-green-500">{{ message }}</p>
      <ul class="text-right divide-y divide-gray-500">
        <li v-for="calc in calculations.data" :key="calc.id" class="pb-1">
          <div class="flex justify-between items-center">
            <Link :href="`/calculations/${calc.id}`" method="delete" class="p-2 hover:underline text-red-500 cursor-pointer">
              Delete
            </Link>
            <p class="p-2">
              {{ calc.expression }}
            </p>
          </div>
          <Link :href="`/?calculation=${calc.id}`" class="p-2 text-2xl">
            {{ calc.answer_formatted }}
          </Link>
        </li>
      </ul>
    </div>
    <div class="fixed bottom-0 w-full p-8 flex flex-col sm:flex-row gap-5 bg-black">
      <Link class="flex-1 block text-center bg-gray-500 px-4 py-2" href="/">Calculator</Link>
      <Link class="flex-1 block text-center bg-red-500 px-4 py-2" href="/calculations" method="delete">Clear history</Link>
    </div>
  </InfiniteScroll>
</template>

<script setup>
import { Link, InfiniteScroll } from '@inertiajs/vue3'

defineProps({
  calculations: Object,
  message: String,
})
</script>
