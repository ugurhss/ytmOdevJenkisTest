<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
  ChevronDown,
  Megaphone,
  History,
  BookOpen,
  Clock,
  User,
  Sparkles
} from 'lucide-vue-next';

const props = defineProps({
  user: Object,
  groups: Array,
  recentActions: {
    type: Array,
    default: () => []
  },
});

const openGroups = ref({});
const searchTerm = ref('');

const filteredGroups = computed(() => {
  if (!searchTerm.value) return props.groups;
  const term = searchTerm.value.toLowerCase().trim();
  return props.groups.filter(group =>
    group.groups_name.toLowerCase().includes(term)
  );
});

const getInitials = (name) => {
  return name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
};

const groupsCount = computed(() => props.groups?.length || 0);
const announcementsCount = computed(() => {
  return props.groups?.reduce((acc, group) => acc + (group.announcements?.length || 0), 0) || 0;
});
</script>

<template>
  <Head title="Öğrenci Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg">
              {{ getInitials(user.name) }}
            </div>
            <div>
              <h2 class="text-3xl font-bold text-gray-900">
                Hoş geldin, {{ user.name }} 👋
              </h2>
              <p class="text-gray-600 text-sm mt-0.5">Bugün neler yapacağını keşfet!</p>
            </div>
          </div>
        </div>
        <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-blue-50 rounded-lg border border-blue-100">
          <Sparkles class="h-5 w-5 text-blue-600" />
          <span class="text-sm font-medium text-blue-700">Aktif Öğrenci</span>
        </div>
      </div>
    </template>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-blue-700">Üyesi Olduğun Gruplar</p>
              <p class="text-3xl font-bold text-blue-600 mt-1">{{ groupsCount }}</p>
            </div>
            <div class="p-3 bg-blue-200 text-blue-600 rounded-lg">
              <BookOpen class="h-6 w-6" />
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-5 border border-orange-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-orange-700">Yeni Duyurular</p>
              <p class="text-3xl font-bold text-orange-600 mt-1">{{ announcementsCount }}</p>
            </div>
            <div class="p-3 bg-orange-200 text-orange-600 rounded-lg">
              <Megaphone class="h-6 w-6" />
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-5 border border-emerald-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-emerald-700">Son Aktiviteler</p>
              <p class="text-3xl font-bold text-emerald-600 mt-1">{{ recentActions.length }}</p>
            </div>
            <div class="p-3 bg-emerald-200 text-emerald-600 rounded-lg">
              <Clock class="h-6 w-6" />
            </div>
          </div>
        </div>
      </div>

      <section class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-100 rounded-lg">
              <BookOpen class="h-6 w-6 text-blue-600" />
            </div>
            <h3 class="text-2xl font-bold text-gray-900">Gruplarım</h3>
          </div>
        </div>

        <div v-if="groups.length > 0" class="divide-y divide-gray-100">
          <div
            v-for="group in groups"
            :key="group.id"
            class="border-b border-gray-100 last:border-b-0"
          >
            <button
              @click="openGroups[group.id] = !openGroups[group.id]"
              class="w-full px-6 py-4 flex justify-between items-center hover:bg-blue-50 transition-colors group"
            >
              <div class="flex items-center gap-3 text-left flex-1">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm flex-shrink-0">
                  {{ group.groups_name.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                    {{ group.groups_name }}
                  </h4>
                  <p class="text-sm text-gray-600">
                    {{ group.announcements?.length || 0 }} duyuru
                  </p>
                </div>
              </div>
              <ChevronDown
                :class="{ 'rotate-180': openGroups[group.id] }"
                class="h-5 w-5 text-gray-400 transition-transform duration-200 flex-shrink-0"
              />
            </button>

            <div
              v-show="openGroups[group.id]"
              class="px-6 py-4 bg-gray-50 border-t border-gray-100"
            >
              <div v-if="group.announcements?.length > 0" class="space-y-3">
                <div
                  v-for="ann in group.announcements"
                  :key="ann.id"
                  class="bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all"
                >
                  <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center flex-shrink-0 text-sm font-bold">
                      {{ ann.user?.name.charAt(0).toUpperCase() || 'A' }}
                    </div>
                    <div class="flex-1 min-w-0">
                      <h5 class="font-bold text-gray-900 text-sm line-clamp-1">{{ ann.title }}</h5>
                      <p class="text-gray-700 text-sm mt-1 line-clamp-2">
                        {{ ann.content || 'Açıklama yok' }}
                      </p>
                      <div class="mt-3 flex items-center justify-between text-xs text-gray-600 flex-wrap gap-2">
                        <span class="flex items-center gap-1">
                          <User class="h-3 w-3" />
                          {{ ann.user?.name || 'Bilinmeyen' }}
                        </span>
                        <time class="flex items-center gap-1">
                          <Clock class="h-3 w-3" />
                          {{ new Date(ann.created_at).toLocaleString('tr-TR') }}
                        </time>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else class="text-center py-6">
                <Megaphone class="h-8 w-8 text-gray-300 mx-auto mb-2" />
                <p class="text-gray-500 text-sm font-medium">Bu grupta henüz duyuru yok.</p>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="p-12 text-center">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <BookOpen class="h-8 w-8 text-gray-400" />
          </div>
          <p class="text-gray-600 font-medium mb-1">Henüz bir gruba üye değilsin.</p>
          <p class="text-gray-500 text-sm">Yöneticiden grubuna eklenmeni isteyebilirsin.</p>
        </div>
      </section>

      <section class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-emerald-100 rounded-lg">
              <History class="h-6 w-6 text-emerald-600" />
            </div>
            <h3 class="text-2xl font-bold text-gray-900">Son Aktiviteler</h3>
          </div>
        </div>

        <div class="divide-y divide-gray-100">
          <div v-if="recentActions.length === 0" class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <History class="h-8 w-8 text-gray-400" />
            </div>
            <p class="text-gray-600 font-medium">Henüz yeni bir aktivite yok.</p>
          </div>

          <div v-else>
            <div
              v-for="announcement in recentActions"
              :key="announcement.id"
              class="p-5 hover:bg-gray-50 transition-colors"
            >
              <div class="flex items-start gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">
                  {{ announcement.user?.name.charAt(0).toUpperCase() || 'A' }}
                </div>
                <div class="flex-1 min-w-0">
                  <h5 class="font-semibold text-gray-900 text-sm">
                    {{ announcement.title }}
                  </h5>
                  <p class="text-gray-600 text-sm mt-1 line-clamp-2">
                    {{ announcement.content || 'Açıklama yok' }}
                  </p>
                  <div class="mt-3 flex items-center justify-between text-xs text-gray-600 flex-wrap gap-2">
                    <span class="flex items-center gap-1">
                      <User class="h-3 w-3" />
                      {{ announcement.user?.name || 'Bilinmeyen' }}
                    </span>
                    <time class="flex items-center gap-1">
                      <Clock class="h-3 w-3" />
                      {{ new Date(announcement.created_at).toLocaleString('tr-TR') }}
                    </time>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
