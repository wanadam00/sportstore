<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import { Plus } from '@element-plus/icons-vue'
import { Inertia } from '@inertiajs/inertia';

// Get users from the page props
const { props } = usePage();
const users = props.users;
const pageProps = usePage().props;
const search = ref(pageProps.filters.search || '');

// Reactive state for managing modal visibility and edit mode
const dialogVisible = ref(false);
const editMode = ref(false);

const searchUser = () => {
    Inertia.get(route('users.index'), { search: search.value }, { preserveState: true });
};

// Form data for creating/updating a user
const form = useForm({
    id: '',
    name: '',
    email: '',
    password: '',
});

// Open the modal for adding a new user
function openAddUserModal() {
    editMode.value = false;
    form.reset(); // Clear the form fields for new user
    dialogVisible.value = true;
}

// Open the modal for editing an existing user
function openEditUserModal(user) {
    editMode.value = true;
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.password = ''; // Keep password field empty for security
    dialogVisible.value = true;
}

// Add a new user
function addUser() {
    form.post(route('users.store'), {
        onSuccess: page => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: page.props.flash.success,
                timer: 3000,
                timerProgressBar: true,
            })
            dialogVisible.value = false;
            router.visit(route('users.index'));
        },
        onError: (errors) => {
            // console.log(resp)
            form.errors = errors;
        }
    });
}

// Update an existing user
function updateUser() {
    form.put(route('users.update', { user: form.id }), {
        onSuccess: page => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmButton: false,
                title: page.props.flash.success,
                timer: 3000,
                timerProgressBar: true,
            })
            dialogVisible.value = false;
            router.visit(route('users.index'));
        },
        onError: (errors) => {
            // console.log(resp)
            form.errors = errors;
        }
    });
}

// Delete user
function deleteUser(id) {
    if (confirm('Are you sure?')) {
        form.delete(route('users.destroy', id), {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                    timer: 3000,
                    timerProgressBar: true,
                })
                dialogVisible.value = false;
                router.visit(route('users.index'));
            },
        });
    }
}

const capitalizeInitialWords = (str) => {
    if (!str) return '';
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
};
</script>
<template>
    <AppLayout2>
        <section class="  p-3 sm:p-5">
            <div class="pb-4 mx-auto max-w-screen-xl px-4 lg:px-12 flex items-center">
                <button @click="searchUser" class="absolute ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 ">
                        <defs>
                            <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur in="SourceAlpha" stdDeviation="2" />
                                <feOffset dx="1" dy="1" result="offsetblur" />
                                <feFlood flood-color="rgba(0, 0, 0, 0.5)" />
                                <feComposite in2="offsetblur" operator="in" />
                                <feMerge>
                                    <feMergeNode />
                                    <feMergeNode in="SourceGraphic" />
                                </feMerge>
                            </filter>
                        </defs>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                            filter="url(#shadow)" />
                    </svg>
                </button>
                <input v-model="search" placeholder="Search by name" @keyup.enter="searchUser"
                    class="rounded-lg text-xs  border border-gray-300 shadow-md text-center" />
            </div>
            <div>
                <!-- <h1>User Management</h1> -->
                <!-- <button @click="openAddUserModal" class="btn btn-primary">Add User</button> -->

                <!-- Users Table -->
                <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                    <!-- Start coding here -->
                    <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                        <div
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                            <div class="w-full md:w-1/2">
                                <div class="text-lg font-semibold">User List</div>
                            </div>
                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button type="button" @click="openAddUserModal"
                                    class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5 mr-2 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="white" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 12h14m-7 7V5" />
                                    </svg>
                                    Add user
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Name</th>
                                        <th scope="col" class="px-4 py-3">Email</th>
                                        <!-- <th scope="col" class="px-4 py-3">Brand</th>
                                <th scope="col" class="px-4 py-3">Quantity</th>
                                <th scope="col" class="px-4 py-3">Price</th>
                                <th scope="col" class="px-4 py-3">Stock</th>
                                <th scope="col" class="px-4 py-3">Publish</th> -->
                                        <th scope="col" class="px-4 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-200">
                                        <th scope="row"
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white uppercase">
                                            {{ capitalizeInitialWords(user.name) }}</th>
                                        <td class="px-4 py-3">{{ user.email }}</td>
                                        <td class="px-4 py-3 flex items-center justify-end">

                                            <button :id="`${user.id}-button`" :data-dropdown-toggle="`${user.id}`"
                                                class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                                type="button">
                                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                            <div :id="`${user.id}`"
                                                class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                    :aria-labelledby="`${user.id}-button`">

                                                    <li>
                                                        <a href="#" @click="openEditUserModal(user)"
                                                            class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                                    </li>
                                                </ul>
                                                <div class="py-1">
                                                    <a href="#" @click="deleteUser(user.id)"
                                                        class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <button @click="openEditUserModal(user)">Edit</button>
                            <button @click="deleteUser(user.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table> -->

                <!-- Modal for Adding or Editing User -->
                <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit User' : 'Add User'" width="30%">
                    <form @submit.prevent="editMode ? updateUser() : addUser()">
                        <div class="relative z-0 w-full mb-6 group">
                            <input v-model="form.name" type="text" name="floating_name" id="floating_name"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="floating_name"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Name</label>
                            <span v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</span>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input v-model="form.email" type="text" name="floating_email" id="floating_email"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="floating_email"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
                            <span v-if="form.errors.email" class="text-red-500 text-xs">{{ form.errors.email }}</span>
                        </div>
                        <div class="relative z-0 w-full mb-6 group">
                            <input v-model="form.password" type="password" name="floating_password"
                                id="floating_password"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="floating_password"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
                            <span v-if="form.errors.password" class="text-red-500 text-xs">{{ form.errors.password
                                }}</span>
                        </div>
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
                    </form>
                </el-dialog>
            </div>
        </section>
    </AppLayout2>
</template>
