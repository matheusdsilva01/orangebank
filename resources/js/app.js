import './bootstrap.js';
import Chart from 'chart.js/auto';

window.Chart = Chart;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-chart-config]').forEach((canvas) => {
        const configName = canvas.dataset.chartConfig;
        const config = window.chartConfigs?.[configName];

        if (config) {
            new Chart(canvas, config);
        }
    });
});
