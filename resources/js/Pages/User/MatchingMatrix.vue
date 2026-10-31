<template>
  <Head title="AI Matching Matrix" />

  <div class="min-h-screen bg-retro-dark p-4 md:p-8">
    <div class="max-w-6xl mx-auto bg-black/40 border-4 border-black rounded-3xl p-6 md:p-10">
      
      <!-- Top Header -->
      <div class="flex justify-between items-center mb-8 border-b-4 border-black pb-4">
        <div>
          <h1 class="font-comic text-retro-cream text-4xl md:text-6xl uppercase">
            AI MATCHING MATRIX 🔍
          </h1>
          <p class="font-comic text-retro-orange text-xl">DETEKSI SAHABAT BARANG HILANGMU BERDASARKAN SKOR KECOCOKAN</p>
        </div>
        <Link :href="route('dashboard')" class="bg-retro-red text-white font-comic text-2xl px-6 py-2 border-4 border-black shadow-brutal hover:translate-y-1 transition-all">
          KEMBALI
        </Link>
      </div>

      <!-- Daftar Hasil Matching -->
      <div v-if="matches.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div 
          v-for="item in matches" 
          :key="item.id" 
          class="bg-retro-cream border-4 border-black p-6 shadow-brutal flex flex-col justify-between"
        >
          <div>
            <div class="flex justify-between items-center mb-4">
              <span class="bg-retro-blue text-white font-comic text-2xl px-4 py-1 border-2 border-black">
                MATCH SKOR: {{ item.skor }}%
              </span>
              <span class="font-comic text-xl text-gray-700">STATUS: {{ item.status }}</span>
            </div>

            <div class="grid grid-cols-2 gap-4 bg-white border-2 border-black p-4 mb-4">
              <div>
                <p class="font-comic text-retro-orange text-lg">BARANGMU YANG HILANG:</p>
                <h3 class="font-comic text-2xl uppercase">{{ item.laporan_kehilangan.nama_barang }}</h3>
                <p class="text-sm text-gray-600">{{ item.laporan_kehilangan.deskripsi }}</p>
              </div>

              <div class="border-l-2 border-black pl-4">
                <p class="font-comic text-retro-blue text-lg">BARANG DITEMUKAN:</p>
                <h3 class="font-comic text-2xl uppercase">{{ item.laporan_penemuan.nama_barang }}</h3>
                <p class="text-sm text-gray-600">{{ item.laporan_penemuan.deskripsi }}</p>
              </div>
            </div>
          </div>

          <button 
            @click="openKlaimModal(item)"
            class="w-full bg-retro-orange text-white font-comic text-2xl py-3 border-4 border-black shadow-brutal hover:bg-orange-600 transition-all uppercase"
          >
            AJUKAN KLAIM & JAWAB VERIFIKASI
          </button>
        </div>
      </div>

      <div v-else class="text-center py-16 bg-white border-4 border-black shadow-brutal">
        <h2 class="font-comic text-4xl text-gray-600">BELUM ADA BARANG YANG COCOK SAAT INI DETEKTIF!</h2>
      </div>

    </div>

    <!-- Modal Form Jawab Verifikasi Klaim -->
    <div v-if="selectedMatch" class="fixed inset-0 bg-black/70 flex items-center justify-center p-4 z-50">
      <div class="bg-retro-cream border-4 border-black p-8 shadow-brutal w-full max-w-xl relative max-h-[90vh] overflow-y-auto">
        <h2 class="font-comic text-4xl mb-2">JAWAB PERTANYAAN VERIFIKASI</h2>
        <p class="font-comic text-retro-orange text-xl mb-6">Buktikan bahwa barang temuan ini memang milikmu!</p>

        <form @submit.prevent="submitKlaimForm" class="flex flex-col gap-4">
          <div 
            v-for="q in selectedMatch.laporan_penemuan.pertanyaan_verifikasi" 
            :key="q.id"
            class="bg-white p-4 border-2 border-black"
          >
            <label class="font-comic text-xl block mb-2 text-retro-blue">❓ {{ q.pertanyaan }}</label>
            <input 
              v-model="klaimForm.jawaban[q.id]" 
              type="text" 
              required 
              class="w-full border-2 border-black p-2 font-comic text-lg"
              placeholder="Jawaban bukti kamu..."
            />
          </div>

          <div class="flex gap-4 justify-end mt-4">
            <button type="button" @click="selectedMatch = null" class="bg-retro-red text-white font-comic text-2xl px-6 py-2 border-4 border-black shadow-brutal">BATAL</button>
            <button type="submit" :disabled="klaimForm.processing" class="bg-retro-blue text-white font-comic text-2xl px-6 py-2 border-4 border-black shadow-brutal">KIRIM JAWABAN</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
  matches: Array
});

const selectedMatch = ref(null);

const klaimForm = useForm({
  laporan_kehilangan_id: null,
  laporan_penemuan_id: null,
  jawaban: {}
});

const openKlaimModal = (matchItem) => {
  selectedMatch.value = matchItem;
  klaimForm.laporan_kehilangan_id = matchItem.laporan_kehilangan_id;
  klaimForm.laporan_penemuan_id = matchItem.laporan_penemuan_id;
  klaimForm.jawaban = {};
};

const submitKlaimForm = () => {
  klaimForm.post(route('matching.klaim'), {
    onSuccess: () => {
      selectedMatch.value = null;
      alert('Klaim & Jawaban Berhasil Dikirim!');
    }
  });
};
</script>