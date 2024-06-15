@extends('index')
@section('title', 'Sizes')
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
                            <label for="statusFilter">Sizes status</label>
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
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">SIZES</h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    ng-click="setSize(false)"></button>
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
                                        <th class="text-center">Order</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="size in list track by $index">
                                        <td ng-bind="size.size_id" class="text-center small font-monospace text-uppercase">
                                        </td>
                                        <td class="text-center" ng-bind="size.size_name"></td>
                                        <td class="text-center" ng-bind="size.size_order"></td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%statusObject.color[size.size_visible]%> rounded-pill font-monospace p-2"><%statusObject.name[size.size_visible]%></span>

                                        </td>
                                        <td class="col-fit">
                                            <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                ng-click="setSize($index)"></button>
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
        <div class="modal fade" id="sizeForm" tabindex="-1" role="dialog" data-bs-backdrop="static"
            data-bs-keyboard="false" aria-labelledby="sizeFormLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="POST" id="sizeF" action="/sizes/submit">
                            @csrf
                            <input ng-if="updateSize !== false" type="hidden" name="_method" value="put">
                            <input type="hidden" name="id" id="size_id" ng-value="list[updateSize].size_id">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="sizeName">
                                            Size Name <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="name"
                                            ng-value="list[updateSize].size_name" id="sizeName" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="sizeOrder">
                                            Size Order </label>
                                        <input type="text" class="form-control" name="order" max="100"
                                            ng-value="list[updateSize].size_order" id="sizeOrder" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" name="visible"
                                            value="1" ng-checked="+list[updateSize].size_visible" id="sizeS">
                                        <label class="form-check-label" for="sizeS">Size Status</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer d-flex">
                            <button type="button" class="btn btn-outline-secondary me-auto"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="sizeF" class="btn btn-outline-primary"
                                ng-disabled="submitting">Submit</button>
                            <span class="spinner-border spinner-border-sm text-warning ms-2" role="status"
                                ng-if="submitting"></span>
                        </div>
                    </div>
                </div>
                <script>
                    $(function() {
                        $('#sizeF').on('submit', e => e.preventDefault()).validate({
                            rules: {
                                order: {
                                    digits: true,
                                    required: true
                                }
                            },
                            submitHandler: function(form) {
                                console.log(form);
                                var formData = new FormData(form),
                                    action = $(form).attr('action'),
                                    method = $(form).attr('method');

                                scope.$apply(() => scope.submitting = true);
                                $(form).find('button').prop('disabled', true);
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
                                        $('#sizeForm').modal('hide');
                                        scope.$apply(() => {
                                            scope.submitting = false;
                                            if (scope.updateSize === false) {
                                                scope.list.unshift(response
                                                    .data);
                                                sizeClsForm()
                                            } else {
                                                scope.list[scope
                                                    .updateSize] = response.data;
                                            }
                                        });
                                    } else toastr.error(response.message);
                                }).fail(function(jqXHR, textStatus, errorThrown) {
                                    toastr.error("error");
                                }).always(function() {
                                    $(form).find('button').prop('disabled', false);
                                });
                            }
                        });
                    });

                    function sizeClsForm() {
                        $('#size_id').val('');
                        $('#sizeName').val('');
                        $('#sizeOrder').val('');
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
            $scope.updateSize = false;
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

                $.post("/sizes/load", request, function(data) {

                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        if (ln) {
                            $scope.noMore = ln < limit;
                            $scope.list = data;
                            console.log(data)
                            $scope.last_id = data[ln - 1].size_id;
                        }
                    });
                }, 'json');
            }

            $scope.setSize = (indx) => {
                $scope.updateSize = indx;
                $('#sizeForm').modal('show');
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
