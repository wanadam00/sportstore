<template>
    <AppLayout2>
        <!-- Features -->
        <div class="max-w-[85rem] pb-6 sm:py-10 mx-auto">
            <!-- Grid -->
            <div class="items-center">
                <div
                    class="lg:col-span-8 relative lg:before:absolute lg:before:top-0 lg:before:-start-12 lg:before:w-px lg:before:h-full lg:before:bg-gray-200 lg:before:dark:bg-neutral-700">
                    <div class="grid gap-6 grid-cols-2 md:grid-cols-4 lg:grid-cols-4 sm:gap-8">
                        <!-- Total Sales -->
                        <div class="bg-white rounded-lg border p-4 shadow-sm">
                            <p class="text-3xl font-semibold text-yellow-500">
                                RM {{ displayTotalSales }}
                            </p>
                            <p class="mt-1 text-gray-500 dark:text-neutral-500">Total Sales</p>
                        </div>

                        <!-- Sales Growth -->
                        <div class="bg-white rounded-lg border p-4 shadow-sm">
                            <p class="text-3xl font-semibold text-yellow-500">
                                {{ displaySalesGrowth }}
                            </p>
                            <p class="mt-1 text-gray-500 dark:text-neutral-500">Sales Growth</p>
                        </div>

                        <!-- Average Order Value -->
                        <div class="bg-white rounded-lg border p-4 shadow-sm">
                            <p class="text-3xl font-semibold text-yellow-500">
                                RM {{ displayAverageOrderValue }}
                            </p>
                            <p class="mt-1 text-gray-500 dark:text-neutral-500">Average Order Value (AOV)</p>
                        </div>

                        <!-- Total Orders -->
                        <div class="bg-white rounded-lg border p-4 shadow-sm">
                            <p class="text-3xl font-semibold text-yellow-500">
                                {{ displayTotalOrders }}
                            </p>
                            <p class="mt-1 text-gray-500 dark:text-neutral-500">Total Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Sales Chart -->
        <div class="max-w w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            <div class="flex justify-between mb-5">
                <div>
                    <h5 class="leading-none text-3xl font-bold text-yellow-500 dark:text-white pb-2">
                        RM {{ displaySalesThisWeek }}
                    </h5>
                    <p class="text-base font-normal text-gray-500 dark:text-gray-400">Sales this week</p>
                </div>
                <!-- Align button to the right -->
                <div class="flex items-center">
                    <!-- <button @click="resetWeeklySales" class="btn btn-primary bg-yellow-500 p-2 text-sm font-medium rounded-lg mb-4">Refresh
                        Weekly Sales</button> -->
                </div>
            </div>

            <div id="weekly-sales-chart"></div>
        </div>
    </AppLayout2>
</template>


<script setup>
import { ref, onMounted, computed } from 'vue';
import { initFlowbite } from 'flowbite';
import AppLayout2 from '@/Layouts/AppLayout2.vue';
import ApexCharts from 'apexcharts';

const props = defineProps(['totalSales', 'salesGrowth', 'averageOrderValue', 'totalOrders', 'salesThisWeek', 'weeklySalesData']);

const displayTotalSales = computed(() =>
    typeof props.totalSales === 'number' ? props.totalSales.toFixed(2) : '0.00'
);

const displaySalesGrowth = computed(() =>
    typeof props.salesGrowth === 'number' ? `${props.salesGrowth.toFixed(2)}%` : 'N/A'
);

const displayAverageOrderValue = computed(() =>
    typeof props.averageOrderValue === 'number' ? props.averageOrderValue.toFixed(2) : '0.00'
);

const displayTotalOrders = computed(() =>
    typeof props.totalOrders === 'number' ? props.totalOrders : 0
);

const displaySalesThisWeek = computed(() =>
    typeof props.salesThisWeek === 'number' ? props.salesThisWeek.toFixed(2) : '0.00'
);

// Helper function to format dates
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { day: '2-digit', month: 'short', year: 'numeric' };
    return date.toLocaleDateString('en-GB', options); // Change 'en-GB' for locale preference
}

// Prepare data for the weekly sales chart
const categories = computed(() => {
    // Extract dates from the weeklySalesData prop
    return props.weeklySalesData.map(item => formatDate(item.date));
});

const salesData = computed(() => {
    // Extract total sales from the weeklySalesData prop
    return props.weeklySalesData.map(item => item.total_sales);
});

// Initialize the chart when the component is mounted
onMounted(() => {
    renderWeeklySalesChart();
});

// Function to render the weekly sales chart
function renderWeeklySalesChart() {
    const options = {
        series: [{
            name: 'Sales',
            data: salesData.value,
            color: "#1a1a1a",
        }],
        chart: {
            sparkline: {
                enabled: false
            },
            height: "100%",
            width: "100%",
            type: "area",
            fontFamily: "Inter, sans-serif",
            dropShadow: {
                enabled: false,
            },
            toolbar: {
                show: false,
            },
        },
        tooltip: {
            enabled: true,
            x: {
                show: false,
            },
        },
        xaxis: {
            categories: categories.value,
            labels: {
                style: {
                    colors: '#1a1a1a',
                    fontSize: '12px',
                },
            },
        },
        yaxis: {
            labels: {
                formatter: value => `RM ${value.toFixed(2)}`,
                style: {
                    colors: '#1a1a1a',
                    fontSize: '12px',
                },
            },
        },
        fill: {
            type: "gradient",
            gradient: {
                opacityFrom: 0.55,
                opacityTo: 0,
                shade: "#1a1a1a",
                gradientToColors: ["#1a1a1a"],
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: 6,
        },
        legend: {
            show: false
        },
        grid: {
            show: false,
        },
    };

    // Create the chart and render it
    const chart = new ApexCharts(document.getElementById("weekly-sales-chart"), options);
    chart.render();
}
// Function to reset and fetch new weekly sales data
async function resetWeeklySales() {
    try {
        const response = await fetch('/api/weekly-sales'); // Ensure this endpoint returns the updated data
        const data = await response.json();

        // Update the props with the new weekly sales data
        props.weeklySalesData = data;

        // Re-render the chart with the new data
        renderWeeklySalesChart();
    } catch (error) {
        console.error("Error fetching weekly sales data:", error);
    }
}
</script>
