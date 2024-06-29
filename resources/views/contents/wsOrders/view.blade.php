@extends('index')
@section('title', 'View order')

@section('style')
    <style>
        .product-img {
            width: 90px;
            height: 90px;
            background-size: contain;
            background-position: center top;
            background-repeat: no-repeat;
            margin: 10px;
        }

        .qty-input {
            width: 50px;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid container" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card card-box mb-3" ng-repeat="(pk, p) in parsedProducts">
                    <div class="card-body d-sm-flex">
                        <div class="product-img rounded mb-2"
                            style="background-image: url({{ asset('media/product/') }}/<% p.info.product_id %>/<% p.info.media_file %>);">
                        </div>

                        <div class="flex-fill">
                            <div class="d-flex">
                                <h6 class="fw-semibold pt-1 text-uppercase small me-auto">
                                    <span class="me-1"><%p.info.product_name%></span>
                                    <span class="text-secondary">#<%p.info.product_code%></span>
                                </h6>
                                {{-- <a href="" class="link-danger h5 bi bi-x"
                                    ng-click="delProduct(p.info.product_id)"></a> --}}
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover sizes-table">
                                    <thead>
                                        <tr class="text-center small">
                                            <th class="text-start">Color</th>
                                            <th>Size</th>
                                            <th>WSP</th>
                                            <th>Req.Qty</th>
                                            <th ng-if="p.info.order_status > 2">Srv. Qty</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="small text-center" ng-repeat="(sk, s) in p.sizes">
                                            <td ng-bind="s.prodcolor_name" class="text-uppercase text-start"></td>
                                            <td width="70" ng-bind="s.size_name">
                                            <td width="70" class="font-monospace" ng-bind="s.ordprod_price"></td>
                                            <td class="col-fit">
                                                <input class="qty-input" ng-readonly="s.order_status > 2" type="text"
                                                    ng-model="s.ordprod_request_qty"
                                                    ng-blur="updateQty(s.ordprod_id, s.ordprod_request_qty, 0)">
                                            </td>
                                            <td class="col-fit" ng-if="s.order_status > 2">
                                                <input class="qty-input" type="text" ng-model="s.ordprod_served_qty"
                                                    ng-blur="updateQty(s.ordprod_id, s.ordprod_served_qty, 1)">
                                            </td>
                                            <td width="80"
                                                ng-bind="fn.toFixed(s.ordprod_request_qty * s.ordprod_price, 2)"
                                                class="text-center font-monospace"></td>
                                            <td class="col-fit">
                                                <a href="" class="link-danger bi bi-x"
                                                    ng-click="delSize(s.ordprod_id)"></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-center font-monospace small">
                                            <td colspan="3"></td>
                                            <td ng-bind="p.qty">qty</td>
                                            <td ng-if="s.order_status > 2">qty</td>
                                            <td ng-bind="fn.sepNumber(p.total)">total</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col order-lg-first">
                <div class="card card-box">
                    <div class="card-body product-block">
                        <h6 class="fw-semibold text-uppercase">
                            <span>ORDER</span> <span><%order.order_code%></span>
                        </h6>
                        <h6 class="small"><%order.season_name%></h6>
                        <span class="text-primary"><%statusObject.name[order.order_status]%></span>
                        <p class="text-secondary mb-0 small">created: <span class="font-monospace"
                                ng-bind="fn.slice(order.order_created, 0, 16)"></span></p>
                        <p class="text-secondary mb-0 small" ng-if="order.order_placed">placed: <span class="font-monospace"
                                ng-bind="fn.slice(order.order_placed, 0, 16)"></span></p>
                        <hr>
                        <h6 class="fw-semibold text-uppercase">Retailer info</h6>
                        <p class="mb-0 small font-monospace text-seconday"><% retailer.retailer_code %></p>
                        <p class="mb-0 small"><% retailer.retailer_fullName %></p>
                        <p class="mb-0 small"><% retailer.retailer_email %></p>
                        <hr>
                        <table class="table small">
                            <tbody>
                                <tr ng-if="+order.order_discount">
                                    <td class="col-fit">Subtotal</td>
                                    <td class="text-end font-monospace">
                                        <span ng-bind="order.currency_code"></span>
                                        <span ng-bind="fn.sepNumber(order.order_subtotal)"></span>
                                    </td>
                                </tr>
                                <tr ng-if="+order.order_discount">
                                    <td class="col-fit">Discount</td>
                                    <td class="text-end font-monospace">
                                        <span ng-bind="order.currency_code"></span>
                                        <span ng-bind="fn.sepNumber(order.order_discount)"></span>
                                    </td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="col-fit">Total</td>
                                    <td class="text-end font-monospace">
                                        <span ng-bind="order.currency_code"></span>
                                        <span ng-bind="fn.sepNumber(order.order_total)"></span>
                                    </td>
                                </tr>
                                <tr class="text-secondary">
                                    <td class="col-fit">Adv. Payment 30%</td>
                                    <td class="text-end font-monospace">
                                        <span ng-bind="order.currency_code"></span>
                                        <span ng-bind="fn.sepNumber(order.order_total * 0.3)"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h6 class="font-monospace small">Qty: <span ng-bind="orderQty"></span></h6>
                        <div class="mt-4">
                            <form action="/ws_orders/change_status" id="statusForm" method="post">
                                <input type="hidden" name="id" ng-value="order.order_id">
                                @csrf
                                <label for="orderStatus">Status</label>
                                <div class="input-group mb-3">
                                    <select id="orderStatus" name="status" class="form-select"
                                        ng-value="order.order_status">
                                        <option value="0">DRAFT</option>
                                        <option value="1">CANCELLED</option>
                                        <option value="2">PLACED</option>
                                        <option value="3">CONFIRMED</option>
                                        <option value="4">ADVANCE PAYMENT IS PENDING</option>
                                        <option value="5">BALANCE PAYMENT IS PENDING</option>
                                        <option value="6">SHIPPED</option>
                                    </select>
                                    <button type="submit" class="btn btn-outline-dark bi bi-arrow-right"></button>
                                </div>
                            </form>
                            <script>
                                $(function() {
                                    $('#statusForm').on('submit', e => e.preventDefault()).validate({
                                        submitHandler: function(form) {
                                            var formData = new FormData(form),
                                                action = $(form).attr('action'),
                                                method = $(form).attr('method');

                                            scope.$apply(() => scope.statusSubmit = true);
                                            $(form).find('button').prop('disabled', true);
                                            $.ajax({
                                                url: action,
                                                type: method,
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                            }).done(function(data, textStatus, jqXHR) {
                                                var response = JSON.parse(data);
                                                scope.$apply(() => {
                                                    scope.statusSubmit = false;
                                                    if (response.status) {
                                                        toastr.success('Status changed successfully');
                                                        scope.order = response.data;
                                                    } else toastr.error(response.message);
                                                });
                                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                                console.error("Error");
                                            });
                                        }
                                    });
                                });
                            </script>

                            <div class="d-flex align-items-center">
                                <a ng-if="order.order_status == 2" class="btn btn-outline-dark btn-sm" style="width: 95%"
                                    ng-click="Confirmation(order.order_id)" aria-describedby="basic-addon2">Get Order
                                    Confirmation </a>
                                <div class="loading-spinner-confirmation spinner-border ms-auto spinner-border-sm text-warning"
                                    role="status" aria-hidden="true">
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <a ng-if="fn.inArray(order.order_status, [3, 4])" class="btn btn-outline-primary btn-sm"
                                    style="width: 95%" ng-click="proforma(order.order_id)"
                                    aria-describedby="basic-addon2">Get Proforma
                                    Invoice</a>
                                <div class="loading-spinner-proforma spinner-border ms-auto spinner-border-sm text-warning"
                                    role="status" aria-hidden="true">
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <a ng-if="fn.inArray(order.order_status, [5, 6])"
                                    class="btn btn-outline-success btn-sm w-100" style="width: 95%"
                                    ng-click="invoice(order.order_id)" aria-describedby="basic-addon2">Get Invoice</a>
                                <div class="loading-spinner-invoice spinner-border ms-auto spinner-border-sm text-warning"
                                    role="status" aria-hidden="true">
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var scope,
            app = angular.module('ngApp', ['ngSanitize'], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('ngCtrl', function($scope) {
            $scope.fn = NgFunctions;
            $scope.statusObject = {
                name: [
                    'Draft', 'Canceled', 'Placed',
                    'Confirmed', 'Advance Payment Is Pending',
                    'Balance Payment Is Pending', 'Shipped'
                ],
            };
            $('.loading-spinner-confirmation').hide();
            $('.loading-spinner-proforma').hide();
            $('.loading-spinner-invoice').hide();
            $scope.statusSubmit = false;
            $scope.qtyUpdate = false;
            $scope.focusedQty = 0;
            $scope.orderDisc = 0;
            $scope.orderQty = 0;
            $scope.retailer = <?= json_encode($retailer) ?>;
            $scope.order = <?= json_encode($order) ?>;
            $scope.products = <?= json_encode($products) ?>;
            $scope.parsedProducts = {};
            $scope.parseProducts = function() {
                $scope.parsedProducts = {};
                $scope.orderQty = 0;
                $.map($scope.products, function(p) {
                    if (typeof $scope.parsedProducts[p.prodcolor_slug] == 'undefined')
                        $scope.parsedProducts[p.prodcolor_slug] = {
                            info: p,
                            sizes: [],
                            qty: 0,
                            total: 0
                        };
                    $scope.parsedProducts[p.prodcolor_slug].sizes.push(p);
                    $scope.parsedProducts[p.prodcolor_slug].qty += +p.ordprod_request_qty;
                    $scope.parsedProducts[p.prodcolor_slug].total += +p.ordprod_request_qty * +p
                        .ordprod_price;
                    $scope.orderQty += +p.ordprod_request_qty;
                });
            }

            $scope.delProduct = function(id) {
                if (!confirm('Are you sure to delete this product?')) return;
                $.post('/ws_orders/del_product', {
                    order: $scope.order.order_id,
                    product: id,
                    _token: '{{ csrf_token() }}',
                }, function(response) {
                    $scope.$apply(function() {
                        if (response.status) {
                            $scope.order = response.order;
                            $scope.products = response.products;
                            $scope.parseProducts();
                        } else {
                            toastr.error('Error deleting please reload the page');
                            console.log(response.message);
                        }
                    });
                }, 'json');
            };

            $scope.delSize = function(id) {
                if (!confirm('Are you sure to delete this item?')) return;
                $.post('/ws_orders/del_size', {
                    order: $scope.order.order_id,
                    size: id,
                    _token: '{{ csrf_token() }}',
                }, function(response) {
                    $scope.$apply(function() {
                        if (response.status) {
                            $scope.order = response.order;
                            $scope.products = response.products;
                            $scope.parseProducts();
                        } else {
                            toastr.error('Error deleting please reload the page');
                            console.log(response.message);
                        }
                    });
                }, 'json');
            };

            $scope.updateQty = function(id, qty, target) {
                $scope.qtyUpdate = true;
                $.post('/ws_orders/update_qty', {
                    order: $scope.order.order_id,
                    product: id,
                    qty: qty,
                    target: target,
                    _token: '{{ csrf_token() }}',
                }, function(response) {
                    $scope.$apply(function() {
                        $scope.qtyUpdate = false;
                        if (response.status) {
                            $scope.order = response.order;
                            $scope.products = response.products;
                            $scope.parseProducts();
                        } else {
                            toastr.error('Error updating qty please reload the page');
                            console.log(response.message);
                        }
                    });
                }, 'json');
            }

            $scope.Confirmation = function(id) {
                $('.loading-spinner-confirmation').show();
                $.get('/ws_orders/get_confirmed/' + id, function(response) {
                    $('.loading-spinner-confirmation').hide();
                    $scope.$apply(function() {
                        if (response) {
                            toastr.success('Get Order Confirmation successfully');
                        } else {
                            toastr.error('Error In Order Confirmation ');
                            console.log(response.message);
                        }
                    });
                }, 'json');
            }

            $scope.proforma = function(id) {
                $('.loading-spinner-proforma').show();
                $.get('/ws_orders/get_proforma/' + id, function(response) {
                    $('.loading-spinner-proforma').hide();
                    $scope.$apply(function() {
                        if (response) {
                            toastr.success('Get Order Porforma successfully');
                        } else {
                            toastr.error('Error In Order Porforma');
                            console.log(response.message);
                        }
                    });
                }, 'json');
            }

            $scope.invoice = function(id) {
                $('.loading-spinner-invoice').show();
                $.get('/ws_orders/invoice/' + id, function(response) {
                    $('.loading-spinner-invoice').hide();
                    $scope.$apply(function() {
                        if (response) {
                            toastr.success('Get invoice successfully');
                        } else {
                            toastr.error('Error In invoice ');
                            console.log(response.message);
                        }
                    });
                }, 'json');
            }

            $scope.parseProducts();
            scope = $scope;
        });
    </script>
@endsection
