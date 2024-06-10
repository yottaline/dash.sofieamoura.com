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
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">
                                <span class="text-warning" role="status"></span><span>FILTERS</span>
                            </h5>
                            <div>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-funnel"></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="statusFilter">Seasons status</label>
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
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">SEASONS</h5>
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
                                                class="badge bg-<%currentObject.color[season.season_current]%> rounded-pill font-monospace p-2"><%currentObject.name[season.season_current]%></span>

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
        <div class="modal fade" id="seasonForm" tabindex="-1" role="dialog" data-bs-backdrop="static"
            data-bs-keyboard="false" aria-labelledby="seasonFormLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="POST" id="seasonF" action="/seasons/submit">
                            @csrf
                            <input ng-if="updateSeason !== false" type="hidden" name="_method" value="put">
                            <input type="hidden" name="id" id="season_id" ng-value="list[updateSeason].season_id">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="seasonName">
                                            Season Name <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="name"
                                            ng-value="list[updateSeason].season_name" id="seasonName" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="AdvancePayment">
                                            Season Advance Payment </label>
                                        <input type="text" class="form-control" name="adv_payment" max="100"
                                            ng-value="list[updateSeason].season_adv_payment" id="AdvancePayment" />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="description">
                                            Season Advance Description <b class="text-danger">&ast;</b></label>
                                        <textarea class="form-control" name="description" id="description" cols="30" rows="7"><%list[updateSeason].season_adv_context%></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="inStock">
                                            Season Delivery details for in-stock <b class="text-danger">&ast;</b></label>
                                        <textarea class="form-control" name="in_stock" id="inStock" cols="30" rows="5"><%list[updateSeason].season_delivery_1%></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="perOrder">
                                            Season Delivery details for pre-orders <b class="text-danger">&ast;</b></label>
                                        <textarea class="form-control" name="per_order" id="perOrder" cols="30" rows="5"><%list[updateSeason].season_delivery_2%></textarea>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label>Season Start<b class="text-danger">&ast;</b></label>
                                        <input id="seasonStart" type="text" class="form-control text-center"
                                            name="start" maxlength="10" ng-value="list[updateSeason].season_start" />
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label>Season End<b class="text-danger">&ast;</b></label>
                                        <input id="seasonEnd" type="text" class="form-control text-center"
                                            name="end" maxlength="10" ng-value="list[updateSeason].season_end" />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="Lookbook">
                                            Season Lookbook</label>
                                        <input type="file" class="form-control" name="Lookbook"
                                            ng-value="list[updateSeason].season_lookbook" id="Lookbook" />
                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" name="current"
                                            value="1" ng-checked="+list[updateSeason].season_current"
                                            id="seasonC">
                                        <label class="form-check-label" for="seasonC">Season Current</label>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" name="visible"
                                            value="1" ng-checked="+list[updateSeason].season_visible"
                                            id="seasonS">
                                        <label class="form-check-label" for="seasonS">Season Status</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer d-flex">
                            <button type="button" class="btn btn-outline-secondary me-auto"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" form="seasonF" class="btn btn-outline-primary"
                                ng-disabled="submitting">Submit</button>
                            <span class="spinner-border spinner-border-sm text-warning ms-2" role="status"
                                ng-if="submitting"></span>
                        </div>
                    </div>
                </div>

                <script>
                    $(function() {
                        $('#seasonF').on('submit', e => e.preventDefault()).validate({
                            rules: {
                                name: {
                                    required: true
                                },
                                adv_payment: {
                                    digits: true,
                                },
                                description: {
                                    required: true,
                                },
                                start: {
                                    required: true,
                                },
                                end: {
                                    required: true,
                                },
                                in_stock: {
                                    required: true,
                                },
                                per_order: {
                                    required: true,
                                }
                            },
                            submitHandler: function(form) {
                                console.log(form);
                                var formData = new FormData(form),
                                    action = $(form).attr('action'),
                                    method = $(form).attr('method');

                                $(form).find('button').prop('disabled', true);
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
                                        $('#seasonForm').modal('hide');
                                        scope.$apply(() => {
                                            scope.submitting = false;
                                            if (scope.updateSeason === false) {
                                                scope.list.unshift(response
                                                    .data);
                                                scope.load();
                                                categoyreClsForm()
                                            } else {
                                                scope.list[scope
                                                    .updateSeason] = response.data;
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

                    function categoyreClsForm() {
                        $('#season_id').val('');
                        $('#seasonName').val('');
                        $('#AdvancePayment').val('');
                        $('#description').val('');
                        $('#perOrder').val('');
                        $('#inStock').val('');
                        $('#seasonStart').val('');
                        $('#seasonEnd').val('');
                        $('#Lookbook').val('');
                    }

                    $("#seasonStart").datetimepicker($.extend({}, dtp_opt, {
                        showTodayButton: false,
                        format: "YYYY-MM-DD",
                    }));

                    $("#seasonEnd").datetimepicker($.extend({}, dtp_opt, {
                        showTodayButton: false,
                        format: "YYYY-MM-DD",
                    }));
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
            $scope.currentObject = {
                name: ['Not current', 'Current'],
                color: ['danger', 'success']
            };

            $scope.submitting = false;
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

                var request = {
                    q: $scope.q,
                    last_id: $scope.last_id,
                    limit: limit,
                    status: $('#status-filter').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.post("/seasons/load", request, function(data) {
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
