<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import {
  Server,
  Users,
  UserCog,
  GraduationCap,
  Plus,
  Megaphone,
  UsersIcon,
  Search,
  X,
  ChevronDown,
  TrendingUp,
  Activity
} from 'lucide-vue-next';

const props = defineProps({
  stats: Object,
  groups: Array,
});

const isOpen = ref(false);
const searchTerm = ref('');

const links = computed(() => ({
  groupCreate: route('groups.create'),
  announcements: '/announcements',
  groups: '/groups',
}));

const formattedStats = computed(() => [
  {
    key: 'total_users',
    label: 'Toplam Kullanıcı',
    value: props.stats.total_users,
    icon: Users,
    color: 'blue',
    trend: '+12%'
  },
  {
    key: 'total_admins',
    label: 'Toplam Admin',
    value: props.stats.total_admins,
    icon: UserCog,
    color: 'emerald',
    trend: '+3%'
  },
  {
    key: 'total_students',
    label: 'Toplam Öğrenci',
    value: props.stats.total_students,
    icon: GraduationCap,
    color: 'orange',
    trend: '+18%'
  },
  {
    key: 'system_health',
    label: 'Sistem Sağlığı',
    value: props.stats.system_health,
    icon: Activity,
    color: 'teal',
    badge: true
  },
]);

const quickLinks = computed(() => [
  {
    href: links.value.groupCreate,
    label: 'Yeni Grup Oluştur',
    icon: Plus,
    color: 'blue',
    description: 'Hızlıca yeni bir grup oluşturun'
  },
  {
    href: links.value.announcements,
    label: 'Tüm Duyurular',
    icon: Megaphone,
    color: 'rose',
    description: 'Duyuruları görüntüleyin ve yönetin'
  },
  {
    href: links.value.groups,
    label: 'Tüm Grupları Yönet',
    icon: UsersIcon,
    color: 'emerald',
    description: 'Grupları düzenleyin ve yönetin'
  },
]);

const filteredGroups = computed(() => {
  if (!searchTerm.value) return props.groups;
  const term = searchTerm.value.toLowerCase().trim();
  return props.groups.filter(group =>
    group.groups_name.toLowerCase().includes(term)
  );
});

const getColorClasses = (color) => {
  const colors = {
    blue: {
      bg: 'bg-blue-50',
      text: 'text-blue-600',
      border: 'border-blue-200',
      iconBg: 'bg-blue-100',
      hover: 'hover:bg-blue-100',
      linkHover: 'hover:text-blue-700'
    },
    emerald: {
      bg: 'bg-emerald-50',
      text: 'text-emerald-600',
      border: 'border-emerald-200',
      iconBg: 'bg-emerald-100',
      hover: 'hover:bg-emerald-100',
      linkHover: 'hover:text-emerald-700'
    },
    orange: {
      bg: 'bg-orange-50',
      text: 'text-orange-600',
      border: 'border-orange-200',
      iconBg: 'bg-orange-100',
      hover: 'hover:bg-orange-100',
      linkHover: 'hover:text-orange-700'
    },
    teal: {
      bg: 'bg-teal-50',
      text: 'text-teal-600',
      border: 'border-teal-200',
      iconBg: 'bg-teal-100',
      hover: 'hover:bg-teal-100',
      linkHover: 'hover:text-teal-700'
    },
    rose: {
      bg: 'bg-rose-50',
      text: 'text-rose-600',
      border: 'border-rose-200',
      iconBg: 'bg-rose-100',
      hover: 'hover:bg-rose-100',
      linkHover: 'hover:text-rose-700'
    },
  };
  return colors[color] || colors.blue;
};
</script>

