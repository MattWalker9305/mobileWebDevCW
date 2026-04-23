// Fetches aggregated transaction data from the server and renders two Chart.js charts.
fetch('/mobileWebDevCW/app/api/get_chart_data.php')
    .then(function (response) {
        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }
        return response.json();
    })
    .then(function (data) {
        renderCategoryChart(data.byCategory);
        renderMonthlyChart(data.byMonth);
    })
    .catch(function (err) {
        console.error('Chart data fetch failed:', err);
        document.getElementById('categoryChart').parentElement.innerHTML =
            '<p class="text-danger">Could not load chart data. Check the browser console for details.</p>';
        document.getElementById('monthlyChart').parentElement.innerHTML = '';
    });

/**
 * Doughnut chart: total expenses broken down by category.
 */
function renderCategoryChart(byCategory) {
    var ctx = document.getElementById('categoryChart');
    var labels = Object.keys(byCategory);
    var amounts = Object.values(byCategory);

    if (labels.length === 0) {
        ctx.parentElement.innerHTML = '<p class="text-muted text-center">No expense data to display.</p>';
        return;
    }

    var palette = [
        '#4dc9f6','#f67019','#f53794','#537bc4','#acc236',
        '#166a8f','#00a950','#58595b','#8549ba','#e8c534'
    ];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: amounts,
                backgroundColor: labels.map(function (_, i) { return palette[i % palette.length]; }),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            return ' ' + ctx.label + ': £' + ctx.parsed.toFixed(2);
                        }
                    }
                }
            }
        }
    });
}

/**
 * Bar chart: income vs expenses grouped by month.
 */
function renderMonthlyChart(byMonth) {
    var ctx = document.getElementById('monthlyChart');
    var months = Object.keys(byMonth);

    if (months.length === 0) {
        ctx.parentElement.innerHTML = '<p class="text-muted text-center">No data to display.</p>';
        return;
    }

    var incomeData  = months.map(function (m) { return byMonth[m].income  || 0; });
    var expenseData = months.map(function (m) { return byMonth[m].expense || 0; });

    // Format labels as e.g. "Apr 2026"
    var labels = months.map(function (m) {
        var parts = m.split('-');
        var d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, 1);
        return d.toLocaleDateString('en-GB', { month: 'short', year: 'numeric' });
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    data: incomeData,
                    backgroundColor: 'rgba(75, 192, 100, 0.7)',
                    borderColor: 'rgba(75, 192, 100, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Expenses',
                    data: expenseData,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) { return '£' + value; }
                    }
                }
            },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            return ' ' + ctx.dataset.label + ': £' + ctx.parsed.y.toFixed(2);
                        }
                    }
                }
            }
        }
    });
}
