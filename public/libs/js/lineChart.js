const primary = '#6993FF';
const success = '#1BC5BD';
const info = '#8950FC';
const warning = '#FFA800';
const danger = '#F64E60';

function GetChart(){

    var formAttr = $("#GetChart");
    
    url = formAttr.attr("action");
    method = formAttr.attr("method");

    var data = {
        "interval": $("#range").val(),
        "to": $("#to").val(),
        "month": $("#month").val(),
        "month_to": $("#month_to").val(),
        "year_to": $("#year_to").val(),
        "from": $("#from").val(),
        "month_from": $("#month_from").val(),
        "year_from": $("#year_from").val()
    };

    if($("#range").val() == "7") {
        data["year"] =  $("#d_year").val();
    } else if($("#range").val() == "20") {
        data["year"] =  $("#m_year").val();
    }

    //console.log(data);

    $.ajax({
        url, method, data,
        success: function (response) {
            //console.log(response);
            response = JSON.parse(response);

            salesChart(response.sales, response.ticks, response.num_orders);

        }
    });
}

var salesChart = function (sales, dates, numbers) {

    // $("#Stats").html("");

    var data = {
        labels: dates,
        datasets: [{
            label: 'Total Orders',
            data: numbers,
            backgroundColor: ['rgba(255, 99, 132, 0.2)',],
            borderColor: ['rgba(65,131,196,1)',],
            borderWidth: 2,
            fill: false
        }, {
            label: 'Total Amount',
            data: sales,
            backgroundColor: ['rgba(255, 99, 132, 0.2)'],
            borderColor: ['rgba(255,99,132,1)',],
            borderWidth: 2,
            
            fill: false
        }]
    };

    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        legend: {
            display: false
        },
        elements: {
            point: {
                // pointBorderWidth: '2px',
                // radius: 0
            }
        }

    };

    if ($("#Stats").length) {
        var StatsCanvas = $("#Stats").get(0).getContext("2d");
        var Stats = new Chart(StatsCanvas, {
            type: 'line',
            data: data,
            options: options
        });
    }

    // const apexChart = "#Stats";
    // var options = {
    //     series: [{
    //         name: 'Orders',
    //         data: numbers
    //     }, {
    //         name: 'Amount',
    //         data: sales
    //     }],
    //     chart: {
    //         height: 250,
    //         type: 'area'
    //     },
    //     dataLabels: {
    //         enabled: false
    //     },
    //     stroke: {
    //         curve: 'smooth'
    //     },
    //     xaxis: {
    //         type: 'datetime',
    //         categories: dates
    //     },
    //     tooltip: {
    //         x: {
    //             format: 'dd/MM/yy'
    //         },
    //     },
    //     colors: [primary, success]
    // };

    // var chart = new ApexCharts(document.querySelector(apexChart), options);
    // chart.render();
}

$("#range").change(function(){

    val = $(this).val();
    if(val == 7) {
        $(".yearly").hide();
        $(".yyy").attr("disabled","disabled");

        $(".monthly").hide();
        $(".mmm").attr("disabled","disabled");

        $(".daily").show();
        $(".ddd").removeAttr("disabled");

    }
    else if(val == 30)
    {
        $(".daily").hide();
        $(".ddd").attr("disabled","disabled");
        
        $(".yearly").hide();
        $(".yyy").attr("disabled","disabled");

        $(".monthly").show();
        $(".mmm").removeAttr("disabled");

    }
    else if(val == 365)
    {
        $(".daily").hide();
        $(".ddd").attr("disabled","disabled");
        
        $(".monthly").hide();
        $(".mmm").attr("disabled","disabled");

        $(".yearly").show();
        $(".yyy").removeAttr("disabled");

    }

    GetChart();

});

$(".ddd, .mmm, .yyy").change(function(){ GetChart() });

GetChart();