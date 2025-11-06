<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Server, Users, UserCog, GraduationCap } from 'lucide-vue-next';

defineProps({
  stats: Object,
});

// Route linklerini computed ile oluşturabilirsiniz
const links = computed(() => ({
  groupCreate: route('groups.create'),
  groupList: route('groups.show', 32), // Örnek group id 32
  studentList: route('students.index', 32),
  studentCreate: route('students.create', 32),
}));
</script>

<template>
  <Head title="Super Admin Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Super Admin Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- İstatistik Kartları -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="(val, key) in formattedStats"
            :key="key"
            class="bg-white shadow rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition"
          >
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">
                  {{ val.label }}
                </h3>
                <p class="text-3xl font-bold text-indigo-600 mt-1">
                  {{ val.value }}
                </p>
              </div>
              <div
                class="p-3 bg-indigo-100 text-indigo-600 rounded-full shadow-inner"
              >
                <component :is="val.icon" class="h-6 w-6" />
              </div>
            </div>
          </div>
        </div>

        <!-- Sistem Durumu -->
        <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Sistem Durumu
          </h3>
          <p class="text-gray-600">
            Şu anda sistem durumu:
            <span class="font-semibold text-emerald-600">
              {{ stats.system_health }}
            </span>
          </p>
        </div>

        <!-- Linkler -->
        <div class="bg-white shadow rounded-2xl p-6 border border-gray-100 mt-6 space-y-3">
          <h3 class="text-lg font-semibold text-gray-700 mb-2">Hızlı Linkler</h3>
          <ul class="space-y-1">
            <li>
              <Link :href="links.groupCreate" class="text-indigo-600 hover:underline">
                Yeni Grup Oluştur
              </Link>
            </li>
            <li>
              <Link :href="links.groupList" class="text-indigo-600 hover:underline">
                Grup Detayları (ID: 32)
              </Link>
            </li>
            <li>
              <Link :href="links.studentList" class="text-indigo-600 hover:underline">
                Öğrenci Listesi (Grup 32)
              </Link>
            </li>
            <li>
              <Link :href="links.studentCreate" class="text-indigo-600 hover:underline">
                Yeni Öğrenci Ekle (Grup 32)
              </Link>
            </li>
          </ul>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
export default {
  computed: {
    formattedStats() {
      return {
        total_users: { label: 'Toplam Kullanıcı', value: this.stats.total_users, icon: Users },
        total_admins: { label: 'Toplam Admin', value: this.stats.total_admins, icon: UserCog },
        total_students: { label: 'Toplam Öğrenci', value: this.stats.total_students, icon: GraduationCap },
        system_health: { label: 'Sistem Sağlığı', value: this.stats.system_health, icon: Server },
      };
    },
  },
};
</script>
