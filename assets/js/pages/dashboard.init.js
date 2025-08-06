// Revenue Analytics
var options = {
    series: [{
        name: "Revenue",
        data: [20, 25, 30, 35, 40, 55, 70, 110, 150, 180, 210, 250]
    }],
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
            show: false,
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '65%',
            endingShape: 'rounded'
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 5,
        colors: ['transparent']
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
        labels: {
            style: {
                colors: '#87888a',
                fontSize: '13px',
                fontFamily: 'poppins',
                fontWeight: 400,
                cssClass: 'apexcharts-xaxis-label',
            },
        }
    },
    yaxis: {
        title: {
            text: '$ (thousands)',
            style: {
                fontSize: '13px',
                fontFamily: 'poppins',
                fontWeight: 400,
                cssClass: 'apexcharts-yaxis-label',
            },
        },
        labels: {
            style: {
                colors: '#87888a',
                fontSize: '13px',
                fontFamily: 'poppins',
                fontWeight: 400,
                cssClass: 'apexcharts-yaxis-label',
            },
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "$ " + val + " thousands"
            }
        }
    },
    colors: ['#0ab39c'],
};

var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
chart.render();

// Sales by Locations
var options = {
    series: [75, 55, 25],
    chart: {
        height: 269,
        type: 'donut',
    },
    labels: ['United States', 'Canada', 'Russia'],
    colors: ['#0ab39c', '#405189', '#f06548'],
    plotOptions: {
        pie: {
            donut: {
                size: '75%'
            }
        }
    },
    legend: {
        show: false,
    },
    stroke: {
        width: 0,
    },
    dataLabels: {
        dropShadow: {
            enabled: false
        }
    }
};

var chart = new ApexCharts(document.querySelector("#sales-by-locations"), options);
chart.render(); 