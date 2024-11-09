<template>
    <AppLayout2>
        <section class="text-gray-600 body-font">
            <div class="container mx-auto flex px-5 py-16 md:flex-row flex-col items-center">
                <div
                    class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left items-left text-left">
                    <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">
                        <p class="">{{ capitalizeInitialWords(product.name ?? '-') }}</p>
                    </h1>
                    <p class="mb-8 leading-relaxed">
                    <p class="">{{ capitalizeInitialWords(product.name ?? '-') }}</p>
                    <p><strong>Category:</strong> {{ capitalizeInitialWords(product.category?.name ?? '-') }}</p>
                    <p><strong>Brand:</strong> {{ capitalizeInitialWords(product.brand?.name ?? '-') }}</p>
                    <p><strong>Service:</strong> {{ capitalizeInitialWords(product.service?.name ?? '-') }}</p>
                    <p><strong>Quantity:</strong> {{ product.quantity }}</p>
                    <p><strong>Price:</strong> RM {{ product.price ?? '-' }}</p>
                    <p><strong>Promo Price:</strong> RM {{ product.promo_price ?? '-' }}</p>
                    <p><strong>Description:</strong> <br>{{ product.description }}</p>

                    </p>
                    <!-- <div class="flex justify-center">
                        <button
                            class="inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Button</button>
                        <button
                            class="ml-4 inline-flex text-gray-700 bg-gray-100 border-0 py-2 px-6 focus:outline-none hover:bg-gray-200 rounded text-lg">Button</button>
                    </div> -->
                </div>
                <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6">
                    <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6">
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <transition name="fade" mode="out-in">
                                    <img v-if="currentImage" :key="currentImage.image" :src="`/${currentImage.image}`"
                                        :alt="currentImage.alt"
                                        :class="getImageClass(currentImage.width, currentImage.height)" />
                                    <img v-else :key="product.imageAlt"
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                                        :alt="product.imageAlt"
                                        class="size-96 object-cover object-center rounded-md'" />
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
                                <button @click="nextImage"
                                    :disabled="currentIndex === product.product_images.length - 1"
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

                            <!-- Line to show current image information -->
                            <!-- <div class="mt-2 text-center text-gray-600">
                                <span>Image {{ currentIndex + 1 }} of {{ product.product_images.length }}</span>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout2>
</template>

<script setup>
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const { props } = usePage();
const product = props.product;

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
    return width > height ? 'size-96 object-fill object-center rounded-md'
        : 'size-96 object-contain object-center rounded-md';
};

</script>

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
