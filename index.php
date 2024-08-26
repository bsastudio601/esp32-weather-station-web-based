<?php
require 'config.php';

$db = connectDB(); // Connect to the database

$sql = "SELECT * FROM tbl_temperature ORDER BY id DESC LIMIT 30";
$result = $db->query($sql);

if (!$result) {
    echo "Error: " . $sql . "<br>" . $db->error;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Real Time Weather Station</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  

  <style>
    
    body {
      padding: 0px;
      font-family: Arial, sans-serif;
      background-color: #ffffff; /* Light theme background */
      transition: background-color 0.3s ease, color 0.3s ease;
      color: #000000;
      overflow-x: hidden;
      margin: 0 ;
    }

    .containerx {
      position: relative;
      display: inline-block;
      width: 70px;
      height: 40px;
      background-color: #15181f; /* Toggle background color */
      border-radius: 50px;
      cursor: pointer;
      overflow: hidden;
      margin-bottom: 20px;
    }

    .table {
      width: 100%; /* Set the width of the table */
      border: 1px solid #ffffff; /* Optional: Add border around the table */
      border-collapse: collapse; /* Optional: Collapse borders between cells */
    }

    .table th, .table td {
      padding: 10px; /* Adjust padding inside table cells */
      border: 1px solid #ffffff; /* Optional: Add border around cells */
      text-align: center; /* Optional: Align text within cells */
      font-size: 14px; /* Optional: Adjust font size within cells */
    }

    #toggle {
      opacity: 0;
      width: 100%;
      height: 100%;
      position: absolute;
      top: 0;
      left: 0;
      z-index: 1;
      cursor: pointer;
    }

    .switch {
      position: absolute;
      top: 5px;
      left: 5px;
      width: 30px;
      height: 30px;
      background-color: #ffffff; /* Switch color */
      border-radius: 50%;
      transition: transform 0.3s ease, background-color 0.3s ease;
      z-index: 2;
    }

    #toggle:checked + .switch {
      transform: translateX(30px);
      background-color: #15181f; /* Dark theme switch color */
    }

    .dark-theme {
      background-color: #15181f; /* Dark theme body background */
      color: #ffffff;
    }

    .dark-theme .containerx {
      background-color: #ffffff; /* Dark theme toggle background */
    }

    .dark-theme .switch {
      background-color: #15181f; /* Dark theme switch background */
    }

    .dark-theme .chart-container {
      background-color: #15181f;
      color: #ffffff;
    }

    .dark-theme .row {
      background-color: #15181f;
      color: #ffffff;
    }

    .dark-theme .chart-text {
      color: #ffffff;
    }

    .chart-container {
      
      
      position: relative;
      width: 100%;
      min-height: 400px;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 20px;
      transition: background-color 0.3s ease;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .chart-text {
      position: absolute;
      font-size: 30px;
      font-weight: bold;
      color: #333;
      text-align: center;
      transform: translate(-50%, -50%);
      top: 55%;
      left: 50%;
    }

    .row {
      
      position: relative;
      align-items: center;
      
      transition: background-color 0.3s ease;
    }
    .mode{
      
      font-size: 14px;
      position: relative;
      align-items: center;
      top: 70px;
    }
    .dark-theme .table{
      color: #ffffff;
    }
    .x{
      
      border-radius: 20px;
      min-width: 100%;
    }
    .dark-theme .x{
      background-color: #00164469;
    }  
    
    @media screen and (max-width: 768px) {
      

      .row{
        min-width: 100%;
      }
      .table{
        width: 50%;
      }
      
      
    }
  
  </style>
</head>
<body>

  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <h1>ESP Weather Station</h1>
        <h1 class = "mode" id="text-to-change">Dark Mode</h1>
        <div class="containerx">
          <input type="checkbox" id="toggle">
          <div class="switch"></div>
        </div>
      </div>
    </div>
      
    
    
    

    <div class="row">
      <div class="col-md-4">
        <div class="chart-container">
          <canvas id="chart_temperature"></canvas>
          <div class="chart-text" id="temperatureText">--°C</div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="chart-container">
          <canvas id="chart_humidity"></canvas>
          <div class="chart-text" id="humidityText">--%</div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="chart-container">
          <canvas id="chart_pressure"></canvas>
          <div class="chart-text" id="pressureText">-- hPa</div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="chart-container">
          <canvas class="x" id="lineChart"></canvas>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Temperature</th>
              <th scope="col">Humidity</th>
              <th scope="col">Pressure</th>
              <th scope="col">Date Time</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; while ($row = mysqli_fetch_assoc($result)) {?>
              <tr>
                <th scope="row"><?php echo $i++;?></th>
                <td><?php echo $row['temperature'];?></td>
                <td><?php echo $row['humidity'];?></td>
                <td><?php echo $row['pressure'];?></td>
                <td><?php echo date("Y-m-d h:i A", strtotime($row['created_date']));?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // Static data for temperature, humidity, and pressure
    let currentTemperature = 25;
    let currentHumidity = 60;
    let currentPressure = 1013;

  

    // Chart configuration for temperature
    const temperatureConfig = {
      type: 'doughnut',
      data: {
        labels: ['Temperature'],
        datasets: [{
          data: [currentTemperature, 100 - currentTemperature], // Initial data, calculated to simulate gauge effect
          backgroundColor: getGradientColors(currentTemperature),
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        cutout: '70%', // Adjust the size of the hole in the middle (optional)
        plugins: {
          legend: {
            display: true
          },
          title: {
            display: false,
            text: 'Temperature'
          }
        },
        rotation: 180 // Start angle (clockwise from bottom), Math.PI / 2 is 90 degrees
      }
    };

    // Chart configuration for humidity
    const humidityConfig = {
      type: 'doughnut',
      data: {
        labels: ['Humidity'],
        datasets: [{
          data: [currentHumidity, 100 - currentHumidity], // Initial data, calculated to simulate gauge effect
          backgroundColor: getGradientColors(currentHumidity),
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        cutout: '70%', // Adjust the size of the hole in the middle (optional)
        plugins: {
          legend: {
            display: true
          },
          title: {
            display: false,
            text: 'Humidity Gauge'
          }
        },
        rotation: 180 // Start angle (clockwise from bottom), Math.PI / 2 is 90 degrees
      }
    };

    // Chart configuration for pressure
    const pressureConfig = {
      type: 'doughnut',
      data: {
        labels: ['Pressure'],
        datasets: [{
          data: [currentPressure, 100 - currentPressure], // Initial data, calculated to simulate gauge effect
          backgroundColor: getGradientColors(currentPressure),
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        cutout: '70%', // Adjust the size of the hole in the middle (optional)
        plugins: {
          legend: {
            display: true
          },
          title: {
            display: false,
            text: 'Pressure Gauge'
          }
        },
        rotation: 180 // Start angle (clockwise from bottom), Math.PI / 2 is 90 degrees
      }
    };
    const data = {
      labels: [],
      datasets: [
        {
          label: 'Temperature (°C)',
          borderColor: 'rgb(255, 99, 132)',
          backgroundColor: 'rgba(255, 99, 132, 0.5)',
          data: [],
          fill: true
        },
        {
          label: 'Humidity (%)',
          borderColor: 'rgb(0, 255, 64)',
          backgroundColor: 'rgba(0, 255, 64, 0.5)',
          data: [],
          fill: true
        },
        {
          label: 'Pressure (hPa)',
          borderColor: 'rgb(75, 192, 192)',
          backgroundColor: 'rgba(75, 192, 192, 0.5)',
          data: [],
          fill: true
        }
      ]
    };

    // Chart configuration
    const config = {
    type: 'line',
    data: data,
    options: {
      responsive: true,
      maintainAspectRatio:false,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Weather Data Over Time',
        },
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Time',
          },
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Value',
          },
        },
      },
    },
  };
    // Initialize the chart
   




    

    // Create temperature chart
    var temperatureChart = new Chart(
      document.getElementById('chart_temperature'),
      temperatureConfig
    );

    // Create humidity chart
    var humidityChart = new Chart(
      document.getElementById('chart_humidity'),
      humidityConfig
    );

    // Create pressure chart
    var pressureChart = new Chart(
      document.getElementById('chart_pressure'),
      pressureConfig
    );
    var lineChart = new Chart(
      document.getElementById('lineChart'),
      config
    );

    // Function to get gradient colors based on value
    function getGradientColors(value) {
      // Define gradient colors based on value (example)
      let startColor, endColor;
      if (value <= 30) {
        startColor = 'rgba(0, 255, 0, 0.5)'; // Green
        endColor = 'rgba(200, 200, 200)'; // Grey
      } else if (value <= 50) {
        startColor = 'rgba(255, 255, 0, 0.5)'; // Yellow
        endColor = 'rgba(200, 200, 200)'; // Grey
      } else {
        startColor = 'rgba(255, 0, 0, 0.5)'; // Red
        endColor = 'rgba(200, 200, 200)'; // Grey
      }

      // Return gradient array
      return [startColor, endColor];
    }
    

    document.getElementById("toggle").addEventListener("change", function() {
      document.body.classList.toggle("dark-theme");
      var textElement = document.getElementById("text-to-change");
      if (this.checked) {
            textElement.textContent = "Light Mode";
        } else {
            textElement.textContent = "Dark Mode";
        }
    });
    function refreshData() {
        $.ajax({
            url: 'getdata.php',
            dataType: 'json',
            success: function (response) {
                currentTemperature = parseFloat(response.temperature).toFixed(2);
                currentHumidity = parseFloat(response.humidity).toFixed(2);
                currentPressure = parseFloat(response.pressure).toFixed(2);

                document.getElementById('temperatureText').textContent = `${currentTemperature}°C`;
                document.getElementById('humidityText').textContent = `${currentHumidity}%`;
                document.getElementById('pressureText').textContent = `${currentPressure} hPa`;

                updateChart(temperatureChart, currentTemperature);
                updateChart(humidityChart, currentHumidity);
                updateChart(pressureChart, currentPressure);

                addDataToLineChart(lineChart, response.created_date, currentTemperature, currentHumidity,currentPressure);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown + ': ' + textStatus);
            }
        });
    }

    function updateChart(chart, value) {
        chart.data.datasets[0].data[0] = value;
        chart.data.datasets[0].data[1] = 100 - value;
        chart.data.datasets[0].backgroundColor = getGradientColors(value);
        chart.update();
    }
    function addDataToLineChart(chart, created_date, temperature, humidity, pressure) {
      chart.data.labels.push(created_date);
      chart.data.datasets[0].data.push(temperature);
      chart.data.datasets[1].data.push(humidity);
      chart.data.datasets[2].data.push(pressure);

      if (chart.data.labels.length > 20) { // Limit to 20 data points
        chart.data.labels.shift();
        chart.data.datasets.forEach((dataset) => {
          dataset.data.shift();
        });
      }

      chart.update();
    }


    setInterval(refreshData, 5000);
  </script>
</body>
</html>



<?php
$db->close(); // Close the database connection
?>
