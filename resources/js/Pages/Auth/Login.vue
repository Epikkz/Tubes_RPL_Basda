<template>
  <div class="min-h-screen bg-grid-pattern flex flex-col items-center justify-center p-4">
    
    <!-- Judul Header -->
    <h1 class="font-comic text-retro-cream text-5xl md:text-8xl mb-12 drop-shadow-brutal text-center uppercase">
      LOGIN DULU NYAK
    </h1>

    <!-- Form Login -->
    <form @submit.prevent="submit" class="w-full max-w-lg flex flex-col gap-6">
      
      <!-- Input Email (Di gambar tertulis 'NAMA', bisa disesuaikan placeholder-nya) -->
      <div>
        <BrutalInput 
          v-model="form.email" 
          placeholder="NAMA / EMAIL" 
          type="email" 
          required
        />
        <div v-if="form.errors.email" class="text-retro-cream bg-retro-red mt-2 px-3 py-1 font-bold border-2 border-black inline-block">
          {{ form.errors.email }}
        </div>
      </div>

      <!-- Input Password -->
      <div>
        <BrutalInput 
          v-model="form.password" 
          placeholder="PASSWORD" 
          type="password" 
          required
        />
        <div v-if="form.errors.password" class="text-retro-cream bg-retro-red mt-2 px-3 py-1 font-bold border-2 border-black inline-block">
          {{ form.errors.password }}
        </div>
      </div>

      <!-- Tombol Login -->
      <div class="flex justify-center mt-6">
        <button 
          type="submit" 
          :disabled="form.processing"
          class="bg-retro-blue text-white font-comic text-4xl px-16 py-3 rounded-full border-4 border-black shadow-brutal hover:translate-y-1 hover:shadow-brutal-sm transition-all uppercase disabled:opacity-50"
        >
          LOGIN
        </button>
      </div>

    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import BrutalInput from '@/Components/BrutalInput.vue'; // Komponen input yang dibuat sebelumnya

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>