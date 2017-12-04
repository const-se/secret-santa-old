angular.module('santa').controller('IndexController', function ($scope, $interval) {
    $scope.beforeNewYear = true;
    $scope.days1 = 0;
    $scope.days2 = 0;
    $scope.hours1 = 0;
    $scope.hours2 = 0;
    $scope.minutes1 = 0;
    $scope.minutes2 = 0;
    $scope.seconds1 = 0;
    $scope.seconds2 = 0;
    $scope.progress = 0;

    var newYearTime = new Date('2018/01/01 00:00:00').getTime();
    var fromTime = new Date('2017/12/01 00:00:00').getTime();
    var timer = $interval(function () {
        var estimate = Math.floor((newYearTime - new Date().getTime()) / 1000);

        if (estimate > 0) {
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

            var progress = Math.round((new Date().getTime() - fromTime) / (newYearTime - fromTime) * 100);

            if (progress > $scope.progress) {
                $scope.progress = progress;
            }
        } else {
            $scope.beforeNewYear = false;
            $interval.cancel(timer);
        }
    }, 1000);
});
angular.module('santa').controller('RegistrationController', function ($scope, $http) {
    $scope.participant = participant;
    $scope.participantErrors = {};
    $scope.loading = false;
    $scope.submitRegistrationForm = function () {
        $scope.loading = true;
        $scope.participantErrors = {};
        $http
            .post(Routing.generate('api_validate_registration'), $scope.participant)
            .then(function (response) {
                if (response.data.valid) {
                    $('.js-registration-form').submit();
                } else {
                    $scope.participantErrors = response.data.errors.children;
                    $.each($scope.participantErrors, function (index, value) {
                        $scope.registrationForm[index].$setValidity(index, !value.errors.length);
                    });
                    $scope.loading = false;
                }
            });
    };
});
angular.module('santa').controller('WelcomeController', function ($scope) {});
$('.js-rules-audio').click(function (e) {
    e.preventDefault();
    var $this = $(this),
        icon = $this.find('.js-rules-audio-icon'),
        text = $this.find('.js-rules-audio-text'),
        $audio = $('#rules-audio'),
        audio = $audio.get(0);

    if ($audio.data('playing')) {
        audio.pause();
        audio.currentTime = 0;
        $audio.data('playing', false);
        icon.removeClass('fa-pause').addClass('fa-volume-up');
        text.text('Прослушать');
    } else {
        audio.play();
        $audio.data('playing', true);
        icon.removeClass('fa-volume-up').addClass('fa-pause');
        text.text('Остановить');
    }
});