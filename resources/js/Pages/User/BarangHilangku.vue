<template>
  <Head title="Barang Hilangku" />

  <div class="min-h-screen bg-retro-dark p-4 md:p-8">
    <div class="max-w-5xl mx-auto bg-black/40 border-4 border-black rounded-3xl p-6 md:p-10">
      
      <!-- Top Header -->
      <div class="flex justify-between items-center mb-8 border-b-4 border-black pb-4">
        <div>
          <h1 class="font-comic text-retro-cream text-4xl md:text-6xl uppercase">BARANG HILANGKU 📦</h1>
          <p class="font-comic text-retro-orange text-xl">PANTAU STATUS PENCARIAN & KLAIM BARANG HILANGMU</p>
        </div>
        <Link :href="route('dashboard')" class="bg-retro-red text-white font-comic text-2xl px-6 py-2 border-4 border-black shadow-brutal hover:translate-y-1 transition-all">KEMBALI</Link>
      </div>

      <!-- List Barang -->
      <div v-if="myReports.length > 0" class="grid grid-cols-1 gap-6">
        <div v-for="item in myReports" :key="item.id" class="bg-retro-cream border-4 border-black p-6 shadow-brutal flex flex-col md:flex-row gap-6 justify-between items-start">
          
          <div class="flex flex-col sm:flex-row gap-4 items-start w-full">
            <div class="w-28 h-28 bg-gray-200 border-2 border-black flex items-center justify-center font-comic text-xl text-black overflow-hidden flex-shrink-0">
              <img v-if="item.foto" :src="'/storage/' + item.foto" class="w-full h-full object-cover" />
              <span v-else>IMG</span>
            </div>
            <div class="flex-1">
              <span class="bg-retro-blue text-white font-comic text-sm px-2 py-0.5 border border-black uppercase">{{ item.kategori ? item.kategori.nama_kategori : 'Umum' }}</span>
              <h3 class="font-comic text-3xl uppercase text-black mt-1">{{ item.nama_barang }}</h3>
              <p class="font-comic text-lg text-gray-700 mb-1">{{ item.deskripsi }}</p>
              <button 
                type="button"
                @click="hapusBarang(item.id)" 
                class="bg-retro-red text-white font-comic text-lg px-4 py-1 mt-2 border-2 border-black hover:bg-red-700 transition-all uppercase shadow-brutal"
              >
                🗑️ Hapus Laporan
              </button>
            </div>
          </div>

          <!-- Status Penemuan (Diperbarui Logikanya) -->
          <div class="w-full md:w-80 bg-white border-2 border-black p-4 flex flex-col justify-between">
            <h4 class="font-comic text-xl text-black border-b border-black pb-1 mb-2">STATUS PENEMUAN:</h4>
            
            <!-- Jika sudah ada klaim -->
            <div v-if="item.klaim && item.klaim.length > 0" class="flex flex-col gap-2">
              <div v-for="k in item.klaim" :key="k.id" class="flex flex-col gap-1">
                <span :class="['font-comic text-lg px-2 py-1 border border-black text-center uppercase', k.status === 'disetujui' ? 'bg-green-500 text-white' : k.status === 'ditolak' ? 'bg-retro-red text-white' : 'bg-yellow-400 text-black']">
                  {{ k.status === 'disetujui' ? 'DITEMUKAN & DISETUJUI!' : k.status === 'ditolak' ? 'KLAIM DITOLAK' : 'MENUNGGU VERIFIKASI' }}
                </span>
                <a v-if="k.status === 'disetujui' && k.laporan_penemuan && k.laporan_penemuan.user" :href="'https://wa.me/' + (k.laporan_penemuan.user.no_hp || '')" target="_blank" class="bg-green-600 text-white font-comic text-center text-lg py-1 border-2 border-black shadow-brutal hover:bg-green-700 transition-all uppercase block mt-2">
                  📱 CHAT PENEMU
                </a>
              </div>
            </div>

            <!-- Jika belum ada klaim, TAPI AI Matrix menemukan kecocokan -->
            <div v-else-if="item.matches && item.matches.length > 0" class="bg-blue-100 border-2 border-blue-900 p-3 mb-3">
              <p class="font-comic text-sm text-blue-900 text-center uppercase">
                🚨 AI Menemukan <strong>{{ item.matches.length }}</strong> Kecocokan!<br>Cek menu AI MATCHING MATRIX.
              </p>
            </div>

            <!-- Jika kosong total -->
            <div v-else class="text-center py-2 text-gray-500 font-comic text-lg">
              Belum ada temuan yang cocok.
            </div>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-16 bg-white border-4 border-black shadow-brutal">
        <h2 class="font-comic text-4xl text-gray-600">KAMU BELUM PERNAH MEMBUAT LAPORAN BARANG HILANG!</h2>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
  myReports: Array
});

const hapusBarang = (id) => {
  if (confirm('Apakah kamu yakin ingin menghapus laporan barang hilang ini?')) {
    // Menggunakan URL langsung agar kebal terhadap error Ziggy route cache
    router.delete(`/kehilangan/${id}`, {
      preserveScroll: true,
      onSuccess: () => {
        alert('Barang berhasil dihapus!');
      }
    });
  }
};
</script>