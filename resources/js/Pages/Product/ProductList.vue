<script setup>
import { router, usePage, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { Plus } from '@element-plus/icons-vue'
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    products: Array,
    categories: Array,
    services: Array,
    brands: Array,
})

const pageProps = usePage().props;
// const products = usePage().props.products;
// const brands = usePage().props.brands;
// const categories = usePage().props.categories;
// const services = usePage().props.services;
const search = ref(pageProps.filters.search || '');

// console.log(products);
const isAddProduct = ref(false);
const editMode = ref(false);
const dialogVisible = ref(false)

//upload mulitpel images
// const productImages = ref([]);
const dialogImageUrl = ref('');
const form = useForm({
    // id: '',
    _method: editMode ? '' : 'PUT',
    name: null,
    price: null,
    promo_price: null,
    quantity: null,
    description: '',
    category_id: null,
    brand_id: null,
    service_id: null,
    product_images: []
});

const searchProducts = () => {
    Inertia.get(route('products.index'), { search: search.value }, { preserveState: true });
};
// Handle file change when an image is uploaded
const handleFileChange = (file, fileList) => {
    // console.log('Uploaded file:', file);
    // console.log('Updated file list:', fileList);

    // // Update productImages with raw files
    // form.product_images = fileList.map(f => ({
    //     name: f.name,
    //     raw: f.raw,
    //     url: URL.createObjectURL(f.raw || f) // Generate preview URL for new files
    // }));
    form.product_images.push(file)
};

// Handle picture preview when an image is clicked
const handlePictureCardPreview = (file) => {
    form.product_images = file.url;  // Use the URL from the file object for preview
    dialogVisible.value = true;       // Open the dialog
};

// Handle removing images from the upload list
const handleRemove = (file, fileList) => {
    // console.log('Removed file:', file);
    // console.log('Updated file list:', fileList);

    // // Update productImages after removal
    // form.product_images = fileList.map(f => ({
    //     name: f.name,
    //     raw: f.raw,
    //     url: URL.createObjectURL(f.raw || f) // Regenerate URLs for remaining files
    // }));
};
//prodct from data
const id = ref('');
const name = ref('')
const price = ref('')
const promo_price = ref('')
const quantity = ref('')
const description = ref('')
const product_images = ref([])
// const published = ref('')
const category_id = ref('')
const brand_id = ref('')
const service_id = ref('')
// const inStock = ref('')
//end

const openEditModal = (product, index) => {

    // console.log(product, index);
    //updatde data
    // id.value = product.id;
    // name.value = product.name;
    // price.value = product.price;
    // promo_price.value = product.promo_price;
    // quantity.value = product.quantity;
    // description.value = product.description;
    // brand_id.value = product.brand_id;
    // service_id.value = product.service_id;
    // category_id.value = product.category_id;
    // product_images.value = product.product_images;
    form.id = product.id;
    form.name = product.name;
    form.quantity = product.quantity;
    form.price = product.price;
    form.promo_price = product.promo_price;
    form.description = product.description;
    form.category_id = product.category_id;
    form.brand_id = product.brand_id;
    form.service_id = product.service_id;
    form.product_images = product.product_images;

    editMode.value = true;
    isAddProduct.value = false
    dialogVisible.value = true
}

//open add modal
const openAddModal = () => {
    isAddProduct.value = true
    dialogVisible.value = true
    editMode.value = false;
    // resetFormData();
    form.reset();
}

// add product method
const AddProduct = async () => {
    form.post(
        route('products.store'),
        {
            errorBag: 'createProduct',
            preserveScroll: true,
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success,
                    timer: 3000,
                    timerProgressBar: true, // Optional: Show a progress bar
                })
                dialogVisible.value = false;
                // resetFormData();
                form.reset();
                router.visit(route('products.index'));
            },
            onError: (errors) => {
                // console.log(resp)
                form.errors = errors;
            }
        }
    );
}

