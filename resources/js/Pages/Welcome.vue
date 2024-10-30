<script setup>
import { Link, router } from "@inertiajs/vue3";
import UserLayouts from "@/Pages/User/Layouts/UserLayouts.vue";
import Hero from "@/Pages/User/Layouts/Hero.vue";
import Products from "@/Pages/User/Components/Products.vue";

// Define props
const props = defineProps({
    products: Array,
    categories: Array,
    brands: Array,
    services: Array,
    products: Array,
});

// Limit the products to the first 4
const limitedProducts = props.products.slice(0, 4);
</script>
<template>
    <UserLayouts>
        <!-- here section  -->
        <Hero :categories="categories" :services="services" :products="products"></Hero>
        <!-- end -->
        <div class="bg-white">
            <div class="mx-auto max-w-2xl px-4 pb-8 sm:px-6 sm:pb-8 lg:max-w-7xl lg:px-8">
                <!-- <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                    List of products
                </h2> -->

                <!-- product list component -->
                <!-- <Products :products="products"></Products> -->
                <Products :products="limitedProducts" />
                <!-- end -->
                <div class="flex justify-center mt-10 pt-10">
                    <Link :href="route('products.list')"
                        class="text-white bg-[#1a1a1a] hover:bg-[#0f0f0f] focus:ring-2 focus:outline-none focus:ring-yellow-500 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                    View All Products</Link>
                </div>
            </div>
            <!-- Brands Section -->
            <div class="pt-6 pb-16 bg-white">
                <!-- <h2 class="text-3xl font-bold text-center mb-8">Featured Brands</h2> -->
                <div class="container mx-auto flex flex-wrap justify-center gap-14">
                    <div v-for="brand in brands" :key="brand.id" class="group relative">
                        <img v-if="brand.brand_images?.length > 0" :src="`/${brand.brand_images[0].image}`"
                            :alt="brand.imageAlt"
                            class="h-20 w-20 object-contain object-center lg:h-20 lg:w-20 rounded-ld" />
                        <img v-else
                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                            :alt="brand.imageAlt"
                            class="h-20 w-20 object-contain object-center lg:h-20 lg:w-20 rounded-lg" />
                        <!-- <div class="text-gray-900 text-md text-center title-font font-medium mt-1">{{ brand.name }}
                        </div> -->
                        <!-- Add more brand logos -->
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-16 pb-24 bg-yellow-500 text-white">
            <div class="container mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">Join Our Newsletter</h2>
                <p class="text-lg mb-6">Sign up for exclusive deals and be the first to know about new product releases.
                </p>
                <form class="flex justify-center">
                    <input type="email" class="w-1/2 px-4 py-3 rounded-l-lg text-gray-800"
                        placeholder="Enter your email">
                    <button
                        class="bg-[#1a1a1a] hover:bg-[#0f0f0f] px-6 py-3 rounded-r-lg font-semibold">Subscribe</button>
                </form>
            </div>
        </div>
    </UserLayouts>
</template>
