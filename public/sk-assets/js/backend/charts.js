
var complete = 7000;
var cancelled = 4000;
var processing = 5000;


function getOrderStat() {

    $.ajax({

        url: 'admin/get-order-stat',
        type: 'get',
        async: false,
        dataType: 'json',

        success: function(data) {
        console.log(data);

    },
    error: function() {
        toastr.error('something went wrong');
    }

});
}


!function (NioApp, $) {
    "use strict";
    var totalSales = {
        labels: ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan", "13 Jan", "14 Jan", "15 Jan", "16 Jan", "17 Jan", "18 Jan", "19 Jan", "20 Jan", "21 Jan", "22 Jan", "23 Jan", "24 Jan", "25 Jan", "26 Jan", "27 Jan", "28 Jan", "29 Jan", "30 Jan"],
        dataUnit: 'Sales',
        lineTension: .3,
        datasets: [{
            label: "Sales",
            color: "#9d72ff",
            background: NioApp.hexRGB('#9d72ff', .25),
            data: [130, 105, 125, 115, 110, 95, 131, 110, 115, 120, 111, 97, 113, 107, 122, 100, 85, 110, 130, 107, 90, 105, 123, 115, 100, 117, 125, 95, 137, 101]
        }]
    };
    var totalOrders = {
        labels: ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan", "13 Jan", "14 Jan", "15 Jan", "16 Jan", "17 Jan", "18 Jan", "19 Jan", "20 Jan", "21 Jan", "22 Jan", "23 Jan", "24 Jan", "25 Jan", "26 Jan", "27 Jan", "28 Jan", "29 Jan", "30 Jan"],
        dataUnit: 'Orders',
        lineTension: .3,
        datasets: [{
            label: "Orders",
            color: "#7de1f8",
            background: NioApp.hexRGB('#7de1f8', .25),
            data: [85, 125, 105, 115, 130, 106, 141, 110, 95, 120, 111, 105, 113, 107, 122, 100, 95, 110, 120, 107, 100, 105, 123, 115, 110, 117, 125, 75, 95, 101]
        }]
    };
    var totalCustomers = {
        labels: ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan", "13 Jan", "14 Jan", "15 Jan", "16 Jan", "17 Jan", "18 Jan", "19 Jan", "20 Jan", "21 Jan", "22 Jan", "23 Jan", "24 Jan", "25 Jan", "26 Jan", "27 Jan", "28 Jan", "29 Jan", "30 Jan"],
        dataUnit: 'Customers',
        lineTension: .3,
        datasets: [{
            label: "Customers",
            color: "#83bcff",
            background: NioApp.hexRGB('#83bcff', .25),
            data: [92, 105, 125, 85, 110, 106, 131, 105, 110, 115, 135, 105, 120, 85, 122, 100, 125, 110, 120, 125, 85, 105, 123, 115, 90, 117, 125, 100, 95, 65]
        }]
    };

    function ecommerceLineS1(selector, set_data) {
        var $selector = selector ? $(selector) : $('.ecommerce-line-chart-s1');
        $selector.each(function () {
            var $self = $(this),
                _self_id = $self.attr('id'),
                _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

            var selectCanvas = document.getElementById(_self_id).getContext("2d");
            var chart_data = [];

            for (var i = 0; i < _get_data.datasets.length; i++) {
                chart_data.push({
                    label: _get_data.datasets[i].label,
                    tension: _get_data.lineTension,
                    backgroundColor: _get_data.datasets[i].background,
                    borderWidth: 2,
                    borderColor: _get_data.datasets[i].color,
                    pointBorderColor: 'transparent',
                    pointBackgroundColor: 'transparent',
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: _get_data.datasets[i].color,
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 2,
                    pointRadius: 4,
                    pointHitRadius: 4,
                    data: _get_data.datasets[i].data
                });
            }

            var chart = new Chart(selectCanvas, {
                type: 'line',
                data: {
                    labels: _get_data.labels,
                    datasets: chart_data
                },
                options: {
                    legend: {
                        display: _get_data.legend ? _get_data.legend : false,
                        rtl: NioApp.State.isRTL,
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            fontColor: '#6783b8'
                        }
                    },
                    maintainAspectRatio: false,
                    tooltips: {
                        enabled: true,
                        rtl: NioApp.State.isRTL,
                        callbacks: {
                            title: function title(tooltipItem, data) {
                                return data['labels'][tooltipItem[0]['index']];
                            },
                            label: function label(tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                            }
                        },
                        backgroundColor: '#1c2b46',
                        titleFontSize: 10,
                        titleFontColor: '#fff',
                        titleMarginBottom: 4,
                        bodyFontColor: '#fff',
                        bodyFontSize: 10,
                        bodySpacing: 4,
                        yPadding: 6,
                        xPadding: 6,
                        footerMarginTop: 0,
                        displayColors: false
                    },
                    scales: {
                        yAxes: [{
                            display: false,
                            ticks: {
                                beginAtZero: true,
                                fontSize: 12,
                                fontColor: '#9eaecf',
                                padding: 0
                            },
                            gridLines: {
                                color: NioApp.hexRGB("#526484", .2),
                                tickMarkLength: 0,
                                zeroLineColor: NioApp.hexRGB("#526484", .2)
                            }
                        }],
                        xAxes: [{
                            display: false,
                            ticks: {
                                fontSize: 12,
                                fontColor: '#9eaecf',
                                source: 'auto',
                                padding: 0,
                                reverse: NioApp.State.isRTL
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 0,
                                zeroLineColor: NioApp.hexRGB("#526484", .2),
                                offsetGridLines: true
                            }
                        }]
                    }
                }
            });
        });
    } // init chart

    NioApp.coms.docReady.push(function () {
        ecommerceLineS1();
    });
    var storeVisitors = {
        labels: ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan", "13 Jan", "14 Jan", "15 Jan", "16 Jan", "17 Jan", "18 Jan", "19 Jan", "20 Jan", "21 Jan", "22 Jan", "23 Jan", "24 Jan", "25 Jan", "26 Jan", "27 Jan", "28 Jan", "29 Jan", "30 Jan"],
        dataUnit: 'People',
        lineTension: .1,
        datasets: [{
            label: "Current Month",
            color: "#9d72ff",
            dash: 0,
            background: "transparent",
            data: [4110, 4220, 4810, 5480, 4600, 5670, 6660, 4830, 5590, 5730, 4790, 4950, 5100, 5800, 5950, 5850, 5950, 4450, 4900, 8000, 7200, 7250, 7900, 8950, 6300, 7200, 7250, 7650, 6950, 4750]
        }]
    };

    function ecommerceLineS2(selector, set_data) {
        var $selector = selector ? $(selector) : $('.ecommerce-line-chart-s2');
        $selector.each(function () {
            var $self = $(this),
                _self_id = $self.attr('id'),
                _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

            var selectCanvas = document.getElementById(_self_id).getContext("2d");
            var chart_data = [];

            for (var i = 0; i < _get_data.datasets.length; i++) {
                chart_data.push({
                    label: _get_data.datasets[i].label,
                    tension: _get_data.lineTension,
                    backgroundColor: _get_data.datasets[i].background,
                    borderWidth: 2,
                    borderDash: _get_data.datasets[i].dash,
                    borderColor: _get_data.datasets[i].color,
                    pointBorderColor: 'transparent',
                    pointBackgroundColor: 'transparent',
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: _get_data.datasets[i].color,
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 2,
                    pointRadius: 4,
                    pointHitRadius: 4,
                    data: _get_data.datasets[i].data
                });
            }

            var chart = new Chart(selectCanvas, {
                type: 'line',
                data: {
                    labels: _get_data.labels,
                    datasets: chart_data
                },
                options: {
                    legend: {
                        display: _get_data.legend ? _get_data.legend : false,
                        rtl: NioApp.State.isRTL,
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            fontColor: '#6783b8'
                        }
                    },
                    maintainAspectRatio: false,
                    tooltips: {
                        enabled: true,
                        rtl: NioApp.State.isRTL,
                        callbacks: {
                            title: function title(tooltipItem, data) {
                                return data['labels'][tooltipItem[0]['index']];
                            },
                            label: function label(tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']];
                            }
                        },
                        backgroundColor: '#1c2b46',
                        titleFontSize: 13,
                        titleFontColor: '#fff',
                        titleMarginBottom: 6,
                        bodyFontColor: '#fff',
                        bodyFontSize: 12,
                        bodySpacing: 4,
                        yPadding: 10,
                        xPadding: 10,
                        footerMarginTop: 0,
                        displayColors: false
                    },
                    scales: {
                        yAxes: [{
                            display: true,
                            position: NioApp.State.isRTL ? "right" : "left",
                            ticks: {
                                fontSize: 12,
                                fontColor: '#9eaecf',
                                padding: 8,
                                stepSize: 2400,
                                display: false
                            },
                            gridLines: {
                                color: NioApp.hexRGB("#526484", .2),
                                tickMarkLength: 0,
                                zeroLineColor: NioApp.hexRGB("#526484", .2)
                            }
                        }],
                        xAxes: [{
                            display: false,
                            ticks: {
                                fontSize: 12,
                                fontColor: '#9eaecf',
                                source: 'auto',
                                padding: 0,
                                reverse: NioApp.State.isRTL
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 0,
                                zeroLineColor: 'transparent',
                                offsetGridLines: true
                            }
                        }]
                    }
                }
            });
        });
    } // init chart

    NioApp.coms.docReady.push(function () {
        ecommerceLineS2();
    });
    var todayOrders = {
        labels: ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"],
        dataUnit: 'Orders',
        lineTension: .3,
        datasets: [{
            label: "Orders",
            color: "#854fff",
            background: "transparent",
            data: [92, 105, 125, 85, 110, 106, 131, 105, 110, 131, 105, 110]
        }]
    };
    var todayRevenue = {
        labels: ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"],
        dataUnit: 'Orders',
        lineTension: .3,
        datasets: [{
            label: "Revenue",
            color: "#33d895",
            background: "transparent",
            data: [92, 105, 125, 85, 110, 106, 131, 105, 110, 131, 105, 110]
        }]
    };
    var todayCustomers = {
        labels: ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"],
        dataUnit: 'Orders',
        lineTension: .3,
        datasets: [{
            label: "Customers",
            color: "#ff63a5",
            background: "transparent",
            data: [92, 105, 125, 85, 110, 106, 131, 105, 110, 131, 105, 110]
        }]
    };
    var todayVisitors = {
        labels: ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM", "12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"],
        dataUnit: 'Orders',
        lineTension: .3,
        datasets: [{
            label: "Visitors",
            color: "#559bfb",
            background: "transparent",
            data: [92, 105, 125, 85, 110, 106, 131, 105, 110, 131, 105, 110]
        }]
    };

    function ecommerceLineS3(selector, set_data) {
        var $selector = selector ? $(selector) : $('.ecommerce-line-chart-s3');
        $selector.each(function () {
            var $self = $(this),
                _self_id = $self.attr('id'),
                _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

            var selectCanvas = document.getElementById(_self_id).getContext("2d");
            var chart_data = [];

            for (var i = 0; i < _get_data.datasets.length; i++) {
                chart_data.push({
                    label: _get_data.datasets[i].label,
                    tension: _get_data.lineTension,
                    backgroundColor: _get_data.datasets[i].background,
                    borderWidth: 2,
                    borderColor: _get_data.datasets[i].color,
                    pointBorderColor: 'transparent',
                    pointBackgroundColor: 'transparent',
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: _get_data.datasets[i].color,
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 2,
                    pointRadius: 4,
                    pointHitRadius: 4,
                    data: _get_data.datasets[i].data
                });
            }

            var chart = new Chart(selectCanvas, {
                type: 'line',
                data: {
                    labels: _get_data.labels,
                    datasets: chart_data
                },
                options: {
                    legend: {
                        display: _get_data.legend ? _get_data.legend : false,
                        rtl: NioApp.State.isRTL,
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            fontColor: '#6783b8'
                        }
                    },
                    maintainAspectRatio: false,
                    tooltips: {
                        enabled: true,
                        rtl: NioApp.State.isRTL,
                        callbacks: {
                            title: function title(tooltipItem, data) {
                                return false;
                            },
                            label: function label(tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                            }
                        },
                        backgroundColor: '#1c2b46',
                        titleFontSize: 8,
                        titleFontColor: '#fff',
                        titleMarginBottom: 4,
                        bodyFontColor: '#fff',
                        bodyFontSize: 8,
                        bodySpacing: 4,
                        yPadding: 6,
                        xPadding: 6,
                        footerMarginTop: 0,
                        displayColors: false
                    },
                    scales: {
                        yAxes: [{
                            display: false,
                            ticks: {
                                beginAtZero: false,
                                fontSize: 12,
                                fontColor: '#9eaecf',
                                padding: 0
                            },
                            gridLines: {
                                color: NioApp.hexRGB("#526484", .2),
                                tickMarkLength: 0,
                                zeroLineColor: NioApp.hexRGB("#526484", .2)
                            }
                        }],
                        xAxes: [{
                            display: false,
                            ticks: {
                                fontSize: 12,
                                fontColor: '#9eaecf',
                                source: 'auto',
                                padding: 0,
                                reverse: NioApp.State.isRTL
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 0,
                                zeroLineColor: NioApp.hexRGB("#526484", .2),
                                offsetGridLines: true
                            }
                        }]
                    }
                }
            });
        });
    } // init chart


    NioApp.coms.docReady.push(function () {
        ecommerceLineS3();
    });
    var salesStatistics = {
        labels: ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan", "13 Jan", "14 Jan", "15 Jan", "16 Jan", "17 Jan", "18 Jan", "19 Jan", "20 Jan", "21 Jan", "22 Jan", "23 Jan", "24 Jan", "25 Jan", "26 Jan", "27 Jan", "28 Jan", "29 Jan", "30 Jan"],
        dataUnit: 'People',
        lineTension: .4,
        datasets: [{
            label: "Total orders",
            color: "#9d72ff",
            dash: 0,
            background: NioApp.hexRGB('#9d72ff', .15),
            data: [3710, 4820, 4810, 5480, 5300, 5670, 6660, 4830, 5590, 5730, 4790, 4950, 5100, 5800, 5950, 5850, 5950, 4450, 4900, 8000, 7200, 7250, 7900, 8950, 6300, 7200, 7250, 7650, 6950, 4750]
        }, {
            label: "Canceled orders",
            color: "#eb6459",
            dash: [5],
            background: "transparent",
            data: [110, 220, 810, 480, 600, 670, 660, 830, 590, 730, 790, 950, 100, 800, 950, 850, 950, 450, 900, 0, 200, 250, 900, 950, 300, 200, 250, 650, 950, 750]
        }]
    };

    function ecommerceLineS4(selector, set_data) {
        var $selector = selector ? $(selector) : $('.ecommerce-line-chart-s4');
        $selector.each(function () {
            var $self = $(this),
                _self_id = $self.attr('id'),
                _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

            var selectCanvas = document.getElementById(_self_id).getContext("2d");
            var chart_data = [];

            for (var i = 0; i < _get_data.datasets.length; i++) {
                chart_data.push({
                    label: _get_data.datasets[i].label,
                    tension: _get_data.lineTension,
                    backgroundColor: _get_data.datasets[i].background,
                    borderWidth: 2,
                    borderDash: _get_data.datasets[i].dash,
                    borderColor: _get_data.datasets[i].color,
                    pointBorderColor: 'transparent',
                    pointBackgroundColor: 'transparent',
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: _get_data.datasets[i].color,
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 2,
                    pointRadius: 4,
                    pointHitRadius: 4,
                    data: _get_data.datasets[i].data
                });
            }

            var chart = new Chart(selectCanvas, {
                type: 'line',
                data: {
                    labels: _get_data.labels,
                    datasets: chart_data
                },
                options: {
                    legend: {
                        display: _get_data.legend ? _get_data.legend : false,
                        rtl: NioApp.State.isRTL,
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            fontColor: '#6783b8'
                        }
                    },
                    maintainAspectRatio: false,
                    tooltips: {
                        enabled: true,
                        rtl: NioApp.State.isRTL,
                        callbacks: {
                            title: function title(tooltipItem, data) {
                                return data['labels'][tooltipItem[0]['index']];
                            },
                            label: function label(tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']];
                            }
                        },
                        backgroundColor: '#1c2b46',
                        titleFontSize: 13,
                        titleFontColor: '#fff',
                        titleMarginBottom: 6,
                        bodyFontColor: '#fff',
                        bodyFontSize: 12,
                        bodySpacing: 4,
                        yPadding: 10,
                        xPadding: 10,
                        footerMarginTop: 0,
                        displayColors: false
                    },
                    scales: {
                        yAxes: [{
                            display: true,
                            stacked: _get_data.stacked ? _get_data.stacked : false,
                            position: NioApp.State.isRTL ? "right" : "left",
                            ticks: {
                                beginAtZero: true,
                                fontSize: 11,
                                fontColor: '#9eaecf',
                                padding: 10,
                                callback: function callback(value, index, values) {
                                    return '$ ' + value;
                                },
                                min: 0,
                                stepSize: 3000
                            },
                            gridLines: {
                                color: NioApp.hexRGB("#526484", .2),
                                tickMarkLength: 0,
                                zeroLineColor: NioApp.hexRGB("#526484", .2)
                            }
                        }],
                        xAxes: [{
                            display: false,
                            stacked: _get_data.stacked ? _get_data.stacked : false,
                            ticks: {
                                fontSize: 9,
                                fontColor: '#9eaecf',
                                source: 'auto',
                                padding: 10,
                                reverse: NioApp.State.isRTL
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 0,
                                zeroLineColor: 'transparent'
                            }
                        }]
                    }
                }
            });
        });
    } // init chart


    NioApp.coms.docReady.push(function () {
        ecommerceLineS4();
    });
    var averargeOrder = {
        labels: ["01 Jan", "02 Jan", "03 Jan", "04 Jan", "05 Jan", "06 Jan", "07 Jan", "08 Jan", "09 Jan", "10 Jan", "11 Jan", "12 Jan", "13 Jan", "14 Jan", "15 Jan", "16 Jan", "17 Jan", "18 Jan", "19 Jan", "20 Jan", "21 Jan", "22 Jan", "23 Jan", "24 Jan", "25 Jan", "26 Jan", "27 Jan", "28 Jan", "29 Jan", "30 Jan"],
        dataUnit: 'People',
        lineTension: .1,
        datasets: [{
            label: "Active Users",
            color: "#b695ff",
            background: "#b695ff",
            data: [1110, 1220, 1310, 980, 900, 770, 1060, 830, 690, 730, 790, 950, 1100, 800, 1250, 850, 950, 450, 900, 1000, 1200, 1250, 900, 950, 1300, 1200, 1250, 650, 950, 750]
        }]
    };

    function ecommerceBarS1(selector, set_data) {
        var $selector = selector ? $(selector) : $('.ecommerce-bar-chart-s1');
        $selector.each(function () {
            var $self = $(this),
                _self_id = $self.attr('id'),
                _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

            var selectCanvas = document.getElementById(_self_id).getContext("2d");
            var chart_data = [];

            for (var i = 0; i < _get_data.datasets.length; i++) {
                chart_data.push({
                    label: _get_data.datasets[i].label,
                    tension: _get_data.lineTension,
                    backgroundColor: _get_data.datasets[i].background,
                    borderWidth: 2,
                    borderColor: _get_data.datasets[i].color,
                    data: _get_data.datasets[i].data,
                    barPercentage: .7,
                    categoryPercentage: .7
                });
            }

            var chart = new Chart(selectCanvas, {
                type: 'bar',
                data: {
                    labels: _get_data.labels,
                    datasets: chart_data
                },
                options: {
                    legend: {
                        display: _get_data.legend ? _get_data.legend : false,
                        rtl: NioApp.State.isRTL,
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            fontColor: '#6783b8'
                        }
                    },
                    maintainAspectRatio: false,
                    tooltips: {
                        enabled: true,
                        rtl: NioApp.State.isRTL,
                        callbacks: {
                            title: function title(tooltipItem, data) {
                                return false; //data['labels'][tooltipItem[0]['index']];
                            },
                            label: function label(tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']];
                            }
                        },
                        backgroundColor: '#1c2b46',
                        titleFontSize: 9,
                        titleFontColor: '#fff',
                        titleMarginBottom: 6,
                        bodyFontColor: '#fff',
                        bodyFontSize: 9,
                        bodySpacing: 4,
                        yPadding: 6,
                        xPadding: 6,
                        footerMarginTop: 0,
                        displayColors: false
                    },
                    scales: {
                        yAxes: [{
                            display: true,
                            position: NioApp.State.isRTL ? "right" : "left",
                            ticks: {
                                beginAtZero: false,
                                fontSize: 12,
                                fontColor: '#9eaecf',
                                padding: 0,
                                display: false,
                                stepSize: 100
                            },
                            gridLines: {
                                color: NioApp.hexRGB("#526484", .2),
                                tickMarkLength: 0,
                                zeroLineColor: NioApp.hexRGB("#526484", .2)
                            }
                        }],
                        xAxes: [{
                            display: false,
                            ticks: {
                                fontSize: 12,
                                fontColor: '#9eaecf',
                                source: 'auto',
                                padding: 0,
                                reverse: NioApp.State.isRTL
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 0,
                                zeroLineColor: 'transparent',
                                offsetGridLines: true
                            }
                        }]
                    }
                }
            });
        });
    } // init chart


    NioApp.coms.docReady.push(function () {
        ecommerceBarS1();
    });

    var trafficSources = {
        labels: ["Organic Search", "Social Media", "Referrals", "Others"],
        dataUnit: 'People',
        legend: false,
        datasets: [{
            borderColor: "#fff",
            background: ["#b695ff", "#b8acff", "#ffa9ce", "#f9db7b"],
            data: [4305, 859, 482, 138]
        }]
    };
    var orderStatistics = {
        labels: ["Completed", "Processing", "Canclled"],
        dataUnit: 'People',
        legend: false,
        datasets: [{
            borderColor: "#fff",
            background: ["#816bff", "#13c9f2", "#ff82b7"],
            data: [complete, processing, cancelled]
        }]
    };
    function ecommerceDoughnutS1(selector, set_data) {
        var $selector = selector ? $(selector) : $('.ecommerce-doughnut-s1');
        $selector.each(function () {
            var $self = $(this),
                _self_id = $self.attr('id'),
                _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

            var selectCanvas = document.getElementById(_self_id).getContext("2d");
            var chart_data = [];

            for (var i = 0; i < _get_data.datasets.length; i++) {
                chart_data.push({
                    backgroundColor: _get_data.datasets[i].background,
                    borderWidth: 2,
                    borderColor: _get_data.datasets[i].borderColor,
                    hoverBorderColor: _get_data.datasets[i].borderColor,
                    data: _get_data.datasets[i].data
                });
            }

            var chart = new Chart(selectCanvas, {
                type: 'doughnut',
                data: {
                    labels: _get_data.labels,
                    datasets: chart_data
                },
                options: {
                    legend: {
                        display: _get_data.legend ? _get_data.legend : false,
                        rtl: NioApp.State.isRTL,
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            fontColor: '#6783b8'
                        }
                    },
                    rotation: -1.5,
                    cutoutPercentage: 70,
                    maintainAspectRatio: false,
                    tooltips: {
                        enabled: true,
                        rtl: NioApp.State.isRTL,
                        callbacks: {
                            title: function title(tooltipItem, data) {
                                return data['labels'][tooltipItem[0]['index']];
                            },
                            label: function label(tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                            }
                        },
                        backgroundColor: '#1c2b46',
                        titleFontSize: 13,
                        titleFontColor: '#fff',
                        titleMarginBottom: 6,
                        bodyFontColor: '#fff',
                        bodyFontSize: 12,
                        bodySpacing: 4,
                        yPadding: 10,
                        xPadding: 10,
                        footerMarginTop: 0,
                        displayColors: false
                    }
                }
            });
        });
    } // init chart

    NioApp.coms.docReady.push(function () {
        ecommerceDoughnutS1();
    });
}(NioApp, jQuery);
"use strict";

