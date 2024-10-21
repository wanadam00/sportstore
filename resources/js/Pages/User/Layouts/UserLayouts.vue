<script setup>
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import Header from '@/Pages/User/Layouts/Header.vue';
import Footer from '@/Pages/User/Layouts/Footer.vue';
import Hero from '@/Pages/User/Layouts/Hero.vue';

// initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})



</script>
<template>
    <!-- header -->
    <Header></Header>
    <!-- end -->
    <!-- main content -->

    <slot />
    <!-- end -->
    <!-- footer -->

    <Footer></Footer>

    <!-- end -->
</template>
