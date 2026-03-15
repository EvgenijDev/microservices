<template>
   <Teleport to="body">
       <div
           v-if="importId"
           class="fixed top-4 right-4 z-50 w-80 rounded-lg border border-gray-200 bg-white p-4 shadow-lg"
           role="status"
           aria-live="polite"
       >
           <div class="flex items-start justify-between gap-2">
               <h3 class="text-sm font-semibold text-gray-800">Статус импорта</h3>
               <button
                   type="button"
                   class="text-gray-400 hover:text-gray-600"
                   aria-label="Закрыть"
                   @click="$emit('close')"
               >
                   ×
               </button>
           </div>

           <!-- В процессе -->
           <div v-if="!completed" class="mt-2 space-y-2">
               <p class="text-sm text-gray-600">Импорт выполняется…</p>
               <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                   <div class="h-full bg-blue-600 rounded-full animate-pulse" style="width: 100%" />
               </div>
           </div>

           <!-- Завершено: успех -->
           <div v-else-if="completed && !errors.length" class="mt-2">
               <p class="text-sm font-medium text-green-600">Импорт завершён успешно.</p>
           </div>

           <!-- Завершено: ошибки -->
           <div v-else class="mt-2">
               <p class="text-sm font-medium text-red-600">Ошибки валидации:</p>
               <ul class="mt-1 max-h-32 overflow-y-auto space-y-1 text-xs text-gray-700">
                   <li
                       v-for="(err, index) in errors"
                       :key="index"
                       class="p-1.5 bg-red-50 rounded border border-red-100"
                   >
                       Строка {{ err.row }}, «{{ err.field }}»: {{ err.errors?.join(', ') || '' }}
                   </li>
               </ul>
           </div>

           <div class="mt-3 flex justify-end">
               <button
                   type="button"
                   class="text-sm font-medium text-gray-600 hover:text-gray-800"
                   @click="$emit('close')"
               >
                   Закрыть
               </button>
           </div>
       </div>
   </Teleport>
</template>

<script>
import api from '../api/api'
const POLL_INTERVAL_MS = 2500
export default {
    name: 'ImportProgressModal',
    props: {
        importId: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            completed: false,
            errors: [],
            pollTimer: null
        }
    },
    beforeUnmount() {
        this.stopPolling()
    },
    watch: {
        importId: {
            handler(id) {
                if (id) {
                    this.completed = false
                    this.errors = []
                    this.startPolling()
                } else {
                    this.stopPolling()
                }
            },
            immediate: true
        }
    },
    methods: {
        startPolling() {
            this.stopPolling()
            const poll = async () => {
                if (!this.importId) return
                try {
                    const { data } = await api.get(`/products/import/${this.importId}/errors`)
                    if (Array.isArray(data)) {
                        this.completed = true
                        if (data.length === 1 && data[0]?.status === 'success') {
                           this.errors = []
                           this.$emit('success')
                        } else {
                           this.errors = data
                        }
                        clearInterval(this.pollTimer)
                        this.pollTimer = null
                     }
                } catch (e) {
                    // 404 или сеть — джоб ещё не завершился, продолжаем опрос
                }
            }
            poll()
            this.pollTimer = setInterval(poll, POLL_INTERVAL_MS)
        },
        stopPolling() {
            if (this.pollTimer) {
                clearInterval(this.pollTimer)
                this.pollTimer = null
            }
        }
    }
}
</script>