<template>
  <Head title="Super Admin Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">
            Super Admin Dashboard
          </h2>
          <p class="text-sm text-gray-600 mt-1">Sistem yönetimi ve genel bakış</p>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="stat in formattedStats"
            :key="stat.key"
            :class="[
              'relative overflow-hidden rounded-xl p-6 transition-all duration-300',
              'bg-white border-2 hover:shadow-xl hover:scale-105',
              getColorClasses(stat.color).border
            ]"
          >
            <div class="flex items-start justify-between">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">
                  {{ stat.label }}
                </p>
                <div class="flex items-baseline mt-3 gap-2">
                  <p :class="['text-4xl font-bold', getColorClasses(stat.color).text]">
                    {{ stat.value }}
                  </p>
                  <span
                    v-if="stat.trend"
                    class="inline-flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full"
                  >
                    <TrendingUp class="h-3 w-3 mr-1" />
                    {{ stat.trend }}
                  </span>
                </div>
              </div>
              <div
                :class="[
                  'p-3 rounded-xl shadow-sm',
                  getColorClasses(stat.color).iconBg,
                  getColorClasses(stat.color).text
                ]"
              >
                <component :is="stat.icon" class="h-7 w-7" />
              </div>
            </div>

            <div
              v-if="stat.badge"
              class="absolute top-3 left-3"
            >
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                Aktif
              </span>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-teal-50 via-white to-emerald-50 rounded-2xl p-8 border border-teal-100 shadow-sm">
          <div class="flex items-start gap-4">
            <div class="p-3 bg-teal-100 text-teal-600 rounded-xl">
              <Server class="h-6 w-6" />
            </div>
            <div>
              <h3 class="text-xl font-bold text-gray-900 mb-2">
                Sistem Durumu
              </h3>
              <p class="text-gray-600 leading-relaxed">
                Tüm sistemler çalışıyor ve performans optimum seviyede. Şu anda sistem durumu:
                <span class="inline-flex items-center ml-1 px-3 py-1 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700">
                  <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                  {{ stats.system_health }}
                </span>
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
          <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <span class="w-1 h-6 bg-blue-600 rounded-full"></span>
            Hızlı İşlemler
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <Link
              v-for="link in quickLinks"
              :key="link.label"
              :href="link.href"
              :class="[
                'group relative overflow-hidden rounded-xl p-6 transition-all duration-300',
                'border-2 hover:shadow-lg hover:scale-105',
                getColorClasses(link.color).border,
                getColorClasses(link.color).hover
              ]"
            >
              <div class="flex items-start gap-4">
                <div
                  :class="[
                    'p-3 rounded-lg transition-transform duration-300 group-hover:scale-110',
                    getColorClasses(link.color).iconBg,
                    getColorClasses(link.color).text
                  ]"
                >
                  <component :is="link.icon" class="h-6 w-6" />
                </div>
                <div class="flex-1">
                  <h4 :class="['font-semibold text-gray-900 mb-1 transition-colors', getColorClasses(link.color).linkHover, 'group-hover:translate-x-1 transition-transform']">
                    {{ link.label }}
                  </h4>
                  <p class="text-sm text-gray-600">{{ link.description }}</p>
                </div>
              </div>
            </Link>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
          <div
            class="p-6 cursor-pointer hover:bg-gray-50 transition-colors select-none"
            @click="isOpen = !isOpen"
          >
            <div class="flex justify-between items-center">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-gray-100 text-gray-600 rounded-lg">
                  <UsersIcon class="h-5 w-5" />
                </div>
                <div>
                  <h3 class="text-xl font-bold text-gray-900">
                    Tüm Gruplar
                  </h3>
                  <p class="text-sm text-gray-600">Toplam {{ groups.length }} grup bulunuyor</p>
                </div>
              </div>
              <div
                :class="[
                  'p-2 bg-gray-100 rounded-lg transition-transform duration-300',
                  { 'rotate-180': isOpen }
                ]"
              >
                <ChevronDown class="h-5 w-5 text-gray-600" />
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
            <div v-show="isOpen" class="border-t border-gray-200">
              <div class="p-6 space-y-4 bg-gray-50">
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
                    <UsersIcon class="h-8 w-8 text-gray-400" />
                  </div>
                  <p class="text-gray-500 font-medium">
                    {{ searchTerm ? 'Arama sonucunda grup bulunamadı.' : 'Henüz grup bulunmamaktadır.' }}
                  </p>
                </div>

                <div v-else class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100">
                  <div
                    v-for="group in filteredGroups"
                    :key="group.id"
                    class="p-4 hover:bg-blue-50 transition-colors group"
                  >
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-4 flex-1">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm">
                          {{ group.groups_name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                          <p class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                            {{ group.groups_name }}
                          </p>
                          <p class="text-sm text-gray-500">ID: {{ group.id }}</p>
                        </div>
                      </div>
                      <Link
                        :href="`/groups/${group.id}`"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md"
                      >
                        Görüntüle
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </transition>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
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
:root {
  font-family: Inter, system-ui, Avenir, Helvetica, Arial, sans-serif;
  line-height: 1.5;
  font-weight: 400;

  color-scheme: light dark;
  color: rgba(255, 255, 255, 0.87);
  background-color: #242424;

  font-synthesis: none;
  text-rendering: optimizeLegibility;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

a {
  font-weight: 500;
  color: #646cff;
  text-decoration: inherit;
}
a:hover {
  color: #535bf2;
}

body {
  margin: 0;
  display: flex;
  place-items: center;
  min-width: 320px;
  min-height: 100vh;
}

h1 {
  font-size: 3.2em;
  line-height: 1.1;
}

button {
  border-radius: 8px;
  border: 1px solid transparent;
  padding: 0.6em 1.2em;
  font-size: 1em;
  font-weight: 500;
  font-family: inherit;
  background-color: #1a1a1a;
  cursor: pointer;
  transition: border-color 0.25s;
}
button:hover {
  border-color: #646cff;
}
button:focus,
button:focus-visible {
  outline: 4px auto -webkit-focus-ring-color;
}

.card {
  padding: 2em;
}

#app {
  max-width: 1280px;
  margin: 0 auto;
  padding: 2rem;
  text-align: center;
}

@media (prefers-color-scheme: light) {
  :root {
    color: #213547;
    background-color: #ffffff;
  }
  a:hover {
    color: #747bff;
  }
  button {
    background-color: #f9f9f9;
  }
}

</style>
