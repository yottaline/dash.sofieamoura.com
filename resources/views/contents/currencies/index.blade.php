@extends('index')
@section('title', 'Currencies')
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
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">
                                <span class="text-warning" role="status"></span><span>FILTERS</span>
                            </h5>
                            <div>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-funnel"></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="statusFilter">Currencies status</label>
                            <select class="form-select" id="status-filter">
                                <option value="0">-- SELECT STATUS --</option>
                                <option value="1">Un visible</option>
                                <option value="2">Visible</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-8 col-lg-9">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">CURRENCIES</h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    ng-click="setCurrency(false)"></button>
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
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="currency in list track by $index">
                                        <td ng-bind="currency.currency_id"
                                            class="text-center small font-monospace text-uppercase"></td>
                                        <td class="text-center" ng-bind="currency.currency_name"></td>
                                        <td class="text-center" ng-bind="currency.currency_code"></td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%statusObject.color[currency.currency_visible]%> rounded-pill font-monospace p-2"><%statusObject.name[currency.currency_visible]%></span>

                                        </td>
                                        <td class="col-fit">
                                            <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                ng-click="setCurrency($index)"></button>
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
        <div class="modal fade" id="currencyForm" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog" aria-labelledby="currencyFormLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="POST" id="modalForm" action="/currencies/submit">
                            @csrf
                            <input ng-if="updateCurrency !== false" type="hidden" name="_method" value="put">
                            <input type="hidden" name="currency_id" id="currency_id"
                                ng-value="list[updateCurrency].currency_id">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="currencyName">
                                            Currency Name</label>
                                        <input type="text" class="form-control" name="name" required
                                            ng-value="list[updateCurrency].currency_name" id="currencyName" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="currencyCode">
                                            Currency Code<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="code" required
                                            ng-value="list[updateCurrency].currency_code" id="currencyCode" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="currencysymbol">
                                            Currency symbol <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="symbol" required
                                            ng-value="list[updateCurrency].currency_symbol" id="currencysymbol" />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="active"
                                            value="1" ng-checked="+list[updateCurrency].currency_visible"
                                            id="currencyS">
                                        <label class="form-check-label" for="currencyS">Currency Status</label>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="modal-footer d-flex">
                            <button type="button" class="btn btn-outline-secondary me-auto"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="modalForm" class="btn btn-outline-primary"
                                ng-disabled="submitting">Submit</button>
                            <span class="spinner-border spinner-border-sm text-warning ms-2" role="status"
                                ng-if="submitting"></span>
                        </div>
                    </div>
                </div>
                <script>
                    $(function() {
                        $('#currencyForm form').on('submit', function(e) {
                            e.preventDefault();
                            var form = $(this),
                                formData = new FormData(this),
                                action = form.attr('action'),
                                method = form.attr('method');
                            scope.$apply(() => scope.submitting = true);

                            $.ajax({
                                url: action,
                                type: method,
                                data: formData,
                                processData: false,
                                contentType: false,
                            }).done(function(data, textStatus, jqXHR) {
                                var response = JSON.parse(data);
                                if (response.status) {
                                    toastr.success('Data processed successfully');
                                    $('#currencyForm').modal('hide');
                                    scope.$apply(() => {
                                        scope.submitting = false;
                                        if (scope.updateCurrency === false) {
                                            scope.list.unshift(response
                                                .data);
                                            scope.load();
                                            categoyreClsForm()
                                        } else {
                                            scope.list[scope
                                                .updateCurrency] = response.data;
                                        }
                                    });
                                } else toastr.error("Error");
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                // error msg
                            });
                        })
                    });

                    function categoyreClsForm() {
                        $('#currency_id').val('');
                        $('#currencyName').val('');
                        $('#currencyCode').val('');
                        $('#currencysymbol').val('');
                        $('#isoCode3').val('');
                    }
                </script>
            </div>
        </div>

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

            $scope.submitting = false;
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.updateCurrency = false;
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
                var request = {
                    q: $scope.q,
                    last_id: $scope.last_id,
                    limit: limit,
                    status: $('#status-filter').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.post("/currencies/load", request, function(data) {

                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        if (ln) {
                            $scope.noMore = ln < limit;
                            $scope.list = data;
                            console.log(data)
                            $scope.last_id = data[ln - 1].currency_id;
                            // scope.submitting = true;
                        }
                    });
                }, 'json');
            }

            $scope.setCurrency = (indx) => {
                $scope.updateCurrency = indx;
                $('#currencyForm').modal('show');
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
