<script setup>
import { ref, onMounted } from 'vue';

const images = [
    '/images/adidas/side.avif',
    '/images/nike/white-side.webp',
    '/images/puma/brown-side.avif',
    // '/images/other-brand/side.avif',
    // Add more images here...
];

const currentImage = ref('');

// Function to change the background image
const changeBackgroundImage = () => {
    const randomIndex = Math.floor(Math.random() * images.length);
    currentImage.value = images[randomIndex];
};

// Set the initial background image and start the interval
onMounted(() => {
    changeBackgroundImage(); // Set initial background image
    setInterval(changeBackgroundImage, 2000); // Change image every 5 seconds
});
</script>
<template>
    <section class="bg-gray-100 dark:bg-gray-900">
        <!-- Hero Section -->
        <div class="relative bg-cover bg-center h-screen" :style="{ backgroundImage: `url(${currentImage})` }">
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

        <!-- Brands Section -->
        <div class="py-16 bg-white">
            <h2 class="text-3xl font-bold text-center mb-8">Featured Brands</h2>
            <div class="container mx-auto flex flex-wrap justify-center gap-8">
                <img src="/images/brands/nike-logo.png" alt="Nike" class="h-12">
                <img src="/images/brands/adidas-logo.png" alt="Adidas" class="h-12">
                <img src="/images/brands/puma-logo.png" alt="Puma" class="h-12">
                <!-- <img src="/images/brands/underarmour-logo.png" alt="Under Armour" class="h-12"> -->
                <!-- Add more brand logos -->
            </div>
        </div>

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

<script>
export default {
    name: 'SportShopHomepage'
}
</script>

<style scoped>
/* Add any custom styles */
</style>
