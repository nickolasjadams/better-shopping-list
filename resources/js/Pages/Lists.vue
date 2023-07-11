<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import Show from "@/Pages/Profile/Show.vue";
import CreateList from "@/Pages/Lists/CreateList.vue";
import ShowLists from "@/Pages/Lists/ShowLists.vue";

let darkMode = (
    localStorage.getItem('color-theme') === 'dark' ||
    (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
);

const props = defineProps({
    lists: {
        type: Object,
        default: () => ({}),
    },
});

</script>

<template>
    <AppLayout title="Lists" :dark-mode=darkMode>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Lists
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
                    <CreateList />
                </div>
            </div>
        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <ShowLists :lists=lists />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
