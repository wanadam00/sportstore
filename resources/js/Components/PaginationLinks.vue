<template>
    <!-- Pagination Controls -->
    <div class="col-span-full flex justify-center space-x-3">
        <button
            :disabled="!pagination.prev_page_url"
            @click="fetchPage(pagination.prev_page_url)"
            class="px-4 py-2 bg-white text-[#1a1a1a] text-xs rounded disabled:opacity-50 shadow-md hover:bg-gray-200"
        >
            Previous
        </button>

        <button
            v-for="page in Array.from({ length: pagination.last_page }, (_, i) => i + 1)"
            :key="page"
            :class="{
                'px-4 py-2 text-xs rounded': true,
                'bg-[#1a1a1a] hover:bg-[#0f0f0f] text-white shadow-lg': page === pagination.current_page,
                'bg-white text-[#1a1a1a] shadow-lg hover:bg-gray-200': page !== pagination.current_page,
            }"
            @click="fetchPage(routeWithPage(page))"
        >
            {{ page }}
        </button>

        <button
            :disabled="!pagination.next_page_url"
            @click="fetchPage(pagination.next_page_url)"
            class="px-4 py-2 bg-white text-[#1a1a1a] text-xs shadow-lg rounded disabled:opacity-50 hover:bg-gray-200"
        >
            Next
        </button>
    </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia';
import { defineProps } from 'vue';
// import route from 'ziggy-js'; // Optional: if you’re using Ziggy for named routes

// Define props to accept pagination data and route name
const props = defineProps({
    pagination: Object,      // Pagination data
    routeName: String,       // Route name to use for page navigation
    query: Object,           // Additional query parameters (optional)
});

// Helper function to navigate to a specific page URL
function fetchPage(url) {
    if (url) {
        Inertia.visit(url);
    }
}

// Build the route URL with a specified page number
function routeWithPage(page) {
    return route(props.routeName, { page, ...props.query });
}
</script>