//rest data after added
const resetFormData = () => {
    id.value = '';
    name.value = '';
    price.value = '';
    promo_price.value = '';
    quantity.value = '';
    category_id.value = '';
    brand_id.value = '';
    service_id.value = '';
    description.value = '';
    product_images.value = [];
    dialogImageUrl.value = ''
};

//delete sigal product image
const deleteImage = async (pimage, index) => {
    try {
        await router.delete('/products/image/' + pimage.id, {
            onSuccess: (page) => {
                form.product_images.splice(index, 1);
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
        })
    } catch (err) {
        console.log(err);
    }
}

//update product method
const updateProduct = async () => {
    const formData = new FormData();
    formData.append('name', form.name);
    formData.append('price', form.price);
    formData.append('promo_price', form.promo_price);
    formData.append('quantity', form.quantity);
    formData.append('description', form.description);
    formData.append('category_id', form.category_id);
    formData.append('brand_id', form.brand_id);
    formData.append('service_id', form.service_id);
    formData.append("_method", 'PUT');
    // Append product images to the FormData
    // Append each product image file individually
    form.product_images.forEach((image, index) => {
        formData.append(`product_images[${index}]`, image.raw || image);
    });

    try {
        await router.post(route('products.update', form.id), formData, {
            onSuccess: (page) => {
                dialogVisible.value = false;
                // resetFormData();
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmButton: false,
                    title: page.props.flash.success,
                    timer: 3000,
                    timerProgressBar: true,
                });
                router.visit(route('products.index'));
            },
            onError: (errors) => {
                // console.log(resp)
                form.errors = errors;
            }
        })
    } catch (err) {
        console.log(err)
    }
    // resetFormData();
    // router.visit(route('products.index'));
}

//delete product method
const deleteProduct = (product, index) => {
    Swal.fire({
        title: 'Are you Sure',
        text: "This actions cannot undo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'No',
        confirmButtonText: 'Yes, Delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            try {
                form.delete('products/destory/' + product.id, {
                    onSuccess: (page) => {
                        // this.delete(product, index);
                        Swal.fire({
                            toast: true,
                            icon: "success",
                            position: "top-end",
                            showConfirmButton: false,
                            title: page.props.flash.success,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                        router.visit(route('products.index'));
                    }
                })
            } catch (err) {
                console.log(err)
            }
        }
    })
}

function viewProduct(id) {
    router.visit(`/products/show/${id}`);
}
const capitalizeInitialWords = (str) => {
    if (!str) return '';
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
};

