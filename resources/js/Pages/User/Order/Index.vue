<template>
    <UserLayouts>
        <div class="container mx-auto p-4 py-20">
            <h1 class="text-2xl font-bold mb-4">Your Orders</h1>

            <!-- Filter Options as Buttons -->
            <div class="flex mb-4">
                <span class=""></span>
                <button @click="selectedStatus = ''"
                    :class="{ 'text-yellow-500 border-b-2 border-yellow-500 py-2 text-lg px-6': selectedStatus === '', 'border-b-2 border-gray-300 py-2 text-lg px-6': selectedStatus !== '' }"
                    class="border-b-2 border-gray-300 py-2 text-lg px-6">
                    All
                </button>
                <button @click="selectedStatus = 'paid'"
                    :class="{ 'text-yellow-500 border-b-2 border-yellow-500 py-2 text-lg px-6': selectedStatus === 'paid', 'border-b-2 border-gray-300 py-2 text-lg px-6': selectedStatus !== 'paid' }"
                    class="border-b-2 border-gray-300 py-2 text-lg px-6">
                    Paid
                </button>
                <button @click="selectedStatus = 'unpaid'"
                    :class="{ 'text-yellow-500 border-b-2 border-yellow-500 py-2 text-lg px-6': selectedStatus === 'unpaid', 'border-b-2 border-gray-300 py-2 text-lg px-6': selectedStatus !== 'unpaid' }"
                    class="border-b-2 border-gray-300 py-2 text-lg px-6">
                    Unpaid
                </button>
                <button @click="selectedStatus = 'to_ship'"
                    :class="{ 'text-yellow-500 border-b-2 border-yellow-500 py-2 text-lg px-6': selectedStatus === 'to_ship', 'border-b-2 border-gray-300 py-2 text-lg px-6': selectedStatus !== 'to_ship' }"
                    class="border-b-2 border-gray-300 py-2 text-lg px-6 whitespace-nowrap">
                    To Ship
                </button>
                <button @click="selectedStatus = 'shipped'"
                    :class="{ 'text-yellow-500 border-b-2 border-yellow-500 py-2 text-lg px-6': selectedStatus === 'shipped', 'border-b-2 border-gray-300 py-2 text-lg px-6': selectedStatus !== 'shipped' }"
                    class="border-b-2 border-gray-300 py-2 text-lg px-6">
                    Shipped
                </button>
                <button @click="selectedStatus = 'delivered'"
                    :class="{ 'text-yellow-500 border-b-2 border-yellow-500 py-2 text-lg px-6': selectedStatus === 'delivered', 'border-b-2 border-gray-300 py-2 text-lg px-6': selectedStatus !== 'delivered' }"
                    class="border-b-2 border-gray-300 py-2 text-lg px-6">
                    Delivered
                </button>
                <!-- <button @click="selectedStatus = 'pending'"
                    :class="{ 'text-indigo-500 border-b-2 border-indigo-500 py-2 text-lg px-6': selectedStatus === 'pending', 'border-b-2 border-gray-300 py-2 text-lg px-6': selectedStatus !== 'pending' }"
                    class="border-b-2 border-gray-300 py-2 text-lg px-6">
                    Pending
                </button> -->
            </div>

            <div v-for="order in filteredOrders" :key="order.id" class="mb-8 p-4 border space-y-0.5 rounded">
                <h2 class="text-xl font-semibold">Order #{{ order.id }}</h2>
                <p><strong>Order Date:</strong> {{ formatDate(order.order_date) }}</p>
                <p><strong>Status:</strong> {{ capitalizeInitialWords(order.status) }}</p>
                <p><strong>Total Price:</strong> RM
                    <span v-if="order.promo_price && order.promo_price > 0">
                        {{ order.promo_price.toFixed(2) }} <span class="text-sm text-gray-500 line-through">RM {{
                            order.total_price.toFixed(2) }}</span>
                    </span>
                    <span v-else>
                        {{ order.total_price.toFixed(2) }}
                    </span>
                </p>
                <template v-if="['to_ship', 'shipped', 'delivered'].includes(order.shipment_status)">
                    <p><strong>Estimated Delivery:</strong> {{ formatDate(order.estimated_delivery_date) }}</p>
                    <p><strong>Tracking Number:</strong> {{ order.tracking_number || 'N/A' }}</p>
                </template>
                <p><strong>Shipping Address:</strong> <br><span v-html="capitalizeInitialWords(order.user_address)"></span>
                </p>
                <p><strong>Contact Number:</strong> <span v-html="capitalizeInitialWords(order.phone_number)"></span>
                </p>
                <h3 class="pt-3 font-semibold">Items:</h3>
                <ul>
                    <li v-for="item in order.items" :key="item.product_id">
                        <div class="flex flex-row">
                            <div class="flex">
                                <img v-if="item.product_images.length > 0" :src="`/${item.product_images[0].image}`"
                                    alt="Product Image" class="w-36 h-36 object-contain rounded-sm mb-4">
                                <img v-else
                                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                                    alt="No Image Available" class="w-36 h-36 object-contain mr-2">
                            </div>
                            <div class="flex mt-16 ml-8">
                                {{ capitalizeInitialWords(item.product_name) }} - {{ item.quantity }} x RM {{
                                    item.unit_price.toFixed(2) }}
                            </div>
                        </div>
                    </li>
                </ul>
                <!-- Conditional form for unpaid orders -->
                <div v-if="order.status === 'unpaid'" class="mt-4">
                    <form @submit.prevent="checkout(order)">
                        <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            class="bg-yellow-500 hover:bg-yellow-700 text-gray-900 font-semibold px-4 py-2 rounded">
                            Make Payment
                        </button>
                    </form>
                </div>
                <div v-if="order.shipment_status === 'shipped'" class="mt-4">
                    <button @click="markAsDelivered(order.id)"
                        class="bg-green-500 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">
                        Delivered
                    </button>
                </div>
            </div>
        </div>
    </UserLayouts>
