<!-- Chart.vue -->
<template>
    <div>
        <LineChart :chartData="chartData" />
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    LinearScale,
} from 'chart.js';

// Register Chart.js components
ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, LinearScale);

// Define props to receive data from the parent component
const props = defineProps({
    chartData: {
        type: Object,
        required: true,
    },
});
</script>

<script>
export default {
    components: {
        LineChart: Line,
    },
};
</script>
