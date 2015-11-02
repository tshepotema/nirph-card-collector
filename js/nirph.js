(function(){
		var nirphApp = angular.module('nirphApp', ['ui.bootstrap']);

		nirphApp.controller('formController', ['$scope', '$http', formController]);	

		function formController($scope, $http) {                        
            
			$scope.selectedpackage = $scope.basic_package = $scope.std_package = $scope.awesome_package = "";

			$scope.selectPackage = function(pack) {
				$scope.selectedpackage = pack;
				switch (pack) {
					case "Basic": 
						$scope.basic_package = "selected";
						$scope.std_package = $scope.awesome_package = "";
					break;
					case "Standard": 						
						$scope.std_package = "selected";
						$scope.basic_package = $scope.awesome_package = "";
					break;
					case "Awesome": 
						$scope.basic_package = $scope.std_package = "";
						$scope.awesome_package = "selected";
					break;
					default:
						$scope.basic_package = $scope.std_package = $scope.awesome_package = "";
					break;
				}								
			};

			$scope.validateSubscription = function() {
				$scope.removeOldAlerts();

				if ($scope.username == "Your username" || $scope.username == "" || !$scope.username) {
					$scope.addAlert("warning", "Please enter a username.");
					return false;
				}
				if ($scope.email == "Your email" || $scope.email == "" || !$scope.email) {
					$scope.addAlert("warning", "Please enter a valid email address.");
					return false;
				}
				if ($scope.pwd == "password" || $scope.pwd == "" || !$scope.pwd) {
					$scope.addAlert("warning", "Please enter a password.");
					return false;
				}
				if ($scope.pwd2 == "confirm password" || !$scope.pwd2) {
					$scope.addAlert("warning", "Please confirm your password.");
					return false;
				}
				if ($scope.pwd != $scope.pwd2) {
					$scope.addAlert("warning", "Password does not match confirmation password.");
					return false;					
				}
				if ($scope.selectedpackage == "") {
					$scope.addAlert("warning", "Please select a subcription package.");
					return false;										
				}

		        // Simple post with subscription details
		        $scope.requestData = {'username': $scope.username, 'email': $scope.email, 'pwd': $scope.pwd, 'pwd2': $scope.pwd2, 'package': $scope.selectedpackage};
		        $http.post('/subscribe.php', $scope.requestData).
		            success(function(data, status, headers, config) {
		                $scope.addAlert("info", "" + data.msg);		                
		            })
		            .error(function(data, status, headers, config) {
		                $scope.addAlert("warning", "Error trying to submit subscription details");
		            });
		            
	            return false;
			};

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
	        
		}

})(); 
