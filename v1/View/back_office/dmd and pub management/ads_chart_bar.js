// Retrieve data parameter from script tag
var dataParam = document.currentScript.getAttribute('data-json');
var data = JSON.parse(dataParam);

console.log(data);

var options = {
    chart: {
        height: 280,
        type: "bar" // Change chart type to "bar"
    },
    dataLabels: {
        enabled: false
    },
    series: [
        {
            name: 'Clicks',
            data: [] // Initialize empty data array
        }
    ],
    xaxis: {
        categories: [] // Initialize empty categories array
    }
};

// Populate series data and x-axis categories
data.forEach(function(item) {
    options.series[0].data.push(item.value);
    options.xaxis.categories.push(item.name);
});

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();

// Click event handling to redirect to ad detail page
chart.addEventListener("click", function(event, chartContext, config) {
    if (config && config.dataPointIndex !== undefined) {
        var adId = data[config.dataPointIndex].id;
        window.location.href = "./view_pub.php?id=" + adId;
    }
});
