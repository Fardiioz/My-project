<template>
  <div class="manga-grid">
    <h1>📚 Komik Terbaru</h1>
    <div v-if="loading">Memuat komik...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else class="grid">
      <div v-for="manga in mangas" :key="manga.id" class="manga-card">
        <img
          v-if="manga.cover_url"
          :src="manga.cover_url"
          :alt="manga.title"
          class="cover"
        />
        <div class="info">
          <h3>{{ manga.title }}</h3>
          <p class="status">{{ manga.status }}</p>
          <p class="year" v-if="manga.year">{{ manga.year }}</p>
          <p class="rating">⭐ {{ manga.rating }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/api/client.js";

const mangas = ref([]);
const loading = ref(true);
const error = ref(null);

onMounted(async () => {
  try {
    const data = await api("/mangas"); // otomatis ke http://localhost:8000/api/mangas
    // Laravel pagination: data.data, atau langsung array
    mangas.value = data.data || data;
  } catch (err) {
    error.value = err.message;
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.manga-grid {
  padding: 2rem;
}
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 1.5rem;
  margin-top: 1rem;
}
.manga-card {
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.cover {
  width: 100%;
  height: 240px;
  object-fit: cover;
  background: #f5f5f5;
}
.info {
  padding: 0.75rem;
}
.info h3 {
  font-size: 1rem;
  margin: 0 0 0.5rem;
  line-height: 1.3;
}
.status,
.year,
.rating {
  font-size: 0.85rem;
  color: #666;
  margin: 0.25rem 0;
}
.error {
  color: #e53e3e;
  font-weight: bold;
}
</style>
