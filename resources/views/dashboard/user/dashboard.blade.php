<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🎓 Öğrenci Dashboard
            </h2>
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                Öğrenci
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Öğrenci İstatistikleri -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-center">
                        <div class="flex justify-center mb-3">
                            <div class="bg-green-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Tamamlanan Dersler</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $studentData['completed_courses'] }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-center">
                        <div class="flex justify-center mb-3">
                            <div class="bg-blue-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Devam Eden Dersler</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $studentData['ongoing_courses'] }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-center">
                        <div class="flex justify-center mb-3">
                            <div class="bg-purple-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Ortalama Not</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $studentData['average_grade'] }}/100</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-orange-500">
                    <div class="text-center">
                        <div class="flex justify-center mb-3">
                            <div class="bg-orange-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Son Teslim</p>
                        {{-- <p class="text-lg font-bold text-gray-900 text-sm">{{ \Carbon\Carbon::parse($studentData['next_assignment'])->format('d.m') }}</p> --}}
                    </div>
                </div>
            </div>

            <!-- Öğrenci Panelleri -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">📖 Derslerim</h3>
                    <div class="space-y-3">
                        <button class="w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition duration-200">
                            <span class="font-medium text-green-700">Matematik 101</span>
                            <p class="text-sm text-green-600 mt-1">Devam ediyor • Son ders: Fonksiyonlar</p>
                        </button>

                        <button class="w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition duration-200">
                            <span class="font-medium text-blue-700">Fizik 101</span>
                            <p class="text-sm text-blue-600 mt-1">Devam ediyor • Mekanik</p>
                        </button>

                        <button class="w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition duration-200">
                            <span class="font-medium text-purple-700">Programlama</span>
                            <p class="text-sm text-purple-600 mt-1">Tamamlandı • Not: 92</p>
                        </button>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">📅 Yaklaşan Etkinlikler</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded border border-yellow-200">
                            <div>
                                <span class="font-medium text-yellow-700">Matematik Sınavı</span>
                                <p class="text-sm text-yellow-600 mt-1">15 Aralık 2024 - 10:00</p>
                            </div>
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">2 gün kaldı</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-red-50 rounded border border-red-200">
                            <div>
                                <span class="font-medium text-red-700">{{ $studentData['next_assignment'] }}</span>
                                <p class="text-sm text-red-600 mt-1">Teslim: 15 Aralık 2024 - 23:59</p>
                            </div>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Acil</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded border border-blue-200">
                            <div>
                                <span class="font-medium text-blue-700">Fizik Laboratuvarı</span>
                                <p class="text-sm text-blue-600 mt-1">17 Aralık 2024 - 14:00</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">4 gün kaldı</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hızlı Erişim -->
            <div class="mt-6 bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">🚀 Hızlı Erişim</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button class="p-4 bg-gray-50 hover:bg-gray-100 rounded-lg text-center transition duration-200">
                        <div class="text-2xl mb-2">📚</div>
                        <span class="text-sm font-medium text-gray-700">Ders Notları</span>
                    </button>
                    <button class="p-4 bg-gray-50 hover:bg-gray-100 rounded-lg text-center transition duration-200">
                        <div class="text-2xl mb-2">📝</div>
                        <span class="text-sm font-medium text-gray-700">Ödevlerim</span>
                    </button>
                    <button class="p-4 bg-gray-50 hover:bg-gray-100 rounded-lg text-center transition duration-200">
                        <div class="text-2xl mb-2">📊</div>
                        <span class="text-sm font-medium text-gray-700">Notlarım</span>
                    </button>
                    <button class="p-4 bg-gray-50 hover:bg-gray-100 rounded-lg text-center transition duration-200">
                        <div class="text-2xl mb-2">👨‍🏫</div>
                        <span class="text-sm font-medium text-gray-700">Eğitmenler</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
