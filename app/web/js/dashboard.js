//DASHBOARD LINE GRAPH
let janRe = parseFloat(januaryReports);
let febRe = parseFloat(februaryReports);
let marRe = parseFloat(marchReports);
let aprilRe = parseFloat(aprilReports);
let mayRe = parseFloat(mayReports);
let junRe = parseFloat(juneReports);
let julRe = parseFloat(julyReports);
let augRe = parseFloat(augustReports);
let septRe = parseFloat(septemberReports);
let octRe = parseFloat(octoberReports);
let novRe = parseFloat(novemberReports);
let decRe = parseFloat(decemberReports);

let janCase = parseFloat(januaryCases);
let febCase = parseFloat(februaryCases);
let marCase = parseFloat(marchCases);
let aprilCase = parseFloat(aprilCases);
let mayCase = parseFloat(mayCases);
let junCase = parseFloat(juneCases);
let julCase = parseFloat(julyCases);
let augCase = parseFloat(augustCases);
let septCase = parseFloat(septemberCases);
let octCase = parseFloat(octoberCases);
let novCase = parseFloat(novemberCases);
let decCase = parseFloat(decemberCases);

var ctx = document.getElementById('reportCaseGraph').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Reports',
            data: [janRe, febRe, marRe, aprilRe, mayRe, junRe, julRe, augRe, septRe, octRe, novRe, decRe],
            borderColor: '#279a47',
            fill: false,
            borderWidth: 1,
            pointBackgroundColor: '#279a47',
            pointBorderColor: '#279a47',
            pointBorderWidth: 2,
            lineTension: 0
        },
            {
                label: 'Cases',
                data: [janCase, febCase, marCase, aprilCase, mayCase, junCase, julCase, augCase, septCase, octCase, novCase, decCase],
                borderColor: '#f79521',
                fill: false,
                borderWidth: 1,
                pointBackgroundColor: '#f79521',
                pointBorderColor: '#f79521',
                pointBorderWidth: 2,
                lineTension: 0
            }
        ]
    },
    options: {
        legend: {display: true},
        tooltips: {
            mode: 'nearest'
        }
    }
});

//REPORT DOUGHNUT CHART
let verified = parseFloat(verifiedReports);
let unverified = parseFloat(unverifiedReports);
var ctx = document.getElementById('reportChart').getContext('2d');
var reportDoughnutChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [verified, unverified],
            backgroundColor: ['#f79521', '#279a47'],
            borderColor: ['#f79521', '#279a47']
        }],
        labels: ['Verified', 'Unverified']
    },
    options: {
        tooltips: {
            mode: 'point'
        },
        cutoutPercentage: 70,
        legend: {
            position: 'left'
        }
    }
});

//CASE DOUGHNUT CHART
let pending = parseFloat(pendingCases);
let withdrawn = parseFloat(withdrawnCases);
let resolved = parseFloat(resolvedCases);
var ctx = document.getElementById('caseChart').getContext('2d');
var caseDoughnutChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [withdrawn, resolved, pending],
            backgroundColor: ['#f79521', '#d6c987', '#279a47'],
            borderColor: ['#f79521', '#d6c987', '#279a47']
        }],
        labels: ['Withdrawn','Resolved','Pending']
    },
    options: {
        tooltips: {
            mode: 'point'
        },
        cutoutPercentage: 70,
        legend: {
            position: 'left'
        }
    }
});