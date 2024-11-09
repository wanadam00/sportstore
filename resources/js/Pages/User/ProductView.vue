<script setup>
import UserLayouts from './Layouts/UserLayouts.vue';
import { usePage } from '@inertiajs/vue3';
import { Link, router } from "@inertiajs/vue3";
import { ref, computed } from 'vue';


const { props } = usePage();
const product = props.products;

// Function to handle adding the product to the cart
const addToCart = (product) => {
    console.log(product);
    router.post(route("cart.store", product), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success,
                });
            }
        },
    });
};
const capitalizeInitialWords = (str) => {
    if (!str) return '';
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
};

const currentIndex = ref(0);

// Computed property to get the current image
const currentImage = computed(() => {
    return product.product_images.length > 0 ? product.product_images[currentIndex.value] : null;
});

// Function to go to the previous image
const prevImage = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
};

// Function to go to the next image
const nextImage = () => {
    if (currentIndex.value < product.product_images.length - 1) {
        currentIndex.value++;
    }
};

const getImageClass = (width, height) => {
    return width > height ? 'h-full w-full object-fill object-center rounded-md'
        : 'h-full w-full object-contain object-center rounded-md';
};
</script>
<template>
    <UserLayouts>
        <section class="text-gray-600 body-font overflow-hidden">
            <div class="container px-5 py-24 mx-auto">
                <div class="lg:w-4/5 mx-auto flex flex-wrap">
                    <div class="lg:w-1/2 w-full flex flex-col justify-center">
                        <div class="relative">
                            <transition name="fade" mode="out-in">
                                <img v-if="currentImage" :key="currentImage.image" :src="`/${currentImage.image}`"
                                    :alt="currentImage.alt"
                                    :class="getImageClass(currentImage.width, currentImage.height)" />
                                <img v-else :key="product.imageAlt"
                                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                                    :alt="product.imageAlt"
                                    class="h-full w-full object-contain object-center rounded-md" />
                            </transition>

                            <!-- Left Arrow Button -->
                            <button @click="prevImage" :disabled="currentIndex === 0"
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-gray-100 bg-opacity-20 p-2 rounded-lg hover:bg-gray-300 transition">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m15 19-7-7 7-7" />
                                </svg>
                            </button>

                            <!-- Right Arrow Button -->
                            <button @click="nextImage" :disabled="currentIndex === product.product_images.length - 1"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-100 bg-opacity-20 p-2 rounded-lg hover:bg-gray-300 transition">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m9 5 7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        <div class="flex justify-center mt-4 w-full">
                            <span>Image {{ currentIndex + 1 }} of {{ product.product_images.length }}</span>
                        </div>
                    </div>

                    <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                        <h2 class="text-sm title-font text-gray-500 tracking-widest">{{
                            capitalizeInitialWords(product.brand ?
                                product.brand.name : 'Unknown Brand') }}</h2>
                        <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">{{
                            capitalizeInitialWords(product.name) }}</h1>
                        <div class="flex mb-4">
                            <span class="flex items-center">
                                <svg fill="currentColor" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-500"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                                    </path>
                                </svg>
                                <svg fill="currentColor" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-500"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                                    </path>
                                </svg>
                                <svg fill="currentColor" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-500"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                                    </path>
                                </svg>
                                <svg fill="currentColor" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-500"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                                    </path>
                                </svg>
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" class="w-4 h-4 text-yellow-500" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                                    </path>
                                </svg>
                                <span class="text-gray-600 ml-3">4 Reviews</span>
                            </span>
                            <span class="flex ml-3 pl-3 py-2 border-l-2 border-gray-200 space-x-2s">
                                <a class="text-gray-500">
                                    <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                                    </svg>
                                </a>
                                <a class="text-gray-500">
                                    <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                        <path
                                            d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z">
                                        </path>
                                    </svg>
                                </a>
                                <a class="text-gray-500">
                                    <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                        <path
                                            d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z">
                                        </path>
                                    </svg>
                                </a>
                            </span>
                        </div>
                        <p class="leading-relaxed">{{ product.description }}</p>
                        <!-- <div class="flex mt-6 items-center pb-5 border-b-2 border-gray-100 mb-5">
                            <div class="flex">
                                <span class="mr-3">Color</span>
                                <button
                                    class="border-2 border-gray-300 rounded-full w-6 h-6 focus:outline-none"></button>
                                <button
                                    class="border-2 border-gray-300 ml-1 bg-gray-700 rounded-full w-6 h-6 focus:outline-none"></button>
                                <button
                                    class="border-2 border-gray-300 ml-1 bg-green-500 rounded-full w-6 h-6 focus:outline-none"></button>
                            </div>
                            <div class="flex ml-6 items-center">
                                <span class="mr-3">Size</span>
                                <div class="relative">
                                    <select
                                        class="rounded border appearance-none border-gray-300 py-2 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-500 text-base pl-3 pr-10">
                                        <option>SM</option>
                                        <option>M</option>
                                        <option>L</option>
                                        <option>XL</option>
                                    </select>
                                    <span
                                        class="absolute right-0 top-0 h-full w-10 text-center text-gray-600 pointer-events-none flex items-center justify-center">
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2" class="w-4 h-4"
                                            viewBox="0 0 24 24">
                                            <path d="M6 9l6 6 6-6"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div> -->
                        <div class="flex mt-6">
                            <span class="title-font font-medium text-2xl text-gray-900">RM {{ product.price }}</span>
                            <!-- <div class="bg-blue-700 p-2 rounded-full">
                                <a @click="addToCart(product)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </a>
                            </div> -->
                            <a @click="addToCart(product)"
                                class="flex ml-auto text-white bg-yellow-500 border-0 py-2 px-6 focus:outline-none hover:bg-yellow-600 rounded">Add
                                To Cart</a>
                            <button
                                class="rounded-full w-10 h-10 bg-black p-0 border-0 inline-flex items-center justify-center text-yellow-500 ml-4">
                                <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    class="w-5 h-5" viewBox="0 0 24 24">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </UserLayouts>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter,
.fade-leave-to

/* .fade-leave-active in <2.1.8 */
    {
    opacity: 0;
}

/* Add any additional styles here if needed */
</style>
<!-- <template>
    <User Layouts>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">{{ product.name }}</h1>
            <p class="text-lg font-semibold">Brand: {{ product.brand ? product.brand.name : 'Unknown Brand' }}</p>
            <div class="flex">
                <img :src="product.image_url" alt="Product Image" class="w-1/2 h-auto rounded-lg shadow-lg" />
                <div class="ml-4">
                    <p class="text-lg font-semibold">Price: ${{ product.price }}</p>
                    <p class="mt-2">{{ product.description }}</p>
                    <p class="mt-4">Quantity Available: {{ product.quantity }}</p>
                    <button @click="addToCart" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </User Layouts>
</template>

<script setup>
import UserLayouts from './Layouts/UserLayouts.vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const product = props.product;

// Function to handle adding the product to the cart
const addToCart = () => {
    // Logic to add the product to the cart
    console.log(`Added ${product.name} to cart`);
};
</script>

<style scoped>
/* Add any additional styles here */
</style> -->
