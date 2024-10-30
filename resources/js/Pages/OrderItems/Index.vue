<!-- <template>
    <div>
        <h1>Order Items</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Product Name</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="orderItem in orderItems" :key="orderItem.id">
                    <td>{{ orderItem.order_id }}</td>
                    <td>{{ orderItem.product_id }}</td>
                    <td>{{ orderItem.quantity }}</td>
                    <td>{{ orderItem.unit_price }}</td>
                    <td>{{ orderItem.product.name || 'N/A' }}</td>
                    <td>{{ orderItem.order.status || 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    orderItems: Array,
});
</script> -->
<template>
    <AppLayout2>
        <OrderList :orders = "orders" ></OrderList>
    </AppLayout2>

</template>

<script setup>
defineProps({
    orders:Array
})
// import AdminLayout from '@/Components/AppLayout'
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import OrderList from '@/Pages/OrderItems/OrderList.vue'
</script>
