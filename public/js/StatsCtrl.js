function sortBy(objData, key) {
    return Object.values(objData).sort((a, b) => { return a[key].localeCompare(b[key]) })
}

$scope = {};

$scope.dayChart = null;
$scope.refererChart = null;
$scope.countryChart = null;

$scope.dateData = [];
$scope.dayData = dayData;
$scope.refererData = refererData;
$scope.browserData = browserData;
$scope.osData = osData;
$scope.deviceData = deviceData;
$scope.countryData = countryData;

$scope.chartOption = {
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            backgroundColor: "rgba(255, 255, 255, 0.95)",
            titleColor: "#333",
            titleFont: { weight: "normal", size: 15 },
            bodyFont: { weight: "normal", size: 16 },
            bodyColor: "rgba(92, 9, 247, 1)",
            padding: 12,
            cornerRadius: 2,
            borderColor: "rgba(0, 0, 0, 0.1)",
            borderWidth: 1,
            displayColors: false,
        }
    },
    responsive: true,
    scales: {
        x: {
            grace: "5%",
            
            ticks: {
                maxTicksLimit: 6,
            }
        },
        y: {
            beginAtZero: true,
        }
    },
};

$scope.populateEmptyDateData = function () {
    // Populate empty days in $scope.dayData with zeroes

    if (dateType == 'day') {

        // Number of days in range
        var arrDate = moment(datePickerRightBound).diff(moment(datePickerLeftBound), 'days');
        var i = moment(datePickerLeftBound);
        if (moment(datePickerRightBound).diff(moment(datePickerLeftBound), 'hours') % 24 > 0) {
            arrDate += 1; // Include the last day if the range is more than 24 hours
        }
        var dateWithData = {};
        // Generate hash map to keep track of dates with data
        $scope.dayData.forEach(function(point) {
            dateWithData[point.x] = true;
        });

        $scope.dateData = $scope.dayData;

        // Push zeroes for days without data
        [...Array(arrDate).keys()].forEach(function () {
            var formattedDate = i.format('YYYY-MM-DD');

            if (!(formattedDate in dateWithData)) {
                // If day does not have data, fill in with 0
                $scope.dateData.push({
                    x: formattedDate,
                    y: 0
                })
            }

            i.add(1, 'day');
        });

        // Sort dayData from least to most recent
        // to ensure Chart.js displays the data correctly
        $scope.dateData = sortBy($scope.dateData, 'x');
    }

    if (dateType == 'hour') {
        // Number of hours in range
        var numHours = moment(datePickerRightBound).diff(moment(datePickerLeftBound), 'hours');
        if (moment(datePickerRightBound).diff(moment(datePickerLeftBound), 'minutes') % 60 > 0) {
            numHours += 1; // Include the last hour if the range is more than 60 minutes
        }
        console.log('numHours: ' + numHours);
        var i = moment(datePickerLeftBound);
        var hoursWithData = {};

        // Generate hash map to keep track of hours with data
        $scope.dayData.forEach(function(point) {
            hoursWithData[point.x + ':00'] = true;
            $scope.dateData.push({ x: point.x + ':00', y: point.y});
        });

        // Push zeroes for hours without data
        [...Array(numHours).keys()].forEach(function () {
            var formattedDate = i.format('YYYY-MM-DD HH:00');

            if (!(formattedDate in hoursWithData)) {
                // If hour does not have data, fill in with 0
                $scope.dateData.push({
                    x: formattedDate,
                    y: 0
                })
            }

            i.add(1, 'hour');
        });

        // Sort dayData from least to most recent
        // to ensure Chart.js displays the data correctly
        $scope.dateData = sortBy($scope.dateData, 'x');
    }

    if (dateType == 'week') {
        // Number of weeks in range
        var numWeeks = moment(datePickerRightBound).diff(moment(datePickerLeftBound), 'weeks');
        var i = moment(datePickerLeftBound);

        var weeksWithData = {};

        // Generate hash map to keep track of weeks with data
        $scope.dayData.forEach(function(point) {
            weeksWithData[point.x] = true;
        });

        $scope.dateData = $scope.dayData;

        // Push zeroes for weeks without data
        [...Array(numWeeks).keys()].forEach(function () {
            var formattedDate = i.format('YYYY-MM W');

            if (!(formattedDate in weeksWithData)) {
                // If week does not have data, fill in with 0
                $scope.dateData.push({
                    x: formattedDate,
                    y: 0
                })
            }

            i.add(1, 'week');
        });

        // Sort dayData from least to most recent
        // to ensure Chart.js displays the data correctly
        $scope.dateData = sortBy($scope.dateData, 'x');
    }

    if (dateType == 'month') {
        // Number of months in range
        var numMonths = moment(datePickerRightBound).diff(moment(datePickerLeftBound), 'months');
        var i = moment(datePickerLeftBound);

        var monthsWithData = {};

        // Generate hash map to keep track of months with data
        $scope.dayData.forEach(function(point) {
            var monthDate = point.x;
            monthsWithData[monthDate] = true;
        });

        $scope.dateData = $scope.dayData;

        // Push zeroes for months without data
        [...Array(numMonths).keys()].forEach(function () {
            var formattedDate = i.format('YYYY-MM');

            if (!(formattedDate in monthsWithData)) {
                // If month does not have data, fill in with 0
                $scope.dateData.push({
                    x: formattedDate,
                    y: 0
                })
            }

            i.add(1, 'month');
        });

        // Sort dayData from least to most recent
        // to ensure Chart.js displays the data correctly
        $scope.dateData = sortBy($scope.dateData, 'x');
    }
}

