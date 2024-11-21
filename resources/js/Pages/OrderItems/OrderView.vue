<!-- File: resources/js/Pages/OrderDetails.vue -->
<template>
    <AppLayout2>
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Order Details</h1>
            <div class=" mb-6 border-b border-gray-300 pb-5 flex flex-row">
                <div class="gap-2 flex flex-col">
                    <p class="font-semibold">Order ID</p>
                    <p class="font-semibold">Contact Number</p>
                    <p class="font-semibold">Customer</p>
                    <p class="font-semibold">Status</p>
                    <p v-if="order.status === 'paid'" class="font-semibold">Total Price</p>
                    <p class="font-semibold">Delivery Date</p>
                    <p class="font-semibold">Address </p>
                </div>
                <div class=" flex flex-col gap-2">
                    <span class=" ml-2">: #{{ order.order_id }}</span>
                    <span class=" ml-2">: {{ order.phone_number }}</span>
                    <span class=" ml-2 uppercase">: {{ capitalizeInitialWords(order.user_name) }}</span>
                    <div class="ml-2"><span>:</span>
                        <span :class="{
                            'text-green-500': order.status === 'paid',
                            'text-red-500': order.status === 'unpaid',
                            'text-gray-500': order.status !== 'paid' && order.status !== 'unpaid' // Optional: for other statuses
                        }" class="ml-1 uppercase">
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
                    <span v-if="order.status === 'paid'" class=" ml-2 uppercase">: {{
                        formatDate(order.estimated_delivery_date)
                        }}</span>
                    <div class="uppercase ml-2"><span>: </span><span
                            v-html="capitalizeInitialWords(order.user_address)"></span>
                    </div>
                </div>
            </div>

            <div class="flex justify-between mb-2">
                <h2 class="text-xl font-semibold mb-3">Order Items</h2>
                <!-- Print Button -->
                <div v-if="order.status === 'paid'" class="">
                    <button @click="printInvoice" class="bg-[#1a1a1a] text-sm text-white px-2 py-1 rounded hover:bg-[#0f0f0f]">
                        Print Invoice
                    </button>
                </div>
            </div>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                capitalizeInitialWords(item.product_name) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">RM {{ item.unit_price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">RM {{ item.quantity *
                                item.unit_price }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Hidden Printable Content -->
        <div id="printable" class="hidden">
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-4">Invoice</h1>
                <p><strong>Order ID:</strong> #{{ order.order_id }}</p>
                <p><strong>Customer Name:</strong> <span class="uppercase">{{ order.user_name }}</span></p>
                <p><strong>Contact Number:</strong> {{ order.phone_number }}</p>
                <p><strong>Address:</strong><br> <span class="uppercase"
                        v-html="capitalizeInitialWords(order.user_address)"></span></p>
                <!-- <p><strong>Status:</strong> {{ capitalizeInitialWords(order.status) }}</p> -->
                <p><strong>Total Price:</strong> RM {{ order.total_price }}</p>

                <h2 class="text-lg font-semibold mt-4">Order Items:</h2>
                <table class="min-w-full divide-y divide-gray-200 mt-2">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Quantity</th>
                            <th class="px-4 py-2">Unit Price</th>
                            <th class="px-4 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in order.items" :key="index">
                            <td class="px-4 py-2">{{ capitalizeInitialWords(item.product_name) }}</td>
                            <td class="px-4 py-2">{{ item.quantity }}</td>
                            <td class="px-4 py-2">RM {{ item.unit_price }}</td>
                            <td class="px-4 py-2">RM {{ item.quantity * item.unit_price }}</td>
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

const formatDate = (dateString) => {
    const date = new Date(dateString);
    // Check if the date is valid
    if (isNaN(date.getTime())) {
        return 'N/A'; // Return 'N/A' if the date is invalid
    }
    // Get day, month, and year
    const day = String(date.getDate()).padStart(2, '0'); // Pad single digit days with a leading zero
    const month = date.toLocaleString('default', { month: 'short' }); // Get short month name
    const year = date.getFullYear(); // Get full year
    // Get time components
    const options = {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true // Change to true for 12-hour format
    };
    const time = date.toLocaleTimeString('en-MY', options); // Format time
    // Combine date and time
    return `${day} ${month} ${year} ${time}`; // Format: "01 Jan 2023 02:00:00 PM"
};

const printInvoice = () => {
    const printableContent = document.getElementById('printable').innerHTML;
    const printWindow = window.open('', 'invoice');
    printWindow.document.open();
    printWindow.document.write(`
        <html>
        <head>
            <title>Invoice</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                }
                th {
                    background-color: #f4f4f4;
                }
                h1 {
                    font-size: 24px;
                }
                .uppercase {
                    text-transform: uppercase; /* Add this line */
                }
                .text-right {
                    text-align: right;
                }
            </style>
        </head>
        <body>
            ${printableContent}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
};
</script>
