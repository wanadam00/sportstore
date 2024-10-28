<template>
    <AppLayout2>
        <CategoryList :categories = "categories" ></CategoryList>
    </AppLayout2>

</template>

<script setup>
defineProps({
    categories:Array
})
// import AdminLayout from '@/Components/AppLayout'
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import CategoryList from '@/Pages/Category/CategoryList.vue'
</script>
