angular.module('santa').controller('IndexController', function ($scope, $interval) {
    $scope.days1 = 0;
    $scope.days2 = 0;
    $scope.hours1 = 0;
    $scope.hours2 = 0;
    $scope.minutes1 = 0;
    $scope.minutes2 = 0;
    $scope.seconds1 = 0;
    $scope.seconds2 = 0;
    $scope.progress = 0;

    var newYearTime = + new Date('2018-01-01 00:00:00');
    var fromTime = + new Date('2017-12-01 00:00:00');
    $interval(function () {
        var estimate = Math.floor((newYearTime - new Date()) / 1000);
        var days = Math.floor(estimate / 60 / 60 / 24);
        $scope.days1 = Math.floor(days / 10);
        $scope.days2 = days % 10;
        estimate = estimate - days * 60 * 60 * 24;
        var hours = Math.floor(estimate / 60 / 60);
        $scope.hours1 = Math.floor(hours / 10);
        $scope.hours2 = hours % 10;
        estimate = estimate - hours * 60 * 60;
        var minutes = Math.floor(estimate / 60);
        $scope.minutes1 = Math.floor(minutes / 10);
        $scope.minutes2 = minutes % 10;
        estimate = estimate - minutes * 60;
        $scope.seconds1 = Math.floor(estimate / 10);
        $scope.seconds2 = estimate % 10;

        var progress = Math.round((new Date() - fromTime) / (newYearTime - fromTime) * 100);

        if (progress > $scope.progress) {
            $scope.progress = progress;
        }
    }, 1000);
});
angular.module('santa').controller('RegistrationController', function ($scope) {
});
