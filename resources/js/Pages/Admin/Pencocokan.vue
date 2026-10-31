<template>
  <Head title="AI Pencocokan Data" />
  <div class="min-h-screen bg-retro-dark p-4 md:p-8">
    <div class="bg-black/40 border-4 border-black rounded-3xl p-6 md:p-10 shadow-brutal">
      <div class="flex justify-between items-center mb-6 border-b-4 border-black pb-4">
        <h1 class="font-comic text-retro-cream text-5xl uppercase">HASIL AI MATRIX 🧠</h1>
        <Link :href="route('admin.dashboard')" class="bg-retro-red text-white font-comic text-xl px-6 py-2 border-4 border-black shadow-brutal">KEMBALI</Link>
      </div>

      <div class="grid grid-cols-1 gap-6">
        <div v-for="match in matches" :key="match.id" class="bg-retro-cream border-4 border-black p-4 shadow-brutal flex justify-between items-center">
          <div>
            <p class="font-comic text-xl">Hilang: <span class="text-retro-orange">{{ match.laporan_kehilangan.nama_barang }}</span></p>
            <p class="font-comic text-xl">Temuan: <span class="text-retro-blue">{{ match.laporan_penemuan.nama_barang }}</span></p>
          </div>
          <div class="flex items-center gap-4">
            <h2 class="font-comic text-4xl bg-white border-4 border-black px-4 py-2">{{ match.skor }}%</h2>
          </div>
        </div>
        <div v-if="matches.length === 0" class="text-center py-10 bg-white border-4 border-black font-comic text-2xl text-gray-500">AI BELUM MENEMUKAN KECOCOKAN.</div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps({ matches: Array });
</script>