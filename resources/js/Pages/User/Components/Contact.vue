<script setup>
import { onMounted } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import UserLayouts from '../Layouts/UserLayouts.vue';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue'

const dialogVisible = ref(false)

onMounted(() => {
    const map = L.map('map').setView([3.0753030688320266, 101.48625608997202], 25);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    // Optional: Add a marker at the center of İzmir
    L.marker([3.0753030688320266, 101.48625608997202])
        .addTo(map)
        .bindPopup('MZR Global')
        .openPopup();
});
// Method to submit feedback
const submitFeedback = async () => {
    const formData = new FormData();
    formData.append('email', email.value);
    formData.append('message', message.value);

    try {
        await router.post('contact/store', formData, {
            onSuccess: page => {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    position: 'top-end',
                    showConfirmButton: false,
                    title: page.props.flash.success
                })
                dialogVisible.value = false;
                resetFormData();
            },
        })
    } catch (err) {
        console.log(err)
    }
};
//rest data after added
const resetFormData = () => {
    email.value = '';
    message.value = '';
};
</script>

<template>
    <UserLayouts>
        <section class="text-gray-600 body-font relative">
            <div class="fixed inset-0">
                <!-- Replace iframe with a Leaflet map container -->
                <div id="map" class="my-16 w-full min-h h-[50vh] md:h-[60vh] lg:h-[70vh] opacity-50"></div>
            </div>
            <form @submit.prevent="submitFeedback()" enctype="multipart/form-data">
                <div class="container px-5 py-24 mx-auto flex">
                    <div
                        class="lg:w-1/3 md:w-1/2 bg-white rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0 relative z-10 shadow-md">
                        <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">Feedback</h2>
                        <p class="leading-relaxed mb-5 text-gray-600">We value your thoughts and suggestions. Please
                            share
                            your feedback!</p>
                        <div class="relative mb-4">
                            <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
                            <input type="email" id="email" name="email"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative mb-4">
                            <label for="message" class="leading-7 text-sm text-gray-600">Message</label>
                            <textarea id="message" name="message"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                        </div>
                        <button type="submit"
                            class="text-white bg-[#1a1a1a] hover:bg-[#0f0f0f] border-0 py-2 px-6 focus:outline-none rounded text-lg">Submit</button>
                        <p class="text-xs text-gray-500 mt-3"></p>
                    </div>
                </div>
            </form>
        </section>
    </UserLayouts>
</template>

<style>
/* Adjust map height */
#map {
    height: 100%;
    /* Full height to fit the parent div */
}
</style>
