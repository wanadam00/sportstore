<script setup>
import UserLayouts from './Layouts/UserLayouts.vue';
import { ref, watch } from 'vue'
import {
    Dialog,
    DialogPanel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { ChevronDownIcon, FunnelIcon, MinusIcon, PlusIcon, Squares2X2Icon } from '@heroicons/vue/20/solid'
import Products from '@/Pages/User/Components/Products.vue'
import SecondaryButtonVue from '@/Components/SecondaryButton.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import PaginationLinks from './Components/PaginationLinks.vue';
// import { Inertia } from '@inertiajs/inertia';
const sortOptions = [
    { name: 'Most Popular', href: '#', current: true },
    { name: 'Best Rating', href: '#', current: false },
    { name: 'Newest', href: '#', current: false },
    { name: 'Price: Low to High', href: '#', current: false },
    { name: 'Price: High to Low', href: '#', current: false },
]
const pageProps = usePage().props;
const search = ref(pageProps.filters.search || '');

const searchProducts = () => {
    Inertia.get(route('products.list'), { search: search.value }, { preserveState: true });
};

// Define and initialize with a single price input for both ranges
const filterPrices = useForm({
    price: '' // Single input field for max price
});

// Method for price filter
const priceFilter = () => {
    const maxPrice = filterPrices.price; // Retrieve single price input value

    filterPrices.transform((data) => ({
        ...data,
        prices: {
            from: 0, // Set from to 0 or adjust as needed
            to: maxPrice
        },
        promoPrices: {
            from: 0, // Set from to 0 or adjust as needed
            to: maxPrice
        }
    })).get('product', {
        preserveState: true,
        replace: true
    });
};

const mobileFiltersOpen = ref(false)

const props = defineProps({
    // products: Object,
    brands: Array,
    categories: Array,
    products: Object,
    pagination: Object,
    searchQuery: String,        // Optional search or filter parameter
    selectedFilter: String,     // Optional filter parameter

});

// Function to navigate to the provided page URL
function fetchPage(url) {
    if (url) {
        Inertia.visit(url);
    }
}

//filter brands and categories
const selectedBrands = ref([])
const selectedCategories = ref([])

watch(selectedBrands, () => {
    updateFilteredProducts()
})
watch(selectedCategories, () => {
    updateFilteredProducts()
})

function updateFilteredProducts() {
    router.get('product', {
        brands: selectedBrands.value,
        categories: selectedCategories.value
    }, {
        preserveState: true,
        replace: true
    })
}

</script>
<template>
    <UserLayouts>
        <div class="bg-white">
            <div>
                <!-- Mobile filter dialog -->
                <TransitionRoot as="template" :show="mobileFiltersOpen">
                    <Dialog as="div" class="relative z-40 lg:hidden" @close="mobileFiltersOpen = false">
                        <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                            enter-from="opacity-0" enter-to="opacity-100"
                            leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                            leave-to="opacity-0">
                            <div class="fixed inset-0 bg-black bg-opacity-25" />
                        </TransitionChild>

                        <div class="fixed inset-0 z-40 flex">
                            <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                                enter-from="translate-x-full" enter-to="translate-x-0"
                                leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                                leave-to="translate-x-full">
                                <DialogPanel
                                    class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-12 shadow-xl">
                                    <div class="flex items-center justify-between px-4">
                                        <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                                        <button type="button"
                                            class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400"
                                            @click="mobileFiltersOpen = false">
                                            <span class="sr-only">Close menu</span>
                                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                        </button>
                                    </div>

                                    <!-- Filters -->
                                    <form class="mt-4 border-t border-gray-200">
                                        <h3 class="sr-only">Categories</h3>
                                        <ul role="list" class="px-2 py-3 font-medium text-gray-900">
                                            <li v-for="category in subCategories" :key="category.name">
                                                <a :href="category.href" class="block px-2 py-3">{{ category.name }}</a>
                                            </li>
                                        </ul>

                                        <Disclosure as="div" v-for="section in filters" :key="section.id"
                                            class="border-t border-gray-200 px-4 py-6" v-slot="{ open }">
                                            <h3 class="-mx-2 -my-3 flow-root">
                                                <DisclosureButton
                                                    class="flex w-full items-center justify-between bg-white px-2 py-3 text-gray-400 hover:text-gray-500">
                                                    <span class="font-medium text-gray-900">{{ section.name }}</span>
                                                    <span class="ml-6 flex items-center">
                                                        <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                                        <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                                    </span>
                                                </DisclosureButton>
                                            </h3>
                                            <DisclosurePanel class="pt-6">
                                                <div class="space-y-6">
                                                    <div v-for="(option, optionIdx) in section.options"
                                                        :key="option.value" class="flex items-center">
                                                        <input :id="`filter-mobile-${section.id}-${optionIdx}`"
                                                            :name="`${section.id}[]`" :value="option.value"
                                                            type="checkbox" :checked="option.checked"
                                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                        <label :for="`filter-mobile-${section.id}-${optionIdx}`"
                                                            class="ml-3 min-w-0 flex-1 text-gray-500">{{ option.label
                                                            }}</label>
                                                    </div>
                                                </div>
                                            </DisclosurePanel>
                                        </Disclosure>
                                    </form>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </Dialog>
                </TransitionRoot>

                <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row items-baseline justify-between border-b border-gray-200 pb-6 pt-24">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900">New Arrivals</h1>
                        <!-- <h2 id="products-heading" class="sr-only">Products</h2> -->
                        <div class=" flex items-center pt-2">
                            <button @click="searchProducts" class="absolute ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6 ">
                                    <defs>
                                        <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                                            <feGaussianBlur in="SourceAlpha" stdDeviation="2" />
                                            <feOffset dx="1" dy="1" result="offsetblur" />
                                            <feFlood flood-color="rgba(0, 0, 0, 0.5)" />
                                            <feComposite in2="offsetblur" operator="in" />
                                            <feMerge>
                                                <feMergeNode />
                                                <feMergeNode in="SourceGraphic" />
                                            </feMerge>
                                        </filter>
                                    </defs>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                                        filter="url(#shadow)" />
                                </svg>
                            </button>
                            <input v-model="search" placeholder="Search by name" @keyup.enter="searchProducts"
                                class="rounded-lg text-sm  border border-gray-300 bg-gray-50 p-2.5 text-center" />
                        </div>

                        <!-- <div class="flex items-center">
                            <Menu as="div" class="relative inline-block text-left">
                                <div>
                                    <MenuButton
                                        class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                                        Sort
                                        <ChevronDownIcon
                                            class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                                            aria-hidden="true" />
                                    </MenuButton>
                                </div>

                                <transition enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <div class="py-1">
                                            <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                                            <a :href="option.href"
                                                :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">{{
                                                    option.name }}</a>
                                            </MenuItem>
                                        </div>
                                    </MenuItems>
                                </transition>
                            </Menu>

                            <button type="button" class="-m-2 ml-5 p-2 text-gray-400 hover:text-gray-500 sm:ml-7">
                                <span class="sr-only">View grid</span>
                                <Squares2X2Icon class="h-5 w-5" aria-hidden="true" />
                            </button>
                            <button type="button" class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden"
                                @click="mobileFiltersOpen = true">
                                <span class="sr-only">Filters</span>
                                <FunnelIcon class="h-5 w-5" aria-hidden="true" />
                            </button>
                        </div> -->
                    </div>
                    <section aria-labelledby="products-heading" class="pb-24 pt-6">
                        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                            <!-- Filters -->
                            <form class=" lg:block">
                                <h3 class="sr-only">Prices</h3>
                                <!-- single input price filter -->
                                <div class="flex items-center space-x-3">
                                    <div class="basis-3/3">
                                        <label for="filters-price"
                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Price Range
                                        </label>
                                        <SecondaryButtonVue @click="priceFilter()" class="absolute ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6 mt-2">
                                                <defs>
                                                    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                                                        <feGaussianBlur in="SourceAlpha" stdDeviation="2" />
                                                        <feOffset dx="1" dy="1" result="offsetblur" />
                                                        <feFlood flood-color="rgba(0, 0, 0, 0.5)" />
                                                        <feComposite in2="offsetblur" operator="in" />
                                                        <feMerge>
                                                            <feMergeNode />
                                                            <feMergeNode in="SourceGraphic" />
                                                        </feMerge>
                                                    </filter>
                                                </defs>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                                                    filter="url(#shadow)" />
                                            </svg>
                                        </SecondaryButtonVue>
                                        <input type="number" id="filters-price" placeholder="Enter max price"
                                            v-model="filterPrices.price" @keyup.enter="priceFilter"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-center focus:border-primary-500 focus:ring-primary-500" />
                                    </div>
                                    <!-- <SecondaryButtonVue class="self-end" @click="priceFilter()">
                                        Ok
                                    </SecondaryButtonVue> -->
                                </div>

                                <!-- Promotional Price Filters -->
                                <!-- <div class="flex items-center justify-between space-x-3 mt-4">
                                    <div class="basis-1/3">
                                        <label for="filters-promo-price-from"
                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Promo From
                                        </label>
                                        <input type="number" id="filters-promo-price-from" placeholder="Min promo price"
                                            v-model="filterPrices.promoPrices[0]"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
                                    </div>
                                    <div class="basis-1/3">
                                        <label for="filters-promo-price-to"
                                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Promo To
                                        </label>
                                        <input type="number" id="filters-promo-price-to"
                                            v-model="filterPrices.promoPrices[1]" placeholder="Max promo price"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" />
                                    </div>
                                    <SecondaryButtonVue class="self-end" @click="promoPriceFilter()">
                                        Ok
                                    </SecondaryButtonVue>
                                </div> -->


                                <!-- end -->

                                <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }">
                                    <h3 class="-my-3 flow-root">
                                        <DisclosureButton
                                            class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                                            <span class="font-medium text-gray-900">Brands</span>
                                            <span class="ml-6 flex items-center">
                                                <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                                <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                            </span>
                                        </DisclosureButton>
                                    </h3>
                                    <DisclosurePanel class="pt-6">
                                        <div class="space-y-4">
                                            <div v-for="brand in brands" :key="brand.id" class="flex items-center">
                                                <input :id="`filter-${brand.id}`" :value="brand.id" type="checkbox"
                                                    v-model="selectedBrands"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label :for="`filter-${brand.id}`" class="ml-3 text-sm text-gray-600">{{
                                                    brand.name }}</label>
                                            </div>
                                        </div>
                                    </DisclosurePanel>
                                </Disclosure>


                                <!-- category filter -->

                                <Disclosure as="div" class="border-b border-gray-200 py-6" v-slot="{ open }">
                                    <h3 class="-my-3 flow-root">
                                        <DisclosureButton
                                            class="flex w-full items-center justify-between bg-white py-3 text-sm text-gray-400 hover:text-gray-500">
                                            <span class="font-medium text-gray-900">Categories</span>
                                            <span class="ml-6 flex items-center">
                                                <PlusIcon v-if="!open" class="h-5 w-5" aria-hidden="true" />
                                                <MinusIcon v-else class="h-5 w-5" aria-hidden="true" />
                                            </span>
                                        </DisclosureButton>
                                    </h3>
                                    <DisclosurePanel class="pt-6">
                                        <div class="space-y-4">
                                            <div v-for="category in categories" :key="category.id"
                                                class="flex items-center">
                                                <input :id="`filter-${category.id}`" :value="category.id"
                                                    type="checkbox" v-model="selectedCategories"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                                <label :for="`filter-${category.id}`"
                                                    class="ml-3 text-sm text-gray-600">{{
                                                        category.name }}</label>
                                            </div>
                                        </div>
                                    </DisclosurePanel>
                                </Disclosure>

                                <!-- end -->
                            </form>

                            <!-- Product grid -->
                            <div class="lg:col-span-3">
                                <!-- Your content -->
                                <Products :products="products.data"></Products>
                            </div>
                            <!-- Pagination Controls -->
                            <div class="col-span-full flex justify-center space-x-2">
                                <PaginationLinks :pagination="pagination" routeName="products.list"
                                    :query="{ search: searchQuery, filter: selectedFilter }" />
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>

    </UserLayouts>
</template>
