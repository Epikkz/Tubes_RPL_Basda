<template>
  <!-- Properti 'rotation' digunakan untuk memutar card (misal: '-rotate-6', 'rotate-12') -->
  <div :class="['relative bg-white p-3 pb-10 border-4 border-black shadow-brutal w-48 transition-transform duration-200 hover:scale-105 hover:z-10', rotation]">
    
    <!-- Pin Merah di atas tengah -->
    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 w-5 h-5 bg-retro-red rounded-full border-2 border-black shadow-brutal-sm"></div>
    
    <!-- Area Gambar -->
    <div class="bg-gray-100 w-full h-40 border-4 border-black flex items-center justify-center overflow-hidden">
      <!-- Jika ada gambar props, tampilkan img. Jika tidak, tampilkan placeholder text -->
      <img v-if="imageUrl" :src="imageUrl" class="w-full h-full object-cover" alt="Barang Hilang" />
      <span v-else class="font-comic text-3xl text-black">IMG</span>
    </div>
  </div>
</template>

<script setup>
defineProps({
  imageUrl: {
    type: String,
    default: null
  },
  rotation: {
    type: String,
    default: '-rotate-6' // Default miring ke kiri
  }
});
</script>