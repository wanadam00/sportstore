<template>
    <div class="antialiased bg-gray-100 dark:bg-gray-900">
        <!-- navbar start -->
        <Navbar />
        <!-- end -->
        <!-- Sidebar -->
        <Sidebar2 />
        <!-- end -->
        <main class="p-4 md:ml-64 h-auto pt-14">

            <slot />
        </main>

    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Navbar from '@/Components/Navbar.vue'
import Sidebar2 from '@/Components/Sidebar2.vue'
// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})
</script>
