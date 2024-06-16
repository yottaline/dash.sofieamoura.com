@extends('index')
@section('title', 'Create new orders')
@section('style')
    <style>
        :root {
            --product-size: 120px;
        }

        .product-box {
            display: block;
            width: var(--product-size);
            margin: auto;
            margin-bottom: 15px;
            text-align: center
        }

        .product-img {
            height: var(--product-size);
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .order-img {
            width: 50px
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid" data-ng-app="ngApp" data-ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-3">
                <div class="card card-box mb-3">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold text-uppercase">Retailet Info</h5>
                        <div class="mb-3">
                            <label for="retailerEmail">Email<b class="text-danger">&ast;</b></label>
                            <input type="email" class="form-control form-control-sm" id="retailerEmail">
                        </div>
                        <div class="mb-3">
                            <label for="retailerName">Name<b class="text-danger">&ast;</b></label>
                            <input type="text" class="form-control form-control-sm" id="retailerName">
                        </div>
                        <div class="mb-3">
                            <label for="retailerBusiness">Business<b class="text-danger">&ast;</b></label>
                            <input type="text" class="form-control form-control-sm" id="retailerBusiness">
                        </div>
                        <div class="mb-3">
                            <label for="retailerPhone">Phone</label>
                            <input type="tel" class="form-control form-control-sm font-monospcae" id="retailerPhone">
                        </div>
                        <div class="mb-3">
                            <label for="retailerCountry">Country</label>
                            <select class="form-select form-select-sm font-monospcae select2" id="retailerCountry">
                                <option value=""></option>
                                @foreach ($countries as $c)
                                    <option value="{{ $c->location_id }}">{{ $c->location_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="retailerCity">City</label>
                            <input type="text" class="form-control form-control-sm font-monospcae" id="retailerCity">
                        </div>
                        <div class="mb-3">
                            <label for="retailerAddress">Address</label>
                            <textarea rows="3" class="form-control form-control-sm font-monospcae" id="retailerAddress"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card card-box">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold text-uppercase">Rtailer Order</h5>
                        <div ng-if="!fn.objectLen(order)" class="py-5 text-center">The list is empty</div>
                        <div ng-if="fn.objectLen(order)" class="table-responsive">
                            <div ng-repeat="(ok, o) in order" class="d-flex">
                                <img ng-if="o.info.prodcolor_media == null" src="/assets/img/default_product_image.png"
                                    alt="" class="order-img">
                                <img ng-if="o.info.prodcolor_media"
                                    src="{{ asset('media/product/') }}/<% o.info.product_id %>/<% o.info.media_file %>"
                                    alt="" class="order-img">
                                <div class="flex-grow-1">
                                    <h6 class="small fw-bold" ng-bind="o.info.product_name"></h6>
                                    <h6 class="small font-monospace text-secondary" ng-bind="o.info.product_code"></h6>
                                    {{-- <div class="fw-bold text-danger bg-muted-2 px-2" ng-bind="c.info.prodcolor_name"> </div> --}}
                                    <table class="table">
                                        <tbody>
                                            <tr class="small" ng-repeat="(sk, s) in o.sizes">
                                                <td ng-bind="s.info.prodcolor_name"></td>
                                                <td width="70" class="text-center" ng-bind="s.info.size_name"></td>
                                                <td width="70" class="font-monospace text-center"
                                                    ng-bind="s.info.prodsize_wsp">
                                                </td>
                                                <td width="60" class="font-monospace text-center" ng-bind="s.qty"></td>
                                                <td width="100" class="px-2 font-monospace text-center"
                                                    ng-bind="fn.toFixed(s.qty * s.info.prodsize_wsp, 2)">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="px-2 d-flex">
                                <span class="fw-bold me-auto">Total</span>
                                <span class="fw-bold font-monospace">0.00</span>
                            </div>
                            <div class="px-2 d-flex">
                                <span class="fw-bold me-auto">Qty</span>
                                <span class="fw-bold font-monospace">0</span>
                            </div>
                            <button class="btn btn-outline-dark w-100 btn-sm mt-4" ng-click="placeOrder()">Place
                                Order</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-box">
                    <div class="card-body">
                        <div ng-if="list.length" class="d-flex flex-wrap align-items-center">
                            <a ng-repeat="(pk, p) in list" href="" class="product-box" ng-click="viewProduct(pk)">
                                <div ng-if="p.prodcolor_media == null" class="product-img"
                                    style="background-image: url(/assets/img/default_product_image.png)"></div>
                                <div ng-if="p.prodcolor_media" class="product-img"
                                    style="background-image: url({{ asset('media/product/') }}/<% p.product_id %>/<% p.media_file %>)">
                                </div>
                                <h6 class="small mb-0 text-dark" ng-bind="p.product_name"></h6>
                                <h6 class="small mb-0 text-dark font-monospace" ng-bind="p.product_code"></h6>
                            </a>
                        </div>
                        @include('layouts.loader')
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="productCanvas"
            aria-labelledby="productCanvasLabel">
            <div ng-if="selectedProduct !== false" class="offcanvas-header">
                <h5 class="offcanvas-title font-monospace small" id="productCanvasLabel"
                    ng-bind="'#' + list[selectedProduct].product_code"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div ng-if="selectedProduct !== false" class="offcanvas-body">
                <div ng-if="list[selectedProduct].prodcolor_media == null" class="product-img"
                    style="background-image: url(/assets/img/default_product_image.png)"></div>
                <div ng-if="list[selectedProduct].prodcolor_media" class="product-img"
                    style="background-image: url({{ asset('media/product/') }}/<% list[selectedProduct].product_id %>/<% list[selectedProduct].media_file %>)">
                </div>
                <h6 class="fw-bold" ng-bind="list[selectedProduct].product_name"></h6>
                <h6 class="text-secondary small" ng-bind="list[selectedProduct].season_name"></h6>
                <div class="py-4">
                    <h6 class="fw-bold">Sizes</h6>
                    <div ng-if="colors === false" class="py-5 text-center">Loading...</div>
                    <div ng-if="colors !== false" class="table-responsive">
                        <div ng-repeat="(ck, c) in colors">
                            <div class="fw-bold text-danger bg-muted-2 px-2" ng-bind="c.info.prodcolor_name">
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr class="small" ng-repeat="(sk, s) in c.sizes">
                                        <td class="me-auto px-2" ng-bind="s.size_name"></td>
                                        <td width="70" class="font-monospace text-center" ng-bind="s.prodsize_wsp">
                                        </td>
                                        <td width="60">
                                            <input class="font-monospace text-center w-100 small prodsize-qty"
                                                data-wsp="<% s.prodsize_wsp %>" data-size="<% ck+','+sk %>"
                                                ng-model="s.qty" ng-change="calProductTotal()">
                                        </td>
                                        <td width="100" class="px-2 font-monospace text-center"
                                            ng-bind="fn.toFixed(s.qty * s.prodsize_wsp, 2)">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-2 d-flex">
                            <span class="fw-bold me-auto">Total</span>
                            <span class="fw-bold font-monospace" id="totalAmount">0.00</span>
                        </div>
                        <div class="px-2 d-flex">
                            <span class="fw-bold me-auto">Qty</span>
                            <span class="fw-bold font-monospace" id="totalQty">0</span>
                        </div>
                        <button class="btn btn-outline-dark w-100 btn-sm mt-4" ng-click="addToOrder()">Order</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('js')
        <script>
            const productCanvas = new bootstrap.Offcanvas('#productCanvas');
            var scope,
                ngApp = angular.module('ngApp', [], function($interpolateProvider) {
                    $interpolateProvider.startSymbol('<%');
                    $interpolateProvider.endSymbol('%>');
                });

            ngApp.controller('ngCtrl', function($scope) {
                $scope.fn = NgFunctions;
                $scope.noMore = false;
                $scope.loading = false;
                $scope.list = [];
                $scope.offset = 0;
                $scope.load = function(reload = false) {
                    if (reload) {
                        $scope.list = [];
                        $scope.offset = 0;
                        $scope.noMore = false;
                    }

                    if ($scope.noMore) return;
                    $scope.loading = true;

                    $.post("/ws_products/load", {
                        offset: $scope.offset,
                        limit: limit,
                        _token: '{{ csrf_token() }}'
                    }, function(data) {
                        var ln = data.length;
                        $scope.$apply(() => {
                            $scope.loading = false;
                            $scope.noMore = ln < limit;
                            if (ln) {
                                $scope.list.push(...data);
                                $scope.offset += ln;
                            }
                        });
                    }, 'json');
                }

                $scope.order = new Object();
                $scope.colors = false;
                $scope.selectedProduct = false;
                $scope.viewProduct = function(ndx) {
                    $scope.selectedProduct = ndx;
                    $scope.colors = false;
                    productCanvas.show();
                    $.post('/product_sizes/load', {
                        product_id: $scope.list[$scope.selectedProduct].product_id,
                        _token: '{{ csrf_token() }}'
                    }, function(data) {
                        var colors = {};
                        $scope.sizes = data;
                        $.map(data, function(item) {
                            if (typeof colors[item.prodcolor_ref] == 'undefined')
                                colors[item.prodcolor_ref] = {
                                    info: item,
                                    sizes: [],
                                };
                            item.qty = 0;
                            colors[item.prodcolor_ref].sizes.push(item);
                        });
                        scope.$apply(() => scope.colors = colors);
                    }, 'json')
                }

                $scope.calProductTotal = function() {
                    var total = 0,
                        qty = 0;
                    $('.prodsize-qty').map(function(n, e) {
                        qty += +e.value;
                        total += (+e.value * $(e).data('wsp'));
                    });
                    $('#totalAmount').text(sepNumber(total.toFixed(2)));
                    $('#totalQty').text(qty);
                }

                $scope.addToOrder = function() {
                    var product_ref, totalQty = 0,
                        sizes = [];

                    $('.prodsize-qty').map(function(n, e) {
                        var keys = $(e).data('size').split(','),
                            ck = keys[0],
                            sk = keys[1],
                            size = $scope.colors[ck].sizes[sk],
                            qty = +$(e).val();
                        totalQty += qty;
                        product_ref = size.product_ref;
                        if (qty) sizes.push({
                            info: size,
                            qty: qty
                        });
                    });

                    if (totalQty) {
                        $scope.order[product_ref] = {
                            info: $scope.list.find(o => o.product_ref == product_ref),
                            sizes: sizes,
                        };
                    } else if (Object.keys($scope.order).includes(product_ref))
                        delete($scope.order[product_ref]);
                    productCanvas.hide();
                }

                $scope.load();
                scope = $scope;
            });

            $(function() {
                // $('.select2').select2();
            });
        </script>
    @endsection
