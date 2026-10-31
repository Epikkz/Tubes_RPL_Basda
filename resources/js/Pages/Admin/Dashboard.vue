<template>
  <Head title="Super Admin Dashboard" />

  <div class="min-h-screen bg-retro-dark flex flex-col p-4 md:p-8">
    
    <!-- Top Header Admin -->
    <div class="bg-black/40 border-4 border-black rounded-3xl p-6 md:p-10 mb-8 flex justify-between items-center shadow-brutal relative z-20">
      <div>
        <h1 class="font-comic text-retro-cream text-5xl md:text-7xl uppercase tracking-wide">
          SUPER ADMIN.
        </h1>
        <p class="font-comic text-retro-orange text-xl mt-2">SISTEM KONTROL PUSAT LOST & FOUND</p>
      </div>

      <!-- Admin Profile & Logout -->
      <div class="relative">
        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-3 bg-white px-6 py-3 border-4 border-black shadow-brutal hover:bg-gray-100 transition-all cursor-pointer">
          <!-- PERBAIKAN ERROR: Tambahkan ?. agar aman saat loading -->
          <span class="font-comic text-2xl uppercase">👑 {{ $page.props.auth?.user?.name || 'ADMIN' }}</span>
          <span class="text-sm">▼</span>
        </button>

        <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-full bg-white border-4 border-black shadow-brutal py-2 flex flex-col z-50">
          <Link :href="route('logout')" method="post" as="button" class="w-full text-left px-4 py-2 font-comic text-2xl text-retro-red hover:bg-retro-cream uppercase transition-all">
            LOGOUT 🚪
          </Link>
        </div>
      </div>
    </div>

    <!-- Main Admin Layout (Sidebar Nav & Stats) -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      
      <!-- Menu Sidebar Admin -->
      <div class="lg:col-span-1 flex flex-col gap-4">
        <Link :href="route('admin.dashboard')" class="bg-retro-orange text-white font-comic text-2xl px-6 py-4 border-4 border-black shadow-brutal uppercase text-center hover:translate-x-2 transition-all">
          📊 DASBOR UTAMA
        </Link>
        <Link :href="route('admin.master.data')" class="bg-white text-black font-comic text-2xl px-6 py-4 border-4 border-black shadow-brutal uppercase hover:bg-gray-200 transition-all">
          👥 MASTER DATA
        </Link>
        <Link :href="route('admin.penemuan')" class="bg-white text-black font-comic text-2xl px-6 py-4 border-4 border-black shadow-brutal uppercase hover:bg-gray-200 transition-all">
          📦 VERIFIKASI PENEMUAN
        </Link>
        <Link :href="route('admin.klaim')" class="bg-white text-black font-comic text-2xl px-6 py-4 border-4 border-black shadow-brutal uppercase hover:bg-gray-200 transition-all">
          ✅ VERIFIKASI KLAIM
        </Link>
        <Link :href="route('admin.pencocokan')" class="bg-white text-black font-comic text-2xl px-6 py-4 border-4 border-black shadow-brutal uppercase hover:bg-gray-200 transition-all">
          🧠 AI PENCOCOKAN
        </Link>
      </div>

      <!-- Statistik Utama (Overview) -->
      <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Stat Card 1 -->
        <div class="bg-retro-blue border-4 border-black p-6 shadow-brutal text-white flex flex-col justify-between min-h-[200px]">
          <h3 class="font-comic text-2xl uppercase">Total Pengguna</h3>
          <!-- Tambahkan ?. dan default value 0 -->
          <p class="font-comic text-7xl text-right mt-4">{{ stats?.total_users || 0 }}</p>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-retro-red border-4 border-black p-6 shadow-brutal text-white flex flex-col justify-between min-h-[200px]">
          <h3 class="font-comic text-2xl uppercase">Penemuan Menunggu</h3>
          <p class="font-comic text-7xl text-right mt-4">{{ stats?.total_temuan_menunggu || 0 }}</p>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-green-500 border-4 border-black p-6 shadow-brutal text-black flex flex-col justify-between min-h-[200px]">
          <h3 class="font-comic text-2xl uppercase">Klaim Baru</h3>
          <p class="font-comic text-7xl text-right mt-4">{{ stats?.total_klaim_baru || 0 }}</p>
        </div>
        
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  stats: Object
});

const dropdownOpen = ref(false);
</script>