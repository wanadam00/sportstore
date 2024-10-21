<template>

    <div class="sidebar h-screen fixed">
        <ul>
            <li><Link :href="route('dashboard')">
                                    <ApplicationMark class="block h-9 w-auto" />
                                </Link></li>
            <li>
                <NavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                    class="pt-4"
                    >Dashboard</NavLink
                >
            </li>
            <li>
                <NavLink
                    :href="route('list')"
                    :active="route().current('list')"
                    class=""
                    >List</NavLink
                >
            </li>
            <!-- <li>
                <NavLink
                    :href="route('profile.show')"
                    :active="route().current('profile.show')"
                    >Profile</NavLink
                >
            </li> -->
        </ul>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import { Link } from "@inertiajs/vue3";
//   import { Head, Link, router } from "@inertiajs/vue3";

import ApplicationMark from "@/Components/ApplicationMark.vue";

import Dropdown from "@/Components/Dropdown.vue";

// import DropdownLink from "@/Components/DropdownLink.vue";

import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

// export default {
//   name: 'Sidebar'
// }
</script>

<style scoped>
.sidebar {
    width: 250px;
    /* height: 115vh; */
    background-color: #f8f9fa;
    padding: 20px;
    box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar ul li {
    margin-bottom: 10px;
}

.sidebar ul li a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
}
</style>
