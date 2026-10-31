<template>
  <Head title="Dashboard Detektif" />

  <div class="min-h-screen bg-retro-dark flex flex-col md:flex-row gap-6 md:gap-0 p-4 md:p-0 overflow-hidden">
    <!-- SIDE COLOR TABS (flush left like the mockup) -->
    <div class="flex md:flex-col gap-4 md:gap-12 justify-start md:justify-center items-center md:pt-0 md:w-28 shrink-0">
      <div class="side-tab bg-retro-orange" style="--d: 0ms"></div>
      <div class="side-tab bg-retro-blue" style="--d: 120ms"></div>
      <div class="side-tab bg-retro-red" style="--d: 240ms"></div>
    </div>

    <!-- MAIN PANEL -->
    <div class="panel flex-1 bg-black/60 border-4 border-black rounded-3xl md:rounded-r-none md:mt-8 p-6 md:p-12 relative flex flex-col gap-10">
      <!-- HEADER -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative z-30">
        <h1 class="font-comic text-retro-cream text-5xl md:text-8xl uppercase tracking-wide leading-none title-in">
          Overview<span class="text-retro-orange">.</span>
        </h1>

        <div ref="dropdownRef" class="relative sm:mr-8 fade-down" style="--d: 300ms">
          <button
            @click.stop="dropdownOpen = !dropdownOpen"
            class="flex items-center gap-0 cursor-pointer group"
            aria-haspopup="true"
            :aria-expanded="dropdownOpen"
          >
            <span class="avatar z-10">
              <svg viewBox="0 0 24 24" class="w-8 h-8 text-gray-400" fill="currentColor">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8z" />
              </svg>
            </span>
            <span class="-ml-5 pl-7 pr-5 py-1 bg-white rounded-full font-comic text-2xl uppercase group-hover:bg-retro-cream transition-colors">
              {{ userName }}
              <span class="text-xs inline-block transition-transform duration-300" :class="dropdownOpen ? 'rotate-180' : ''">▼</span>
            </span>
          </button>

          <Transition name="drop">
            <div
              v-if="dropdownOpen"
              class="absolute right-0 mt-3 w-52 bg-white border-4 border-black shadow-brutal py-2 flex flex-col z-50 origin-top-right"
            >
              <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="w-full text-left px-4 py-2 font-comic text-2xl text-retro-red hover:bg-retro-cream uppercase transition-all hover:pl-6"
              >
                LOGOUT 🚪
              </Link>
            </div>
          </Transition>
        </div>
      </div>

      <!-- CONTENT -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
        <!-- MENU -->
        <nav class="flex flex-col gap-7">
          <Link :href="route('dashboard')" class="menu-btn bg-retro-orange is-active" style="--d: 150ms">
            Mading Barang Hilang
          </Link>

          <Link :href="route('barangku.index')" class="menu-btn bg-retro-red relative" style="--d: 250ms">
            Barang Hilangku 📦
            <span
              v-if="unreadCount > 0"
              class="absolute -top-4 -right-4 bg-white text-retro-red rounded-full min-w-[2rem] h-8 px-1 flex items-center justify-center text-lg border-4 border-black badge-pulse"
            >
              {{ unreadCount }}
            </span>
          </Link>

          <Link :href="route('verifikasi.index')" class="menu-btn bg-green-600" style="--d: 350ms">
            Verifikasi Klaim Masuk 📋
          </Link>

          <Link :href="route('matching.index')" class="menu-btn bg-retro-blue" style="--d: 450ms">
            AI Matching Matrix 🔍
          </Link>

          <button type="button" @click="showModal = true" class="menu-btn bg-retro-orange flex justify-between items-center" style="--d: 550ms">
            <span>Laporan Kehilangan</span><span class="plus">+</span>
          </button>

          <button type="button" @click="showPenemuanModal = true" class="menu-btn bg-retro-blue flex justify-between items-center" style="--d: 650ms">
            <span>Laporan Temuan</span><span class="plus">+</span>
          </button>
        </nav>

        <!-- MADING POLAROID -->
        <div class="board bg-gray-300 border-4 border-black p-6 shadow-brutal min-h-[380px] max-h-[600px] overflow-y-auto relative">
          <div class="pin"></div>
          <div v-if="reports && reports.length > 0" class="flex flex-wrap gap-5 justify-center pt-4">
            <button
              v-for="(item, index) in reports"
              :key="item.id"
              type="button"
              @click="openDetailModal(item)"
              class="polaroid"
              :style="{ '--rot': index % 2 === 0 ? '-6deg' : '6deg', '--d': 200 + index * 70 + 'ms' }"
            >
              <div class="w-full h-24 bg-gray-200 border-2 border-black flex items-center justify-center font-comic text-xl text-black overflow-hidden">
                <img v-if="item.foto" :src="'/storage/' + item.foto" :alt="item.nama_barang" class="w-full h-full object-cover" />
                <span v-else>IMG</span>
              </div>
              <p class="font-comic text-lg text-center mt-2 truncate">{{ item.nama_barang }}</p>
            </button>
          </div>
          <div v-else class="h-full min-h-[300px] flex flex-col items-center justify-center font-comic text-2xl text-gray-600 text-center">
            <span class="text-6xl mb-3 float-slow">🔎</span>
            <span>BELUM ADA LAPORAN BARANG HILANG.</span>
          </div>
        </div>

        <!-- PROFILE BOUNTY CARD -->
        <div class="bounty-wrap">
          <div class="bounty-card bg-white border-4 border-black p-6 shadow-brutal flex flex-col justify-between">
            <div class="flex items-center gap-4">
              <div class="w-20 h-20 bg-retro-orange border-4 border-black flex items-center justify-center text-4xl shrink-0">🕵️</div>
              <div class="min-w-0">
                <p class="font-comic text-xl text-gray-500 leading-none">HELLO, IM</p>
                <h2 class="font-comic text-4xl uppercase leading-tight break-words">{{ userName }}</h2>
              </div>
            </div>
            <div class="mt-6">
              <div class="h-4 w-full bg-retro-orange/40 border-2 border-black overflow-hidden">
                <div class="xp-fill h-full bg-retro-blue" :style="{ width: xpWidth + '%' }"></div>
              </div>
              <div class="flex justify-between items-end mt-2">
                <h3 class="font-comic text-5xl leading-none">LEVEL {{ userLevel }}</h3>
                <span class="font-comic text-xl text-gray-600">{{ userXp }}/100 XP</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL DETAIL -->
    <Transition name="modal">
      <div v-if="selectedReport" class="overlay" @click.self="selectedReport = null">
        <div class="modal-card bg-white">
          <div class="w-full h-56 bg-gray-200 border-4 border-black mb-4 flex items-center justify-center overflow-hidden">
            <img v-if="selectedReport.foto" :src="'/storage/' + selectedReport.foto" :alt="selectedReport.nama_barang" class="w-full h-full object-cover" />
            <span v-else class="font-comic text-4xl text-gray-500">IMG</span>
          </div>
          <h2 class="font-comic text-4xl text-retro-orange uppercase mb-1">{{ selectedReport.nama_barang }}</h2>
          <p class="font-comic text-xl text-gray-600 mb-2">
            KATEGORI: {{ selectedReport.kategori ? selectedReport.kategori.nama_kategori : '-' }}
          </p>
          <p class="font-comic text-xl mb-4 bg-retro-cream p-4 border-2 border-black">{{ selectedReport.deskripsi }}</p>

          <div class="flex flex-col gap-3 mb-6">
            <button
              v-if="selectedReport.user_id !== currentUserId"
              @click="triggerLaporkanTemuanMirip(selectedReport)"
              class="modal-btn w-full bg-retro-blue text-white"
            >
              🔍 LAPORKAN TEMUAN YANG MIRIP
            </button>
          </div>

          <div class="flex justify-between items-center">
            <span class="font-comic text-lg text-gray-500">TGL: {{ selectedReport.tanggal_hilang }}</span>
            <button @click="selectedReport = null" class="modal-btn bg-retro-red text-white px-6">TUTUP</button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- MODAL BARANG HILANG -->
    <Transition name="modal">
      <div v-if="showModal" class="overlay" @click.self="showModal = false">
        <div class="modal-card bg-retro-cream max-h-[90vh] overflow-y-auto">
          <h2 class="font-comic text-4xl mb-6">LAPOR BARANG HILANG</h2>
          <form @submit.prevent="submitReport" class="flex flex-col gap-4">
            <input v-model="form.nama_barang" type="text" placeholder="Nama Barang" required class="field" />
            <div v-if="form.errors.nama_barang" class="err">{{ form.errors.nama_barang }}</div>

            <select v-model="form.kategori_id" required class="field">
              <option value="" disabled>Pilih Kategori</option>
              <option v-for="kat in kategoris" :key="kat.id" :value="kat.id">{{ kat.nama_kategori }}</option>
            </select>

            <input ref="fotoInput" type="file" @change="e => (form.foto = e.target.files[0] || null)" class="field bg-white" accept="image/*" />
            <div v-if="form.errors.foto" class="err">{{ form.errors.foto }}</div>

            <textarea v-model="form.deskripsi" placeholder="Deskripsi" required rows="3" class="field"></textarea>
            <input v-model="form.tanggal_hilang" type="date" required class="field" />

            <div class="flex gap-4 justify-end mt-4">
              <button type="button" @click="showModal = false" class="modal-btn bg-retro-red text-white px-6">BATAL</button>
              <button type="submit" :disabled="form.processing" class="modal-btn bg-retro-blue text-white px-6 disabled:opacity-60">
                {{ form.processing ? 'MENYIMPAN...' : 'SIMPAN' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>

    <!-- MODAL LAPOR TEMUAN -->
    <Transition name="modal">
      <div v-if="showPenemuanModal" class="overlay" @click.self="showPenemuanModal = false">
        <div class="modal-card bg-retro-cream max-h-[90vh] overflow-y-auto">
          <h2 class="font-comic text-4xl mb-6">LAPOR BARANG TEMUAN</h2>
          <form @submit.prevent="submitPenemuan" class="flex flex-col gap-4">
            <input v-model="penemuanForm.nama_barang" type="text" placeholder="Nama Barang" required class="field" />

            <select v-model="penemuanForm.kategori_id" required class="field">
              <option value="" disabled>Pilih Kategori</option>
              <option v-for="kat in kategoris" :key="kat.id" :value="kat.id">{{ kat.nama_kategori }}</option>
            </select>

            <input ref="penemuanFotoInput" type="file" @change="e => (penemuanForm.foto = e.target.files[0] || null)" class="field bg-white" accept="image/*" />

            <textarea v-model="penemuanForm.deskripsi" placeholder="Deskripsi Lokasi & Barang" required rows="3" class="field"></textarea>

            <div>
              <label class="font-comic text-xl block mb-1">PERTANYAAN VERIFIKASI UNTUK PEMILIK</label>
              <TransitionGroup name="list" tag="div">
                <div v-for="(q, index) in penemuanForm.pertanyaan" :key="index" class="flex gap-2 mb-2">
                  <input v-model="penemuanForm.pertanyaan[index]" type="text" required class="field flex-1 !text-lg" placeholder="Pertanyaan..." />
                  <button
                    type="button"
                    @click="removePertanyaan(index)"
                    :disabled="penemuanForm.pertanyaan.length <= 1"
                    class="bg-retro-red text-white font-comic px-3 border-2 border-black font-bold disabled:opacity-40 hover:brightness-110 transition"
                  >
                    X
                  </button>
                </div>
              </TransitionGroup>
              <button type="button" @click="addPertanyaan" class="bg-retro-blue text-white font-comic text-lg px-4 py-1 border-2 border-black hover:-translate-y-0.5 transition-transform">
                + Tambah
              </button>
            </div>

            <div class="flex gap-4 justify-end mt-4">
              <button type="button" @click="showPenemuanModal = false" class="modal-btn bg-retro-red text-white px-6">BATAL</button>
              <button type="submit" :disabled="penemuanForm.processing" class="modal-btn bg-retro-orange text-white px-6 disabled:opacity-60">
                {{ penemuanForm.processing ? 'MENGIRIM...' : 'KIRIM TEMUAN' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
  reports: { type: Array, default: () => [] },
  kategoris: { type: Array, default: () => [] },
  unreadCount: { type: Number, default: 0 },
});

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
const userName = computed(() => user.value?.name || 'DETEKTIF');
const currentUserId = computed(() => user.value?.id ?? null);
const userLevel = computed(() => user.value?.bounty_level || 1);
const userXp = computed(() => Math.min(100, Math.max(0, user.value?.bounty_xp || 0)));

// XP bar animates from 0 on mount
const xpWidth = ref(0);

const dropdownOpen = ref(false);
const dropdownRef = ref(null);
const showModal = ref(false);
const showPenemuanModal = ref(false);
const selectedReport = ref(null);
const fotoInput = ref(null);
const penemuanFotoInput = ref(null);

/* ---------- Forms ---------- */
const form = useForm({
  nama_barang: '',
  kategori_id: '',
  deskripsi: '',
  tanggal_hilang: '',
  foto: null,
});

const defaultQuestion = 'Apa warna casing / ciri khas barang tersebut?';
const penemuanForm = useForm({
  nama_barang: '',
  kategori_id: '',
  deskripsi: '',
  foto: null,
  pertanyaan: [defaultQuestion],
});

/* ---------- Actions ---------- */
const openDetailModal = (item) => {
  selectedReport.value = item;
};

const triggerLaporkanTemuanMirip = (report) => {
  penemuanForm.nama_barang = report.nama_barang;
  penemuanForm.kategori_id = report.kategori_id;
  selectedReport.value = null;
  showPenemuanModal.value = true;
};

const submitReport = () => {
  form.post(route('kehilangan.store'), {
    forceFormData: true,
    onSuccess: () => {
      showModal.value = false;
      form.reset();
      form.clearErrors();
      if (fotoInput.value) fotoInput.value.value = '';
    },
  });
};

const addPertanyaan = () => {
  penemuanForm.pertanyaan.push('');
};

const removePertanyaan = (index) => {
  if (penemuanForm.pertanyaan.length > 1) penemuanForm.pertanyaan.splice(index, 1);
};

const submitPenemuan = () => {
  penemuanForm.post(route('penemuan.store'), {
    forceFormData: true,
    onSuccess: () => {
      showPenemuanModal.value = false;
      penemuanForm.reset();
      penemuanForm.clearErrors();
      if (penemuanFotoInput.value) penemuanFotoInput.value.value = '';
    },
  });
};

/* ---------- UX: outside click, Esc, scroll lock ---------- */
const onDocClick = (e) => {
  if (dropdownOpen.value && dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    dropdownOpen.value = false;
  }
};

const onKey = (e) => {
  if (e.key !== 'Escape') return;
  dropdownOpen.value = false;
  selectedReport.value = null;
  showModal.value = false;
  showPenemuanModal.value = false;
};

watch([selectedReport, showModal, showPenemuanModal], () => {
  const anyOpen = !!selectedReport.value || showModal.value || showPenemuanModal.value;
  document.body.style.overflow = anyOpen ? 'hidden' : '';
});

onMounted(() => {
  document.addEventListener('click', onDocClick);
  document.addEventListener('keydown', onKey);
  setTimeout(() => (xpWidth.value = userXp.value), 700);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', onDocClick);
  document.removeEventListener('keydown', onKey);
  document.body.style.overflow = '';
});
</script>

<style scoped>
/* ---------- Entrance animations ---------- */
@keyframes slide-in-left {
  from { transform: translateX(-100%); opacity: 0; }
  to   { transform: translateX(0); opacity: 1; }
}
@keyframes slide-up {
  from { transform: translateY(40px); opacity: 0; }
  to   { transform: translateY(0); opacity: 1; }
}
@keyframes slide-down {
  from { transform: translateY(-20px); opacity: 0; }
  to   { transform: translateY(0); opacity: 1; }
}
@keyframes pop-in {
  0%   { transform: scale(0.3) rotate(calc(var(--rot) * 3)); opacity: 0; }
  70%  { transform: scale(1.08) rotate(var(--rot)); opacity: 1; }
  100% { transform: scale(1) rotate(var(--rot)); opacity: 1; }
}
@keyframes bounty-in {
  from { transform: translateX(120px) rotate(30deg); opacity: 0; }
  to   { transform: translateX(0) rotate(0); opacity: 1; }
}
@keyframes float {
  0%, 100% { transform: translateY(0) rotate(-1.5deg); }
  50%      { transform: translateY(-12px) rotate(1.5deg); }
}
@keyframes pulse-badge {
  0%, 100% { transform: scale(1); }
  50%      { transform: scale(1.2); }
}
@keyframes shine {
  from { transform: translateX(-120%) skewX(-20deg); }
  to   { transform: translateX(260%) skewX(-20deg); }
}

.panel { animation: slide-up 0.7s cubic-bezier(0.22, 1, 0.36, 1) both; }
.title-in { animation: slide-up 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.1s both; }
.fade-down { animation: slide-down 0.6s ease-out var(--d, 0ms) both; }
.float-slow { animation: float 4s ease-in-out infinite; display: inline-block; }
.badge-pulse { animation: pulse-badge 1.2s ease-in-out infinite; }

/* ---------- Side tabs ---------- */
.side-tab {
  width: 3rem;
  height: 4rem;
  border: 4px solid #000;
  box-shadow: 4px 4px 0 #000;
  animation: slide-in-left 0.6s cubic-bezier(0.22, 1, 0.36, 1) var(--d) both;
  transition: width 0.25s ease;
}
@media (min-width: 768px) {
  .side-tab {
    width: 6.5rem;
    height: 5rem;
    border-left: 0;
    border-radius: 0 0.5rem 0.5rem 0;
  }
  .side-tab:hover { width: 7.5rem; }
}

/* ---------- Menu buttons (white offset shadow like mockup) ---------- */
.menu-btn {
  position: relative;
  display: block;
  overflow: hidden;
  color: #fffbe0;
  font-family: inherit;
  font-size: 1.6rem;
  text-transform: uppercase;
  text-align: left;
  padding: 0.8rem 1.5rem;
  border: 0;
  box-shadow: 10px 10px 0 #fff;
  cursor: pointer;
  animation: slide-up 0.6s cubic-bezier(0.22, 1, 0.36, 1) var(--d, 0ms) both;
  transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.2s;
}
.menu-btn::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 35%;
  height: 100%;
  background: rgba(255, 255, 255, 0.25);
  transform: translateX(-120%) skewX(-20deg);
}
.menu-btn:hover {
  transform: translate(-3px, -3px);
  box-shadow: 13px 13px 0 #fff;
  filter: brightness(1.08);
}
.menu-btn:hover::after { animation: shine 0.7s ease; }
.menu-btn:active {
  transform: translate(8px, 8px);
  box-shadow: 2px 2px 0 #fff;
}
.menu-btn.is-active { transform: translateX(14px); }
.menu-btn.is-active:hover { transform: translate(11px, -3px); }
.menu-btn .plus {
  font-size: 2rem;
  line-height: 1;
  transition: transform 0.3s ease;
}
.menu-btn:hover .plus { transform: rotate(90deg) scale(1.2); }

/* ---------- Avatar ---------- */
.avatar {
  width: 5.5rem;
  height: 5.5rem;
  border-radius: 9999px;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s ease;
}
button:hover > .avatar { transform: rotate(-8deg) scale(1.06); }

/* ---------- Board + polaroids ---------- */
.board { animation: slide-up 0.7s cubic-bezier(0.22, 1, 0.36, 1) 0.3s both; }
.pin {
  position: absolute;
  top: -0.75rem;
  left: 50%;
  transform: translateX(-50%);
  width: 1.5rem;
  height: 1.5rem;
  background: #e03e3e;
  border: 2px solid #000;
  border-radius: 9999px;
  box-shadow: inset -3px -3px 0 rgba(0, 0, 0, 0.25);
}
.polaroid {
  width: 9rem;
  padding: 0.5rem 0.5rem 1.5rem;
  background: #fff;
  border: 4px solid #000;
  box-shadow: 5px 5px 0 #000;
  font-family: inherit;
  cursor: pointer;
  transform: rotate(var(--rot));
  animation: pop-in 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) var(--d) both;
  transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s;
}
.polaroid:hover {
  transform: rotate(0deg) scale(1.15);
  box-shadow: 9px 9px 0 #000;
  z-index: 20;
}

