<template>
  <Head title="Verifikasi Klaim & Serah Terima" />
  <div class="min-h-screen bg-retro-dark p-4 md:p-8">
    <div class="bg-black/40 border-4 border-black rounded-3xl p-6 md:p-10 shadow-brutal">
      <div class="flex justify-between items-center mb-6 border-b-4 border-black pb-4">
        <h1 class="font-comic text-retro-cream text-5xl uppercase">SERAH TERIMA KLAIM ✅</h1>
        <Link :href="route('admin.dashboard')" class="bg-retro-red text-white font-comic text-xl px-6 py-2 border-4 border-black shadow-brutal">KEMBALI</Link>
      </div>

      <div class="grid grid-cols-1 gap-6">
        <div v-for="klaim in klaims" :key="klaim.id" class="bg-white border-4 border-black p-4 shadow-brutal">
          <div class="flex justify-between items-center mb-2">
            <h3 class="font-comic text-2xl uppercase">KLAIM ID #{{ klaim.id }}</h3>
            <span class="bg-yellow-400 font-comic px-3 border border-black">{{ klaim.status }}</span>
          </div>
          <p class="font-comic text-lg">Barang: <span class="text-retro-blue">{{ klaim.laporan_penemuan.nama_barang }}</span></p>
          <p class="font-comic text-lg">Pemilik: {{ klaim.laporan_kehilangan.user.name }} | Penemu: {{ klaim.laporan_penemuan.user.name }}</p>
        </div>
        <div v-if="klaims.length === 0" class="text-center py-10 bg-white border-4 border-black font-comic text-2xl text-gray-500">TIDAK ADA KLAIM.</div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps({ klaims: Array });
</script>