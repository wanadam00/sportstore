<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
const canLogin = usePage().props.canLogin;
const canRegister = usePage().props.canRegister;
const auth = usePage().props.auth;
const cart = computed(() => usePage().props.cart);
const isTransparent = ref(true); // State to track if the navbar is transparent

// Function to handle scroll event
const handleScroll = () => {
    // Check the scroll position
    if (window.scrollY > 0) {
        isTransparent.value = false; // Set to false when scrolled down
    } else {
        isTransparent.value = true; // Set to true when at the top
    }
};

// Lifecycle hooks to add/remove the scroll event listener
onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);
});
// Logout function
const logout = async () => {
    await router.post(route('logout'), {}, {
        onSuccess: () => {
            // Redirect to the login page or another page after successful logout
            router.visit(route('welcome'));
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};
</script>
<template>
    <nav :class="{ 'bg-transparent border-b ': isTransparent, 'bg-white border-b': !isTransparent }"
        class="border-gray-200 fixed top-0 left-0 w-full z-50 transition-all duration-300">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-2">
            <Link :href="route('welcome')" class="flex items-center">
            <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg> -->
            <img src="/images/global-crop.png" alt="Sport Shop Logo" class="w-28 h-12 object-contain" />
            <!-- <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Sport Shop</span> -->
            </Link>
            <div v-if="canLogin" class="flex items-center md:order-2">
                <div class="mr-4">

                    <Link :href="route('cart.view')"
                        class="relative inline-flex items-center p-2 text-sm font-medium text-center text-white  ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke=""
                        class="w-7 h-7 rounded-lg drop-shadow-lg"
                        :class="{ 'text-white hover:stroke-white stroke-[#1a1a1a] transition-colors duration-300': isTransparent, 'text-[#1a1a1a] stroke-[#1a1a1a] hover:stroke-yellow-500 transition-colors duration-300': !isTransparent }">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>

                    <span class="sr-only">cart</span>
                    <div :class="{ 'bg-[#1a1a1a] text-white -top-0 -right-0 ': isTransparent, 'bg-[#1a1a1a] text-white -top-0 -right-0': !isTransparent }"
                        class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold rounded-full transition-colors duration-300">
                        {{ cart.data.count }}</div>
                    </Link>


                </div>
                <div v-if="auth.user" type="button" class="flex mr-3 text-sm rounded-full md:mr-0 "
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <button v-if="$page.props.jetstream.managesProfilePhotos"
                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                        <img class="h-9 w-9 rounded-full object-cover drop-shadow" :src="$page.props.auth.user.profile_photo_url"
                            :alt="$page.props.auth.user.name">
                    </button>
                </div>
                <div v-else class="pr-3">
                    <Link :href="route('login')" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-7 drop-shadow-lg"
                        :class="{ 'text-white hover:stroke-white stroke-[#1a1a1a] transition-colors duration-300': isTransparent, 'text-[#1a1a1a] stroke-[#1a1a1a] hover:stroke-yellow-500 transition-colors duration-300': !isTransparent }">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    </Link>
                    <!-- <Link :href="route('login')" type="button"
                        class="text-white bg-[#212121] hover:bg-[#0f0f0f] focus:ring-2 focus:outline-none focus:ring-white font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                    Login</Link>
                    <Link :href="route('register')" v-if="canRegister" type="button"
                        class="text-white bg-[#212121] hover:bg-[#0f0f0f] focus:ring-2 focus:outline-none focus:ring-white font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                    Register</Link> -->

                </div>



                <!-- Dropdown menu -->
                <div v-if="auth.user"
                    class="z-50 hidden my-4 text-base list-none  bg-white divide-y border divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                    id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white">{{ auth.user.name }}</span>
                        <span class="block text-sm  text-gray-500 truncate dark:text-gray-400">{{ auth.user.email
                            }}</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <Link :href="route('profile.show')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                            Profile</Link>
                        </li>
                        <li>
                            <Link :href="route('dashboard')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                            Dashboard</Link>
                        </li>
                        <li>
                            <Link :href="route('user.orders.index')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                            My Orders</Link>
                        </li>
                        <li>
                            <!-- <Link :href="route('logout')" method="post" as="button"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                            Sign Out</Link> -->
                            <div class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <button @click="logout">
                                    Sign Out</button>
                            </div>
                        </li>
                    </ul>
                </div>
                <button data-collapse-toggle="navbar-user" type="button"
                    class="inline-flex items-center p-2 mr-4 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1"
                :class="{ '': !auth.user }" id="navbar-user">
                <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 md:flex-row md:space-x-8 md:mt-0 sm:bg-white sm:bg-opacity-0 bg-white bg-opacity-100">
                    <li>
                        <Link :href="route('welcome')"
                            :class="{ 'text-[#1a1a1a] hover:text-white': isTransparent, 'text-[#1a1a1a] hover:text-yellow-500 -top-0 -right-0': !isTransparent }"
                            class="block py-2 pl-3 pr-4 text-gray-900 rounded md:bg-transparent md:p-0 transition-colors duration-300 drop-shadow-lg"
                            aria-current="page">Home</Link>
                    </li>
                    <li>
                        <Link :href="route('products.list')"
                            :class="{ 'text-[#1a1a1a] hover:text-white': isTransparent, 'text-[#1a1a1a] hover:text-yellow-500 -top-0 -right-0': !isTransparent }"
                            class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:p-0 transition-colors duration-300 drop-shadow-lg">
                        Product</Link>
                    </li>
                    <li>
                        <Link :href="route('products.contact')"
                            :class="{ 'text-[#1a1a1a] hover:text-white': isTransparent, 'text-[#1a1a1a] hover:text-yellow-500 -top-0 -right-0': !isTransparent }"
                            class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:p-0 transition-colors duration-300 drop-shadow-lg">
                        Contact</Link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
