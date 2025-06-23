<?php
// Sample data generation function
function generateChartData() {
    // You can replace this with your actual data generation logic
    return [45, 52, 38, 45, 19, 23, 2];
}

// Generate data
$data = generateChartData();

// Convert data array to JSON format
$data_json = json_encode($data);
?>

<html>
<head>
    <title>Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>
    <style>

    #chart {
    max-width: 650px;
    margin: 35px auto;
    }

    </style>
    
    <div id="chart">

    </div>

    <script>
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
                    name: "Series 1",
                    data: <?php echo $data_json; ?> // Inject PHP data here
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
                categories: [
                    "01 Jan",
                    "02 Jan",
                    "03 Jan",
                    "04 Jan",
                    "05 Jan",
                    "06 Jan",
                    "07 Jan"
                ]
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

    <?php 
    
        include_once './Controller/user_con.php';

        $googleLogin1 = new GoogleLogin();

    ?>
</body>
</html>
