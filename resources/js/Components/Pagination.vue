<template>
    <nav v-if="links.length > 3" class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
        <div class="flex w-0 flex-1">
            <Link
                v-if="links[0].url"
                :href="links[0].url"
                class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
            >
                <svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Önceki
            </Link>
        </div>
        <div class="hidden md:flex">
            <template v-for="(link, index) in links" :key="index">
                <Link
                    v-if="index > 0 && index < links.length - 1"
                    :href="link.url"
                    class="inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium"
                    :class="{
                        'border-blue-500 text-blue-600': link.active,
                        'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': !link.active
                    }"
                    v-html="link.label"
                />
            </template>
        </div>
        <div class="flex w-0 flex-1 justify-end">
            <Link
                v-if="links[links.length - 1].url"
                :href="links[links.length - 1].url"
                class="inline-flex items-center border-t-2 border-transparent pt-4 pl-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
            >
                Sonraki
                <svg class="ml-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </Link>
        </div>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array
});
</script>
