<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import { Modal } from 'flowbite-vue'
import { ref } from 'vue'

    const props = defineProps({
        list: Object,
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    });

    const form = useForm({
        name: '',
        list_id: props.list.id,
        quantity: 1,
        instructions: ''
    });

    const submit = () => {
        form.post(route("items.store"));
        form.reset();
    };

    function getRandomInt(max) {
        return Math.floor(Math.random() * max);
    }

</script>

<template>
    <Head title="Welcome" />

    <form @submit.prevent="submit">
        <input type="hidden" name="list_id" :value="list.id" />
        <div class="grid gap-6 mb-6 md:grid-cols-1">
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Add Item</label>
                <input v-model="form.name" placeholder="Add Item" type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
            </div>
            <div v-if="form.errors.name" class="text-sm text-red-600">
                {{ form.errors.name }}
            </div>
        </div>

        <div class="flex justify-between">
            <button type="submit" :disabled="form.processing" :class="{ 'opacity-25': form.processing }" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Create
            </button>
        </div>
    </form>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
