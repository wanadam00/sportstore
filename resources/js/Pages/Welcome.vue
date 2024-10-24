<script setup>
import { Link, router } from "@inertiajs/vue3";
import UserLayouts from "@/Pages/User/Layouts/UserLayouts.vue";
import Hero from "@/Pages/User/Layouts/Hero.vue";
import Products from "@/Pages/User/Components/Products.vue";
//products list
defineProps({
    products: Array,
});
</script>
<template>
    <UserLayouts>
        <!-- here section  -->
        <Hero></Hero>
        <!-- end -->
        <div class="bg-white">
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                    List of products
                </h2>

                <!-- product list component -->
                <Products :products="products"></Products>
                <!-- end -->
                <div class="flex justify-center mt-5">
                    <Link :href="route('products.list')"
                        class="text-white bg-[#1a1a1a] hover:bg-[#0f0f0f] focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                    View All Products</Link>
                </div>
            </div>
        </div>
        <div class="py-16 bg-yellow-500 text-white">
            <div class="container mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">Join Our Newsletter</h2>
                <p class="text-lg mb-6">Sign up for exclusive deals and be the first to know about new product releases.
                </p>
                <form class="flex justify-center">
                    <input type="email" class="w-1/2 px-4 py-3 rounded-l-lg text-gray-800"
                        placeholder="Enter your email">
                    <button class="bg-[#1a1a1a] px-6 py-3 rounded-r-lg font-semibold">Subscribe</button>
                </form>
            </div>
        </div>
    </UserLayouts>
</template>
