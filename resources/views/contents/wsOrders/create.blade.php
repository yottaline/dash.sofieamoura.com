@extends('index')
@section('title', 'Create new orders')
@section('style')
    <style>
        :root {
            --product-size: 200px;
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
            width: var(--product-size);
            margin: 0 auto 10px;
            background-position: center;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .order-img {
            width: 70px;
            height: 70px;
            background-position: center 0;
            background-repeat: no-repeat;
            background-size: contain;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid" data-ng-app="ngApp" data-ng-controller="ngCtrl">
        <div class="row">
            <div class="col-12 col-sm-4 col-lg-3">
                <div class="card card-box mb-3">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold text-uppercase">Retailer Info</h5>
                        <div class="input-group mb-3">
                            <select name="retailer" id="retailer" class="form-select">
                                <option value="defalt">-- SELECT RETAILER NAME --</option>
                                <option ng-repeat="retailer in retailers" ng-value="retailer.retailer_id"
                                    ng-bind="retailer.retailer_fullName"></option>
                            </select>
                            <button data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                                aria-controls="collapseExample" class="btn btn-outline-dark bi bi-plus"
                                type="button"></button>
                        </div>

                        <div class="collapse" id="collapseExample">
                            <div class="mb-3">
                                <label for="retailerEmail">Email<b class="text-danger">&ast;</b></label>
                                <input type="email" class="form-control form-control-sm retailer-field" id="retailerEmail"
                                    ng-disabled="submitting">
                            </div>
                            <div class="mb-3">
                                <label for="retailerName">Name<b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control form-control-sm retailer-field" id="retailerName"
                                    ng-disabled="submitting">
                            </div>
                            <div class="mb-3">
                                <label for="retailerBusiness">Business<b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control form-control-sm retailer-field"
                                    id="retailerBusiness" ng-disabled="submitting">
                            </div>
                            <div class="mb-3">
                                <label for="retailerPhone">Phone</label>
                                <input type="tel" class="form-control form-control-sm retailer-field font-monospcae"
                                    id="retailerPhone" ng-disabled="submitting">
                            </div>
                            <div class="mb-3">
                                <label for="retailerCountry">Country<b class="text-danger">&ast;</b></label>
                                <select class="form-select form-select-sm font-monospcae retailer-field select2"
                                    id="retailerCountry" ng-disabled="submitting">
                                    <option value=""></option>
                                    @foreach ($countries as $c)
                                        <option value="{{ $c->location_id }}">{{ $c->location_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="retailerCity">City</label>
                                <input type="text" class="form-control form-control-sm retailer-field font-monospcae"
                                    id="retailerCity" ng-disabled="submitting">
                            </div>
                            <div class="mb-3">
                                <label for="retailerAddress">Address</label>
                                <textarea rows="3" class="form-control form-control-sm retailer-field font-monospcae" id="retailerAddress"
                                    ng-disabled="submitting"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-box">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold text-uppercase">Rtailer Order</h5>
                        <div ng-if="!fn.objectLen(order)" class="py-5 text-center">The list is empty</div>
                        <div ng-if="fn.objectLen(order)">
                            <div ng-repeat="(ok, o) in order" class="mb-3 border-bottom">
                                <div class="d-flex">
                                    <div ng-if="o.info.prodcolor_media == null"
                                        class="order-img rounded align-self-start m-1"
                                        style="background-image:url(/assets/img/default_product_image.png)"></div>
                                    <div ng-if="o.info.prodcolor_media" class="order-img rounded align-self-start m-1"
                                        style="background-image:url({{ asset('media/product/') }}/<% o.info.product_id %>/<% o.info.media_file %>)">
                                    </div>
                                    <div class="flex-fill">
                                        <h6 class="small">
                                            <span class="fw-bold me-2" ng-bind="o.info.product_name"></span>
                                            <span class="font-monospace text-secondary"
                                                ng-bind="'#'+o.info.product_code"></span>
                                        </h6>
                                        <table class="table mb-0">
                                            <tbody>
                                                <tr class="small" ng-repeat="(sk, s) in o.sizes">
                                                    <td ng-bind="s.info.prodcolor_name"></td>
                                                    <td width="40" class="text-center" ng-bind="s.info.size_name"></td>
                                                    <td width="40" class="font-monospace text-center"
                                                        ng-bind="s.info.prodsize_wsp">
                                                    </td>
                                                    <td width="30" class="font-monospace text-center"
                                                        ng-bind="s.qty">
                                                    </td>
                                                    <td width="60" class="px-2 font-monospace text-center"
                                                        ng-bind="fn.toFixed(s.total, 2)">
                                                    </td>
                                                    <td class="col-fit" ng-click="removeFromOrder(ok, sk)">
                                                        <a href="" class="bi bi-x-circle link-danger"></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="font-monospace small text-end py-2">
                                    <div class="me-3 d-inline-block">Qty: <span ng-bind="o.qty"></span></div>
                                    <div class="d-inline-block">Total: <span ng-bind="fn.toFixed(o.total, 2)"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="px-2 d-flex">
                                <span class="fw-bold me-auto">Total</span>
                                <span class="fw-bold font-monospace" ng-bind="fn.toFixed(orderTotal, 2)">0.00</span>
                            </div>
                            <div class="px-2 d-flex">
                                <span class="fw-bold me-auto">Qty</span>
                                <span class="fw-bold font-monospace" ng-bind="orderQty">0</span>
                            </div>
                            <div class="mt-3">
                                <label for="orderNote">Note</label>
                                <textarea id="orderNote" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                            <p ng-if="+orderTotal < 2000" class="m-0 mt-3 text-danger">
                                <i class="bi bi-info-circle me-1"></i>MOA EUR 2000
                            </p>
                            <button class="btn btn-outline-dark w-100 btn-sm mt-3" ng-click="placeOrder()"
                                ng-disabled="!fn.objectLen(order) || submitting">
                                <span ng-if="submitting" class="spinner-border spinner-border-sm me-2"
                                    role="status"></span>Place Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card card-box">
                    <div class="card-body">
                        <div ng-if="list.length" class="row">
                            <div ng-repeat="(pk, p) in list" class="col-12 col-sm-6  col-lg-4 col-xl-3">
                                <a href="" class="product-box" ng-click="viewProduct(pk)">
                                    <div ng-if="p.prodcolor_media == null" class="product-img rounded mb-2"
                                        style="background-image: url(/assets/img/default_product_image.png)"></div>
                                    <div ng-if="p.prodcolor_media" class="product-img rounded mb-2"
                                        style="background-image: url({{ asset('media/product/') }}/<% p.product_id %>/<% p.media_file %>)">
                                    </div>
                                    <h6 class="mb-0 text-dark fw-bold" ng-bind="p.product_name"></h6>
                                    <h6 class="small mb-0 text-secondary font-monospace" ng-bind="p.product_code"></h6>
                                </a>
                            </div>
                        </div>
                        @include('layouts.loader')
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="productCanvas" aria-labelledby="productCanvasLabel">
            <div ng-if="selectedProduct !== false" class="offcanvas-header">
                <h5 class="offcanvas-title font-monospace small" id="productCanvasLabel"
                    ng-bind="'#' + list[selectedProduct].product_code"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div ng-if="selectedProduct !== false" class="offcanvas-body">
                <div ng-if="list[selectedProduct].prodcolor_media == null" class="product-img rounded"
                    style="background-image: url(/assets/img/default_product_image.png)"></div>
                <a href="{{ asset('media/product/') }}/<% list[selectedProduct].product_id %>/<% list[selectedProduct].media_file %>"
                    ng-if="list[selectedProduct].prodcolor_media" class="product-img rounded d-block" target="_blank"
                    style="background-image: url({{ asset('media/product/') }}/<% list[selectedProduct].product_id %>/<% list[selectedProduct].media_file %>)">
                </a>
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
            $scope.submitting = false;
            $scope.list = [];
            $scope.offset = 0;
            $scope.retailers = <?= json_encode($retailers) ?>;
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

            // order = {prod_ref: {info: {}, sizes: [{info: {}, qty: n, total: n},], qty: n, total: n},}
            $scope.order = {};
            $scope.orderQty = 0;
            $scope.orderTotal = 0;
            $scope.colors = false;
            $scope.selectedProduct = false;
            $scope.viewProduct = function(ndx) {
                if ($scope.submitting) return;
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
                    scope.$evalAsync(() => {
                        $('.prodsize-qty').on('focus', function(e) {
                            if ($(this).val() === '0') $(this).val('');
                        }).on('blur', function(e) {
                            if ($(this).val() === '') $(this).val('0');
                        });
                    });
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
                    totalAmount = 0,
                    sizes = [];

                $('.prodsize-qty').map(function(n, e) {
                    var keys = $(e).data('size').split(','),
                        ck = keys[0],
                        sk = keys[1],
                        size = $scope.colors[ck].sizes[sk],
                        qty = +$(e).val(),
                        amount = qty * size.prodsize_wsp;

                    totalQty += qty;
                    totalAmount += amount;
                    product_ref = size.product_ref;

                    if (qty) sizes.push({
                        info: size,
                        qty: qty,
                        total: amount,
                    });
                });

                if (totalQty) {
                    $scope.order[product_ref] = {
                        info: $scope.list.find(o => o.product_ref == product_ref),
                        sizes: sizes,
                        qty: totalQty,
                        total: totalAmount,
                    };
                } else if (Object.keys($scope.order).includes(product_ref))
                    delete($scope.order[product_ref]);
                productCanvas.hide();
                $scope.calOrderTotal();
            }

            $scope.removeFromOrder = function(ok, sk) {
                if ($scope.submitting) return;
                $scope.order[ok].sizes.splice(sk, 1);
                if ($scope.order[ok].sizes.length) {
                    $scope.order[ok].qty = 0;
                    $scope.order[ok].total = 0;
                    $.map($scope.order[ok].sizes, e => {
                        $scope.order[ok].qty += e.qty;
                        $scope.order[ok].total += e.total;
                    });
                } else delete($scope.order[ok]);
                $scope.calOrderTotal();
            }

            $scope.calOrderTotal = function() {
                $scope.orderQty = 0;
                $scope.orderTotal = 0;
                $.map($scope.order, e => {
                    $scope.orderQty += e.qty;
                    $scope.orderTotal += e.total;
                });
            }

            $scope.placeOrder = function() {
                var email = $('#retailerEmail').val(),
                    name = $.trim($('#retailerName').val()),
                    biz = $.trim($('#retailerBusiness').val()),
                    phone = $.trim($('#retailerPhone').val()),
                    country = +$('#retailerCountry').val(),
                    city = $.trim($('#retailerCity').val()),
                    address = $.trim($('#retailerAddress').val()),
                    note = $.trim($('#orderNote').val()),
                    obj = Object.values(scope.order),
                    qty = $(obj).map((n, e) => $(e.sizes).map((x, y) => y.qty).get()).get().join(),
                    sizes = $(obj).map((n, e) => $(e.sizes).map((x, y) => y.info.prodsize_id).get()).get()
                    .join();
                if (!email || !validateEmail(email)) {
                    $('#retailerEmail').focus();
                    return;
                }
                if (!name) {
                    $('#retailerName').focus();
                    return;
                }
                if (!biz) {
                    $('#retailerBusiness').focus();
                    return;
                }
                if (!country) {
                    $('#retailerCountry').focus();
                    return;
                }
                $scope.submitting = true;
                $.post('/ws_orders/submit', {
                    qty: qty,
                    sizes: sizes,
                    email: email,
                    name: name,
                    biz: biz,
                    country: country,
                    city: city,
                    phone: phone,
                    address: address,
                    note: note,
                    _token: '{{ csrf_token() }}',
                }, function(response) {
                    scope.$apply(() => {
                        $scope.submitting = false;
                        if (response.status) {
                            $('.retailer-field').val(null).prop('readonly', false);;
                            $scope.order = {};
                            toastr.success('Order placed');
                        }
                    });
                }, 'json');
            }

            $scope.load();
            scope = $scope;
        });

        $(function() {
            $('#retailer').on('change', function() {
                var idState = this.value;
                console.log(idState);
                $.ajax({
                    url: '/ws_orders/get_retailer/' + idState,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        $('#retailerEmail').val(res.retailer_email).prop('readonly', true);
                        $('#retailerName').val(res.retailer_fullName).prop('readonly', true);
                        $('#retailerBusiness').val(res.retailer_company).prop('readonly', true);
                        $('#retailerPhone').val(res.retailer_phone).prop('readonly', true);
                        $('#retailerCountry').val(res.retailer_country).prop('readonly', true);
                    }
                });
            });

        });

        const validateEmail = email => {
            return String(email).toLowerCase().match(
                /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        };
    </script>
@endsection
