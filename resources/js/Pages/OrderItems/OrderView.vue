<!-- File: resources/js/Pages/OrderDetails.vue -->
<template>
    <AppLayout2>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Order Details</h1>

            <div class="mb-6 border-b border-gray-300 pb-4">
                <p><strong>Order ID:</strong> {{ order.order_id }}</p>
                <p><strong>Status:</strong> {{ capitalizeInitialWords(order.status || 'N/A') }}</p>
                <p class="font-semibold"><strong>Total Price:</strong> RM {{ order.total_price }}</p>
                <p class="capitalize "><strong>Address:</strong> {{ capitalizeInitialWords(order.user_address) }}</p>
            </div>

            <h2 class="text-xl font-semibold mb-3">Order Items</h2>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product ID</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantity</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit
                            Price</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(item, index) in order.items" :key="index">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.product_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 ">{{ item.product_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">RM {{ item.unit_price }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">RM {{ item.quantity *
                            item.unit_price }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout2>
</template>

<script setup>
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const order = props.order;

const capitalizeInitialWords = (str) => {
    if (!str) return '';
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
};
</script>

<style scoped>
/* Add custom styling if needed */
</style>
