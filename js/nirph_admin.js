(function(){
	var nirphApp = angular.module('nirphApp', ['ui.bootstrap']);

	nirphApp.controller('listController', ['$scope', '$http', listController]);	

	function listController($scope, $http) {    

        $scope.requestData = {'action': "listSubs"};
        $http.post('/serve.php', $scope.requestData).
            success(function(data, status, headers, config) {
                $scope.subs = data;
            })
            .error(function(data, status, headers, config) {
                $scope.addAlert("warning", "Error trying to fetch subscription details");
            });

	        $scope.alerts = [];        
	        
	        $scope.addAlert = function(alertType, alertMsg) {
	            $scope.alerts.push({
	                type: alertType,
	                msg: alertMsg
	            });
	        };
	        
	        $scope.removeOldAlerts = function() {
	            var totalAlerts = $scope.alerts.length;
	            for (var i = totalAlerts; i >= 0; i--) {
	                $scope.closeAlert(i);
	            }            
	        };        

	        $scope.closeAlert = function(index) {
	            $scope.alerts.splice(index, 1);
	        };

	        $scope.showModal = false;
	        $scope.toggleModal = function(){
	            $scope.showModal = !$scope.showModal;
	        }; 

	        $scope.viewSub = function(subID) {
	        	$scope.toggleModal();
	        	$scope.requestData = {'action': "viewSub", "id": subID};
		        $http.post('/serve.php', $scope.requestData).
		            success(function(data, status, headers, config) {
		                $scope.subscriber = data;
		                console.log('email = ' + $scope.subscriber.email);
		            })
		            .error(function(data, status, headers, config) {
		                $scope.addAlert("warning", "Error trying to fetch subscription details");
		            });
				
	        }  

	}	

    
    nirphApp.directive('modal', function () {
        return {
            template: '<div class="modal fade">' + 
                '<div class="modal-dialog">' + 
                  '<div class="modal-content">' + 
                    '<div class="modal-header">' + 
                      '<button type="button" class="close" ng-click="toggleModal()" aria-hidden="true">&times;</button>' + 
                      '<h4 class="modal-title">{{ title }}</h4>' + 
                    '</div>' + 
                    '<div class="modal-body" ng-transclude></div>' + 
                  '</div>' + 
                '</div>' + 
              '</div>',
            restrict: 'E',
            transclude: true,
            replace:true,
            scope:true,
            link: function postLink(scope, element, attrs) {

                (function ($) {
                                    
                    scope.title = attrs.title;

                    scope.$watch(attrs.visible, function(value){
                        if(value == true) {
                          $(element).modal('show');
                        } else {
                          $(element).modal('hide');
                        }
                    });

                    $(element).on('shown.bs.modal', function(){
                        scope.$apply(function(){
                          scope.$parent[attrs.visible] = true;
                        });
                    });

                    $(element).on('hidden.bs.modal', function(){
                        scope.$apply(function(){
                          scope.$parent[attrs.visible] = false;
                        });
                    });

                }(jQuery));

            }
        };
    });

})(); 