/* ---------- Bounty card ---------- */
.bounty-wrap {
  animation: bounty-in 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.5s both;
}
.bounty-card {
  transform: rotate(12deg);
  transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.bounty-card:hover { transform: rotate(0deg) scale(1.04); }
.xp-fill { transition: width 1.4s cubic-bezier(0.22, 1, 0.36, 1); }

/* ---------- Modals ---------- */
.overlay {
  position: fixed;
  inset: 0;
  z-index: 50;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  background: rgba(0, 0, 0, 0.75);
  backdrop-filter: blur(3px);
}
.modal-card {
  position: relative;
  width: 100%;
  max-width: 32rem;
  padding: 2rem;
  border: 4px solid #000;
  box-shadow: 10px 10px 0 #000;
}
.modal-btn {
  font-family: inherit;
  font-size: 1.5rem;
  padding: 0.5rem 1rem;
  border: 4px solid #000;
  box-shadow: 4px 4px 0 #000;
  transition: transform 0.12s ease, box-shadow 0.12s ease, filter 0.2s;
}
.modal-btn:hover:not(:disabled) { transform: translate(-2px, -2px); box-shadow: 6px 6px 0 #000; filter: brightness(1.1); }
.modal-btn:active:not(:disabled) { transform: translate(4px, 4px); box-shadow: 0 0 0 #000; }

.field {
  border: 4px solid #000;
  padding: 0.5rem;
  font-family: inherit;
  font-size: 1.25rem;
  outline: none;
  transition: box-shadow 0.15s ease, transform 0.15s ease;
}
.field:focus { box-shadow: 4px 4px 0 #000; transform: translate(-2px, -2px); }
.err { color: #e03e3e; font-family: inherit; margin-top: -0.5rem; }

.modal-enter-active, .modal-leave-active { transition: opacity 0.25s ease; }
.modal-enter-active .modal-card, .modal-leave-active .modal-card {
  transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s;
}
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-card { transform: scale(0.8) translateY(30px) rotate(-3deg); opacity: 0; }
.modal-leave-to .modal-card { transform: scale(0.9) translateY(20px); opacity: 0; }

.drop-enter-active, .drop-leave-active { transition: opacity 0.2s, transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
.drop-enter-from, .drop-leave-to { opacity: 0; transform: scale(0.85) translateY(-8px); }

.list-enter-active, .list-leave-active { transition: all 0.3s ease; }
.list-enter-from, .list-leave-to { opacity: 0; transform: translateX(30px); }

/* ---------- Reduced motion ---------- */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
}
</style>