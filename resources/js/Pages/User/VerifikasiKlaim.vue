<template>
  <Head title="Verifikasi Klaim Masuk" />

  <div class="min-h-screen bg-retro-dark p-4 md:p-8">
    <div class="max-w-5xl mx-auto bg-black/40 border-4 border-black rounded-3xl p-6 md:p-10">
      
      <!-- Top Header -->
      <div class="flex justify-between items-center mb-8 border-b-4 border-black pb-4">
        <div>
          <h1 class="font-comic text-retro-cream text-4xl md:text-6xl uppercase">
            VERIFIKASI KLAIM MASUK 📋
          </h1>
          <p class="font-comic text-retro-orange text-xl">CEK JAWABAN PEMILIK BARANG SEBELUM MENYERAHKAN BARANG</p>
        </div>
        <Link :href="route('dashboard')" class="bg-retro-red text-white font-comic text-2xl px-6 py-2 border-4 border-black shadow-brutal hover:translate-y-1 transition-all">
          KEMBALI
        </Link>
      </div>

      <!-- List Klaim Masuk -->
      <div v-if="klaimMasuk.length > 0" class="flex flex-col gap-6">
        <div 
          v-for="item in klaimMasuk" 
          :key="item.id" 
          class="bg-retro-cream border-4 border-black p-6 shadow-brutal"
        >
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b-2 border-black pb-4 mb-4 gap-2">
            <div>
              <h3 class="font-comic text-3xl uppercase text-retro-blue">
                BARANG TEMUAN: {{ item.laporan_penemuan.nama_barang }}
              </h3>
              <p class="font-comic text-xl text-gray-700">
                PENGLAIM: <span class="text-retro-orange">{{ item.laporan_kehilangan.user.name }}</span> ({{ item.laporan_kehilangan.user.email }})
              </p>
            </div>
            
            <span :class="[
              'font-comic text-2xl px-4 py-1 border-2 border-black uppercase',
              item.status === 'disetujui' ? 'bg-green-500 text-white' : 
              item.status === 'ditolak' ? 'bg-retro-red text-white' : 'bg-yellow-400 text-black'
            ]">
              STATUS: {{ item.status }}
            </span>
          </div>

          <!-- Jawaban Verifikasi Pemilik -->
          <div class="mb-6">
            <h4 class="font-comic text-2xl mb-3 text-black">JAWABAN VERIFIKASI PEMILIK:</h4>
            <div class="flex flex-col gap-3">
              <div 
                v-for="jwb in item.jawaban_klaim" 
                :key="jwb.id" 
                class="bg-white p-3 border-2 border-black"
              >
                <p class="font-comic text-lg text-gray-500">❓ {{ jwb.pertanyaan_verifikasi ? jwb.pertanyaan_verifikasi.pertanyaan : 'Pertanyaan' }}</p>
                <p class="font-comic text-2xl text-black">💬 "{{ jwb.jawaban }}"</p>
              </div>
            </div>
          </div>

          <!-- Tombol Approve / Reject -->
          <div v-if="item.status === 'dalam_klaim'" class="flex gap-4 justify-end">
            <button 
              @click="respondKlaim(item.id, 'ditolak')"
              class="bg-retro-red text-white font-comic text-2xl px-6 py-2 border-4 border-black shadow-brutal hover:bg-red-700 transition-all uppercase"
            >
              ❌ TOLAK KLAIM
            </button>
            <button 
              @click="respondKlaim(item.id, 'disetujui')"
              class="bg-green-600 text-white font-comic text-2xl px-6 py-2 border-4 border-black shadow-brutal hover:bg-green-700 transition-all uppercase"
            >
              ✅ SETUJUI & BERI KONTAR
            </button>
          </div>

          <!-- Link Kontak Pribadi WhatsApp jika sudah disetujui -->
          <div v-else-if="item.status === 'disetujui'" class="bg-green-100 border-2 border-black p-4 flex justify-between items-center">
            <span class="font-comic text-xl text-green-900">KLAIM TELAH DISETUJUI! HUBUNGI PENGLAIM VIA WHATSAPP:</span>
            <a 
              :href="'https://wa.me/' + (item.laporan_kehilangan.user.no_hp || '')" 
              target="_blank"
              class="bg-green-600 text-white font-comic text-xl px-4 py-2 border-2 border-black shadow-brutal hover:bg-green-700"
            >
              HUBUNGI WHATSAPP 📱
            </a>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-16 bg-white border-4 border-black shadow-brutal">
        <h2 class="font-comic text-4xl text-gray-600">BELUM ADA KLAIM MASUK UNTUK BARANG TEMUANMU DETEKTIF!</h2>
      </div>

    </div>
  </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
  klaimMasuk: Array
});

const respondKlaim = (id, status) => {
  if (confirm(`Apakah kamu yakin ingin ${status} klaim ini?`)) {
    router.post(route('verifikasi.respond', id), { status });
  }
};
</script>