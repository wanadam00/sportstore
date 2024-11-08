<template>
    <AppLayout2>
        <CategoryList :categories="categories"></CategoryList>
        <PaginationLinks :pagination="pagination" routeName="categories.index"
            :query="{ search: searchQuery, filter: selectedFilter }" />
    </AppLayout2>

</template>

<script setup>
defineProps({
    categories: Array,
    pagination: Object,
    searchQuery: String,        // Optional search or filter parameter
    selectedFilter: String,     // Optional filter parameter
})
// import AdminLayout from '@/Components/AppLayout'
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import CategoryList from '@/Pages/Category/CategoryList.vue'
import PaginationLinks from '@/Components/PaginationLinks.vue';
</script>
