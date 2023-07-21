<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AddListItem from "@/Pages/List/AddListItem.vue";
import ShowListItems from "@/Pages/List/ShowListItems.vue";

let darkMode = (
    window.localStorage.getItem('color-theme') === 'dark' ||
    (!('color-theme' in window.localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
);

const props = defineProps({
    list: {
        type: Object,
        default: () => ({}),
    },
    items: {
        type: Array
    }

});

</script>

<template>
    <AppLayout title="Lists" :dark-mode=darkMode>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ list.name }}
            </h2>
        </template>

        <!-- flash message start -->
        <div
            v-if="$page.props.flash.message"
            class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
            role="alert"
        >
                    <span class="font-medium">
                        {{ $page.props.flash.message }}
                    </span>
        </div>
        <!-- flash message end -->

        <div class="pt-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <AddListItem :list=list />
                </div>
            </div>
        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <ShowListItems :list=list :items=items />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
