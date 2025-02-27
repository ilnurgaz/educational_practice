<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 p-5">

@include('partials.navbar')

<div class="container mx-auto p-6 rounded-lg shadow-md bg-white mt-5">
    <h2 class="text-2xl font-bold mb-4">График покупок</h2>

    <!-- Фильтр по датам -->
    <div class="mb-4">
        <label for="start_date" class="block text-gray-700">Начальная дата:</label>
        <input type="date" id="start_date" name="start_date" class="border p-2 rounded">

        <label for="end_date" class="block text-gray-700 mt-2">Конечная дата:</label>
        <input type="date" id="end_date" name="end_date" class="border p-2 rounded">

        <button id="filterButton" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Применить фильтр</button>
        <button id="resetButton" class="mt-2 bg-gray-500 text-white px-4 py-2 rounded">Сбросить фильтр</button>
    </div>

    <!-- График количества покупок -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Количество покупок</h3>
        <div class="chart-container" style="position: relative; height: 300px;">
            <canvas id="purchasesChart"></canvas>
        </div>
    </div>

    <!-- График суммы закупок -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Сумма закупок</h3>
        <div class="chart-container" style="position: relative; height: 300px;">
            <canvas id="amountChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const purchasesCtx = document.getElementById('purchasesChart').getContext('2d');
        const amountCtx = document.getElementById('amountChart').getContext('2d');
        let purchasesChart, amountChart;

        // Функция для загрузки данных
        function loadData(startDate, endDate) {
            fetch(`/sales-data?start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.date);
                    const purchasesData = data.map(item => item.total_purchases);
                    const totalAmountData = data.map(item => item.total_amount);

                    // Если графики уже существуют, уничтожаем их
                    if (purchasesChart) {
                        purchasesChart.destroy();
                    }
                    if (amountChart) {
                        amountChart.destroy();
                    }

                    // График количества покупок
                    purchasesChart = new Chart(purchasesCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Количество покупок',
                                data: purchasesData,
                                backgroundColor: 'rgba(79, 70, 229, 0.3)',
                                borderColor: 'rgba(79, 70, 229, 1)',
                                borderWidth: 1,
                                hoverBackgroundColor: 'rgba(99, 102, 241, 0.5)',
                                hoverBorderColor: 'rgba(79, 70, 229, 1)',
                                borderRadius: 8,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, // Отключаем автоматическое соотношение сторон
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        color: 'rgba(0, 0, 0, 0.7)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: 'rgba(0, 0, 0, 0.7)'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        color: 'rgba(0, 0, 0, 0.8)',
                                        font: {
                                            size: 14
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(79, 70, 229, 0.9)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    borderColor: 'rgba(79, 70, 229, 1)',
                                    borderWidth: 1,
                                    cornerRadius: 8
                                }
                            }
                        }
                    });

                    // График суммы закупок
                    amountChart = new Chart(amountCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Сумма закупок',
                                data: totalAmountData,
                                backgroundColor: 'rgba(255, 99, 132, 0.3)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                hoverBackgroundColor: 'rgba(255, 99, 132, 0.5)',
                                hoverBorderColor: 'rgba(255, 99, 132, 1)',
                                borderRadius: 8,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, // Отключаем автоматическое соотношение сторон
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        color: 'rgba(0, 0, 0, 0.7)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: 'rgba(0, 0, 0, 0.7)'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        color: 'rgba(0, 0, 0, 0.8)',
                                        font: {
                                            size: 14
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(255, 99, 132, 0.9)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1,
                                    cornerRadius: 8
                                }
                            }
                        }
                    });
                });
        }

        // Функция для сброса фильтра
        function resetFilter() {
            const defaultStartDate = new Date();
            defaultStartDate.setMonth(defaultStartDate.getMonth() - 1);

            // Устанавливаем значения по умолчанию в поля ввода
            document.getElementById('start_date').value = defaultStartDate.toISOString().split('T')[0];
            document.getElementById('end_date').value = new Date().toISOString().split('T')[0];

            // Загружаем данные за последний месяц
            loadData(defaultStartDate.toISOString().split('T')[0], new Date().toISOString().split('T')[0]);
        }

        // Загружаем данные по умолчанию (последний месяц)
        resetFilter();

        // Обработчик для фильтра
        document.getElementById('filterButton').addEventListener('click', function () {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            loadData(startDate, endDate);
        });

        // Обработчик для сброса фильтра
        document.getElementById('resetButton').addEventListener('click', resetFilter);
    });
</script>

</body>
</html>