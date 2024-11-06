<!-- File: resources/js/Pages/OrderDetails.vue -->
<template>
    <AppLayout2>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Order Details</h1>
            <div class=" mb-6 border-b border-gray-300 pb-5 flex flex-row">
                <div class="gap-2 flex flex-col">
                    <p class="font-medium"><strong>Order ID</strong></p>
                    <p class="font-medium"><strong>Customer</strong></p>
                    <p><strong>Status</strong></p>
                    <p class="font-medium"><strong>Total Price</strong></p>
                    <p class="capitalize font-medium"><strong>Address</strong> </p>
                </div>
                <div class=" flex flex-col gap-2">
                    <span class=" ml-2">: #{{ order.order_id }}</span>
                    <span class=" ml-2">: {{
                        capitalizeInitialWords(order.user_name) }}</span>
                    <div class="ml-2"><span>:</span>
                        <span :class="{
                            'text-green-500': order.status === 'paid',
                            'text-red-500': order.status === 'unpaid',
                            'text-gray-500': order.status !== 'paid' && order.status !== 'unpaid' // Optional: for other statuses
                        }" class="ml-1">
                            {{ capitalizeInitialWords(order.status || 'N/A') }}
                        </span>
                    </div>
                    <div class="ml-2"><span>:</span>
                        <span :class="{
                            'text-green-500': order.status === 'paid',
                            'text-red-500': order.status === 'unpaid',
                            'text-gray-500': order.status !== 'paid' && order.status !== 'unpaid' // Optional: for other statuses
                        }" class="">
                            RM {{ order.total_price }}
                        </span>
                    </div>
                    <div class=" ml-2"><span>: </span><span v-html="capitalizeInitialWords(order.user_address)"></span>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-semibold mb-3">Order Items</h2>
            <div class="overflow-x-auto"> <!-- Added wrapper for horizontal scrolling -->
                <table class="min-w-full divide-y divide-gray-200 shadow-lg rounded-lg overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <!-- <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product ID</th> -->
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Unit
                                Price</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white ">
                        <tr v-for="(item, index) in order.items" :key="index" class="hover:bg-gray-200">
                            <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.product_id }}</td> -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 ">{{ item.product_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">RM {{ item.unit_price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">RM {{ item.quantity *
                                item.unit_price }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
