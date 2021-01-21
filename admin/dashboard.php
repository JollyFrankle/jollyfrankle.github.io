<?include'header.php';?>
<h2 class="border-bottom pb-2">Dashboard</h2>
<canvas id="myChart" width="400" height="200"></canvas>
<div id="echo"></div>
<?include'footer.php';?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var data = JSON.parse($.ajax({
        url:  'action/dashboard.php',
        dataType: "json", 
        async: false
    }).responseText);
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: JSON.parse(data.usia_rentang),
        datasets: [
        {
            label: 'Jumlah GTK',
            data: JSON.parse(data.usia_jumlah),
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            borderColor: 'rgba(0, 123, 255, 1)',
            pointBorderColor: 'rgba(0, 123, 255, 1)',
            borderWidth: 2
        },
        {
            label: 'Jumlah PNS',
            data: JSON.parse(data.SP_PNS),
            backgroundColor: 'rgb(40 167 69 / .2)',
            borderColor: 'rgb(40 167 69)',
            pointBorderColor: 'rgb(40 167 69)',
            borderWidth: 1
        },
        {
            label: 'Jumlah Non-PNS',
            data: JSON.parse(data.SP_NPNS),
            backgroundColor: 'rgb(220 53 69 / .2)',
            borderColor: 'rgb(220 53 69)',
            pointBorderColor: 'rgb(220 53 69)',
            borderWidth: 1
        },
        {
            label: 'Laki-laki',
            data: JSON.parse(data.JK_L),
            backgroundColor: 'rgb(255 193 7 / .2)',
            borderColor: 'rgb(255 193 7)',
            pointBorderColor: 'rgb(255 193 7)',
            borderWidth: 1
        },
        {
            label: 'Perempuan',
            data: JSON.parse(data.JK_P),
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            pointBorderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        tooltips: {
            enabled: true,
            mode: 'single',
            callbacks: {
                title: function(tooltipItems, data) { 
                    return 'Usia '+tooltipItems[0].xLabel+' tahun:';
                }
            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>