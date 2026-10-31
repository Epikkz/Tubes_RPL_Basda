<template>
  <Head title="Master Data Admin" />
  <div class="min-h-screen bg-retro-dark p-4 md:p-8">
    <div class="bg-black/40 border-4 border-black rounded-3xl p-6 md:p-10 mb-8 shadow-brutal">
      <div class="flex justify-between items-center mb-6 border-b-4 border-black pb-4">
        <h1 class="font-comic text-retro-cream text-5xl uppercase">MASTER DATA 👥</h1>
        <Link :href="route('admin.dashboard')" class="bg-retro-red text-white font-comic text-xl px-6 py-2 border-4 border-black shadow-brutal">KEMBALI</Link>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Tabel User -->
        <div class="bg-white border-4 border-black p-4 shadow-brutal h-96 overflow-y-auto">
          <h2 class="font-comic text-2xl mb-4 bg-retro-blue text-white inline-block px-4 py-1 border-2 border-black">DAFTAR PENGGUNA</h2>
          <ul>
            <li v-for="user in users" :key="user.id" class="border-b-2 border-black py-2 font-comic text-lg flex justify-between">
              <span>{{ user.name }} ({{ user.role }})</span>
              <span class="text-retro-orange">LVL {{ user.bounty_level || 1 }}</span>
            </li>
          </ul>
        </div>
        
        <!-- Tabel Kategori -->
        <div class="bg-white border-4 border-black p-4 shadow-brutal h-96 overflow-y-auto">
          <h2 class="font-comic text-2xl mb-4 bg-retro-orange text-white inline-block px-4 py-1 border-2 border-black">KATEGORI BARANG</h2>
          <ul>
            <li v-for="kat in kategoris" :key="kat.id" class="border-b-2 border-black py-2 font-comic text-lg">
              📦 {{ kat.nama_kategori }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps({ users: Array, kategoris: Array });
</script>