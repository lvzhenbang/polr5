var parseInputDate = function (inputDate) {
    return moment(inputDate);
};

function sortBy(objData, key) {
    return Object.values(objData).sort((a, b) => { return a[key].localeCompare(b[key]) })
}

$scope = {};

$scope.dayChart = null;
$scope.refererChart = null;
$scope.countryChart = null;

$scope.dayData = dayData;
$scope.refererData = refererData;
$scope.countryData = countryData;

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
        options: {
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                }],
                yAxes: [{
                    ticks: {
                        min: 0
                    }
                }]
            }
        }
    });
};
$scope.initRefererChart = function () {
    // Traffic sources
    var ctx = $("#refererChart");

    var srcLabels = [];
    // var bgColors = [];
    var bgColors = [ '#003559', '#162955', '#2E4272', '#4F628E', '#7887AB', '#b9d6f2'];
    var srcData = [];

    $scope.refererData.forEach(function(item) {
        if (srcLabels.length > 6) {
            // If more than 6 referers are listed, push the seventh and
            // beyond into "other"
            srcLabels[6] = 'Other';
            srcData[6] += item.clicks;
            bgColors[6] = 'brown';
            return;
        }

        srcLabels.push(item.label);
        srcData.push(item.clicks);
    });

    $scope.refererChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: srcLabels,
            datasets: [{
                data: srcData,
                backgroundColor: bgColors
            }]
        }
    });

    $('#refererTable').DataTable();
};
$scope.initCountryChart = function () {
    var parsedCountryData = {};

    $scope.countryData.forEach(function(country) {
        parsedCountryData[country.label] = country.clicks;
    });

    $('#mapChart').vectorMap({
        map: 'world_mill',
        series: {
            regions: [{
                values: parsedCountryData,
                scale: ['#C8EEFF', '#0071A4'],
                normalizeFunction: 'polynomial'
            }]
        },
        onRegionTipShow: function(e, el, code) {
            el.html(el.html()+' (' + (parsedCountryData[code] || 0) + ')');
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
    $scope.initRefererChart();
    $scope.initCountryChart();
    $scope.initDatePickers();
};

$scope.init();
