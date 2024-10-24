<template>
    <AppLayout2>
        <BrandList :brands = "brands" ></BrandList>
    </AppLayout2>

</template>

<script setup>
defineProps({
    brands:Array
})
// import AdminLayout from '@/Components/AppLayout'
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import BrandList from '@/Pages/Brand/BrandList.vue'
</script>
