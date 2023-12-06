angular.module('CleanSmartApp', [])

.config(function($interpolateProvider) {

  // To prevent the conflict of `{{` and `}}` symbols

  // between Blade template engine and AngularJS templating we need

  // to use different symbols for AngularJS.



  $interpolateProvider.startSymbol('[[');

  $interpolateProvider.endSymbol(']]');

})

.controller('ScopeController', function($scope, $http, $templateCache) {

    //var todoList = this;

    

    $scope.addedCliningOptions  = [];

    $scope.scopeform  = {'area_id':'','area_name':'','clining_option_id':'','clining_option_name':''};



    $scope.scopeLoad = function() {

      // $scope.code = null;

      // $scope.response = null;



      var req = {

        method: 'GET',

        url: '/scope/get-area',

        responseType: 'json',

        headers: {

          //'Content-Type': undefined

        },

        //data: { test: 'test' },

        //cache: $templateCache

      }



      $http(req).

        then(function(response) {

          if(response.data.success == true){

            $scope.scopeResult  = response.data;

          }else{

            alert("Something went wrong!");

          }

          console.log(response);

          // $scope.status = response.status;

        }, function(response) {

          alert("Something went wrong!");

          console.log(response);

          $scope.data = response.data || 'Request failed';

          $scope.status = response.status;

      });

    };



    $scope.getCliningOption = function(areaId) {

      $scope.addedCliningOptions  = [];

      $scope.scopeform.clining_option_id  = '';

      $scope.scopeform.clining_option_name  = '';



      var req = {

        method: 'POST',

        url: '/scope/get-clining-option',

        responseType: 'json',

        data: { 'area_id': areaId },

        cache: $templateCache

      }



      $http(req).

        then(function(response) {

          if(response.data.success == true){

            $scope.scopeResult.cliningOptionList  = response.data.cliningOptionList;

          }else{

            alert("Something went wrong!");

          }

          console.log(response);

          // $scope.status = response.status;

        }, function(response) {

          alert("Something went wrong!");

          console.log(response);

          $scope.data = response.data || 'Request failed';

          $scope.status = response.status;

      });

    };



    $scope.addCustomCliningOption = function(){

      if($scope.scopeform.clining_option_name.length > 0){

        $scope.addedCliningOptions[$scope.addedCliningOptions.length] = {'id':$scope.scopeform.clining_option_name,'name':$scope.scopeform.clining_option_name,'custom':true,'selected':false};

        //$scope.validateCliningOptionList();

        $scope.scopeform.clining_option_name  = "";

        //console.log($scope.addedCliningOptions);

      }

    }



    $scope.validateCliningOptionList = function(){

      $scope.scopeResult.cliningOptionList.forEach(function(cliningOptionList, index){

        $scope.addedCliningOptions.forEach(function(addedCliningOptionList){

          if(addedCliningOptionList.id == cliningOptionList.id){

            $scope.scopeResult.cliningOptionList.splice(index, 1);

          }

        });

      });

    }



    $scope.selectCliningOption = function(data,index){

      if($scope.addedCliningOptions[index].selected == true){

        data.selected = false;

      }else{

        data.selected = true;

      }

      if(data.id){

        var req = {

          method: 'POST',

          url: '/scope/get-clining-option-items',

          responseType: 'json',

          data: { 'cleaning_option_id': data.id },

          cache: $templateCache

        }

  

        $http(req).

          then(function(response) {

            if(response.data.success == true){

              data.cliningOptionItemList  = response.data.cliningOptionItemList;

            }else{

              alert("Something went wrong!");

            }

            console.log(response);

            // $scope.status = response.status;

          }, function(response) {

            alert("Something went wrong!");

            console.log(response);

            $scope.data = response.data || 'Request failed';

            $scope.status = response.status;

        });

      }

      $scope.addedCliningOptions[index] = data;

      console.log($scope.addedCliningOptions);

      

      $("#clining_option_"+data.id).select2({

        closeOnSelect : false,

        placeholder : "Please select or type to add...",

        allowClear: true,

        tags: true

      });

    }



    $scope.addArea = function(){



    }



    $scope.selectChange = function(type,data) {
      console.log(data.val());
      if(type == "area"){

        $scope.$apply(() => {
          
          $scope.scopeform.area_id        = data.val();
          //$scope.scopeform.area_custom    = data.val();
          $scope.scopeform.area_custom    = "";
          $scope.scopeform.area_custom    = data.val();

          $scope.scopeform.area_name  = data.find('option:selected').text();

        });

        $scope.getCliningOption(data.val());

      }

      else if(type == "cliningOption"){

        $scope.$apply(() => {

          $scope.scopeform.clining_option_id    = data.val();

          //$scope.scopeform.clining_option_name  = data.find('option:selected').text();

          if(data.find('option:selected').text().length > 0){

            $scope.addedCliningOptions[$scope.addedCliningOptions.length] = {'id':data.val(),'name':data.find('option:selected').text(),'custom':false,'selected':false};

            $scope.validateCliningOptionList();

            /* var clining_option_name = "";

            $scope.addedCliningOptions.forEach(function(data, index){

              if(index > 0){

                clining_option_name = clining_option_name+", "+data.name;

              }else{

                clining_option_name = data.name;

              }

            })

            $scope.scopeform.clining_option_name  = clining_option_name; */

          }

          //addedCliningOptions[] = {};



        });

      }

    };



    $(document).ready(function () {

      $(".chnageArea").on("change", function (e) { 

        $scope.selectChange('area',$(this));

      });

      $(".chnageCliningOption").on("change", function (e) { 

        $scope.selectChange('cliningOption',$(this));

      });

      $('.multiSelect').each((i,obj) => {

        if (!$(obj).hasClass("select2-hidden-accessible")) {

            $(obj).select2({

                closeOnSelect : false,

                placeholder : "Please select or type to add...",

                allowClear: true,

                tags: true

            });

        }

    });

    $('.frequencySelect').each((i,obj) => {

        if (!$(obj).hasClass("select2-hidden-accessible")) {

            $(obj).select2({

                placeholder : "Please select or type to add...",

                allowHtml: true,

                tags: true

            });

        }

    });

    });



});