var selectedDropDownValue = '';
var todayConvcersaionAppName = '';
var newsFeeedArray = [];
$(document).ready(function () {
    selectedDropDownValue = 'today';
    todayConvcersaionAppName = $('#conversion_graph_intervalsByAppName').val();
    //  console.log('todayConvcersaionAppName', todayConvcersaionAppName);
    $.ajax({
        type: 'POST',
        url: '/stats',
        cache: false,
        data: {filter: 'campaign', interval: 'today'},
        dataType: 'json',
        success: function (response) {
            console.log('campaign', response);
            plotCampaignGraphs(response.data.text.email, response.data.categories, response.data.series.email, 'emailChart');
            plotCampaignGraphs(response.data.text.push, response.data.categories, response.data.series.push, 'pushChart');
            plotCampaignGraphs(response.data.text.inapp, response.data.categories, response.data.series.inapp, 'inappChart');
        }
    });

    $.ajax({
        type: 'POST',
        url: '/stats',
        cache: false,
        data: {filter: 'newsfeed', interval: 'today'},
        success: function (response) {
            console.log('newsfeedResponse', response);
            plotGraphs(response, 'newsfeedChart');
        }
    });

    $.ajax({
        type: 'POST',
        url: '/stats',
        cache: false,
        data: {filter: 'conversion', interval: 'today', app_name: todayConvcersaionAppName},
        success: function (response) {
            console.log('conversaion', response);
            plotGraphs(response, 'conversionChart');
        }
    });
});

$('#campaign_graph_intervals').bind('change', function () {
    var value = $("#" + $(this).attr('id') + " option:selected").val();

    if (value !== '') {
        $.ajax({
            type: 'POST',
            url: '/stats',
            cache: false,
            data: {filter: 'campaign', interval: value},
            dataType: 'json',
            success: function (response) {
                plotCampaignGraphs(response.data.text.email, response.data.categories, response.data.series.email, 'emailChart');
                plotCampaignGraphs(response.data.text.push, response.data.categories, response.data.series.push, 'pushChart');
                plotCampaignGraphs(response.data.text.inapp, response.data.categories, response.data.series.inapp, 'inappChart');
            }
        });
        $.ajax({
            type: 'POST',
            url: '/stats',
            cache: false,
            data: {_token: $('meta[name=csrf-token]').val(), filter: 'newsfeed', interval: value},
            success: function (response) {
                //   console.log('newsFeed', response);
                plotGraphs(response, 'newsfeedChart');
            }
        });
    }
});

$('#newsfeed_graph_intervals').bind('change', function () {
    var value = $("#" + $(this).attr('id') + " option:selected").val();

    if (value !== '') {
        $.ajax({
            type: 'POST',
            url: '/stats',
            cache: false,
            data: {_token: $('meta[name=csrf-token]').val(), filter: 'newsfeed', interval: value},
            success: function (response) {
                plotGraphs(response, 'newsfeedChart');
            }
        });
    }
});

$('#conversion_graph_intervals').bind('change', function () {
    selectedDropDownValue = $("#" + $(this).attr('id') + " option:selected").val();
    convertsationGraph(selectedDropDownValue, todayConvcersaionAppName);
});

$('#conversion_graph_intervalsByAppName').bind('change', function () {
    todayConvcersaionAppName = $("#" + $(this).attr('id') + " option:selected").val();
    console.log('val', todayConvcersaionAppName);
    convertsationGraph(selectedDropDownValue, todayConvcersaionAppName);
});

function convertsationGraph(value, app_name) {
    console.log('value', value);
    console.log('app_name', app_name);
    if (value !== '') {
        $.ajax({
            type: 'POST',
            url: '/stats',
            cache: false,
            data: {filter: 'conversion', interval: value, app_name: app_name},
            success: function (response) {
                console.log('conversationResponse', response);
                plotGraphs(response, 'conversionChart');
            }
        });
    }
}

function plotGraphs(response, element) {
    Highcharts.chart(element, {
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        chart: {
            type: 'spline'
        },
        title: {
            text: response.data.text,
            align: 'left',
            x: 0,
            style: {
                fontSize: "14px",
                fontWeight: "bold",
                display:"block",
                width:"100%"
            }
        },
        xAxis: {
            categories: response.data.categories,
            visible: false,
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'white'
                }
            }
        },
        yAxis: {
            minRange: 0,
            title: {
                text: '',
            },
            stackLabels: {
                enabled: false,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: 0,
            verticalAlign: 'top',
            y: 40,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 5,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        if (this.y != 0) {
                            return this.y;
                        } else {
                            return null;
                        }
                    }
                },
                enableMouseTracking: false
            }
        },
        series: response.data.series
    });
}

function plotCampaignGraphs(text, categories, series, element) {
    Highcharts.chart(element, {
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        chart: {
            type: 'spline',
        },
        title: {
            text: text,
            align: 'left',
            x: 0,
            style: {
                fontSize: "14px",
                fontWeight: "bold",
                display:"block",
                width:"100%"
            }
        },
        xAxis: {
            categories: categories,
            visible: false,
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'white'
                }
            }
        },
        yAxis: {
            title: {
                text: '',
                align: 'top',
                y: 0
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: 0,
            verticalAlign: 'top',
            y: 40,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 5,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        if (this.y != 0) {
                            return this.y;
                        } else {
                            return null;
                        }
                    }
                },
                enableMouseTracking: false
            }
        },
        series: series
    });
}

function showCampaignGraph(element) {
    $('.campaignChart').addClass('hide');
    $('.' + element).removeClass('hide');
}