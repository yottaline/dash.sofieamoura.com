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
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">
                                <span class="text-warning" role="status"></span><span>FILTERS</span>
                            </h5>
                            <div>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-funnel"></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="statusFilter">Retailer status</label>
                            <select class="form-select" id="status-filter">
                                <option value="0">-- SELECT STATUS --</option>
                                <option value="1">Not Approved</option>
                                <option value="2">Approved</option>
                            </select>
                        </div>
                        <div>
                            <label for="statusFilter">Country Name</label>
                            <select class="form-select" id="country-filter">
                                <option value="0">-- SELECT NAME --</option>
                                <option ng-repeat="country in locations" ng-value="country.location_id"
                                    ng-bind="country.location_name"></option>
                            </select>
                        </div>

                        <div class="mt-3">
                            <label for="statusFilter">Currency Name</label>
                            <select class="form-select" id="currecy-filter">
                                <option value="0">-- SELECT NAME --</option>
                                <option ng-repeat="currecy in currencies" ng-value="currecy.currency_id"
                                    ng-bind="currecy.currency_name"></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-8 col-lg-9">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">RETAILERS</h5>
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

        {{-- start add and update model --}}
        <div class="modal fade" id="retailerForm" tabindex="-1" role="dialog" data-bs-backdrop="static"
            data-bs-keyboard="false" aria-labelledby="retailerFormLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="POST" id="modalForm" action="/retailers/submit">
                            @csrf
                            <input ng-if="updateRetailer !== false" type="hidden" name="_method" value="put">
                            <input type="hidden" name="retailer_id" id="retailer_id"
                                ng-value="list[updateRetailer].retailer_id">
                            <div class="row">

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="retailerName">
                                            Full Name <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="name" required
                                            ng-value="list[updateRetailer].retailer_fullName" id="retailerName" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="email">
                                            Email <b class="text-danger">&ast;</b></label>
                                        <input type="email" class="form-control" name="email" required
                                            ng-value="list[updateRetailer].retailer_email" id="email" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="password">
                                            Password <b class="text-danger">&ast;</b></label>
                                        <input type="password" class="form-control" name="password" id="password" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="phone">
                                            Phone <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="phone" required
                                            ng-value="list[updateRetailer].retailer_phone" id="phone" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="company">
                                            Company Name <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="company" required
                                            ng-value="list[updateRetailer].retailer_company" id="company" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="logo">
                                            Company Logo</label>
                                        <input type="file" class="form-control" name="logo" id="logo" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="website">
                                            Company Website </label>
                                        <input type="text" class="form-control" name="website"
                                            ng-value="list[updateRetailer].retailer_website" id="website" />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="desc">
                                            Company Description</label>
                                        <textarea class="form-control" name="desc" cols="30" rows="7" id="desc"></textarea>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="province">
                                            Province <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="province" required
                                            ng-value="list[updateRetailer].retailer_province" id="province" />
                                    </div>
                                </div>

                                {{-- <div class="col-6">
                                    <div class="mb-3">
                                        <label for="Payment">
                                            Advance Payment </label>
                                        <input type="text" class="form-control" name="payment"
                                            ng-value="list[updateRetailer].retailer_adv_payment" id="Payment" />
                                    </div>
                                </div> --}}

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="currency">
                                            Currency <b class="text-danger">&ast;</b></label>
                                        <select name="currency" id="currency" class="form-select" required>
                                            <option ng-if="+list[updateRetailer].retailer_currency"
                                                ng-value="list[updateRetailer].currency_id"
                                                ng-bind="list[updateRetailer].currency_name">
                                            <option value="default" ng-if="!+list[updateRetailer].retailer_currency">--
                                                SELECT YOUR CURRENCIES --</option>
                                            <option ng-repeat="currency in currencies" ng-value="currency.currency_id"
                                                ng-bind="currency.currency_name">
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="country">
                                            Country <b class="text-danger">&ast;</b></label>
                                        <select name="country" id="country" class="form-select" required>
                                            <option ng-if="+list[updateRetailer].retailer_country"
                                                ng-value="list[updateRetailer].location_id"
                                                ng-bind="list[updateRetailer].location_name">
                                            <option value="default" ng-if="!+list[updateRetailer].retailer_country">--
                                                SELECT YOUR COUNTRY --</option>
                                            <option ng-repeat="country in locations" ng-value="country.location_id"
                                                ng-bind="country.location_name"></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="city">
                                            City <b class="text-danger">&ast;</b></label></label>
                                        <input type="text" class="form-control" name="city"
                                            ng-value="list[updateRetailer].retailer_city" id="city" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="zipCode">
                                            city zip code</label>
                                        <input type="text" class="form-control" name="zip"
                                            ng-value="list[updateRetailer].address_zip" id="zipCode" />
                                    </div>
                                </div>
                                {{--
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="streetN">
                                            Street number or street name</label>
                                        <input type="text" class="form-control" name="line1"
                                            ng-value="list[updateRetailer].address_line1" id="streetN" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="Anumber">
                                            Apartment number </label>
                                        <input type="text" class="form-control" name="line2"
                                            ng-value="list[updateRetailer].address_line2" id="Anumber" />
                                    </div>
                                </div> --}}

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="address">
                                            Address</label>
                                        <input type="text" class="form-control" name="address"
                                            ng-value="list[updateRetailer].retailer_address" id="address" />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" name="status"
                                            value="1" ng-checked="+list[updateRetailer].retailer_blocked"
                                            id="retailerStatus">
                                        <label class="form-check-label" for="retailerStatus">Retailer Status</label>
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
                        $('#retailerForm form').on('submit', function(e) {
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
                                    $('#retailerForm').modal('hide');
                                    scope.$apply(() => {
                                        scope.submitting = false;
                                        if (scope.updateRetailer === false) {
                                            scope.list.unshift(response
                                                .data);
                                            scope.load();
                                            categoyreClsForm()
                                        } else {
                                            scope.list[scope
                                                .updateRetailer] = response.data;
                                        }
                                    });
                                } else toastr.error("Error");
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                // error msg
                            })
                        })
                    });

                    function categoyreClsForm() {
                        $('#retailer_id').val('');
                        $('#retailerName').val('');
                        $('#email').val('');
                        $('#password').val('');
                        $('#phone').val('');
                        $('#company').val('');
                        $('#logo').val('');
                        $('#website').val('');
                        $('#desc').val('');
                        $('#province').val('');
                        $('#Payment').val('');
                        $('#currency').val('');
                        $('#city').val('');
                        $('#shipAdd').val('');
                        $('#billAdd').val('');
                        $('#zipCode').val('');
                        $('#streetN').val('');
                        $('#streetN').val('');
                        $('#address').val('');
                    }
                </script>
            </div>
        </div>
        {{-- end add and update model --}}

        {{-- start approved model --}}
        {{-- edit approved --}}
        <div class="modal fade" id="editApproved" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="POST" action="/retailers/edit_approved">
                            @csrf
                            <input ng-if="updateRetailer !== false" type="hidden" name="_method" value="put">
                            <input type="hidden" name="id" ng-value="list[updateRetailer].retailer_id">
                            <div class="row">
                                <div class="col-12">
                                    <p class="mb-2">Are you sure you want to approved the retailer account ?</p>
                                </div>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-outline-secondary me-auto"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-outline-primary">Approved</button>
                                </div>
                        </form>
                    </div>
                </div>
                <script>
                    $(function() {
                        $('#editApproved form').on('submit', function(e) {
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
                                    toastr.success('Actived successfully');
                                    $('#editApproved').modal('hide');
                                    scope.$apply(() => {
                                        scope.submitting = false;
                                        if (scope.updateRetailer === false) {
                                            scope.list.unshift(response.data);
                                            // scope.load(true);
                                        } else {
                                            scope.list[scope.updateRetailer] = response.data;
                                        }
                                    });
                                } else toastr.error("Error");
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                toastr.error("error");
                                controls.log(jqXHR.responseJSON.message);
                                $('#editApproved').modal('hide');
                            });

                        })
                    });
                </script>
            </div>
        </div>

        {{-- end approved model --}}
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

            $scope.submitting = false;
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

                var request = {
                    q: $scope.q,
                    last_id: $scope.last_id,
                    limit: limit,
                    status: $('#status-filter').val(),
                    country: $('#country-filter').val(),
                    currecy: $('#currecy-filter').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.post("/retailers/load", request, function(data) {

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
