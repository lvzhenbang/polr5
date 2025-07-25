$scope = {};

$scope.init = function () {
    $('[data-toggle="popover"]').popover({
        trigger: "hover",
        placement: "right"
    });
};

$scope.init();