$scope.initDayChart = function () {
    var ctx = $("#dayChart");

    // Populate empty date in dateData
    $scope.populateEmptyDateData();

    $scope.dayChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                label: 'Clicks',
                data: $scope.dateData,
                pointHoverBackgroundColor: "rgba(75,192,192,1)",
                pointHoverBorderColor: "rgba(220,220,220,1)",
                backgroundColor: "rgba(75,192,192,0.4)",
                borderColor: "rgba(75,192,192,1)",
            }]
        },
        options: $scope.chartOption
    });
};

$scope.initRefererChart = function () {
    let ctx = $("#refererChart");

    let srcLabels = [];
    let bgColors = [ '#003559', '#162955', '#2E4272', '#4F628E', '#7887AB', '#b9d6f2'];
    let srcData = [];

    $scope.refererData.forEach(function(item) {
        if (srcLabels.length > 6) {
            srcLabels[6] = 'Other';
            srcData[6] += item.clicks;
            bgColors[6] = 'brown';
            return;
        }

        srcLabels.push(item.label);
        srcData.push(item.clicks);
    });

    $scope.refererChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: srcLabels,
            datasets: [{
                data: srcData,
                backgroundColor: bgColors
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    backgroundColor: "rgba(255, 255, 255, 0.95)",
                    titleColor: "#333",
                    titleFont: { weight: "normal", size: 15 },
                    bodyFont: { weight: "normal", size: 16 },
                    bodyColor: "rgba(92, 9, 247, 1)",
                    padding: 12,
                    cornerRadius: 2,
                    borderColor: "rgba(0, 0, 0, 0.1)",
                    borderWidth: 1,
                    displayColors: false,
                }
            },
            responsive: true,
        }
    });
};

$scope.initBrowserChart = function() {
    let ctx = $("#browserChart");

    let srcLabels = [];
    let bgColors = [ '#003559', '#162955', '#2E4272', '#4F628E', '#7887AB', '#b9d6f2' ];
    let srcData = [];

    $scope.browserData.forEach(function(item) {
        if (srcLabels.length > 6) {
            srcLabels[6] = 'Other';
            srcData[6] += item.clicks;
            bgColors[6] = 'brown';
            return;
        }

        srcLabels.push(item.label);
        srcData.push(item.clicks);
    });

    $scope.browserChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: srcLabels,
            datasets: [{
                label: 'views',
                data: srcData,
                backgroundColor: bgColors
            }]
        },
        options: $scope.chartOption
    });
};

