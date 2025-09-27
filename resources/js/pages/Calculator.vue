<template>
  <div class="flex min-h-screen flex-col">
    <div class="flex h-[25vh] flex-col justify-end text-right sm:h-auto">
      <p :class="['p-[.5vmax] text-[2vmax]', form.errors.expression ? 'text-red-500' : 'text-white']">
        &nbsp;{{ form.errors.expression }} <button @click.prevent="form.expression = lastAnswer" class="cursor-pointer">{{ lastAnswer }}</button>
      </p>
      <input v-model="form.expression" type="text" class="w-full border-b border-white p-[.5vmax] text-right text-[4vmax] focus:outline-0" />
    </div>
    <div class="grid flex-1 grid-cols-4 gap-[1vmax] p-[1vmax]">
      <CalculatorButton
        v-for="buttonKey in buttonOrder"
        :key="buttonKey"
        @click.prevent="onButtonClick(buttonConfig[buttonKey])"
        :theme="buttonConfig[buttonKey].theme"
      >
        {{ buttonConfig[buttonKey].label }}
      </CalculatorButton>
    </div>
  </div>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3'
import CalculatorButton from '@/components/CalculatorButton.vue'

const props = defineProps({
  lastAnswer: Number,
  currentValue: Number,
})
const form = useForm({
  expression: props.currentValue?.toString() || '',
})
const buttonOrder = ['AC', '(', ')', '/', '7', '8', '9', '*', '4', '5', '6', '-', '1', '2', '3', '+', 'H', '0', '.', '=']
const buttonConfig = {
  AC: {
    label: 'AC',
    theme: 'orange',
    action: () => {
      form.expression = ''
      form.clearErrors()
    },
  },
  '(': { label: '(' },
  ')': { label: ')' },
  '/': { label: '/', theme: 'teal' },
  7: { label: '7' },
  8: { label: '8' },
  9: { label: '9' },
  '*': { label: '*', theme: 'teal' },
  4: { label: '4' },
  5: { label: '5' },
  6: { label: '6' },
  '-': { label: '-', theme: 'teal' },
  1: { label: '1' },
  2: { label: '2' },
  3: { label: '3' },
  '+': { label: '+', theme: 'teal' },
  H: { label: 'H', action: () => router.visit('/calculations') },
  0: { label: '0' },
  '.': { label: '.' },
  '=': {
    label: '=',
    theme: 'purple',
    action: () => {
      form.post('/calculations', {
        onSuccess: () => {
          form.expression = ''
        },
      })
    },
  },
}
const onButtonClick = (button) => {
  if (typeof button.action === 'function') {
    return button.action()
  }

  form.expression += button.label
}
</script>
