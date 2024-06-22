@extends('index')
@section('title', 'View order')

@section('style')
    <style>
        .product-img {
            width: 70px;
            height: 120px;
            background-size: contain;
            background-position: center top;
            background-repeat: no-repeat;
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
            <div class="col-12 col-lg-8 order-lg-last">
                <div class="card card-box mb-3" ng-repeat="p in parsedProducts">
                    <div class="card-body d-sm-flex">
                        <div class="product-img rounded mb-2"
                            style="background-image: url({{ asset('media/product/') }}/<% p.info.product_id %>/<% p.info.media_file %>);">
                        </div>
                        <div class="flex-fill">
                            <h6 class="card-title fw-semibold pt-1 text-uppercase small">
                                <span class="text-warning me-2" role="status"></span><span><%p.info.product_name%>
                                    #<%p.info.product_ref%></span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-hover sizes-table" id="example">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Color</th>
                                            <th>Size</th>
                                            <th>WSP</th>
                                            <th>Qty</th>
                                            <th ng-if="p.info.order_status > 2">Qty</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center" ng-repeat="s as p.sizes track by $index">
                                            <td ng-bind="s.prodcolor_name" class="small text-uppercase"></td>
                                            <td ng-bind="s.size_name">
                                            <td class="font-monospace" ng-bind="s.prodsize_wsp"></td>
                                            <td>
                                                <input class="qty-input" ng-readonly="s.order_status > 2" type="number"
                                                    ng-model="s.ordprod_request_qty">
                                            </td>
                                            <td ng-if="s.order_status > 2">
                                                <input class="qty-input" type="number" ng-model="s.ordprod_request_qty">
                                            </td>
                                            <td ng-bind="fn.toFixed(o.ordprod_request_qty * o.prodsize_wsp, 2)"
                                                class="text-center font-monospace"></td>
                                            <td class="col-fit">
                                                <a class="link-danger bi bi-x" ng-click="delProduct($index)"></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td ng-bind="p.qty">qty</td>
                                            <td ng-if="s.order_status > 2">qty</td>
                                            <td ng-bind="p.total">total</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card card-box">
                    <div class="card-body product-block">
                        <h4 class="card-title fw-semibold pt-1 me-auto text-uppercase">
                            <span>ORDER #<%order.order_code%></span><br>
                        </h4>
                        <h6>
                            <span>Season: <%order.season_name%></span>
                        </h6>
                        <hr>
                        <span class="fw-semibold">Retailer: <%retailer.retailer_fullName%></span><br>
                        <span><%statusObject.name[order.order_status]%></span>
                        <p class="font-secondary">created: <span ng-bind="order.order_created"></span></p>
                        <hr>
                        <div class="subtotal">
                            <p>Subtotal: <span ng-bind="calculateSubtotal()"></span></p>
                            <hr>
                            <p class="text-danger fw-semibold">Discount: <input type="number" value="4"
                                    class="text-center" style="width:70px;text-align:center" ng-model="order.order_discount"
                                    ng-change="calculateSubtotal()">
                            </p>
                            <hr>
                            <p class="fw-bold">Total: <span ng-bind="calculateSubtotal()"></span></p>
                        </div>
                        <hr>
                        <div class="delivery">
                            <form action="/ws_orders/change_status" id="statusForm" method="post">
                                <input type="hidden" name="id" ng-value="order.order_id">
                                @csrf
                                <span>status</span>
                                <div class="input-group mb-3">
                                    <select name="status" class="form-select">
                                        <option value="0">DRAFT</option>
                                        <option value="1">CANCELLED</option>
                                        <option value="2">PLACED</option>
                                        <option value="3">CONFIRMED</option>
                                        <option value="4">ADVANCE PAYMENT IS PENDING</option>
                                        <option value="5">BALANCE PAYMENT IS PENDING</option>
                                        <option value="6">SHIPPED</option>
                                    </select>
                                    <button type="submit" class="btn btn-outline-dark bi bi-arrow-right" type="button"
                                        id="button-addon2"></button>
                                </div>
                            </form>
                            <script>
                                $(function() {
                                    $('#statusForm').on('submit', e => e.preventDefault()).validate({
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
                                                    toastr.success('status change successfully');
                                                    scope.$apply(() => {
                                                        scope.order = response
                                                            .data;
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
                            </script>
                            <a ng-if="order.order_status == 2" class="btn btn-outline-dark mt-4 w-100"
                                href="/ws_orders/get_confirmed/<%order.order_id%>">Get
                                Order
                                Confirmed</a>
                            <hr>
                            <a ng-if="order.order_status >= 3" class="btn btn-outline-dark mt-4 w-100"
                                href="/ws_orders/get_proforma/<%order.order_id%>">Get Proforma
                                Invoice</a>

                            <a class="btn btn-outline-dark mt-4 w-100" href="/ws_orders/invoice/<%order.order_id%>">Get
                                Invoice</a>
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
            app = angular.module('ngApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('ngCtrl', function($scope) {
            $scope.fn = NgFunctions;
            $scope.statusObject = {
                name: [
                    'Draft', 'Cancelled', 'Placed',
                    'Confirmed', 'Advance Payment Is Pending',
                    'Balance Payment Is Pending', 'Shipped'
                ],
            };
            $scope.orderDisc = 0;
            $scope.retailer = <?= json_encode($retailer) ?>;
            $scope.order = <?= json_encode($order) ?>;
            $scope.products = <?= json_encode($products) ?>;
            $scope.parsedProducts = {};
            $scope.parseProducts = function() {
                $scope.parsedProducts = {};
                $.map($scope.products, function(p) {
                    if (typeof $scope.parsedProducts[p.prodcolor_slug] == 'undefined')
                        $scope.parsedProducts[p.prodcolor_slug] = {
                            info: p,
                            sizes: [],
                            qty: 0,
                            total: 0
                        };
                    $scope.parsedProducts[p.prodcolor_slug].sizes.push(p);
                    $scope.parsedProducts[p.prodcolor_slug].qty += p.ordprod_request_qty;
                    $scope.parsedProducts[p.prodcolor_slug].qty += p.ordprod_total;
                });
            }
            // $scope.productTotal = slug => $.map($scope.products, e => e.prodcolor_slug == slug ? e.ordprod_total :
            //     0).reduce((accumulator, currentValue) => accumulator + currentValue);

            $scope.delProduct = function(index) {
                $scope.products.splice(index, 1);
                $scope.parseProducts();
            };

            // $scope.calculateSubtotal = function() {
            //     var total = 0;
            //     $scope.products.map(p => total += p.ordprod_request_qty * p.prodsize_wsp);
            //     return total.toFixed(2);
            // };

            // $scope.updateTotal = function(index) {
            //     var product = $scope.products[index];
            //     product.total = (product.ordprod_request_qty * product.prodsize_wsp).toFixed(2);
            // };

            $scope.parseProducts();
            scope = $scope;
        });
    </script>
@endsection