</template>

<script setup>
import UserLayouts from '../Layouts/UserLayouts.vue';
import { Inertia } from '@inertiajs/inertia';
import { router, usePage, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const { props } = usePage();
const orders = props.orders;

const form = useForm({
    products: [],
});

const selectedStatus = ref(''); // State for selected status

// Computed property to filter orders based on selected status
const filteredOrders = computed(() => {
    if (!selectedStatus.value) return orders; // Return all orders if no filter is selected
    return orders.filter(order => {
        if (selectedStatus.value === 'paid') {
            return order.status === 'paid'; // Orders that are paid but not yet shipped
        }
        else if (selectedStatus.value === 'unpaid') {
            return order.status === 'unpaid'; // Orders that are paid but not yet shipped
        }
        else if (selectedStatus.value === 'to_ship') {
            return order.shipment_status === 'to_ship'; // Orders that are paid but not yet shipped
        }
        else if (selectedStatus.value === 'shipped') {
            return order.shipment_status === 'shipped'; // Orders that are paid but not yet shipped
        }
        else if (selectedStatus.value === 'delivered') {
            return order.shipment_status === 'delivered'; // Orders that are paid but not yet shipped

        }
        return order.status === selectedStatus.value;
    });
});

// Function to capitalize the first letter of each word
const capitalizeInitialWords = (str) => {
    if (!str) return '';
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
};

// Function to format date (you can customize this as needed)
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const checkout = (order) => {
    const routeName = order.status === 'unpaid' ? 'checkout.retryPayment' : 'checkout.store';
    const data = {
        carts: usePage().props.cart.data.items,
        products: order,
        total: usePage().props.cart.data.total,
        address: order.user_address,
        order_id: order.id,
    };

    Inertia.post(route(routeName, { orderId: order.id }), data)
        .then(response => {
            console.log('Checkout successful:', response);
        })
        .catch(error => {
            console.error('Checkout failed:', error);
        });
};
const markAsDelivered = async (orderId) => {
    try {
        // Prepare the data to send
        const data = {
            shipment_status: 'delivered' // Set the shipment status to 'delivered'
        };

        // Make an API call to update the shipment status
        await axios.put(`/orders/user-update-shipment/${orderId}`, data);
        router.visit(route('user.orders.index'));

        // Optionally, refresh the orders or update the local state
        // Example: fetchOrders();
    } catch (error) {
        console.error('Error marking order as delivered:', error);
    }
};
</script>
