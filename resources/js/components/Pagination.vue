<template>
   <div class="flex items-center gap-2 mt-4">
     <!-- Кнопка "Назад" -->
     <button
       class="px-3 py-1 border rounded disabled:opacity-50"
       :class="{'hover:bg-gray-200 cursor-pointer': currentPage > 1 }"
       :disabled="currentPage <= 1"
       @click="$emit('page-changed', currentPage - 1)"
     >
       ← Назад
     </button>
 
     <!-- Номера страниц -->
     <div class="flex gap-1">
       <button
         v-for="page in pages"
         :key="page"
         class="px-3 py-1 border rounded"
         :class="{ 
            'bg-blue-500 text-white': page === currentPage,
            'hover:bg-gray-200  cursor-pointer': page !== currentPage
          }"
         @click="$emit('page-changed', page)"
       >
         {{ page }}
       </button>
     </div>
 
     <!-- Кнопка "Вперёд" -->
     <button
       class="px-3 py-1 border rounded disabled:opacity-50"
       :class="{'hover:bg-gray-200 cursor-pointer': lastPage != currentPage }"
       :disabled="currentPage >= lastPage"
       @click="$emit('page-changed', currentPage + 1)"
     >
       Вперёд →
     </button>
   </div>
 </template>
 
 <script setup>
 import { computed } from "vue";
 
 const props = defineProps({
   currentPage: { type: Number, required: true },
   lastPage: { type: Number, required: true },
 });
 
 const pages = computed(() => {
   // ограничиваем список страниц (например, макс. 5 кнопок)
   const total = props.lastPage;
   const current = props.currentPage;
   const delta = 2; // сколько страниц показывать слева/справа
   const range = [];
 
   for (
     let i = Math.max(1, current - delta);
     i <= Math.min(total, current + delta);
     i++
   ) {
     range.push(i);
   }
 
   return range;
 });
 </script>
 