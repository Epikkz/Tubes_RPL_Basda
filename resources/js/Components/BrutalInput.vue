<template>
  <input 
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    class="w-full bg-retro-orange text-black font-comic text-xl px-6 py-3 rounded-full border-4 border-black shadow-brutal focus:outline-none focus:ring-4 focus:ring-retro-blue focus:translate-y-1 focus:shadow-brutal-sm transition-all uppercase placeholder-black/50"
    :placeholder="placeholder"
    :type="type"
  />
</template>

<script setup>
defineProps({
  modelValue: String,
  placeholder: String,
  type: {
    type: String,
    default: 'text'
  }
});
defineEmits(['update:modelValue']);
</script>