<script setup>
import { computed, reactive } from 'vue'

import UserLayouts from './Layouts/UserLayouts.vue';
import { router, usePage, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    userAddress: Object
})

const carts = computed(() => usePage().props.cart.data.items)
const products = computed(() => usePage().props.cart.data.products)

const total = computed(() => {
    return carts.value.reduce((sum, item) => {
        // Find the corresponding product in the products list using product_id
        const product = products.value.find(p => p.id === item.product_id);

        // Check if the product has a promo_price, use it if available, otherwise use the regular price
        const price = product && product.promo_price > 0 ? product.promo_price : product.price;

        // Calculate the price for this product (quantity * price) and add it to the total
        return sum + (item.quantity * price);
    }, 0).toFixed(2);  // Ensures the total has two decimal places
});

const itemId = (id) => carts.value.findIndex((item) => item.product_id === id);

const form = useForm({
    address1: props.userAddress?.address1,
    state: props.userAddress?.state,
    city: props.userAddress?.city,
    postcode: props.userAddress?.postcode,
    country_name: props.userAddress?.country_name,
    // type: props.userAddress?,
    phone_number: props.userAddress?.phone_number,
    carts: carts,
    products: products,
    total: total,
})
const formFilled = computed(() => {
    return (form.address1 !== null &&
        form.state !== null &&
        form.city !== null &&
        form.postcode !== null &&
        form.country_name !== null &&
        form.phone_number !== null
        // form.country_code !== null
        // form.type !== null
    )
})



const update = (product, quantity) =>
    router.patch(route('cart.update', product), {
        quantity,
    });
//remove form cart
// const remove = (product) => {
//     form.delete(route('cart.delete', product))
// };

const remove = (product) => {
    // console.log(product);
    form.delete(route("cart.delete", product), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        },
    });
};
//confirm order

function submit() {
    form.post(route('checkout.store'), {
        errorBag: 'createOrder',
        method: 'post',
        data: {
            carts: usePage().props.cart.data.items,
            products: usePage().props.cart.data.products,
            total: usePage().props.cart.data.total,
            address: form
        }
    })
}
const capitalizeInitialWords = (str) => {
    if (!str) return '';
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
};

// Function to handle checkout based on address
const checkAddressBeforeCheckout = () => {
    if (!props.userAddress || !props.userAddress.address1) {
        promptAddAddress();
    } else if (formFilled.value) {
        submit();
    } else {
        Swal.fire({
            title: 'Incomplete Address',
            text: 'Please complete your address details to proceed.',
            icon: 'warning',
            confirmButtonText: 'Complete Address',
        });
    }
};

