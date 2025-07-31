function sortBy(objData, key) {
    return Object.values(objData).sort((a, b) => { return a[key].localeCompare(b[key]) })
}

$scope = {};

$scope.dayChart = null;
$scope.refererChart = null;
$scope.countryChart = null;

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

$scope.populateEmptyDayData = function () {
    // Populate empty days in $scope.dayData with zeroes

    // Number of days in range
    var numDays = moment(datePickerRightBound).diff(moment(datePickerLeftBound), 'days');
    var i = moment(datePickerLeftBound);

    var daysWithData = {};

    // Generate hash map to keep track of dates with data
    $scope.dayData.forEach(function(point) {
        var dayDate = point.x;
        daysWithData[dayDate] = true;
    });

    // Push zeroes for days without data
    [...Array(numDays).keys()].forEach(function () {
        var formattedDate = i.format('YYYY-MM-DD');

        if (!(formattedDate in daysWithData)) {
            // If day does not have data, fill in with 0
            $scope.dayData.push({
                x: formattedDate,
                y: 0
            })
        }

        i.add(1, 'day');
    });

    // Sort dayData from least to most recent
    // to ensure Chart.js displays the data correctly
    $scope.dayData = sortBy($scope.dayData, 'x');
}

$scope.initDayChart = function () {
    var ctx = $("#dayChart");

    // Populate empty days in dayData
    $scope.populateEmptyDayData();

    $scope.dayChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                label: 'Clicks',
                data: $scope.dayData,
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
    var $leftPicker = $('#left-bound-picker');
    var $rightPicker = $('#right-bound-picker');

    var leftFp = $leftPicker.flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d h:i K",
        defaultDate: datePickerLeftBound
    });
    var rightFp = $rightPicker.flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d h:i K",
        defaultDate: datePickerRightBound
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
