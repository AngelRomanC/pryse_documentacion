<template>
  <input
    type="checkbox"
    :checked="modelValue"
    @change="$emit('update:modelValue', $event.target.checked)"
    class="form-checkbox h-5 w-5 text-blue-600 transition duration-150 ease-in-out"
  />
</template>

<script setup>
defineProps({
  modelValue: Boolean
})
</script>
