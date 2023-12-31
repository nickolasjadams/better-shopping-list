<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SectionBorder from '@/Components/SectionBorder.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const confirmInput = ref(null)

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const userIsSocial = computed(
    () => {
        return usePage().props.auth.user.is_social
    }
)

const deleteUser = () => {
    if (userIsSocial) {
        if (form.confirm.toLowerCase() == "confirm") {
            form.password = usePage().props.auth.user.name + usePage().props.auth.user.email;
            form.delete(route('current-user.destroy'), {
                preserveScroll: true,
                onSuccess: () => closeModal(),
                onError: () => confirmInput.value.focus(),
                onFinish: () => form.reset(),
            });
        } else {
            confirmInput.value.focus();
        }
    } else {
        form.delete(route('current-user.destroy'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.value.focus(),
            onFinish: () => form.reset(),
        });
    }
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <div>
        <SectionBorder />

        <ActionSection class="mt-10 sm:mt-0">
            <template #title>
                Delete Account
            </template>

            <template #description>
                Permanently delete your account.
            </template>

            <template #content>
                <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                </div>

                <div class="mt-5">
                    <DangerButton @click="confirmUserDeletion">
                        Delete Account
                    </DangerButton>
                </div>

                <!-- Delete Account Confirmation Modal -->
                <DialogModal :show="confirmingUserDeletion" @close="closeModal">
                    <template #title>
                        Delete Account
                    </template>

                    <template #content>
                        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.

                        <div class="mt-4">
                            <div v-if="userIsSocial">
                                Type 'confirm' to permanently delete your account.
                                <TextInput
                                    ref="confirmInput"
                                    v-model="form.confirm"
                                    type=text
                                    class="mt-1 block w-3/4"
                                    placeholder="confirm"
                                    @keyup.enter="deleteUser"
                                />
                                <TextInput
                                    ref="passwordInput"
                                    v-model="form.password"
                                    type=hidden
                                    class="mt-1 block w-3/4"
                                    placeholder="Password"
                                    autocomplete="current-password"
                                />
                            </div>
                            <div v-else>
                                <TextInput
                                    ref="passwordInput"
                                    v-model="form.password"
                                    type=password
                                    class="mt-1 block w-3/4"
                                    placeholder="Password"
                                    autocomplete="current-password"
                                    @keyup.enter="deleteUser"
                                />
                            </div>

                            <InputError :message="form.errors.password" class="mt-2" />
                        </div>
                    </template>

                    <template #footer>
                        <SecondaryButton @click="closeModal">
                            Cancel
                        </SecondaryButton>

                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteUser"
                        >
                            Delete Account
                        </DangerButton>
                    </template>
                </DialogModal>
            </template>
        </ActionSection>
    </div>
</template>
