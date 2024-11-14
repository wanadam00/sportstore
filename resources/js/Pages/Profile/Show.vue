<script setup>
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import Header from '../User/Layouts/Header.vue';
import Navbar from '@/Components/Navbar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
onMounted(() => {
    initFlowbite();
})
</script>

<template>
    <!-- <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profile
        </h2>
    </template> -->
    <Navbar />

    <div>
        <div class="max-w-7xl mx-auto py-20 sm:px-6 lg:px-8">
            <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                <UpdateProfileInformationForm :user="$page.props.auth.user" />

                <SectionBorder />
            </div>

            <div v-if="$page.props.jetstream.canUpdatePassword">
                <UpdatePasswordForm class="mt-10 sm:mt-0" />

                <SectionBorder />
            </div>

            <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication"
                    class="mt-10 sm:mt-0" />

                <SectionBorder />
            </div>

            <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

            <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                <SectionBorder />

                <DeleteUserForm class="mt-10 sm:mt-0" />
            </template>
        </div>
    </div>
</template>
