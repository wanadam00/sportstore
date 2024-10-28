
<!-- VisitorChart.vue -->
<template>
    <div>
        <LineChart :chartData="chartData" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    LinearScale,
    TitleOptions,
} from 'chart.js';

// Register Chart.js components
ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, LinearScale);

// Sample data for the chart
const chartData = ref({
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], // Sample months
    datasets: [
        {
            label: 'Visitors',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            data: [65, 59, 80, 81, 56, 55, 40], // Sample visitor numbers
        },
    ],
});
</script>

<script>
export default {
    components: {
        LineChart: Line,
    },
};
</script>
