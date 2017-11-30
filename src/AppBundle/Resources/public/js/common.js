var santa = angular
    .module('santa', [])
    .config(function ($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }).directive('a', function () {
        return {
            restrict: 'E',
            link: function ($scope, element, attrs) {
                if (attrs.ngClick || '#' === attrs.href) {
                    element.click(function (e) {
                        e.preventDefault();
                    });
                }
            }
        };
    });
