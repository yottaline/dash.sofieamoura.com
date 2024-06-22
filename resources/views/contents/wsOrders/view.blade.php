@extends('index')
@section('title', 'View order')
@section('content')
    <div class="container-fluid container" data-ng-app="myApp" data-ng-controller="myCtrl">
        <div class="row">

            <div class="col-12 col-sm-8 col-lg-9">
                <div class="card card-box mt-2" data-ng-repeat="data in orderData">
                    <div class="d-flex mt-2">
                        <h5 class="card-title fw-semibold pt-1 text-uppercase" style="margin-left: 245px">
                            <span class="text-warning me-2" role="status"></span><span><%data.product_name%>
                                #<%data.product_ref%></span>
                        </h5>
                    </div>
                    <div class="card-body row">
                        <div class="col-12 col-sm-4 col-md-3 mb-5" ng-if="data.prodcolor_media == null"
                            class="product-img rounded mb-2"
                            style="background-image: url(/assets/img/default_product_image.png);padding:100px"></div>
                        <div class="col-12 col-sm-4 col-md-3" ng-if="data.prodcolor_media" class="product-img rounded mb-2"
                            style="background-image: url({{ asset('media/product/') }}/<% data.product_id %>/<% data.media_file %>);padding:100px;background-size:contain">
                        </div>
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-hover sizes-table" id="example">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Color</th>
                                            <th class="text-center">Size</th>
                                            <th class="text-center">WSP</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-center">Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td data-ng-bind="data.prodcolor_ref"
                                                class="text-center small font-monospace text-uppercase">
                                            </td>
                                            <td class="text-center" data-ng-bind="data.size_name">
                                            <td class="text-center" data-ng-bind="data.prodsize_wsp">
                                            </td>
                                            <td class="text-center">
                                                <input type="number" value="4"
                                                    data-ng-model="data.ordprod_request_qty"
                                                    data-ng-change="updateTotal(0)">
                                            </td>
                                            <td data-ng-bind="(data.ordprod_request_qty * data.prodsize_wsp).toFixed(2)"
                                                class="text-center font-monospace"></td>
                                            <td class="col-fit">
                                                <a class="btn link-danger btn-circle bi bi-x"
                                                    data-ng-click="delProduct($index)"></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-4 col-lg-3 mt-2">
                <div class="card card-box">
                    <div class="card-body product-block">
                        <h4 class="card-title fw-semibold pt-1 me-auto text-uppercase">
                            <span>ORDER #<%order.order_code%></span><br>
                        </h4>
                        <h6>
                            <span>Season/<%order.season_name%></span>
                        </h6>
                        <hr>
                        <span class="fw-semibold">Retailer: <%retailer.retailer_fullName%></span><br>
                        <span><%statusObject.name[order.order_status]%></span>
                        <p class="font-secondary">created: <span data-ng-bind="order.order_created"></span></p>
                        <hr>
                        <div class="subtotal">
                            <p>Subtotal: <span data-ng-bind="calculateSubtotal()"></span></p>
                            <hr>
                            <p class="text-danger fw-semibold">Discount: <input type="number" value="4"
                                    class="text-center" style="margin-left:140px; width:100px"
                                    data-ng-model="order.order_discount" ng-change="calculateSubtotal()">
                            </p>
                            <hr>
                            <p class="fw-bold">Total: <span data-ng-bind="calculateSubtotal()"></span></p>
                        </div>
                        <hr>
                        <div class="delivery">
                            <form action="/ws_orders/change_status" id="statusForm" method="post">
                                <input type="hidden" name="id" ng-value="order.order_id">
                                @csrf
                                <span>status</span>
                                <div class="input-group mb-3">
                                    <select name="status" id="status" class="form-select">
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
                            <a ng-if="order.order_status = 2" class="btn btn-outline-dark mt-4 w-100"
                                href="/ws_orders/get_confirmed/<%order.order_id%>">Get
                                Order
                                Confirmed</a>
                            <button ng-if="order.order_status >= 3" class="btn btn-outline-dark mt-4 w-100">Get Proforma
                                Invoice</button>
                            <button ng-if="order.order_status >= 3" class="btn btn-outline-dark mt-4 w-100">Get Proforma
                                Invoice</button>
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
            app = angular.module('myApp', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

        app.controller('myCtrl', function($scope) {
            $scope.statusObject = {
                name: ['Draft', 'Cancelled', 'Placed', 'Confirmed', 'Advance Payment Is Pending',
                    'Balance Payment Is Pending', 'Shipped'
                ],
            };
            $scope.orderDisc = 0;
            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.retailer = <?= json_encode($retailer) ?>;
            $scope.order = <?= json_encode($order) ?>;
            $scope.orderData = <?= json_encode($orderData) ?>;

            // console.log($scope.orderData);
            $scope.delProduct = function(index) {
                $scope.orderData.splice(index, 1);
                console.log(scope.orderData);
            };

            $scope.calculateSubtotal = function() {
                var total = 0;
                $scope.orderData.map(p => total += p.ordprod_request_qty * p.prodsize_wsp);
                return total.toFixed(2);
            };

            $scope.updateTotal = function(index) {
                var product = $scope.orderData[index];
                product.total = (product.ordprod_request_qty * product.prodsize_wsp).toFixed(2);
            };

            scope = $scope;
        });
    </script>
@endsection