const dialogWidth = computed(() => {
    return window.innerWidth < 1024 ? '90%' : '30%'; // 90% for mobile, 30% for larger screens
});
const sortedProducts = computed(() => {
    return [...props.products].sort((a, b) => {
        const nameA = a.name.toLowerCase(); // Convert to lowercase for case-insensitive comparison
        const nameB = b.name.toLowerCase();
        return nameA.localeCompare(nameB); // Compare the names alphabetically
    });
});
</script>
<template>
    <section class="p-3 sm:p-5">
        <div class="pb-4 mx-auto max-w-screen-xl px-4 lg:px-12 flex items-center">
            <button @click="searchProducts" class="absolute ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5 ">
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
                class="rounded-lg text-xs  border border-gray-300 shadow-md text-center" />
        </div>
        <!-- dialog for adding product or editing product -->
        <el-dialog v-model="dialogVisible" :title="editMode ? 'Edit product' : 'Add Product'"
            _style="{ width: dialogWidth }" class="max-w-md">
            <!-- :before-close="handleClose" -->
            <!-- form start -->
            <form @submit.prevent="editMode ? updateProduct() : AddProduct()" enctype="multipart/form-data">
                <div class="flex flex-row gap-2">
                    <div class="relative z-0 w-full mb-6 group">
                        <input v-model="form.name" type="text" name="floating_name" id="floating_name"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " />
                        <label for="floating_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Name</label>
                        <span v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</span>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="number" name="qty" id="floating_qty"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " v-model="form.quantity" />
                        <label for="floating_qty"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Quantity</label>
                        <span v-if="form.errors.quantity" class="text-red-500 text-xs">{{ form.errors.quantity }}</span>
                    </div>
                </div>
                <div class="flex flex-row gap-2">
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="number" name="floating_price" id="floating_price" step="0.01"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " v-model="form.price" />
                        <label for="floating_price"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Price</label>
                        <span v-if="form.errors.price" class="text-red-500 text-xs">{{ form.errors.price }}</span>
                    </div>
                    <div class="relative z-0 w-full mb-6 group">
                        <input type="number" name="floating_price" id="floating_price" step="0.01"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " v-model="form.promo_price" />
                        <label for="floating_price"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Promo
                            Price</label>
                        <span v-if="form.errors.promo_price" class="text-red-500 text-xs">{{ form.errors.promo_price
                            }}</span>
                    </div>
                </div>
                <!-- <div class="relative z-0 w-full mb-6 group">
                    <input type="number" name="qty" id="floating_qty"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " v-model="quantity" />
                    <label for="floating_qty"
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Quantity</label>
                </div> -->

                <div>
                    <label for="countries" class="block my-2 text-sm font-medium text-gray-900 dark:text-white">Select
                        Category</label>
                    <select id="countries" v-model="form.category_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name
                            }}
                        </option>
                    </select>
                    <span v-if="form.errors.category_id" class="text-red-500 text-xs">{{ form.errors.category_id
                        }}</span>
                </div>

                <div>
                    <label for="countries" class="block my-2 text-sm font-medium text-gray-900 dark:text-white">Select
                        Brand</label>
                    <select id="countries" v-model="form.brand_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                    </select>
                    <span v-if="form.errors.brand_id" class="text-red-500 text-xs">{{ form.errors.brand_id }}</span>
                </div>

                <div>
                    <label for="countries" class="block my-2 text-sm font-medium text-gray-900 dark:text-white">Select
                        Service</label>
                    <select id="countries" v-model="form.service_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="service in services" :key="service.id" :value="service.id">{{ service.name }}
                        </option>
                    </select>
                    <span v-if="form.errors.service_id" class="text-red-500 text-xs">{{ form.errors.service_id }}</span>
                </div>

                <div class="grid  md:gap-6">
                    <div class="relative z-0 w-full mb-6 group">

                        <label for="message"
                            class="block my-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="message" rows="4" v-model="form.description"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Leave a comment..."></textarea>
                    </div>
                </div>

                <div class="grid md:gap-6">
                    <!-- Multiple images upload using el-upload -->
                    <div class="relative z-0 w-full mb-6 group">
                        <el-upload v-model:file-list="form.product_images" list-type="picture-card" multiple
                            :on-preview="handlePictureCardPreview" :on-remove="handleRemove"
                            :on-change="handleFileChange" :auto-upload="false">
                            <el-icon>
                                <Plus />
                                <span v-if="form.errors.product_images" class="text-red-500 text-xs">{{
                                    form.errors.product_images }}</span>
                            </el-icon>
                        </el-upload>
                    </div>
                    <!-- Image preview dialog (optional implementation) -->
                    <!-- <el-dialog v-model="dialogVisible">
                        <img :src="dialogImageUrl" alt="Image Preview" class="w-full h-auto">
                    </el-dialog> -->
                </div>
                <!-- end -->

                <!-- <pre class="text-xs">{{ form.product_images }}</pre> -->

                <!-- list of images for selected product -->
                <div v-if="editMode" class="flex flex-nowrap mb-8 ">
                    <div v-for="(pimage, index) in form.product_images" :key="pimage.id" class="relative w-32 h-32 ">
                        <img class="w-24 h-20 object-contain rounded" :src="`${pimage.url}`" alt="">
                        <span
                            class="absolute top-0 right-8 transform -translate-y-1/2 w-3.5 h-3.5 bg-red-400 border-2 border-white dark:border-gray-800 rounded-full">
                            <span @click="deleteImage(pimage, index)"
                                class="text-white text-xs font-bold absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">x</span>
                        </span>
                    </div>
                </div>

                <!-- <div>
                    Processing: {{ form.processing }}
                    <progress max="100" :value="form.progress?.percentage" />
                </div> -->
                <!-- Progress bar -->
                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 mb-6">
                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-1 leading-none rounded-full transition-all duration-300"
                        :style="{ width: `${form.progress?.percentage || 0}%` }">
                        {{ form.processing ? `${form.progress?.percentage}%` : '' }}
                    </div>
                </div>

                <!-- end -->
                <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
            </form>
            <!-- end -->
        </el-dialog>
        <!-- end -->
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <div class="text-lg font-semibold">Product List</div>
                        <!-- <form class="flex items-center">
                            <label for="simple-search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="simple-search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Search" required="">
                            </div>
                        </form> -->
                    </div>
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <button type="button" @click="openAddModal"
                            class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            <svg class="w-5 h-5 mr-2 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14m-7 7V5" />
                            </svg>
                            Add product
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="pl-2 text-center">No.</th> <!-- Added numbering column -->
                                <th scope="col" class="px-4 py-3">Product name</th>
                                <!-- <th scope="col" class="px-4 py-3">Category</th>
                                <th scope="col" class="px-4 py-3">Brand</th>
                                <th scope="col" class="px-4 py-3">Service</th> -->
                                <th scope="col" class="px-4 py-3">Qty</th>
                                <th scope="col" class="px-4 py-3">Price</th>
                                <th scope="col" class="px-4 py-3 whitespace-nowrap">Promo Price</th>
                                <!-- <th scope="col" class="px-4 py-3">Stock</th> -->
                                <!-- <th scope="col" class="px-4 py-3">Publish</th> -->
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in products" :key="product.id" class="hover:bg-gray-200">
                                <td class="pl-2 text-center">{{ index + 1 }}.</td> <!-- Displaying the numbering -->
                                <th scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ capitalizeInitialWords(product.name ?? '-') }}</th>
                                <!-- <td class="px-4 py-3">
                                    <span v-if="product.category.name">{{ capitalizeInitialWords(product.category.name)
                                        }}</span>
                                    <span v-else>-</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span v-if="product.brand.name">{{ capitalizeInitialWords(product.brand.name)
                                        }}</span>
                                    <span v-else>-</span>
                                </td>
                                <td class="px-4 py-3">{{ capitalizeInitialWords(product.service ? product.service.name :
                                    '-') }}</td> -->
                                <td class="px-4 py-3">{{ product.quantity }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span v-if="product.price">RM {{ product.price }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span v-if="product.promo_price">RM {{ product.promo_price }}</span>
                                    <span v-else>-</span>
                                </td>
                                <!-- <td class="px-4 py-3" v-if="product.promo_price">RM {{ product.promo_price ?? '-' }}</td> -->
                                <!-- <td class="px-4 py-3">
                                    <span v-if="product.inStock == 0"
                                        class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">inStock</span>
                                    <span v-else
                                        class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Out
                                        of Stock</span>
                                </td> -->
                                <!-- <td class="px-4 py-3">
                                    <button v-if="product.published == 0" type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Published</button>
                                    <button v-else type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">UnPublished</button>
                                </td> -->

                                <td class="px-4 py-3 flex items-center justify-end">

                                    <button :id="`${product.id}-button`" :data-dropdown-toggle="`${product.id}`"
                                        class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                        type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                    <div :id="`${product.id}`"
                                        class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                            :aria-labelledby="`${product.id}-button`">

                                            <li>
                                                <a href="#" @click="viewProduct(product.id)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    View Details
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#" @click="openEditModal(product)"
                                                    class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="#" @click="deleteProduct(product, index)"
                                                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <!-- <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                    aria-label="Table navigation">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Showing
                        <span class="font-semibold text-gray-900 dark:text-white">1-10</span>
                        of
                        <span class="font-semibold text-gray-900 dark:text-white">1000</span>
                    </span>
                    <ul class="inline-flex items-stretch -space-x-px">
                        <li>
                            <a href="#"
                                class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                        </li>
                        <li>
                            <a href="#" aria-current="page"
                                class="flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-50 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">100</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav> -->
            </div>
        </div>
    </section>
</template>
