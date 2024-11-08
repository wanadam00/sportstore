<template>
    <AppLayout2>
        <ServiceList :services = "services" ></ServiceList>
        <PaginationLinks :pagination="pagination" routeName="products.services"
            :query="{ search: searchQuery, filter: selectedFilter }" />
    </AppLayout2>

</template>

<script setup>
defineProps({
    services: Array,
    pagination: Object,
    searchQuery: String,        // Optional search or filter parameter
    selectedFilter: String,     // Optional filter parameter
})
// import AdminLayout from '@/Components/AppLayout'
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import ServiceList from '@/Pages/Service/ServiceList.vue'
import PaginationLinks from '@/Components/PaginationLinks.vue';
</script>
