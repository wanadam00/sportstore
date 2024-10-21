<template>
    <AppLayout2>
        <ProductList :products = "products" ></ProductList>
    </AppLayout2>

</template>

<script setup>
defineProps({
    products:Array
})
// import AdminLayout from '@/Components/AppLayout'
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import ProductList from '@/Pages/Product/ProductList.vue'
</script>
