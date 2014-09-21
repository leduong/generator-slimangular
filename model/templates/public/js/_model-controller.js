'use strict';

angular.module('<%= baseName %>')
  .controller('<%= _.capitalize(name) %>Ctrl', ['$scope', '$modal', 'resolved<%= _.capitalize(name) %>', '<%= _.capitalize(name) %>',
    function ($scope, $modal, resolved<%= _.capitalize(name) %>, <%= _.capitalize(name) %>) {

      $scope.<%= pluralize(name) %> = resolved<%= _.capitalize(name) %>;

      $scope.create = function () {
        $scope.clear();
        $scope.open();
      };

      $scope.update = function (id) {
        $scope.<%= name %> = <%= _.capitalize(name) %>.get({id: id});
        $scope.open(id);
      };

      $scope.delete = function (id) {
        <%= _.capitalize(name) %>.delete({id: id},
          function () {
            $scope.<%= pluralize(name) %> = <%= _.capitalize(name) %>.query();
          });
      };

      $scope.save = function (id) {
        if (id) {
          <%= _.capitalize(name) %>.update({id: id}, $scope.<%= name %>,
            function () {
              $scope.<%= pluralize(name) %> = <%= _.capitalize(name) %>.query();
              $scope.clear();
            });
        } else {
          <%= _.capitalize(name) %>.save($scope.<%= name %>,
            function () {
              $scope.<%= pluralize(name) %> = <%= _.capitalize(name) %>.query();
              $scope.clear();
            });
        }
      };

      $scope.clear = function () {
        $scope.<%= name %> = {
          <% _.each(attrs, function (attr) { %>"<%= attr.attrName.replace(" ", "_").toLowerCase() %>": "",
          <% }); %>
          "id": ""
        };
      };

      $scope.open = function (id) {
        var <%= name %>Save = $modal.open({
          templateUrl: 'views/<%= name %>/<%= name %>-modal.html',
          controller: <%= _.capitalize(name) %>SaveCtrl,
          resolve: {
            <%= name %>: function () {
              return $scope.<%= name %>;
            }
          }
        });

        <%= name %>Save.result.then(function (entity) {
          $scope.<%= name %> = entity;
          $scope.save(id);
        });
      };
    }]);

var <%= _.capitalize(name) %>SaveCtrl =
  function ($scope, $modalInstance, <%= name %>) {
    $scope.<%= name %> = <%= name %>;

    <% _.each(attrs, function (attr) { if (attr.attrType === 'Date') { %>
    $scope.<%= attr.attrName %>DateOptions = {
      dateFormat: 'yy-mm-dd',
      <% if (attr.dateConstraint === 'Past') { %>maxDate: -1<% } %>
      <% if (attr.dateConstraint === 'Future') { %>minDate: 1<% } %>
    };<% }}); %>

    $scope.submit = function () {
      $modalInstance.close($scope.<%= name %>);
    };

    $scope.dismiss = function () {
      $modalInstance.dismiss('dismiss');
    };
  };
