<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ⚙️ Admin Dashboard
            </h2>
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                Admin
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- İstatistik Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Aktif Öğrenciler</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['active_students'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-full">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Onay Bekleyen</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_approvals'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Son 24 Saat</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['recent_activity'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- İşlem Panelleri -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">👥 Öğrenci Yönetimi</h3>
                    <div class="space-y-3">
                        <button class="w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition duration-200">
                            <span class="font-medium text-blue-700">Öğrenci Listesi</span>
                            <p class="text-sm text-blue-600 mt-1">Tüm öğrencileri görüntüle ve yönet</p>
                        </button>

                        <button class="w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition duration-200">
                            <span class="font-medium text-green-700">Yeni Öğrenci Ekle</span>
                            <p class="text-sm text-green-600 mt-1">Manuel öğrenci kaydı oluştur</p>
                        </button>

                        <button class="w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition duration-200">
                            <span class="font-medium text-purple-700">Toplu İşlemler</span>
                            <p class="text-sm text-purple-600 mt-1">Toplu kayıt ve güncelleme</p>
                        </button>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">📚 İçerik Yönetimi</h3>
                    <div class="space-y-3">
                        <button class="w-full text-left px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg border border-indigo-200 transition duration-200">
                            <span class="font-medium text-indigo-700">Dersleri Yönet</span>
                            <p class="text-sm text-indigo-600 mt-1">Ders içeriklerini düzenle</p>
                        </button>

                        <button class="w-full text-left px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg border border-orange-200 transition duration-200">
                            <span class="font-medium text-orange-700">Ödev Takibi</span>
                            <p class="text-sm text-orange-600 mt-1">Ödev durumlarını kontrol et</p>
                        </button>

                        <button class="w-full text-left px-4 py-3 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 transition duration-200">
                            <span class="font-medium text-red-700">Duyurular</span>
                            <p class="text-sm text-red-600 mt-1">Sistem duyurularını yayınla</p>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
