<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link, router } from "@inertiajs/vue3";

// const { props } = usePage();
// const categories = props.categories;

const props = defineProps({
    categories: Array,
    brands: Array,
    services: Array,
    products: Array,
});
const categories = props.categories;
const brands = props.brands;
const services = props.services;

// const images = [
//     '/images/adidas/side.avif',
//     '/images/nike/white-side.webp',
//     '/images/puma/brown-side.avif',
//     // '/images/other-brand/side.avif',
//     // Add more images here...
// ];

const currentImage = ref('');
const imageOpacity = ref(1); // Opacity for fade effect

// Function to change the background image
const changeBackgroundImage = () => {
    // Create an array to hold all product images
    const images = props.products.flatMap(product =>
        product.product_images.map(image => `/${image.image}`) // Adjust this based on your image path
    );

    // Check if there are images available
    if (images.length > 0) {
        const randomIndex = Math.floor(Math.random() * images.length);

        // Fade out
        imageOpacity.value = 0;

        // Change image after a short delay to allow fade out
        setTimeout(() => {
            currentImage.value = images[randomIndex];
            // Fade in
            imageOpacity.value = 1;
        }, 500); // Match this delay with the fade-out duration
    }
};

// Set the initial background image and start the interval
onMounted(() => {
    changeBackgroundImage(); // Set initial background image
    setInterval(changeBackgroundImage, 5000); // Change image every 5 seconds
});
const capitalizeInitialWords = (str) => {
    if (!str) return '';
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
};
// Function to determine the image class based on dimensions
const getImageClass = (width, height) => {
    return width > height ? 'h-full w-full object-fill object-center transition-transform duration-1000 ease-in-out transform group-hover:scale-110 rounded-md'
        : 'h-full w-full object-cover object-center transition-transform duration-1000 ease-in-out transform group-hover:scale-110 rounded-md';
};
</script>
<template>
    <section class="bg-gray-100 dark:bg-gray-900">
        <!-- Hero Section -->
        <div class="relative bg-cover bg-center h-screen transition-opacity duration-1000" :style="{ backgroundImage: `url(${currentImage})`, opacity: imageOpacity }">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white px-6">
                <h1 class="text-5xl font-extrabold mb-4 tracking-tight">Gear Up For Your Next Adventure</h1>
                <p class="text-lg font-light mb-8 max-w-md">Find the perfect gear from top brands like Nike, Adidas,
                    Puma, and more.</p>
                <a href="#shop"
                    class="py-3 px-8 bg-yellow-500 text-gray-900 rounded-lg font-semibold hover:bg-yellow-600">Shop
                    Now</a>
            </div>
        </div>

        <!-- Categories Section -->
        <!-- <div class="py-16 bg-white">
            <h2 class="text-3xl font-bold text-center mb-8">Explore Categories</h2>
            <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
                <div class="group relative overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                    style="background-image: url('/images/adidas/side.avif')">
                    <div
                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <a href="#" class="py-3 px-5 bg-yellow-500 text-black rounded-lg font-semibold">Shop
                            Sneakers</a>
                    </div>
                    <div class="absolute bottom-4 left-4 text-white text-2xl font-bold">Sneakers</div>
                </div>

                <div class="group relative overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                    style="background-image: url('/images/activewear.jpg')">
                    <div
                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <a href="#" class="py-3 px-5 bg-yellow-500 text-black rounded-lg font-semibold">Shop
                            Activewear</a>
                    </div>
                    <div class="absolute bottom-4 left-4 text-white text-2xl font-bold">Activewear</div>
                </div>

                <div class="group relative overflow-hidden rounded-lg shadow-lg bg-cover bg-center"
                    style="background-image: url('/images/accessories.jpg')">
                    <div
                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <a href="#" class="py-3 px-5 bg-yellow-500 text-black rounded-lg font-semibold">Shop
                            Accessories</a>
                    </div>
                    <div class="absolute bottom-4 left-4 text-white text-2xl font-bold">Accessories</div>
                </div>
            </div>
        </div> -->

        <!-- Featured Products -->
        <!-- <div class="py-16 bg-gray-100">
            <h2 class="text-3xl font-bold text-center mb-8">Top Products</h2>
            <div class="container mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 px-4"> -->
        <!-- Product Card -->
        <!-- <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <img src="/images/product-1.jpg" class="w-full h-64 object-cover rounded-t-lg">
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">Nike Air Zoom Pegasus 37</h3>
                        <p class="text-gray-500 mb-4">Men's Running Shoe</p>
                        <span class="text-xl font-semibold text-yellow-500">$120.00</span>
                        <a href="#" class="block mt-4 text-yellow-500 hover:text-yellow-600 font-semibold">View Details</a>
                    </div>
                </div> -->

        <!-- Repeat similar product cards... -->
        <!-- </div>
        </div> -->
        <!-- Category Section -->
        <!-- <div class="pt-16 pb-10 bg-white">
            <h2 class="text-3xl font-bold text-center mb-8">Category</h2>
            <div class="container mx-auto flex flex-wrap justify-center gap-8">
                <div v-for="category in categories" :key="category.id" class="group relative">
                    <img v-if="category.category_images?.length > 0" :src="`/${category.category_images[0].image}`"
                        :alt="category.imageAlt"
                        class="h-20 w-20 object-contain object-center lg:h-20 lg:w-20 rounded-full" />
                    <img v-else
                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                        :alt="category.imageAlt"
                        class="h-20 w-20 object-contain object-center lg:h-20 lg:w-20 rounded-full" />
                    <div class="text-gray-900 text-md text-center title-font font-medium mt-1">{{ category.name }}</div>
                    <img src="/images/brands/underarmour-logo.png" alt="Under Armour" class="h-12">
                    Add more brand logos
                </div>
            </div>
        </div> -->
        <!-- <div class="pt-16 pb-10 bg-white">
            <h2 class="text-3xl font-bold text-center mb-8">Service</h2>
            <div class="container mx-auto flex flex-col sm:flex-row justify-center gap-8">
                <div v-for="service in services" :key="service.id" class="group flex flex-col items-center relative">
                    <div class="overflow-hidden h-96 w-64 sm:h-96 sm:w-64 rounded-md">
                        <img v-if="service.service_images?.length > 0" :src="`/${service.service_images[0].image}`"
                            :alt="service.imageAlt"
                            class="h-full w-full object-cover object-center transition-transform duration-1000 ease-in-out transform group-hover:scale-110 rounded-md" />
                        <img v-else
                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                            :alt="service.imageAlt"
                            class="h-full w-full object-cover object-center transition-transform duration-1000 ease-in-out transform group-hover:scale-110 rounded-md" />
                    </div>
                    <div
                        class=" uppercase absolute bottom-6 left-28 sm:bottom-6 sm:left-6 text-gray-900 text-md title-font font-thin tracking-wider bg-white bg-opacity-90 p-2 rounded-md shadow-md">
                        {{ capitalizeInitialWords(service.name) }}
                    </div>
                </div>
            </div>
        </div> -->
        <div class="pt-16 pb-10 bg-white">
            <h2 class="text-3xl font-bold text-center mb-8">Service</h2>
            <div class="container mx-auto flex flex-col sm:flex-row justify-center gap-8">
                <div v-for="service in services" :key="service.id" class="group flex flex-col items-center relative">
                    <div class="overflow-hidden h-96 w-64 sm:h-96 sm:w-64 rounded-md">
                        <img v-if="service.service_images?.length > 0" :src="`/${service.service_images[0].image}`"
                            :alt="service.imageAlt"
                            :class="getImageClass(service.service_images[0].width, service.service_images[0].height)" />
                        <img v-else
                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                            :alt="service.imageAlt"
                            :class="getImageClass(service.service_images[0].width, service.service_images[0].height)" />
                    </div>
                    <div
                        class="uppercase absolute bottom-6 left-28 sm:bottom-6 sm:left-6 text-gray-900 text-sm title-font font-thin tracking-widest bg-white bg-opacity-90 p-2 rounded-md shadow-md">
                        {{ capitalizeInitialWords(service.name) }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Brands Section -->

        <!-- Testimonials Section -->
        <!-- <div class="py-16 bg-gray-100">
            <h2 class="text-3xl font-bold text-center mb-8">What Our Customers Say</h2>
            <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
                <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                    <p class="text-gray-500 mb-4">"Amazing quality and fast shipping! My new go-to store for all things
                        sportswear."</p>
                    <h3 class="font-bold text-lg">John Doe</h3>
                </div> -->

        <!-- Repeat similar testimonials... -->
        <!-- </div>
        </div> -->

        <!-- Newsletter Signup -->
        <!-- <div class="py-16 bg-yellow-500 text-white">
            <div class="container mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">Join Our Newsletter</h2>
                <p class="text-lg mb-6">Sign up for exclusive deals and be the first to know about new product releases.
                </p>
                <form class="flex justify-center">
                    <input type="email" class="w-1/2 px-4 py-3 rounded-l-lg text-gray-800"
                        placeholder="Enter your email">
                    <button class="bg-black px-6 py-3 rounded-r-lg font-semibold">Subscribe</button>
                </form>
            </div>
        </div> -->
    </section>
</template>

<!-- <script>
export default {
    name: 'SportShopHomepage'
}
</script> -->

<style>
/* Default background size for smaller screens */
.relative {
    /* background-size: 180%; */
    background-position: center;
    background-repeat: no-repeat;
}

/* Zoom out effect for large screens */
@media (min-width: 1024px) {

    /* Adjust the min-width as needed */
    .relative {
        background-size: 60%;
        /* Change this value to zoom out more or less */
        padding-top: 10px;
    }
}
</style>
