@extends('index')
@section('title', 'Categories')
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
                            <label for="statusFilter">Categories status</label>
                            <select class="form-select" id="status-filter">
                                <option value="0">-- SELECT STATUS --</option>
                                <option value="1">Un visible</option>
                                <option value="2">Visible</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">CATEGORIES</h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    ng-click="setCategory(false)"></button>
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
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Gender</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="category in list track by $index">
                                        <td ng-bind="category.category_id"
                                            class="text-center small font-monospace text-uppercase"></td>
                                        <td class="text-center" ng-bind="category.category_name"></td>
                                        <td class="text-center">
                                            <span
                                                class="rounded-pill font-monospace p-2"><%typeObject.name[category.category_type]%></span>

                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="rounded-pill font-monospace p-2"><%genderObject.name[category.category_gender]%></span>

                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%statusObject.color[category.category_visible]%> rounded-pill font-monospace p-2"><%statusObject.name[category.category_visible]%></span>

                                        </td>
                                        <td class="col-fit">
                                            <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                ng-click="setCategory($index)"></button>
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

        <div class="modal fade" id="categoryForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="modalForm" method="POST" action="/categories/submit">
                            @csrf
                            <input ng-if="updateCategory !== false" type="hidden" name="_method" value="put">
                            <input type="hidden" name="id" id="category_id"
                                ng-value="list[updateCategory].category_id">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="categoryName">
                                            Category Name <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="name" required
                                            ng-value="list[updateCategory].category_name" id="categoryName" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="type">
                                            Category Type <b class="text-danger">&ast;</b></label>
                                        <select name="type" id="type" class="form-select" required>
                                            <option value="default">--
                                                SELECT CATEGORY TYPE --</option>
                                            <option value="0">All</option>
                                            <option value="1">Babies</option>
                                            <option value="2">Kids</option>
                                            <option value="3">Teens</option>
                                            <option value="4">Adults</option>
                                            </option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="gender">
                                            Category Gender <b class="text-danger">&ast;</b></label>
                                        <select name="gender" id="gender" class="form-select" required>
                                            <option value="default">--
                                                SELECT CATEGORY GENDER --</option>
                                            <option value="2">Boy</option>
                                            <option value="1">Girl</option>
                                            <option value="0">Both</option>
                                            </option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" name="visible"
                                            value="1" ng-checked="+list[updateCategory].category_visible"
                                            id="categoryV" checked>
                                        <label class="form-check-label" for="categoryV">Visible</label>
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
            </div>
            <script>
                $(function() {
                    $('#categoryForm form').on('submit', function(e) {
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
                                $('#categoryForm').modal('hide');
                                scope.$apply(() => {
                                    scope.submitting = false;
                                    if (scope.updateCategory === false) {
                                        scope.list.unshift(response
                                            .data);
                                        scope.load();
                                        categoyreClsForm()
                                    } else {
                                        scope.list[scope
                                            .updateCategory] = response.data;
                                    }
                                });
                            } else toastr.error("Error");
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            // error msg
                        });
                    });
                });

                function categoyreClsForm() {
                    $('#category_id').val('');
                    $('#categoryName').val('');
                }
            </script>
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
            $scope.typeObject = {
                name: ['All', 'Babies', 'Kids', 'Teens', 'Adults'],
            };
            $scope.genderObject = {
                name: ['Both', 'Girl', 'Boy']
            };

            $scope.submitting = false;
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.updateCategory = false;
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

                $.post("/categories/load", request, function(data) {
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        if (ln) {
                            $scope.noMore = ln < limit;
                            $scope.list = data;
                            console.log(data)
                            $scope.last_id = data[ln - 1].category_id;
                        }
                    });
                }, 'json');
            }

            $scope.setCategory = (indx) => {
                $scope.updateCategory = indx;
                $('#categoryForm').modal('show');
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
