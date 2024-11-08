<template>
    <AppLayout2>
        <BrandList :brands="brands"></BrandList>
        <div class="col-span-full flex justify-center space-x-2">
            <PaginationLinks :pagination="pagination" routeName="brands.index"
                :query="{ search: searchQuery, filter: selectedFilter }" />
        </div>
    </AppLayout2>

</template>

<script setup>
defineProps({
    brands: Array,
    pagination: Object,
    searchQuery: String,        // Optional search or filter parameter
    selectedFilter: String,     // Optional filter parameter
})
// import AdminLayout from '@/Components/AppLayout'
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import BrandList from '@/Pages/Brand/BrandList.vue'
import PaginationLinks from '@/Components/PaginationLinks.vue';
</script>
