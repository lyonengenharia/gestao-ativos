angular.module('termo', ['ngMessages']);
angular.module('termo').controller('listaTelefonica', function ($scope, $http,$compile) {
    $scope.error = false;
    $scope.errorMsg = "";
    $scope.Msg = "";
    //$scope.teste = "Olá mundo";
    $scope.editar = function (termo) {
        $scope.exibirEditar = true;
        $scope.exibirNovo = false;
        $scope.existingTermo = angular.copy(termo);
    }
    $scope.editarSalvar = function (termo) {

        $http.post('termo/update', termo).then(function successCallback(response) {
            console.log(response);
            if (response.data.error) {
                $scope.errorMsg = "";
                $scope.error = true;
                $.each(response.data.data, function (item, index) {
                    $scope.errorMsg += response.data.data[item];
                });

            }else{
                $scope.error = false;
                angular.element(
                    swal({
                        title: response.data.msg,
                        text: "Essa página será recarreganda em 3 segundos.",
                        timer: 3000
                    }).then(
                        function () {},
                        function (dismiss) {
                            if (dismiss === 'timer') {
                                window.location.reload();
                            }
                        }
                    )

                );
            }

        });
    }
    $scope.cancelarEdit = function () {
        $scope.exibirEditar = false;
    }
    $scope.novoContato = function () {
        $scope.exibirEditar = false;
        $scope.exibirNovo = !$scope.exibirNovo;
        $scope.error = false;
    }
    $scope.novoSalvar = function (termo) {
        $http.post('termo/novo', termo).then(function successCallback(response) {
            if (response.data.error) {
                $scope.errorMsg = "";
                $scope.error = true;
                $.each(response.data.data, function (item, index) {
                    $scope.errorMsg += response.data.data[item];
                });

            }else{
                $scope.error = false;
                angular.element(
                    swal({
                        title: response.data.msg,
                        text: "Essa página será recarreganda em 3 segundos.",
                        timer: 3000
                    }).then(
                        function () {},
                        // handling the promise rejection
                        function (dismiss) {
                            if (dismiss === 'timer') {
                                window.location.reload();
                            }
                        }
                    )

                );
            }

        });
    }
    $scope.cancelarNovo = function () {
        $scope.exibirNovo = !$scope.exibirNovo;
        $scope.error = false;
    }
    $scope.delete = function (termo) {
        angular.element(swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            deleteRemote(termo);
        },function (dismiss) {
                if (dismiss === 'cancel') {
                }
            }

        ));
        deleteRemote = function (termo) {
            $http({
                method: 'delete',
                url: 'termo/delete/'+termo.id,
                data:termo
            }).then(function successCallback(response) {
                console.log("***************Response**************");
                console.log(response);
                if(response.data.error==0){
                    angular.element(
                        swal(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    );
                }
                // this callback will be called asynchronously
                // when the response is available
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
        }

    }
})
