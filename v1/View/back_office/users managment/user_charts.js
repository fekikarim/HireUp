// Retrieve data parameter from script tag
var dataParam = document.currentScript.getAttribute('data-json');
var data = JSON.parse(dataParam);

console.log(data);

var options = {
    chart: {
        height: 280,
        type: "area"
    },
    dataLabels: {
        enabled: false
    },
    series: [
        {
            name: 'Accounts Created',
            data: [] // Initialize empty data array
        }
    ],
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.9,
            stops: [0, 90, 100]
        }
    },
    xaxis: {
        categories: [] // Initialize empty categories array
    }
};

// Populate series data and x-axis categories
data.forEach(function(item) {
    options.series[0].data.push(item.accounts_created);
    options.xaxis.categories.push(item.date.slice(8, 10) + ' ' + item.date.slice(5, 7));
});

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
