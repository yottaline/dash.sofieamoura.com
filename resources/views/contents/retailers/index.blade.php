@extends('index')
@section('title', 'Reatilers')
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

            <div class="col-12 col-sm-8 col-lg-9">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">
                                <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                    role="status"></span><span>RETAILERS</span>
                            </h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    ng-click="setRetailer(false)"></button>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    ng-click="load(true)"></button>
                            </div>
                        </div>

                        <div ng-if="list.length" class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Full Name</th>
                                        <th class="text-center">Company Name</th>
                                        <th class="text-center">Country</th>
                                        <th class="text-center">Address</th>
                                        <th class="text-center">Province</th>
                                        <th class="text-center">Advance Payment</th>
                                        <th class="text-center">Currency</th>
                                        <th class="text-center">Apperoved Date</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="retailer in list track by $index">
                                        <td ng-bind="retailer.retailer_code"
                                            class="text-center small font-monospace text-uppercase"></td>
                                        <td>
                                            <span ng-bind="retailer.retailer_fullName" class="fw-bold"></span><br>
                                            <small ng-if="+retailer.retailer_phone"
                                                class="me-1 db-inline-block dir-ltr font-monospace badge bg-primary">
                                                <i class="bi bi-phone me-1"></i>
                                                <span ng-bind="retailer.retailer_phone" class="fw-normal"></span>
                                            </small>
                                            <small ng-if="retailer.retailer_email"
                                                class="db-inline-block dir-ltr font-monospace badge bg-primary">
                                                <i class="bi bi-envelope-at me-1"></i>
                                                <span ng-bind="retailer.retailer_email" class="fw-normal"></span>
                                            </small>
                                        </td>
                                        <td class="text-center" ng-bind="retailer.retailer_company"></td>
                                        <td class="text-center" ng-bind="retailer.location_name"></td>
                                        <td class="text-center" ng-bind="retailer.retailer_address"></td>
                                        <td class="text-center" ng-bind="retailer.retailer_province"></td>
                                        <td class="text-center" ng-bind="retailer.retailer_adv_payment"></td>
                                        <td class="text-center" ng-bind="retailer.currency_name"></td>
                                        <td class="text-center">
                                            <span ng-if="retailer.retailer_approved == null">Not Approved</span>
                                            <span ng-if="retailer.retailer_approved != null"
                                                ng-bind="retailer.retailer_approved"></span>
                                        </td>

                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%statusObject.color[retailer.retailer_blocked]%> rounded-pill font-monospace p-2"><%statusObject.name[retailer.retailer_blocked]%></span>

                                        </td>
                                        <td class="col-fit">

                                            <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                ng-click="setRetailer($index)"></button>
                                            <button ng-if="retailer.retailer_approved == null"
                                                class="btn btn-outline-dark btn-circle bi bi-shield-fill-check"
                                                ng-click="editApproved($index)"></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @include('layouts.loader')

                    </div>
                </div>
            </div>
        </div>

        @include('contents.components.modal.retailers')
    </div>
@endsection

@section('js')
    <script>
        var scope,
            app = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('ngCtrl', function($scope) {
            $scope.statusObject = {
                name: ['Blacked', 'Available'],
                color: ['danger', 'success']
            };
            $('.loading-spinner').hide();
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.updateRetailer = false;
            $scope.list = [];
            $scope.last_id = 0;

            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.locations = <?= json_encode($locations) ?>;
            $scope.currencies = <?= json_encode($currencies) ?>;
            $scope.load = function(reload = false) {
                if (reload) {
                    $scope.list = [];
                    $scope.last_id = 0;
                    $scope.noMore = false;
                }

                if ($scope.noMore) return;
                $scope.loading = true;

                $('.loading-spinner').show();
                var request = {
                    q: $scope.q,
                    last_id: $scope.last_id,
                    limit: limit,
                    _token: '{{ csrf_token() }}'
                };

                $.post("/retailers/load", request, function(data) {
                    $('.loading-spinner').hide();
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        if (ln) {
                            $scope.noMore = ln < limit;
                            $scope.list = data;
                            console.log(data)
                            $scope.last_id = data[ln - 1].retailer_id;
                        }
                    });
                }, 'json');
            }

            $scope.setRetailer = (indx) => {
                $scope.updateRetailer = indx;
                $('#retailerForm').modal('show');
            };

            $scope.editApproved = (index) => {
                $scope.updateRetailer = index;
                $('#editApproved').modal('show');
            }
            $scope.load();
            scope = $scope;
        });

        $('#nvSearch').on('submit', function(e) {
            e.preventDefault();
            scope.$apply(() => scope.q = $(this).find('input').val());
            scope.load(true);
        });
    </script>
@endsection
