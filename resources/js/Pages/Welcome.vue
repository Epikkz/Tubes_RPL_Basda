<template>
  <Head title="Cari Barang Hilang" />
  
  <div class="min-h-screen bg-grid-pattern flex flex-col items-center justify-center p-6 text-center">
    
    <!-- Header Title -->
    <h1 class="font-comic text-retro-cream text-5xl md:text-8xl tracking-wider leading-tight mb-8 drop-shadow-brutal">
      CARI BARANG HILANG? <br /> SIMPEL.
    </h1>

    <!-- Navigasi Action -->
    <div class="flex flex-col sm:flex-row gap-6 w-full max-w-md justify-center items-center">
      <Link 
        :href="route('login')" 
        class="w-full sm:w-auto bg-retro-orange text-white font-comic text-3xl px-12 py-3 rounded-none border-4 border-black shadow-brutal hover:translate-y-1 hover:shadow-brutal-sm transition-all text-center uppercase"
      >
        LOGIN
      </Link>
      
      <Link 
        :href="route('register')" 
        class="w-full sm:w-auto bg-retro-blue text-white font-comic text-3xl px-12 py-3 rounded-none border-4 border-black shadow-brutal hover:translate-y-1 hover:shadow-brutal-sm transition-all text-center uppercase"
      >
        REGISTER
      </Link>
    </div>

  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>