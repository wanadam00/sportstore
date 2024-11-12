<template>
    <div>
        <h1>Edit User</h1>
        <form @submit.prevent="updateUser">
            <input v-model="form.name" placeholder="Name" required />
            <input v-model="form.email" type="email" placeholder="Email" required />
            <button type="submit">Save</button>
        </form>
    </div>
</template>

<script setup>
import { router, usePage, useForm } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

// Retrieve the user prop from the Inertia page
const { props } = usePage();
const user = props.user;

// Initialize form with existing user data
const form = useForm({
    name: user.name,
    email: user.email,
});

// Method to update user information
function updateUser() {
    form.put(route('users.update', user.id), {
        onSuccess: () => {
            // Optional: handle successful update (e.g., redirect or show success message)
        }
    });
}
</script>
