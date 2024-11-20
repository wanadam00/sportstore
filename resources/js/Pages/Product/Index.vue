<template>
    <AppLayout2>
        <ProductList :products="products" :categories="categories" :services="services" :brands="brands"/>
        <div class="col-span-full flex justify-center space-x-2">
            <PaginationLinks :pagination="pagination" routeName="products.index"
                :query="{ search: searchQuery, filter: selectedFilter }" />
        </div>
    </AppLayout2>

</template>

<script setup>
const props = defineProps({
    products: Array,
    categories: Array,
    services: Array,
    brands: Array,
    pagination: Object,
    searchQuery: String,        // Optional search or filter parameter
    selectedFilter: String,     // Optional filter parameter

})
// import AdminLayout from '@/Components/AppLayout'
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import ProductList from '@/Pages/Product/ProductList.vue'
import PaginationLinks from '@/Components/PaginationLinks.vue';
</script>