!function (NioApp, $) {
    "use strict";

    NioApp.Package.name = "DashLite";
    NioApp.Package.version = "2.3";
    var $win = $(window),
        $body = $('body'),
        $doc = $(document),
        //class names
        _body_theme = 'nio-theme',
        _menu = 'nk-menu',
        _mobile_nav = 'mobile-menu',
        _header = 'nk-header',
        _header_menu = 'nk-header-menu',
        _sidebar = 'nk-sidebar',
        _sidebar_mob = 'nk-sidebar-mobile',
        //breakpoints
        _break = NioApp.Break;

    function extend(obj, ext) {
        Object.keys(ext).forEach(function (key) {
            obj[key] = ext[key];
        });
        return obj;
    } // ClassInit @v1.0


    NioApp.ClassBody = function () {
        NioApp.AddInBody(_sidebar);
    }; // ClassInit @v1.0


    NioApp.ClassNavMenu = function () {
        NioApp.BreakClass('.' + _header_menu, _break.lg, {
            timeOut: 0
        });
        $win.on('resize', function () {
            NioApp.BreakClass('.' + _header_menu, _break.lg);
        });
    }; // Code Prettify @v1.0

    NioApp.Prettify = function () {
        window.prettyPrint && prettyPrint();
    }; // Copied @v1.0


    NioApp.Copied = function () {
        var clip = '.clipboard-init',
            target = '.clipboard-text',
            sclass = 'clipboard-success',
            eclass = 'clipboard-error'; // Feedback

        function feedback(el, state) {
            var $elm = $(el),
                $elp = $elm.parent(),
                copy = {
                    text: 'Copy',
                    done: 'Copied',
                    fail: 'Failed'
                },
                data = {
                    text: $elm.data('clip-text'),
                    done: $elm.data('clip-success'),
                    fail: $elm.data('clip-error')
                };
            copy.text = data.text ? data.text : copy.text;
            copy.done = data.done ? data.done : copy.done;
            copy.fail = data.fail ? data.fail : copy.fail;
            var copytext = state === 'success' ? copy.done : copy.fail,
                addclass = state === 'success' ? sclass : eclass;
            $elp.addClass(addclass).find(target).html(copytext);
            setTimeout(function () {
                $elp.removeClass(sclass + ' ' + eclass).find(target).html(copy.text).blur();
                $elp.find('input').blur();
            }, 2000);
        } // Init ClipboardJS


        if (ClipboardJS.isSupported()) {
            var clipboard = new ClipboardJS(clip);
            clipboard.on('success', function (e) {
                feedback(e.trigger, 'success');
                e.clearSelection();
            }).on('error', function (e) {
                feedback(e.trigger, 'error');
            });
        } else {
            $(clip).css('display', 'none');
        }

        ;
    }; // CurrentLink Detect @v1.0

    NioApp.CurrentLink = function () {
        var _link = '.nk-menu-link, .menu-link, .nav-link',
            _currentURL = window.location.href,
            fileName = _currentURL.substring(0, _currentURL.indexOf("#") == -1 ? _currentURL.length : _currentURL.indexOf("#")),
            fileName = fileName.substring(0, fileName.indexOf("?") == -1 ? fileName.length : fileName.indexOf("?"));

        $(_link).each(function () {
            var self = $(this),
                _self_link = self.attr('href');

            if (fileName.match(_self_link)) {
                self.closest("li").addClass('active current-page').parents().closest("li").addClass("active current-page");
                self.closest("li").children('.nk-menu-sub').css('display', 'block');
                self.parents().closest("li").children('.nk-menu-sub').css('display', 'block');
            } else {
                self.closest("li").removeClass('active current-page').parents().closest("li:not(.current-page)").removeClass("active");
            }
        });
    }; // PasswordSwitch @v1.0

    NioApp.PassSwitch = function () {
        NioApp.Passcode('.passcode-switch');
    }; // Toastr Message @v1.0

    NioApp.Toast = function (msg, ttype, opt) {
        var ttype = ttype ? ttype : 'info',
            msi = '',
            ticon = ttype === 'info' ? 'ni ni-info-fill' : ttype === 'success' ? 'ni ni-check-circle-fill' : ttype === 'error' ? 'ni ni-cross-circle-fill' : ttype === 'warning' ? 'ni ni-alert-fill' : '',
            def = {
                position: 'bottom-right',
                ui: '',
                icon: 'auto',
                clear: false
            },
            attr = opt ? extend(def, opt) : def;
        attr.position = attr.position ? 'toast-' + attr.position : 'toast-bottom-right';
        attr.icon = attr.icon === 'auto' ? ticon : attr.icon ? attr.icon : '';
        attr.ui = attr.ui ? ' ' + attr.ui : '';
        msi = attr.icon !== '' ? '<span class="toastr-icon"><em class="icon ' + attr.icon + '"></em></span>' : '', msg = msg !== '' ? msi + '<div class="toastr-text">' + msg + '</div>' : '';

        if (msg !== "") {
            if (attr.clear === true) {
                toastr.clear();
            }

            var option = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": attr.position + attr.ui,
                "closeHtml": '<span class="btn-trigger">Close</span>',
                "preventDuplicates": true,
                "showDuration": "1500",
                "hideDuration": "1500",
                "timeOut": "2000",
                "toastClass": "toastr",
                "extendedTimeOut": "3000"
            };
            toastr.options = extend(option, attr);
            toastr[ttype](msg);
        }
    }; // Toggle Screen @v1.0

    NioApp.TGL.screen = function (elm) {
        if ($(elm).exists()) {
            $(elm).each(function () {
                var ssize = $(this).data('toggle-screen');

                if (ssize) {
                    $(this).addClass('toggle-screen-' + ssize);
                }
            });
        }
    }; // Toggle Content @v1.0

    NioApp.TGL.content = function (elm, opt) {
        var toggle = elm ? elm : '.toggle',
            $toggle = $(toggle),
            $contentD = $('[data-content]'),
            toggleBreak = true,
            toggleCurrent = false,
            def = {
                active: 'active',
                content: 'content-active',
                "break": toggleBreak
            },
            attr = opt ? extend(def, opt) : def;
        NioApp.TGL.screen($contentD);
        $toggle.on('click', function (e) {
            toggleCurrent = this;
            NioApp.Toggle.trigger($(this).data('target'), attr);
            e.preventDefault();
        });
        $doc.on('mouseup', function (e) {
            if (toggleCurrent) {
                var $toggleCurrent = $(toggleCurrent),
                    $s2c = $('.select2-container'),
                    $dpd = $('.datepicker-dropdown'),
                    $tpc = $('.ui-timepicker-container');

                if (!$toggleCurrent.is(e.target) && $toggleCurrent.has(e.target).length === 0 && !$contentD.is(e.target) && $contentD.has(e.target).length === 0 && !$s2c.is(e.target) && $s2c.has(e.target).length === 0 && !$dpd.is(e.target) && $dpd.has(e.target).length === 0 && !$tpc.is(e.target) && $tpc.has(e.target).length === 0) {
                    NioApp.Toggle.removed($toggleCurrent.data('target'), attr);
                    toggleCurrent = false;
                }
            }
        });
        $win.on('resize', function () {
            $contentD.each(function () {
                var content = $(this).data('content'),
                    ssize = $(this).data('toggle-screen'),
                    toggleBreak = _break[ssize];

                if (NioApp.Win.width > toggleBreak) {
                    NioApp.Toggle.removed(content, attr);
                }
            });
        });
    }; // ToggleExpand @v1.0

    NioApp.TGL.expand = function (elm, opt) {
        var toggle = elm ? elm : '.expand',
            def = {
                toggle: true
            },
            attr = opt ? extend(def, opt) : def;
        $(toggle).on('click', function (e) {
            NioApp.Toggle.trigger($(this).data('target'), attr);
            e.preventDefault();
        });
    }; // Dropdown Menu @v1.0

    NioApp.TGL.ddmenu = function (elm, opt) {
        var imenu = elm ? elm : '.nk-menu-toggle',
            def = {
                active: 'active',
                self: 'nk-menu-toggle',
                child: 'nk-menu-sub'
            },
            attr = opt ? extend(def, opt) : def;
        $(imenu).on('click', function (e) {
            if (NioApp.Win.width < _break.lg || $(this).parents().hasClass(_sidebar)) {
                NioApp.Toggle.dropMenu($(this), attr);
            }

            e.preventDefault();
        });
    }; // Show Menu @v1.0

    NioApp.TGL.showmenu = function (elm, opt) {
        var toggle = elm ? elm : '.nk-nav-toggle',
            $toggle = $(toggle),
            $contentD = $('[data-content]'),
            toggleBreak = $contentD.hasClass(_header_menu) ? _break.lg : _break.xl,
            toggleOlay = _sidebar + '-overlay',
            toggleClose = {
                profile: true,
                menu: false
            },
            def = {
                active: 'toggle-active',
                content: _sidebar + '-active',
                body: 'nav-shown',
                overlay: toggleOlay,
                "break": toggleBreak,
                close: toggleClose
            },
            attr = opt ? extend(def, opt) : def;
        $toggle.on('click', function (e) {
            NioApp.Toggle.trigger($(this).data('target'), attr);
            e.preventDefault();
        });
        $doc.on('mouseup', function (e) {
            if (!$toggle.is(e.target) && $toggle.has(e.target).length === 0 && !$contentD.is(e.target) && $contentD.has(e.target).length === 0 && NioApp.Win.width < toggleBreak) {
                NioApp.Toggle.removed($toggle.data('target'), attr);
            }
        });
        $win.on('resize', function () {
            if ((NioApp.Win.width < _break.xl || NioApp.Win.width < toggleBreak) && !NioApp.State.isMobile) {
                NioApp.Toggle.removed($toggle.data('target'), attr);
            }
        });
    }; // Compact Sidebar @v1.0

    NioApp.sbCompact = function () {
        var toggle = '.nk-nav-compact',
            $toggle = $(toggle),
            $content = $('[data-content]');
        $toggle.on('click', function (e) {
            e.preventDefault();
            var $self = $(this),
                get_target = $self.data('target'),
                $self_content = $('[data-content=' + get_target + ']');
            $self.toggleClass('compact-active');
            $self_content.toggleClass('is-compact');
        });
    }; // Animate FormSearch @v1.0

    NioApp.Ani.formSearch = function (elm, opt) {
        var def = {
                active: 'active',
                timeout: 400,
                target: '[data-search]'
            },
            attr = opt ? extend(def, opt) : def;
        var $elem = $(elm),
            $target = $(attr.target);

        if ($elem.exists()) {
            $elem.on('click', function (e) {
                e.preventDefault();
                var $self = $(this),
                    the_target = $self.data('target'),
                    $self_st = $('[data-search=' + the_target + ']'),
                    $self_tg = $('[data-target=' + the_target + ']');

                if (!$self_st.hasClass(attr.active)) {
                    $self_tg.add($self_st).addClass(attr.active);
                    $self_st.find('input').focus();
                } else {
                    $self_tg.add($self_st).removeClass(attr.active);
                    setTimeout(function () {
                        $self_st.find('input').val('');
                    }, attr.timeout);
                }
            });
            $doc.on({
                keyup: function keyup(e) {
                    if (e.key === "Escape") {
                        $elem.add($target).removeClass(attr.active);
                    }
                },
                mouseup: function mouseup(e) {
                    if (!$target.find('input').val() && !$target.is(e.target) && $target.has(e.target).length === 0 && !$elem.is(e.target) && $elem.has(e.target).length === 0) {
                        $elem.add($target).removeClass(attr.active);
                    }
                }
            });
        }
    }; // Animate FormElement @v1.0

    NioApp.Ani.formElm = function (elm, opt) {
        var def = {
                focus: 'focused'
            },
            attr = opt ? extend(def, opt) : def;

        if ($(elm).exists()) {
            $(elm).each(function () {
                var $self = $(this);

                if ($self.val()) {
                    $self.parent().addClass(attr.focus);
                }

                $self.on({
                    focus: function focus() {
                        $self.parent().addClass(attr.focus);
                    },
                    blur: function blur() {
                        if (!$self.val()) {
                            $self.parent().removeClass(attr.focus);
                        }
                    }
                });
            });
        }
    }; // Form Validate @v1.0

    NioApp.Validate = function (elm, opt) {
        if ($(elm).exists()) {
            $(elm).each(function () {
                var def = {
                        errorElement: "span"
                    },
                    attr = opt ? extend(def, opt) : def;
                $(this).validate(attr);
            });
        }
    };

    NioApp.Validate.init = function () {
        NioApp.Validate('.form-validate', {
            errorElement: "span",
            errorClass: "invalid",
            errorPlacement: function errorPlacement(error, element) {
                if (element.parents().hasClass('input-group')) {
                    error.appendTo(element.parent().parent());
                } else {
                    error.appendTo(element.parent());
                }
            }
        });
    }; // Dropzone @v1.1

    NioApp.Dropzone = function (elm, opt) {
        if ($(elm).exists()) {
            $(elm).each(function () {
                var maxFiles = $(elm).data('max-files'),
                    maxFiles = maxFiles ? maxFiles : null;
                var maxFileSize = $(elm).data('max-file-size'),
                    maxFileSize = maxFileSize ? maxFileSize : 256;
                var acceptedFiles = $(elm).data('accepted-files'),
                    acceptedFiles = acceptedFiles ? acceptedFiles : null;
                var def = {
                        autoDiscover: false,
                        maxFiles: maxFiles,
                        maxFilesize: maxFileSize,
                        acceptedFiles: acceptedFiles
                    },
                    attr = opt ? extend(def, opt) : def;
                $(this).addClass('dropzone').dropzone(attr);
            });
        }
    }; // Dropzone Init @v1.0

    NioApp.Dropzone.init = function () {
        NioApp.Dropzone('.upload-zone', {
            url: "/images"
        });
    }; // Wizard @v1.0

    NioApp.Wizard = function () {
        var $wizard = $(".nk-wizard");

        if ($wizard.exists()) {
            $wizard.each(function () {
                var $self = $(this),
                    _self_id = $self.attr('id'),
                    $self_id = $('#' + _self_id).show();

                $self_id.steps({
                    headerTag: ".nk-wizard-head",
                    bodyTag: ".nk-wizard-content",
                    labels: {
                        finish: "Submit",
                        next: "Next",
                        previous: "Prev",
                        loading: "Loading ..."
                    },
                    titleTemplate: '<span class="number">0#index#</span> #title#',
                    onStepChanging: function onStepChanging(event, currentIndex, newIndex) {
                        // Allways allow previous action even if the current form is not valid!
                        if (currentIndex > newIndex) {
                            return true;
                        } // Needed in some cases if the user went back (clean up)


                        if (currentIndex < newIndex) {
                            // To remove error styles
                            $self_id.find(".body:eq(" + newIndex + ") label.error").remove();
                            $self_id.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                        }

                        $self_id.validate().settings.ignore = ":disabled,:hidden";
                        return $self_id.valid();
                    },
                    onFinishing: function onFinishing(event, currentIndex) {
                        $self_id.validate().settings.ignore = ":disabled";
                        return $self_id.valid();
                    },
                    onFinished: function onFinished(event, currentIndex) {
                        window.location.href = "#";
                    }
                }).validate({
                    errorElement: "span",
                    errorClass: "invalid",
                    errorPlacement: function errorPlacement(error, element) {
                        error.appendTo(element.parent());
                    }
                });
            });
        }
    }; // DataTable @1.1

    NioApp.DataTable = function (elm, opt) {
        if ($(elm).exists()) {
            $(elm).each(function () {
                var auto_responsive = $(this).data('auto-responsive'),
                    has_export = typeof opt.buttons !== 'undefined' && opt.buttons ? true : false;
                var export_title = $(this).data('export-title') ? $(this).data('export-title') : 'Export';
                var btn = has_export ? '<"dt-export-buttons d-flex align-center"<"dt-export-title d-none d-md-inline-block">B>' : '',
                    btn_cls = has_export ? ' with-export' : '';
                var dom_normal = '<"row justify-between g-2' + btn_cls + '"<"col-7 col-sm-4 text-left"f><"col-5 col-sm-8 text-right"<"datatable-filter"<"d-flex justify-content-end g-2"' + btn + 'l>>>><"datatable-wrap my-3"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>>';
                var dom_separate = '<"row justify-between g-2' + btn_cls + '"<"col-7 col-sm-4 text-left"f><"col-5 col-sm-8 text-right"<"datatable-filter"<"d-flex justify-content-end g-2"' + btn + 'l>>>><"my-3"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>>';
                var dom = $(this).hasClass('is-separate') ? dom_separate : dom_normal;
                var def = {
                        responsive: true,
                        autoWidth: false,
                        dom: dom,
                        language: {
                            search: "",
                            searchPlaceholder: "Type in to Search",
                            lengthMenu: "<span class='d-none d-sm-inline-block'>Show</span><div class='form-control-select'> _MENU_ </div>",
                            info: "_START_ -_END_ of _TOTAL_",
                            infoEmpty: "No records found",
                            infoFiltered: "( Total _MAX_  )",
                            paginate: {
                                "first": "First",
                                "last": "Last",
                                "next": "Next",
                                "previous": "Prev"
                            }
                        }
                    },
                    attr = opt ? extend(def, opt) : def;
                attr = auto_responsive === false ? extend(attr, {
                    responsive: false
                }) : attr;
                $(this).DataTable(attr);
                $('.dt-export-title').text(export_title);
            });
        }
    }; // DataTable Init @v1.0

    NioApp.DataTable.init = function (options) {
        NioApp.DataTable('.datatable-init', options);
        NioApp.DataTable('.datatable-init-export', {
            responsive: {
                details: true
            },
            buttons: ['copy', 'excel', 'csv', 'pdf']
        });
        $.fn.DataTable.ext.pager.numbers_length = 7;
    }; // BootStrap Extended

    NioApp.BS.ddfix = function (elm, exc) {
        var dd = elm ? elm : '.dropdown-menu',
            ex = exc ? exc : 'a:not(.clickable), button:not(.clickable), a:not(.clickable) *, button:not(.clickable) *';
        $(dd).on('click', function (e) {
            if (!$(e.target).is(ex)) {
                e.stopPropagation();
                return;
            }
        });

        if (NioApp.State.isRTL) {
            var $dMenu = $('.dropdown-menu');
            $dMenu.each(function () {
                var $self = $(this);

                if ($self.hasClass('dropdown-menu-right') && !$self.hasClass('dropdown-menu-center')) {
                    $self.prev('[data-toggle="dropdown"]').dropdown({
                        popperConfig: {
                            placement: 'bottom-start'
                        }
                    });
                } else if (!$self.hasClass('dropdown-menu-right') && !$self.hasClass('dropdown-menu-center')) {
                    $self.prev('[data-toggle="dropdown"]').dropdown({
                        popperConfig: {
                            placement: 'bottom-end'
                        }
                    });
                }
            });
        }
    }; // BootStrap Specific Tab Open

    NioApp.BS.tabfix = function (elm) {
        var tab = elm ? elm : '[data-toggle="modal"]';
        $(tab).on('click', function () {
            var _this = $(this),
                target = _this.data('target'),
                target_href = _this.attr('href'),
                tg_tab = _this.data('tab-target');

            var modal = target ? $body.find(target) : $body.find(target_href);

            if (tg_tab && tg_tab !== '#' && modal) {
                modal.find('[href="' + tg_tab + '"]').tab('show');
            } else if (modal) {
                var tabdef = modal.find('.nk-nav.nav-tabs');
                var link = $(tabdef[0]).find('[data-toggle="tab"]');
                $(link[0]).tab('show');
            }
        });
    }; // Dark Mode Switch @since v2.0

    NioApp.ModeSwitch = function () {
        var toggle = $('.dark-switch');

        if ($body.hasClass('dark-mode')) {
            toggle.addClass('active');
        } else {
            toggle.removeClass('active');
        }

        toggle.on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $body.toggleClass('dark-mode');
        });
    }; // Knob @v1.0

    NioApp.Knob = function (elm, opt) {
        if ($(elm).exists() && typeof $.fn.knob === 'function') {
            var def = {
                    min: 0
                },
                attr = opt ? extend(def, opt) : def;
            $(elm).each(function () {
                $(this).knob(attr);
            });
        }
    }; // Knob Init @v1.0

    NioApp.Knob.init = function () {
        var knob = {
            "default": {
                readOnly: true,
                lineCap: "round"
            },
            half: {
                angleOffset: -90,
                angleArc: 180,
                readOnly: true,
                lineCap: "round"
            }
        };
        NioApp.Knob('.knob', knob["default"]);
        NioApp.Knob('.knob-half', knob.half);
    }; // Range @v1.0.1

    NioApp.Range = function (elm, opt) {
        if ($(elm).exists() && typeof noUiSlider !== 'undefined') {
            $(elm).each(function () {
                var $self = $(this),
                    self_id = $self.attr('id');

                var _start = $self.data('start'),
                    _start = /\s/g.test(_start) ? _start.split(' ') : _start,
                    _start = _start ? _start : 0,
                    _connect = $self.data('connect'),
                    _connect = /\s/g.test(_connect) ? _connect.split(' ') : _connect,
                    _connect = typeof _connect == 'undefined' ? 'lower' : _connect,
                    _min = $self.data('min'),
                    _min = _min ? _min : 0,
                    _max = $self.data('max'),
                    _max = _max ? _max : 100,
                    _min_distance = $self.data('min-distance'),
                    _min_distance = _min_distance ? _min_distance : null,
                    _max_distance = $self.data('max-distance'),
                    _max_distance = _max_distance ? _max_distance : null,
                    _step = $self.data('step'),
                    _step = _step ? _step : 1,
                    _orientation = $self.data('orientation'),
                    _orientation = _orientation ? _orientation : 'horizontal',
                    _tooltip = $self.data('tooltip'),
                    _tooltip = _tooltip ? _tooltip : false;

                console.log(_tooltip);
                var target = document.getElementById(self_id);
                var def = {
                        start: _start,
                        connect: _connect,
                        direction: NioApp.State.isRTL ? "rtl" : "ltr",
                        range: {
                            'min': _min,
                            'max': _max
                        },
                        margin: _min_distance,
                        limit: _max_distance,
                        step: _step,
                        orientation: _orientation,
                        tooltips: _tooltip
                    },
                    attr = opt ? extend(def, opt) : def;
                noUiSlider.create(target, attr);
            });
        }
    }; // Range Init @v1.0

    NioApp.Range.init = function () {
        NioApp.Range('.form-control-slider');
        NioApp.Range('.form-range-slider');
    };

    NioApp.Select2.init = function () {
        // NioApp.Select2('.select');
        NioApp.Select2('.form-select');
    }; // Slick Slider @v1.0.1

    NioApp.Slick = function (elm, opt) {
        if ($(elm).exists() && typeof $.fn.slick === 'function') {
            $(elm).each(function () {
                var def = {
                        'prevArrow': '<div class="slick-arrow-prev"><a href="javascript:void(0);" class="slick-prev"><em class="icon ni ni-chevron-left"></em></a></div>',
                        'nextArrow': '<div class="slick-arrow-next"><a href="javascript:void(0);" class="slick-next"><em class="icon ni ni-chevron-right"></em></a></div>',
                        rtl: NioApp.State.isRTL
                    },
                    attr = opt ? extend(def, opt) : def;
                $(this).slick(attr);
            });
        }
    }; // Slick Init @v1.0

    NioApp.Slider.init = function () {
        NioApp.Slick('.slider-init');
    }; // Magnific Popup @v1.0.0

    NioApp.Lightbox = function (elm, type, opt) {
        if ($(elm).exists()) {
            $(elm).each(function () {
                var def = {};

                if (type == 'video' || type == 'iframe') {
                    def = {
                        type: 'iframe',
                        removalDelay: 160,
                        preloader: true,
                        fixedContentPos: false,
                        callbacks: {
                            beforeOpen: function beforeOpen() {
                                this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                                this.st.mainClass = this.st.el.attr('data-effect');
                            }
                        }
                    };
                } else if (type == 'content') {
                    def = {
                        type: 'inline',
                        preloader: true,
                        removalDelay: 400,
                        mainClass: 'mfp-fade content-popup'
                    };
                } else {
                    def = {
                        type: 'image',
                        mainClass: 'mfp-fade image-popup'
                    };
                }

                var attr = opt ? extend(def, opt) : def;
                $(this).magnificPopup(attr);
            });
        }
    }; // Controls @v1.0.0

    NioApp.Control = function (elm) {
        var control = document.querySelectorAll(elm);
        control.forEach(function (item, index, arr) {
            item.checked ? item.parentNode.classList.add('checked') : null;
            item.addEventListener("change", function () {
                if (item.type == "checkbox") {
                    item.checked ? item.parentNode.classList.add('checked') : item.parentNode.classList.remove('checked');
                }

                if (item.type == "radio") {
                    document.querySelectorAll('input[name="' + item.name + '"]').forEach(function (item, index, arr) {
                        item.parentNode.classList.remove('checked');
                    });
                    item.checked ? item.parentNode.classList.add('checked') : null;
                }
            });
        });
    }; // Number Spinner @v1.0

    NioApp.NumberSpinner = function (elm, opt) {
        var plus = document.querySelectorAll("[data-number='plus']");
        var minus = document.querySelectorAll("[data-number='minus']");
        plus.forEach(function (item, index, arr) {
            var parent = plus[index].parentNode;
            plus[index].addEventListener("click", function () {
                var child = plus[index].parentNode.children;
                child.forEach(function (item, index, arr) {
                    if (child[index].classList.contains("number-spinner")) {
                        var value = !child[index].value == "" ? parseInt(child[index].value) : 0;
                        var step = !child[index].step == "" ? parseInt(child[index].step) : 1;
                        var max = !child[index].max == "" ? parseInt(child[index].max) : Infinity;

                        if (max + 1 > value + step) {
                            child[index].value = value + step;
                        } else {
                            child[index].value = value;
                        }
                    }
                });
            });
        });
        minus.forEach(function (item, index, arr) {
            var parent = minus[index].parentNode;
            minus[index].addEventListener("click", function () {
                var child = minus[index].parentNode.children;
                child.forEach(function (item, index, arr) {
                    if (child[index].classList.contains("number-spinner")) {
                        var value = !child[index].value == "" ? parseInt(child[index].value) : 0;
                        var step = !child[index].step == "" ? parseInt(child[index].step) : 1;
                        var min = !child[index].min == "" ? parseInt(child[index].min) : 0;

                        if (min - 1 < value - step) {
                            child[index].value = value - step;
                        } else {
                            child[index].value = value;
                        }
                    }
                });
            });
        });
    }; // Extra @v1.1

    NioApp.OtherInit = function () {
        NioApp.ClassBody();
        NioApp.PassSwitch();
        NioApp.CurrentLink();
        NioApp.LinkOff('.is-disable');
        NioApp.ClassNavMenu();
        NioApp.SetHW('[data-height]', 'height');
        NioApp.SetHW('[data-width]', 'width');
        NioApp.NumberSpinner();
        NioApp.Lightbox('.popup-video', 'video');
        NioApp.Lightbox('.popup-iframe', 'iframe');
        NioApp.Lightbox('.popup-image', 'image');
        NioApp.Lightbox('.popup-content', 'content');
        NioApp.Control('.custom-control-input');
    }; // Animate Init @v1.0

    NioApp.Ani.init = function () {
        NioApp.Ani.formElm('.form-control-outlined');
        NioApp.Ani.formSearch('.toggle-search');
    }; // BootstrapExtend Init @v1.0

    NioApp.BS.init = function () {
        NioApp.BS.menutip('a.nk-menu-link');
        NioApp.BS.tooltip('.nk-tooltip');
        NioApp.BS.tooltip('.btn-tooltip', {
            placement: 'top'
        });
        NioApp.BS.tooltip('[data-toggle="tooltip"]');
        NioApp.BS.tooltip('.tipinfo,.nk-menu-tooltip', {
            placement: 'right'
        });
        NioApp.BS.popover('[data-toggle="popover"]');
        NioApp.BS.progress('[data-progress]');
        NioApp.BS.fileinput('.custom-file-input');
        NioApp.BS.modalfix();
        NioApp.BS.ddfix();
        NioApp.BS.tabfix();
    }; // Picker Init @v1.0

    NioApp.Picker.init = function () {
        NioApp.Picker.date('.date-picker');
        NioApp.Picker.dob('.date-picker-alt');
        NioApp.Picker.time('.time-picker');
        NioApp.Picker.date('.date-picker-range', {
            todayHighlight: false,
            autoclose: false
        });
    }; // Addons @v1

    NioApp.Addons.Init = function () {
        NioApp.Knob.init();
        NioApp.Range.init();
        NioApp.Select2.init();
        NioApp.Dropzone.init();
        NioApp.Slider.init();
    }; // Toggler @v1

    NioApp.TGL.init = function () {
        NioApp.TGL.content('.toggle');
        NioApp.TGL.expand('.toggle-expand');
        NioApp.TGL.expand('.toggle-opt', {
            toggle: false
        });
        NioApp.TGL.showmenu('.nk-nav-toggle');
        NioApp.TGL.ddmenu('.' + _menu + '-toggle', {
            self: _menu + '-toggle',
            child: _menu + '-sub'
        });
    };

    NioApp.BS.modalOnInit = function () {
        $('.modal').on('shown.bs.modal', function () {
            NioApp.Select2.init();
            NioApp.Validate.init();
        });
    }; // Initial by default
    /////////////////////////////

    NioApp.init = function () {
        NioApp.coms.docReady.push(NioApp.OtherInit);
        NioApp.coms.docReady.push(NioApp.Prettify);
        NioApp.coms.docReady.push(NioApp.ColorBG);
        NioApp.coms.docReady.push(NioApp.ColorTXT);
        NioApp.coms.docReady.push(NioApp.Copied);
        NioApp.coms.docReady.push(NioApp.Ani.init);
        NioApp.coms.docReady.push(NioApp.TGL.init);
        NioApp.coms.docReady.push(NioApp.BS.init);
        NioApp.coms.docReady.push(NioApp.Validate.init);
        NioApp.coms.docReady.push(NioApp.Picker.init);
        NioApp.coms.docReady.push(NioApp.Addons.Init);
        NioApp.coms.docReady.push(NioApp.Wizard);
        NioApp.coms.docReady.push(NioApp.sbCompact);
        NioApp.coms.winLoad.push(NioApp.ModeSwitch);
    };

    NioApp.init();
    return NioApp;
}(NioApp, jQuery);