$scope.initOsChart = function() {
    let ctx = $("#osChart");

    let srcLabels = [];
    let bgColors = [ '#003559', '#162955', '#2E4272', '#4F628E', '#7887AB', '#b9d6f2' ];
    let srcData = [];

    $scope.osData.forEach(function(item) {
        if (srcLabels.length > 6) {
            srcLabels[6] = 'Other';
            srcData[6] += item.clicks;
            bgColors[6] = 'brown';
            return;
        }

        srcLabels.push(item.label);
        srcData.push(item.clicks);
    });

    $scope.osChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: srcLabels,
            datasets: [{
                label: 'views',
                data: srcData,
                backgroundColor: bgColors
            }]
        },
        options: $scope.chartOption
    });
};

$scope.initDeviceChart = function() {
    let ctx = $("#deviceChart");

    let srcLabels = [];
    let bgColors = [ '#003559', '#162955', '#2E4272', '#4F628E', '#7887AB', '#b9d6f2' ];
    let srcData = [];

    $scope.deviceData.forEach(function(item) {
        if (srcLabels.length > 6) {
            srcLabels[6] = 'Other';
            srcData[6] += item.clicks;
            bgColors[6] = 'brown';
            return;
        }

        srcLabels.push(item.label);
        srcData.push(item.clicks);
    });

    $scope.deviceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: srcLabels,
            datasets: [{
                label: 'views',
                data: srcData,
                backgroundColor: bgColors
            }]
        },
        options: $scope.chartOption
    });
};

$scope.initRefferTable = function() {
    $('#refererTable').DataTable();
}

$scope.initCountryChart = function () {
    var parsedCountryData = {};

    $scope.countryData.forEach(function(country) {
        parsedCountryData[country.label] = country.clicks;
    });

    $('#countryChart').vectorMap({
        map: 'world_mill',
        series: {
            regions: [{
                values: parsedCountryData,
                scale: ['#C8EEFF', '#0071A4'],
                normalizeFunction: 'polynomial'
            }]
        },
        regionStyle: {
            initial: {
                fill: '#d1d5db',
                stroke: '#676767',
                strokeWidth: 2.5,
            },
        },
        backgroundColor: '#fdfdfd',
        onRegionTipShow: function(e, el, code) {
            el.html(el.html()+' <span style="color:rgba(92, 9, 247, 1)">(' + (parsedCountryData[code] || 0) + ')</span>');
        }
    });

};

$scope.initDatePickers = function () {
    var start = moment(datePickerLeftBound);
    var end = moment(datePickerRightBound);
    $('#left-bound-picker').val(start.format('YYYY-MM-DD'));
    $('#right-bound-picker').val(end.format('YYYY-MM-DD'));
    $('#date-type').val(type);

    $('#stat-daterangepicker').daterangepicker({
        "timeZone": "UTC",
        "locale": { format: 'MMMM D, YYYY' },
        "ranges": {
            'Today': [moment().utc(), moment().utc()],
            'Yesterday': [moment().utc().subtract(1, 'days'), moment().utc().subtract(1, 'days')],
            'Last 7 Days': [moment().utc().subtract(6, 'days'), moment().utc()],
            'Last 30 Days': [moment().utc().subtract(29, 'days'), moment().utc()],
            'This Month': [moment().utc().startOf('month'), moment().utc().endOf('month')],
            'Last Month': [moment().utc().subtract(1, 'month').startOf('month'), moment().utc().subtract(1, 'month').endOf('month')]
        },
        "alwaysShowCalendars": true,
        "startDate": start,
        "endDate": end,
        "maxDate": moment().utc(),
    }, function(start, end, label) {
        $('#left-bound-picker').val(start.format('YYYY-MM-DD'));
        $('#right-bound-picker').val(end.format('YYYY-MM-DD'));        
        $('#date-type').val(label.toLocaleLowerCase().replace(' ', '_'));
    });
}

$scope.init = function () {
    $scope.initDayChart();
    $scope.initBrowserChart();
    $scope.initOsChart();
    $scope.initDeviceChart();
    $scope.initRefererChart();
    $scope.initCountryChart();
    $scope.initRefferTable();
    $scope.initDatePickers();
};

$scope.init();
