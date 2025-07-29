$scope = {};

$scope.init = function () {
    $('[data-bs-toggle="tooltip"]').tooltip({
        trigger: "hover",
        placement: "right"
    });
};

$(document).ready(function(){
    $scope.init();
});
