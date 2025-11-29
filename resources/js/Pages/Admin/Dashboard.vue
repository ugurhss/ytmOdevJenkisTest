<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
  ChevronDown,
  Users,
  Clock,
  CheckCircle2,
  BarChart3,
  Plus,
  Megaphone,
  TrendingUp,
  User,
  Search,
  X
} from 'lucide-vue-next';

const props = defineProps({
  stats: Object,
  groups: Array,
  recentActions: { type: Array, default: () => [] },
});

const showGroups = ref(false);
const showAnnouncements = ref(false);
const searchTerm = ref('');

const filteredGroups = computed(() => {
  if (!searchTerm.value) return props.groups;
  const term = searchTerm.value.toLowerCase().trim();
  return props.groups.filter(group =>
    group.groups_name.toLowerCase().includes(term)
  );
});

const statsComputed = computed(() => props.stats || {});

const statCards = computed(() => [
  {
    label: 'Aktif Öğrenciler',
    value: statsComputed.value.active_students || 0,
    icon: Users,
    color: 'blue',
    trend: '+12%'
  },
  {
    label: 'Bekleyen Onaylar',
    value: statsComputed.value.pending_approvals || 0,
    icon: Clock,
    color: 'orange',
    trend: null,
    alert: true
  },
  {
    label: 'Son Aktiviteler',
    value: statsComputed.value.recent_activity || 0,
    icon: BarChart3,
    color: 'emerald',
    trend: '+8%'
  },
]);

const getColorClasses = (color) => {
  const colors = {
    blue: {
      bg: 'bg-blue-50',
      text: 'text-blue-600',
      border: 'border-blue-200',
      iconBg: 'bg-blue-100',
    },
    orange: {
      bg: 'bg-orange-50',
      text: 'text-orange-600',
      border: 'border-orange-200',
      iconBg: 'bg-orange-100',
    },
    emerald: {
      bg: 'bg-emerald-50',
      text: 'text-emerald-600',
      border: 'border-emerald-200',
      iconBg: 'bg-emerald-100',
    },
  };
  return colors[color] || colors.blue;
};
</script>

