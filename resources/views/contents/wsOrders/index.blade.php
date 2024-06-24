@extends('index')
@section('title', 'Orders')
@section('search')
    <form id="nvSearch" role="search">
        <input type="search" name="q" class="form-control my-3 my-md-0 rounded-pill" placeholder="Search...">
    </form>
@endsection
@section('style')
    <style>
        .table>:not(:first-child) {
            border-top: 1px solid #ccc !important;
        }

        tbody input,
        tfoot input {
            padding: 5px;
            border: 1px dashed #ccc !important;
            outline: none !important;
        }

        #inv-item-input {
            padding-left: 35px
        }

        #items-selector {
            position: absolute;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid #ccc;
            border-top: 0;
            box-shadow: 2px 2px 2px #eee;
        }

        #items-selector>.items-list>a {
            border-right: 5px solid transparent;
            text-decoration: none;
            display: block;
            color: #2d2d2d;
            padding: 5px 10px;
        }

        #items-selector>.items-list>a:focus {
            border-right-color: #2d2d2d;
            background-color: #f8f8f8;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid" data-ng-app="myApp" data-ng-controller="myCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-3">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="roleFilter">Retailer Name</label>
                            <input type="text" class="form-control" id="filter-name">
                        </div>

                        <div class="mb-3">
                            <label for="roleFilter">Order Date</label>
                            <input type="text" id="filterOrderDate" class="form-control text-center text-monospace">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-8 col-lg-9">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto  text-uppercase">
                                <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                    role="status"></span><span>ORDERS</span>
                            </h5>
                            {{-- @csrf --}}
                            <div>
                                <a href="/ws_orders/create" class="btn btn-outline-primary btn-circle bi bi-plus-lg"></a>
                                <button type="button" id="exportData"
                                    class="btn btn-outline-success btn-circle bi bi-filetype-xlsx"></button>
                                @csrf
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    data-ng-click="load(true)"></button>
                            </div>
                        </div>
                        <div data-ng-if="list.length" class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Season</th>
                                        <th class="text-center">Retailer</th>
                                        <th class="text-center">Placed</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-ng-repeat="order in list" ng-dblclick="view(order)">
                                        <td class="text-center">
                                            <input class="form-check-input order-checkbox" type="checkbox"
                                                ng-value="order.order_id">
                                        </td>
                                        <td data-ng-bind="order.order_code"
                                            class="text-center small font-monospace text-uppercase">
                                        </td>
                                        <td class="text-center" data-ng-bind="order.season_name">
                                        <td class="text-center" data-ng-bind="order.retailer_fullName">
                                        </td>
                                        <td class="text-center">
                                            <span ng-if="!order.order_placed"> --</span>
                                            <span ng-if="order.order_placed" data-ng-bind="order.order_placed"></span>
                                        </td>
                                        <td data-ng-bind="order.order_subtotal" class="text-center font-monospace"></td>
                                        <td class="text-center">
                                            <span class="rounded-pill"><%statusObject.name[order.order_status]%></span>
                                        </td>
                                        <td class="col-fit">
                                            <a href="/ws_orders/view/<%order.order_id%>" target="_blank"
                                                class="btn btn-outline-dark btn-circle bi bi-eye"></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        @include('layouts.loader')
                    </div>
                    <script>
                        $('#exportData').on('click', function() {

                            var orderid = $('.order-checkbox:checked').map((i, e) => $(e).val()).get();
                            console.log(orderid);
                            window.open('/ws_orders/export?' + $.param({
                                orderid: orderid
                            }));
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var scope,
            app = angular.module('myApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('myCtrl', function($scope) {
            $scope.statusObject = {
                name: ['Draft', 'Canceled', 'Placed',
                    'Confirmed', 'Advance Payment Is Pending',
                    'Balance Payment Is Pending', 'Shipped'
                ],
            };
            $('.loading-spinner').hide();
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.updateOrders = false;
            $scope.list = [];
            $scope.products = [];
            $scope.orderDisc = 0;
            $scope.orderDetails = [];
            $scope.orDe = [];
            $scope.last_id = 0;
            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.retailers = <?= json_encode($retailers) ?>;
            $scope.seasons = <?= json_encode($seasons) ?>;
            $scope.currencies = <?= json_encode($currencies) ?>;
            $scope.locations = <?= json_encode($locations) ?>;
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
                    date: $('#filterOrderDate').val(),
                    r_name: $('#filter-name').val(),
                    q: $scope.q,
                    last_id: $scope.last_id,
                    limit: limit,
                    _token: '{{ csrf_token() }}'
                };

                $.post("/ws_orders/load", request, function(data) {
                    $('.loading-spinner').hide();
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        $scope.noMore = ln < limit;
                        if (ln) {
                            $scope.list.push(...data);
                            $scope.last_id = data[ln - 1].order_id;
                        }
                    });
                }, 'json');
            }
            $scope.setOrder = (indx) => {
                $scope.updateOrders = indx;
                $('#orderModal').modal('show');
            };
            $scope.opt = function(indx, status) {
                Swal.fire({
                    text: "Do you want to change your student status?",
                    icon: "info",
                    showCancelButton: true,
                }).then((result) => {
                    if (!result.isConfirmed) return;
                    $.post('/ws_orders/change_status', {
                        id: $scope.list[indx].order_id,
                        status: status,
                        _token: "{{ csrf_token() }}",
                    }, function(response) {
                        if (response.status) {
                            toastr.success(
                                'The status of the request has been changed successfully');
                            $('#set_deliverd').modal('hide');
                            scope.$apply(() => {
                                if (scope.updateOrders === false) {
                                    scope.list.unshift(response.data);
                                    scope.load(true);
                                } else {
                                    scope.list[scope.updateOrders] = response.data;
                                }
                            });
                        } else toastr.error(response.message);
                    }, 'json');
                });
            }

            $scope.view = function(order) {
                window.open('/ws_orders/view/' + order.order_id);
            }

            // $scope.viewDetails = (order) => {
            //     $.get("/orders/view/" + order.order_id, function(data) {
            //         $('.perm').show();
            //         scope.$apply(() => {

            //             scope.orderDetails = data.items;
            //             scope.orDe = data.order;
            //             console.log(data)
            //             $('#edit_disc').modal('show');
            //         });
            //     }, 'json');
            // }

            $scope.delProduct = (index) => $scope.products.splice(index, 1);

            $scope.clTotal = function() {
                var total = 0;
                $scope.products.map(p => total += p.prodcolor_mincolorqty * p.prodsize_wsp);
                var totals = total - (total * $scope.orderDisc / 100);
                return totals.toFixed();
            }

            $scope.to = function(prodcolor_price, pecAmount) {
                return (pecAmount * prodcolor_price).toFixed();
            };

            $scope.load();
            scope = $scope;
        });

        $(function() {
            $('#nvSearch').on('submit', function(e) {
                e.preventDefault();
                scope.$apply(() => scope.q = $(this).find('input').val());
                scope.load(true);
            });

            $('#productItem').on('change', function() {
                var val = $(this).val();
                console.log(val)
                var request = {
                    product: val
                };
                $.get("/products/get_product/", request, function(data) {
                    // $('.perm').show();
                    scope.$apply(() => {
                        scope.products = data;
                        // console.log(data)
                    });
                }, 'json');
            });
        });
    </script>
@endsection
