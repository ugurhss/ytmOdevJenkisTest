<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Search, Plus, Users } from 'lucide-vue-next';

const props = defineProps({
  groups: {
    type: Array,
    default: () => [],
  },
});

// Arama için
const searchTerm = ref('');

const filteredGroups = computed(() => {
  if (!searchTerm.value) return props.groups;

  const term = searchTerm.value.toLowerCase().trim();

  return props.groups.filter((group) =>
    (group.groups_name || '').toLowerCase().includes(term)
  );
});
</script>

<template>
  <Head title="Gruplar" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">
            Gruplar
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            Sistemdeki tüm grupları görüntüleyin ve yönetin.
          </p>
        </div>

        <Link
          :href="route('groups.create')"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold shadow-sm hover:bg-blue-700 transition-colors"
        >
          <Plus class="w-4 h-4" />
          Yeni Grup Oluştur
        </Link>
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Üst kart -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
              <Users class="w-6 h-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500">Toplam Grup</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ groups.length }}
              </p>
            </div>
          </div>
        </div>

        <!-- Arama + Liste -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
          <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-900">
              Grup Listesi
            </h3>

            <div class="relative w-full max-w-xs">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <Search class="h-4 w-4 text-gray-400" />
              </div>
              <input
                v-model="searchTerm"
                type="text"
                placeholder="Grup adı ile ara..."
                class="block w-full rounded-lg border border-gray-300 pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
          </div>

          <!-- Boş durum -->
          <div v-if="filteredGroups.length === 0" class="px-6 py-10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
              <Users class="w-8 h-8 text-gray-400" />
            </div>
            <p class="text-gray-600 font-medium">
              {{ searchTerm ? 'Arama sonucunda grup bulunamadı.' : 'Henüz oluşturulmuş bir grup bulunmamaktadır.' }}
            </p>
            <p class="mt-1 text-sm text-gray-500">
              Yeni bir grup oluşturmak için sağ üstteki butonu kullanabilirsiniz.
            </p>
          </div>

          <!-- Tablo -->
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    ID
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Grup Adı
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Şehir
                  </th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    İşlemler
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 bg-white">
                <tr
                  v-for="group in filteredGroups"
                  :key="group.id"
                  class="hover:bg-gray-50 transition-colors"
                >
                  <td class="px-6 py-3 text-gray-500">
                    #{{ group.id }}
                  </td>
                  <td class="px-6 py-3">
                    <div class="flex items-center gap-3">
                      <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">
                        {{ (group.groups_name || '?').charAt(0).toUpperCase() }}
                      </div>
                      <div>
                        <p class="font-medium text-gray-900">
                          {{ group.groups_name }}
                        </p>
                        <p class="text-xs text-gray-500">
                          Oluşturan ID: {{ group.user_id }}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-3 text-gray-700">
                    <!-- city ilişkisi yüklüyse göster, yoksa '-' -->
                    <!-- <span>
                      {{ group.city ? group.city.name : '-' }}
                    </span> -->
                  </td>
                  <td class="px-6 py-3 text-right">
                    <div class="inline-flex items-center gap-2">
                      <Link
                        :href="route('groups.show', group.id)"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors"
                      >
                        Görüntüle
                      </Link>
                      <Link
                        :href="route('groups.edit', group.id)"
                        class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-50 text-gray-700 hover:bg-gray-100 transition-colors"
                      >
                        Düzenle
                      </Link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>