<template>
  <Head title="Admin Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div>
        <h2 class="text-3xl font-bold text-gray-900">
          Admin Dashboard
        </h2>
        <p class="text-gray-600 text-sm mt-1">Sistem yönetimi ve İstatistikler</p>
      </div>
    </template>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="stat in statCards"
          :key="stat.label"
          :class="[
            'rounded-xl p-6 border-2 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105',
            getColorClasses(stat.color).bg,
            getColorClasses(stat.color).border
          ]"
        >
          <div class="flex items-start justify-between">
            <div>
              <p :class="['text-sm font-medium uppercase tracking-wide', getColorClasses(stat.color).text]">
                {{ stat.label }}
              </p>
              <div class="flex items-baseline gap-2 mt-3">
                <p :class="['text-4xl font-bold', getColorClasses(stat.color).text]">
                  {{ stat.value }}
                </p>
                <span v-if="stat.trend" class="text-xs font-semibold text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">
                  <TrendingUp class="h-3 w-3 inline mr-1" />
                  {{ stat.trend }}
                </span>
              </div>
            </div>
            <div
              :class="[
                'p-3 rounded-lg',
                getColorClasses(stat.color).iconBg,
                getColorClasses(stat.color).text
              ]"
            >
              <component :is="stat.icon" class="h-7 w-7" />
            </div>
          </div>
          <div v-if="stat.alert" class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-200 text-orange-800">
            <span class="w-1.5 h-1.5 bg-orange-600 rounded-full mr-1.5 animate-pulse"></span>
            Dikkat Gerekli
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="/groups/create" class="group relative overflow-hidden rounded-xl p-6 bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
          <div class="relative z-10 flex items-center gap-3">
            <div class="p-3 bg-blue-500 rounded-lg group-hover:bg-blue-600 transition-colors">
              <Plus class="h-6 w-6" />
            </div>
            <div>
              <h3 class="text-lg font-bold">Grup Oluştur</h3>
              <p class="text-blue-100 text-sm">Yeni bir grup oluşturun</p>
            </div>
          </div>
          <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500 opacity-10 rounded-full -mr-20 -mt-20"></div>
        </a>

        <a href="/announcements" class="group relative overflow-hidden rounded-xl p-6 bg-gradient-to-br from-rose-600 to-rose-700 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
          <div class="relative z-10 flex items-center gap-3">
            <div class="p-3 bg-rose-500 rounded-lg group-hover:bg-rose-600 transition-colors">
              <Megaphone class="h-6 w-6" />
            </div>
            <div>
              <h3 class="text-lg font-bold">Tüm Duyurular</h3>
              <p class="text-rose-100 text-sm">Duyuruları yönetin</p>
            </div>
          </div>
          <div class="absolute top-0 right-0 w-40 h-40 bg-rose-500 opacity-10 rounded-full -mr-20 -mt-20"></div>
        </a>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div
          class="p-6 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors select-none"
          @click="showGroups = !showGroups"
        >
          <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-gray-100 text-gray-600 rounded-lg">
                <Users class="h-5 w-5" />
              </div>
              <div>
                <h3 class="text-xl font-bold text-gray-900">
                  Tüm Gruplarım
                </h3>
                <p class="text-sm text-gray-600">Toplam {{ groups.length }} grup</p>
              </div>
            </div>
            <div
              :class="[
                'p-2 bg-gray-100 text-gray-600 rounded-lg transition-transform duration-300',
                { 'rotate-180': showGroups }
              ]"
            >
              <ChevronDown class="h-5 w-5" />
            </div>
          </div>
        </div>

        <transition
          enter-active-class="transition-all duration-300 ease-out"
          leave-active-class="transition-all duration-200 ease-in"
          enter-from-class="opacity-0 max-h-0"
          enter-to-class="opacity-100 max-h-screen"
          leave-from-class="opacity-100 max-h-screen"
          leave-to-class="opacity-0 max-h-0"
        >
          <div v-show="showGroups" class="border-t border-gray-100">
            <div class="p-6 bg-gray-50 space-y-4">
              <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                  <Search class="h-5 w-5 text-gray-400" />
                </div>
                <input
                  v-model="searchTerm"
                  type="text"
                  placeholder="Grup adı ile ara..."
                  class="block w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
                <button
                  v-if="searchTerm"
                  @click="searchTerm = ''"
                  class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors"
                >
                  <X class="h-5 w-5" />
                </button>
              </div>

              <div v-if="filteredGroups.length === 0" class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-4">
                  <Users class="h-8 w-8 text-gray-400" />
                </div>
                <p class="text-gray-500 font-medium">
                  {{ searchTerm ? 'Arama sonucunda grup bulunamadı.' : 'Henüz grup bulunmamaktadır.' }}
                </p>
              </div>

              <div v-else class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100 max-h-96 overflow-y-auto">
                <div
                  v-for="group in filteredGroups"
                  :key="group.id"
                  class="p-4 hover:bg-blue-50 transition-colors"
                >
                  <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4 flex-1">
                      <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm text-sm">
                        {{ group.groups_name.charAt(0).toUpperCase() }}
                      </div>
                      <div>
                        <p class="font-semibold text-gray-900">
                          {{ group.groups_name }}
                        </p>
                        <p class="text-sm text-gray-500">ID: {{ group.id }}</p>
                      </div>
                    </div>
                    <a
                      :href="`/groups/${group.id}`"
                      class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md flex-shrink-0"
                    >
                      Görüntüle
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </transition>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div
          class="p-6 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors select-none"
          @click="showAnnouncements = !showAnnouncements"
        >
          <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-gray-100 text-gray-600 rounded-lg">
                <Megaphone class="h-5 w-5" />
              </div>
              <div>
                <h3 class="text-xl font-bold text-gray-900">
                  Son Yayınlanan Duyurular
                </h3>
                <p class="text-sm text-gray-600">{{ recentActions.length }} son aktivite</p>
              </div>
            </div>
            <div
              :class="[
                'p-2 bg-gray-100 text-gray-600 rounded-lg transition-transform duration-300',
                { 'rotate-180': showAnnouncements }
              ]"
            >
              <ChevronDown class="h-5 w-5" />
            </div>
          </div>
        </div>

        <transition
          enter-active-class="transition-all duration-300 ease-out"
          leave-active-class="transition-all duration-200 ease-in"
          enter-from-class="opacity-0 max-h-0"
          enter-to-class="opacity-100 max-h-screen"
          leave-from-class="opacity-100 max-h-screen"
          leave-to-class="opacity-0 max-h-0"
        >
          <div v-show="showAnnouncements" class="border-t border-gray-100">
            <div class="divide-y divide-gray-100">
              <div v-if="recentActions.length === 0" class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                  <Megaphone class="h-8 w-8 text-gray-400" />
                </div>
                <p class="text-gray-600 font-medium">Henüz aktivite bulunmuyor.</p>
              </div>

              <div v-else class="bg-gray-50 max-h-96 overflow-y-auto">
                <div
                  v-for="announcement in recentActions"
                  :key="announcement.id"
                  class="p-5 hover:bg-white transition-colors"
                >
                  <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">
                      {{ announcement.user?.name.charAt(0).toUpperCase() || 'A' }}
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="font-semibold text-gray-900">{{ announcement.title }}</p>
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
          </div>
        </transition>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