// Function to prompt user to add address
const promptAddAddress = () => {
    Swal.fire({
        title: 'Address Required',
        text: 'Please add your address to proceed with checkout.',
        icon: 'warning',
        confirmButtonText: 'Add Address',
    }).then((result) => {
        if (result.isConfirmed) {
            router.visit(route('cart.view'));
        }
    });
};
</script>
<template>
    <UserLayouts>
        <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap">
                <div class="lg:w-2/3 md:w-1/2  rounded-lg  sm:mr-10 pt-4 ">

                    <!-- lis tof cart -->
                    <div class="block ">
                        <div v-for="product in products" :key="product.id"
                            class="flex flex-col bg-white border-b dark:bg-gray-800 dark:border-gray-700 p-4">
                            <div class="flex items-center">
                                <div class="w-24 h-24">
                                    <img v-if="product.product_images.length > 0"
                                        :src="`/${product.product_images[0].image}`" alt="Product Image"
                                        class="w-full h-full object-cover rounded-md" />
                                    <img v-else
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                                        alt="No Image Available" class="w-full h-full object-cover rounded-md" />
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{
                                        capitalizeInitialWords(product.name) }}</h3>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        RM {{ product.promo_price > 0 ? product.promo_price : product.price }}
                                    </p>
                                    <div class="flex items-center space-x-3 mt-2">
                                        <button @click.prevent="update(product, carts[itemId(product.id)].quantity - 1)"
                                            :disabled="carts[itemId(product.id)].quantity <= 1"
                                            class="cursor-pointer text-purple-600 h-8 w-8 flex items-center justify-center border rounded-full focus:outline-none">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 18 2">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                            </svg>
                                        </button>
                                        <input type="number" v-model="carts[itemId(product.id)].quantity"
                                            class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="1" required>
                                        <button @click.prevent="update(product, carts[itemId(product.id)].quantity + 1)"
                                            class="h-8 w-8 flex items-center justify-center border rounded-full focus:outline-none">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 18 18">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                            </svg>
                                        </button>
                                        <a @click="remove(product)"
                                            class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <table class="hidden md:table w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Image</span>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Product
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Qty
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="product in products" :key="product.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-32 p-4">
                                    <img v-if="product.product_images.length > 0"
                                        :src="`/${product.product_images[0].image}`" alt="Product Image"
                                        class="w-full h-auto object-cover rounded-md" />
                                    <img v-else
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/No-Image-Placeholder.svg/330px-No-Image-Placeholder.svg.png"
                                        alt="No Image Available" class="w-full h-auto object-cover rounded-md" />
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ capitalizeInitialWords(product.name) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <button @click.prevent="update(product, carts[itemId(product.id)].quantity - 1)"
                                            :disabled="carts[itemId(product.id)].quantity <= 1"
                                            :class="[carts[itemId(product.id)].quantity > 1 ? 'cursor-pointer text-purple-600' : 'cursor-not-allowed text-gray-300 dark:text-gray-500', 'inline-flex items-center justify-center p-1 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700']"
                                            type="button">
                                            <span class="sr-only">Quantity button</span>
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 18 2">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                            </svg>
                                        </button>
                                        <div>
                                            <input type="number" id="first_product"
                                                v-model="carts[itemId(product.id)].quantity"
                                                class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="1" required>
                                        </div>
                                        <button @click.prevent="update(product, carts[itemId(product.id)].quantity + 1)"
                                            class="inline-flex items-center justify-center h-6 w-6 p-1 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                            type="button">
                                            <span class="sr-only">Quantity button</span>
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 18 18">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    RM {{ product.promo_price > 0 ? product.promo_price : product.price }}
                                </td>
                                <td class="px-6 py-4">
                                    <a @click="remove(product)"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
                                </td>
                            </tr>
                        </tbody>
                    </table> -->

                    <!-- end -->
                </div>
                <div class="lg:w-3/3 md:w-1/2 bg-white flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0">
                    <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Summary</h2>
                    <p class="leading-relaxed mb-5 text-gray-600">Total : RM {{ total }} </p>

                    <div v-if="userAddress">
                        <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Shipping Address</h2>
                        <p class="leading-relaxed mb-5 text-gray-600">{{ capitalizeInitialWords(userAddress.address1)
                            }},<br>{{ capitalizeInitialWords(userAddress.city) }}, {{ (userAddress.postcode)
                            }}, {{ capitalizeInitialWords(userAddress.state) }}, <br>{{ capitalizeInitialWords(userAddress.country_name) }}</p>
                        <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Contact Number</h2>
                        <p class="leading-relaxed mb-5 text-gray-600">{{
                            capitalizeInitialWords(userAddress.phone_number) }}
                        </p>
                        <p class="leading-relaxed mb-5 text-gray-600">or you can add new below</p>

                    </div>

                    <div v-else>
                        <p class="leading-relaxed mb-5 text-gray-600"> Add shipping address to continue</p>
                    </div>



                    <form @submit.prevent="submit">
                        <!-- error {{ form.errors }} -->
                        <div class="relative mb-4">
                            <label for="name" class="leading-7 text-sm text-gray-600">Address</label>
                            <input type="text" id="name" name="address1" v-model="form.address1"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <span v-if="form.errors.address1" class="text-red-500 text-xs">{{ form.errors.address1
                                }}</span>
                        </div>
                        <div class="relative mb-4">
                            <label for="email" class="leading-7 text-sm text-gray-600">City</label>
                            <input type="text" id="email" name="city" v-model="form.city"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <span v-if="form.errors.city" class="text-red-500 text-xs">{{ form.errors.city }}</span>
                        </div>
                        <div class="relative mb-4">
                            <label for="email" class="leading-7 text-sm text-gray-600">Postcode</label>
                            <input type="number" id="email" name="postcode" v-model="form.postcode"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <span v-if="form.errors.postcode" class="text-red-500 text-xs">{{ form.errors.postcode
                                }}</span>
                        </div>
                        <div class="relative mb-4">
                            <label for="email" class="leading-7 text-sm text-gray-600">State</label>
                            <input type="text" id="email" name="state" v-model="form.state"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <span v-if="form.errors.state" class="text-red-500 text-xs">{{ form.errors.state }}</span>
                        </div>
                        <div class="relative mb-4">
                            <label for="email" class="leading-7 text-sm text-gray-600">Country</label>
                            <input type="text" id="email" name="countryname" v-model="form.country_name"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <span v-if="form.errors.country_name" class="text-red-500 text-xs">{{
                                form.errors.country_name
                                }}</span>
                        </div>
                        <div class="relative mb-4">
                            <label for="email" class="leading-7 text-sm text-gray-600">Contact Number</label>
                            <input type="text" id="email" name="phonenumber" v-model="form.phone_number"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            <span v-if="form.errors.phone_number" class="text-red-500 text-xs">{{
                                form.errors.phone_number }}</span>
                        </div>
                        <!-- <div class="relative mb-4">
                            <label for="code" class="leading-7 text-sm text-gray-600">Country Code</label>
                            <input type="number" id="code" name="countrycode" v-model="form.country_code"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div> -->
                        <!-- <div class="relative mb-4">
                            <label for="email" class="leading-7 text-sm text-gray-600">Address type</label>
                            <input type="text" id="email" name="type" v-model="form.type"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div> -->

                        <!-- <div v-if="!userHasAddress" class="text-red-500 mb-4">
                            You must add an address to continue.
                        </div> -->

                        <button v-if="formFilled || userAddress" type="submit"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Checkout</button>

                        <button v-else type="submit" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            class="text-white bg-gray-500 border-0 py-2 px-6 focus:outline-none hover:bg-gray-600 rounded text-lg">Add
                            Address to continue</button>

                    </form>

                    <Link :href="route('products.list')"
                        class="text-xs text-gray-500 mt-3 underline hover:font-semibold hover:text-gray-600">Continue
                    Shopping </Link>
                </div>
            </div>
        </section>
    </UserLayouts>
</template>
