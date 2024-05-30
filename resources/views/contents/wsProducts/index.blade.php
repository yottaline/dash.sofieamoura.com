@extends('index')
@section('title', 'WsProducts')

@section('search')
    <form id="nvSearch" role="search">
        <input type="search" name="q" class="form-control my-3 my-md-0 rounded-pill" placeholder="Search...">
    </form>
@endsection

@section('content')
    <div class="container-fluid" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-3">
                <div class="card card-box">
                    <div class="card-body">

                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">WS PRODUCTS</h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus"
                                    data-bs-toggle="modal" data-bs-target="#formModal"></button>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    ng-click="load(true)"></button>
                            </div>
                        </div>

                        <div ng-if="list.length" class="table-responsive">

                        </div>

                        @include('layouts.loader')
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="formModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="post" id="modalForm" action="/ws_products/submit">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="productCode">Code<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="reference" id="productCode" />
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="productName">Name<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="name" id="productName" />
                                    </div>
                                </div>


                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="season">Season<b class="text-danger">&ast;</b></label>
                                        <select name="season" id="season" class="form-select" required>
                                            <option value="default"></option>
                                            <option ng-repeat="season in seasons" ng-bind="season.season_name"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="category">Category<b class="text-danger">&ast;</b></label>
                                        <select name="category" id="category" class="form-select" required>
                                            <option value="default"></option>
                                            <option ng-repeat="category in categories" ng-bind="category.category_name">
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="" class="d-block mb-2">Order Type<b
                                                class="text-danger">&ast;</b></label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="order_type" id="orderType1"
                                                value="1">
                                            <label class="form-check-label" for="orderType1">IN-STOCK</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="order_type" id="orderType2"
                                                value="2" checked>
                                            <label class="form-check-label" for="orderType2">PRE-ORDER</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="me-auto">
                                    <button type="submit" class="btn btn-outline-primary"
                                        ng-disabled="submitting">Submit</button>
                                    <div class="spinner-border spinner-border-sm" role="status" ng-show="submitting">
                                        <span class="visually-hidden">Processing...</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"
                                    ng-disabled="submitting">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                $('#modalForm').on('submit', e => e.preventDefault()).validate({
                    submitHandler: function(form) {
                        var formData = new FormData(form),
                            action = $(form).attr('action'),
                            method = $(form).attr('method');

                        scope.$apply(() => scope.submitting = true);
                        $.ajax({
                            url: action,
                            type: method,
                            data: formData,
                            processData: false,
                            contentType: false,
                        }).done(function(data, textStatus, jqXHR) {
                            var response = JSON.parse(data);
                            scope.$apply(function() {
                                scope.submitting = false;
                                if (response.status) {
                                    toastr.success('Data processed successfully');
                                    scope.list.unshift(response.data);
                                    $('#formModal').modal('hide');
                                } else toastr.error(response.message);
                            });
                        }).fail((jqXHR, textStatus, errorThrown) => toastr.error("Request failed!"));
                    }
                });
            </script>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var scope,
            ngApp = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        ngApp.controller('ngCtrl', function($scope) {
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.submitting = false;
            $scope.list = [];
            $scope.offset = 0;

            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.seasons = <?= json_encode($seasons) ?>;
            $scope.categories = <?= json_encode($categories) ?>;
            $scope.load = function(reload = false) {
                if (reload) {
                    $scope.list = [];
                    $scope.offset = 0;
                    $scope.noMore = false;
                }

                if ($scope.noMore) return;
                $scope.loading = true;

                var request = {
                    q: $scope.q,
                    offset: $scope.offset,
                    limit: limit,
                    _token: '{{ csrf_token() }}'
                };

                $.post("/ws_products/load", request, function(data) {
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        if (ln) {
                            $scope.noMore = ln < limit;
                            $scope.list = data;
                            $scope.offset += ln;
                        }
                    });
                }, 'json');
            }

            scope = $scope;
            $scope.load();
        });

        $('#nvSearch').on('submit', function(e) {
            e.preventDefault();
            scope.$apply(() => scope.q = $(this).find('input').val());
            scope.load(true);
        });
    </script>
@endsection
