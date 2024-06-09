@extends('index')
@section('title', 'Locations')
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
                            <label for="statusFilter">Location Code</label>
                            <input type="text" id="code-filter" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-8 col-lg-9">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">LOCATIONS</h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    ng-click="setLocation(false)"></button>
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
                                        <th class="text-center">code</th>
                                        <th class="text-center">ISO code 2</th>
                                        <th class="text-center">ISO code 3</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="location in list track by $index">
                                        <td ng-bind="location.location_id"
                                            class="text-center small font-monospace text-uppercase"></td>
                                        <td class="text-center" ng-bind="location.location_name"></td>
                                        <td class="text-center" ng-bind="location.location_code"></td>
                                        <td class="text-center" ng-bind="location.location_iso_2"></td>
                                        <td class="text-center" ng-bind="location.location_iso_3"></td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-<%statusObject.color[location.location_visible]%> rounded-pill font-monospace p-2"><%statusObject.name[location.location_visible]%></span>

                                        </td>
                                        <td class="col-fit">
                                            <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                ng-click="setLocation($index)"></button>
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

        <div class="modal fade" id="locationForm" tabindex="-1" role="dialog" aria-labelledby="locationFormLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="POST" id="modalForm" action="/locations/submit">
                            @csrf
                            <input ng-if="updaetLocation !== false" type="hidden" name="_method" value="put">
                            <input type="hidden" name="location_id" id="location_id"
                                ng-value="list[updaetLocation].location_id">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="locationName">
                                            Location Name<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="name" required
                                            ng-value="list[updaetLocation].location_name" id="locationName" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="locationCode">
                                            Location Code<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="code" required
                                            ng-value="list[updaetLocation].location_code" id="locationCode" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="isoCode2">
                                            Location ISO code 2<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="iso_code_2" required
                                            ng-value="list[updaetLocation].location_iso_2" id="isoCode2" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="isoCode3">
                                            Location ISO code 3<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="iso_code_3" required
                                            ng-value="list[updaetLocation].location_iso_3" id="isoCode3" />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="active"
                                            value="1" ng-checked="+list[updaetLocation].location_visible"
                                            id="locationS">
                                        <label class="form-check-label" for="locationS">Location Status</label>
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
                    $('#locationForm form').on('submit', function(e) {
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
                                $('#locationForm').modal('hide');
                                scope.$apply(() => {
                                    if (scope.updaetLocation === false) {
                                        scope.list.unshift(response
                                            .data);
                                        scope.load();
                                        categoyreClsForm()
                                    } else {
                                        scope.list[scope
                                            .updaetLocation] = response.data;
                                    }
                                });
                            } else toastr.error("Error");
                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            // error msg
                        });
                    })
                })

                function categoyreClsForm() {
                    $('#location_id').val('');
                    $('#locationName').val('');
                    $('#locationCode').val('');
                    $('#isoCode2').val('');
                    $('#isoCode3').val('');
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

            $scope.submitting = false;
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.updaetLocation = false;
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
                    code: $('#code-filter').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.post("/locations/load", request, function(data) {
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        if (ln) {
                            $scope.noMore = ln < limit;
                            $scope.list = data;
                            console.log(data)
                            $scope.last_id = data[ln - 1].location_id;
                        }
                    });
                }, 'json');
            }

            $scope.setLocation = (indx) => {
                $scope.updaetLocation = indx;
                $('#locationForm').modal('show');
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
