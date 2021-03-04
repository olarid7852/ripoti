//VIOLATIONS DOUGHNUT CHART
var ctx = document.getElementById('violationsChart').getContext('2d');
var reportDoughnutChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: violationResultValues,
            backgroundColor: ['#279a47', '#272f9a', '#f72821'],
            borderColor: ['#279a47', '#272f9a', '#f72821']
        }],
        labels: violationResultKeys
    },
    options: {
        tooltips: {
            mode: 'point'
        },
        layout: {
            padding: {
                top: 40,
                bottom: 30
            }
        },
        cutoutPercentage: 70,
        legend: {
            showInLegend: "true",
            position: 'left',
            labels: {
                boxWidth: 20,
                fontSize: 15,
                padding: 20
            }
        }
    }
});

//COUNTRY PIE CHART
var ctx = document.getElementById('countryChart').getContext('2d');
var countryPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
        datasets: [{
            data: countryResultValues,
            backgroundColor: ['#28680d', '#ee1212', '#9d7109', '#1d61e9', '#e5a715', '#800606', '#0b2e74'],
            borderColor: ['#28680d', '#ee1212', '#9d7109', '#1d61e9', '#e5a715', '#800606', '#0b2e74']
        }],
        labels: countryResultKeys
    },
    options: {
        tooltips: {
            mode: 'point'
        },
        layout: {
            padding: {
                top: 40,
                bottom: 30
            }
        },
        legend: {
            position: 'left',
            labels: {
                boxWidth: 20,
                fontSize: 15,
                padding: 20
            }
        }
    }
});