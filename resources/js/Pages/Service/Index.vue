<template>
    <AppLayout2>
        <ServiceList :services = "services" ></ServiceList>
    </AppLayout2>

</template>

<script setup>
defineProps({
    services:Array
})
// import AdminLayout from '@/Components/AppLayout'
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import ServiceList from '@/Pages/Service/ServiceList.vue'
</script>
