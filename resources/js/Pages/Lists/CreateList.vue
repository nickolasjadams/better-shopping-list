<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { Modal } from 'flowbite-vue'
import { ref } from 'vue';

    const isShowModal = ref(false);
    const rotatingPlaceholder = ref([]);
    const rotatingPlaceholderIndex = ref(0);
    const currentMonth = ref(new Date().getMonth()+1);
    const firstInput = ref();

    const form = useForm({
        name: '',
    });

    const submit = () => {
        form.post(route("lists.store"));
        closeModal();
        form.name = "";
    };

    function getRandomInt(max) {
        return Math.floor(Math.random() * max);
    }

    function createPlaceholders() {
        switch(currentMonth.value) {
            case 2:
                rotatingPlaceholder.value.push("Valentine's Day Prep");
                rotatingPlaceholder.value.push("Super Bowl Party");
                break;
            case 5:
                rotatingPlaceholder.value.push("Camping Trip");
                break;
            case 7:
                rotatingPlaceholder.value.push("July 4th Barbeque");
                break;
            case 8:
                rotatingPlaceholder.value.push("Back to School");
                break;
            case 10:
                rotatingPlaceholder.value.push("Halloween Party");
                rotatingPlaceholder.value.push("Candy");
                break;
            case 11:
                rotatingPlaceholder.value.push("Thanksgiving");
                break;
            case 12:
                rotatingPlaceholder.value.push("Christmas List");
                rotatingPlaceholder.value.push("Hanukkah - Gifts");
                break;
        }
        while (rotatingPlaceholder.value.length < 3) {
            let potentialPlaceholders = [
                "Japanese Beef Mince Stir-Fry",
                "Chicken Enchiladas",
                "Thai Pork Chops",
                "Red Curry with Tofu",
                "Costco Trip",
                "Art Supplies",
                "Moving Supplies",
                "Prep Stock Up",
                "Office Supplies",
                "Business - Restocking",
                "Wall Repair",
                "Food Prep",
                "Gardening",
                "Video Games"
            ]
            let random = getRandomInt(potentialPlaceholders.length);
            if (!rotatingPlaceholder.value.includes(potentialPlaceholders[random])) {
                rotatingPlaceholder.value.push(potentialPlaceholders[random])
            }
        }
    }
    createPlaceholders();

    function rotatePlaceholder() {
        setTimeout(() => {
            if (rotatingPlaceholderIndex.value === rotatingPlaceholder.value.length-1) {
                rotatingPlaceholderIndex.value = 0;
            } else {
                rotatingPlaceholderIndex.value += 1;
            }
            rotatePlaceholder();
        }, 2500);
    };
    rotatePlaceholder();

    function closeModal() {
        isShowModal.value = false;
    }
    function showModal() {
        isShowModal.value = true;
        setTimeout(() => {
            firstInput.value.focus();
        }, 10);
    }

</script>

<template>
    <div>
        <Head title="Create List" />

    <!--    We could simply remove the modal component and that might look pretty good -->
        <button @click="showModal" type="button" class="mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            New List
        </button>
        <Modal v-if="isShowModal" @close="closeModal">
            <template #header>
                <div class="flex items-center text-lg text-gray-500 dark:text-gray-400">
                    Create a new list
                </div>
            </template>
            <template #body>
                <form @submit.prevent="submit">
                    <div class="grid gap-6 mb-6 md:grid-cols-1">
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">List Name</label>
                            <input v-model="form.name" v-bind:placeholder="rotatingPlaceholder[rotatingPlaceholderIndex]" type="text" id="name" name="name" ref="firstInput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                        <div v-if="form.errors.name" class="text-sm text-red-600">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button @click="closeModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Back
                        </button>
                        <button type="submit" :disabled="form.processing" :class="{ 'opacity-25': form.processing }" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Create
                        </button>
                    </div>
                </form>
            </template>
        </Modal>
    </div>
</template>
