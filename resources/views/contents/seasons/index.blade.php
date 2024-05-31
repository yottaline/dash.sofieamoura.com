@extends('index')
@section('title', 'Seasons')
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
                                    role="status"></span><span>SEASONS</span>
                            </h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    ng-click="setSeason(false)"></button>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    ng-click="load(true)"></button>
                            </div>
                        </div>

                        <div ng-if="list.length" class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Advance Payment </th>
                                        <th class="text-center">Start</th>
                                        <th class="text-center">End</th>
                                        <th class="text-center">Current </th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="season in list track by $index">
                                        <td ng-bind="season.season_code"
                                            class="text-center small font-monospace text-uppercase"></td>
                                        <td class="text-center" ng-bind="season.season_name"></td>
                                        <td class="text-center" ng-bind="season.season_adv_payment"></td>
                                        <td class="text-center" ng-bind="season.season_start"></td>
                                        <td class="text-center" ng-bind="season.season_end"></td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%currentObject.color[season.season_visible]%> rounded-pill font-monospace p-2"><%currentObject.name[season.season_visible]%></span>

                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%statusObject.color[season.season_visible]%> rounded-pill font-monospace p-2"><%statusObject.name[season.season_visible]%></span>

                                        </td>
                                        <td class="col-fit">
                                            <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                ng-click="setSeason($index)"></button>
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

        @include('contents.components.modal.seasons')
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
                name: ['Un visible', 'Visible'],
                color: ['danger', 'success']
            };
            $scope.currentObject = {
                name: ['Not current', 'Current'],
                color: ['danger', 'success']
            };
            $('.loading-spinner').hide();
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.updateSeason = false;
            $scope.list = [];
            $scope.last_id = 0;

            $scope.jsonParse = (str) => JSON.parse(str);
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

                $.post("/seasons/load", request, function(data) {
                    $('.loading-spinner').hide();
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        if (ln) {
                            $scope.noMore = ln < limit;
                            $scope.list = data;
                            console.log(data)
                            $scope.last_id = data[ln - 1].season_id;
                        }
                    });
                }, 'json');
            }

            $scope.setSeason = (indx) => {
                $scope.updateSeason = indx;
                $('#seasonForm').modal('show');
            };
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
