@extends('index')
@section('title', 'Orders')
@section('search')
    <form id="nvSearch" role="search">
        <input type="search" name="q" class="form-control my-3 my-md-0 rounded-pill" placeholder="Search...">
    </form>
@endsection
@section('content')
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
                            <input type="text" id="filterOrderDate" class="form-control text-center text-monospace"
                                id="filter-date">
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
                            @csrf
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    data-ng-click="setOrder(false)"></button>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    data-ng-click="dataLoader(true)"></button>
                            </div>
                        </div>
                        <div data-ng-if="list.length" class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Retailer Name</th>
                                        <th class="text-center">Order Date</th>
                                        <th class="text-center">Discount</th>
                                        <th class="text-center">Total Price</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-ng-repeat="order in list">
                                        <td data-ng-bind="order.order_code"
                                            class="text-center small font-monospace text-uppercase">
                                        </td>
                                        <td class="text-center" data-ng-bind="order.retailer_name">
                                        </td>
                                        <td data-ng-bind="order.order_create" class="text-center"></td>
                                        <td class="text-center"><% order.order_disc %>%</td>
                                        <td data-ng-bind="order.order_subtotal" class="text-center"></td>
                                        <td class="text-center">
                                            <button ng-if="order.order_status == 1"
                                                class="btn btn-outline-danger btn-circle bi bi-x"
                                                ng-click="opt($index, 2)"></button>
                                            <button ng-if="order.order_status == 1"
                                                class="btn btn-outline-primary btn-circle bi bi-check"
                                                ng-click="opt($index, 3)"></button>
                                            <button ng-if="order.order_status == 3"
                                                class="btn btn-outline-success btn-circle bi bi-check"
                                                ng-click="opt($index, 4)"></button>
                                            <button ng-if="order.order_status == 4"
                                                class="btn btn-outline-success btn-circle bi bi-truck"
                                                ng-click="opt($index, 5)"></button>
                                        </td>
                                        <td class="col-fit">
                                            <button class="btn btn-outline-dark btn-circle bi bi-eye"
                                                data-ng-click="viewDetails(order)"></button>
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

        <div class="modal fade" id="orderModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        {{-- <form id="orderForm" method="POST" action="/orders/submit/"> --}}

                        <input data-ng-if="updateOrders !== false" type="hidden" name="_method" value="put">
                        <input type="hidden" name="order_id" data-ng-value="list[updateOrders].product_id">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="mb-3">
                                    <label for="retailer">Retailer<b class="text-danger">&ast;</b></label>
                                    <select name="retailer" id="retailer" class="form-select" required>
                                        <option value="">-- SELECT RETAILER NAME --</option>
                                        <option data-ng-repeat="retailer in retailers" data-ng-value="retailer.retailer_id"
                                            data-ng-bind="retailer.retailer_fullName">
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="season">Season<b class="text-danger">&ast;</b></label>
                                    <select name="season" id="season" class="form-select" required>
                                        <option value="">-- SELECT SEASON NAME --</option>
                                        <option data-ng-repeat="season in seasons" data-ng-value="season.season_id"
                                            data-ng-bind="season.season_name">
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="currencies">currencies<b class="text-danger">&ast;</b></label>
                                    <select name="currency" id="currencies" class="form-select" required>
                                        <option value="">-- SELECT CURRENCY NAME --</option>
                                        <option data-ng-repeat="currency in currencies"
                                            data-ng-value="currency.currency_id" data-ng-bind="currency.currency_name">
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="31"></th>
                                        <th class="text-center" width="200">Product name</th>
                                        <th class="text-center" width="300">Size and Color names</th>
                                        <th class="text-center" width="90">Amount</th>
                                        <th class="text-center" width="200">MAX Order QTY</th>
                                        <th class="text-center" width="90">Price</th>
                                        <th class="text-center" width="120">Totle</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td colspan="7">
                                            <div class="position-relative">
                                                <input id="inv-item-input" type="text"
                                                    class="form-control font-monospace text-center" data-default=""
                                                    autocomplete="off">
                                                <div id="items-selector">
                                                    <div class="no-data text-secondary text-center py-3">
                                                        Item not recognized!
                                                    </div>
                                                    <div class="items-list">
                                                        <a href="#add" class="d-none">
                                                            <small class="record-name fw-bold"></small><br>
                                                            <small class="record-sn text-secondary font-monospace"></small>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="record-item " data-ng-repeat="p in products"
                                        id="invitem-<%p.product_id%>">
                                        <input type="hidden" class="record-id" ng-value="p.product_id">
                                        <td><a href="#del" class="inv-item-del text-danger"
                                                data-ng-click="delProduct($index)"><i class="bi bi-x-circle"></i></a></td>
                                        <td colspan="1">
                                            <small class="fw-bold" data-ng-bind="p.product_name"></small><br>
                                            <small class="text-secondary font-monospace"
                                                data-ng-bind="p.product_code"></small>
                                        </td>
                                        <td class="text-center">
                                            <small class="fw-bold" data-ng-bind="p.size_name"></small><br>
                                            <small class="text-secondary font-monospace"
                                                data-ng-bind="p.prodsize_color"></small>
                                        </td>
                                        <td><input type="number" step="1" min="1" name="qty"
                                                class="record-amount font-monospace text-center w-100"
                                                ng-change="clTotal()" ng-model="p.product_mincolorqty">
                                        </td>
                                        <td><input type="number" disabled
                                                class="record-maxqty font-monospace text-center w-100"
                                                ng-value="p.product_maxqty">
                                        </td>
                                        <td hidden><input type="number" step="1" min="0" name="qty"
                                                class="record-disc font-monospace text-center w-100" ng-change="clTotal()"
                                                ng-model="p.product_discount">
                                        </td>
                                        <td class="text-center" data-ng-bind="p.prodsize_wsp"></td>
                                        <td class="text-center"><span
                                                data-ng-bind="to (p.prodsize_wsp , p.product_mincolorqty, p.product_discount)"></span>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="fw-bold">The final price</td>
                                        <td class="text-center">
                                            <input type="number" id="order_disc" step="1" min="0"
                                                name="order_disc" class="font-monospace text-center w-100"
                                                ng-change="clTotal()" data-ng-model="orderDisc">
                                        </td>
                                        <td class="text-center text-success font-monospace" data-ng-bind="clTotal()"
                                            id="ordertotal">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="cost">Shipping cost for the order</label>
                                    <input type="text" id="cost" class="form-control" name="shipping_cost" />
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="" class="d-block mb-2">Order Type<b
                                            class="text-danger">&ast;</b></label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="order_type" id="orderType1"
                                            value="1">
                                        <label class="form-check-label" for="orderType1">IN-STOCK</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="order_type" id="orderType2"
                                            value="2" checked>
                                        <label class="form-check-label" for="orderType2">PRE-ORDER</label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" name="visible"
                                        value="1" ng-checked="+list[updateSeason].season_visible" id="seasonS">
                                    <label class="form-check-label" for="seasonS">Bill and Shipping Address is the same
                                        as the retailer address ?</label>
                                </div>
                            </div>
                            <hr>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="locations">Bill Country</label>
                                    <select name="location" id="locations" class="form-select" required>
                                        <option value="">-- SELECT LOCATION NAME --</option>
                                        <option data-ng-repeat="location in locations"
                                            data-ng-value="location.location_id" data-ng-bind="location.location_name">
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="billP">Bill Province</label>
                                    <input type="text" id="billP" class="form-control" name="bill_province" />
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="billC">Bill City</label>
                                    <input type="text" id="billC" class="form-control" name="bill_city" />
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="billZ">Bill City Zip Code</label>
                                    <input type="text" id="billZ" class="form-control" name="bill_zip" />
                                </div>
                            </div>


                            <div class="col-12 col-md-3">
                                <div class="mb-3">
                                    <label for="Anumber">
                                        Apartment number </label>
                                    <input type="text" class="form-control" name="line2" id="Anumber" />
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="streetN">
                                        Street number or street name</label>
                                    <input type="text" class="form-control" name="line1" id="streetN" />
                                </div>
                            </div>



                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="billP">
                                        Bill Phone </label>
                                    <input type="text" class="form-control" name="bill_phone" id="billP" />
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div class="mb-3">
                                    <label for="note">Notes</label>
                                    <textarea name="note" id="note" class="form-control" cols="30" rows="7"
                                        data-ng-value="list[updateOrders].product_note"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex">
                            <button type="button" class="btn btn-outline-secondary me-auto"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="submit" class="btn btn-outline-primary btn-sm">Submit</button>
                        </div>

                        {{-- </form> --}}
                        <script>
                            $('#items-selector .items-list').on('keydown', 'a', function(e) {
                                var k = e.key;
                                if (k === 'Enter') $(this).trigger('click');
                                else if (['ArrowUp', 'ArrowDown'].includes(k)) {
                                    var focusable, target, indx;
                                    focusable = $(this).parent().find('a').not('.d-none');
                                    indx = focusable.index(this) + (k === 'ArrowUp' ? -1 : 1);
                                    indx = indx < 0 ? 0 : indx;
                                    target = focusable.eq(indx);
                                    if (target.length) target.focus();
                                    return false;
                                }
                            });

                            $("#inv-item-input").on('change', function(e) {
                                var input = $(this);
                                $("#items-selector").hide().find(".no-data").hide();
                                $("#items-selector > .items-list > a").not(".d-none").remove();
                                input.removeClass('invalid');
                                if (!input.val()) return;

                                input.prop("disabled", true);
                                itemSearch(input.val(), function(items) {
                                    input.prop("disabled", false);

                                    if (!items.length) {
                                        $("#items-selector").show().find(".no-data").show();
                                        input.addClass('invalid').focus();
                                    } else if (items.length == 1)
                                        invRecord(items[0]);
                                    else {
                                        items.forEach(function(item) {
                                            var elem = $("#items-selector > .items-list > a.d-none").clone();
                                            elem.removeClass('d-none').find('.record-name').text(item.product_name);
                                            elem.find('.record-sn').text(item.product_code);
                                            elem.on('click', function(e) {
                                                // invRecord(item);
                                                $("#items-selector").hide();

                                                if ($(`#invitem-${item.product_id}`).length) {
                                                    toastr.info(
                                                        'This item has already been added, modify the quantity on the added item'
                                                    );
                                                    return;
                                                }

                                                item.pecAmount = 1;
                                                item.product_disc = +item.product_disc;

                                                scope.$apply(() => scope.products.push(item));

                                                $("#inv-item-input").val("").focus();
                                            }).appendTo("#items-selector > .items-list");
                                        });
                                        $("#items-selector").show().find('a').not('.d-none').first().focus();
                                    }
                                });
                            });

                            function itemSearch(keyword, callback) {
                                $.post('/ws_orders/get_product', {
                                    product: keyword,
                                    _token: "{{ csrf_token() }}",
                                }, callback, 'json');
                            }




                            $('#orderModal').on('show.bs.modal', function() {
                                $("#items-selector, #inv-loading").hide();
                                // $(this).find('select').val(null).trigger('change');
                                $(this).find('.record-item').not('.d-none').remove();
                                $(this).find('input, textarea').not("#id-input").each(function() {
                                    $(this).val($(this).data('default'));
                                });
                            });

                            function invRecord(item) {
                                if ($(`#invitem-${item.product_id}`).length) {
                                    toastr.info('This item has already been added, modify the quantity on the added item');
                                    return;
                                }
                                item.pecAmount = 1;
                                item.product_disc = +item.product_disc;
                                scope.$apply(() => scope.products.push(item));
                                $("#inv-item-input").val("").focus();
                            }

                            $('#submit').on('click', function(e) {
                                var cart = {
                                        id: [],
                                        pck: [],
                                        qty: [],
                                        disc: []
                                    },
                                    retailer = $('#retailer').val(),
                                    note = $('#note').val(),
                                    orderD = $('#order_disc').val(),
                                    orderTotal = $('#ordertotal').text();
                                orderDate = $('#orderDate').val();
                                $('.record-item').not('.d-none').map((i, e) => {
                                    cart.id.push($(e).find('.record-id').val());
                                    cart.qty.push($(e).find('.record-amount').val());
                                    cart.disc.push($(e).find('.record-disc').val());
                                });

                                $(this).prop('disabled', true);
                                $.post('/orders/submit/', {
                                    id: cart.id.join(),
                                    qty: cart.qty.join(),
                                    disc: cart.disc.join(),
                                    retailer_id: retailer,
                                    note: note,
                                    orderdisc: orderD,
                                    total: orderTotal,
                                    orderDate: orderDate,
                                    _token: "{{ csrf_token() }}",
                                }, function(data) {
                                    var response = JSON.parse(data);
                                    if (response.status) {
                                        toastr.success('The operation was completed successfully');
                                        $('#orderModal').modal('hide');
                                        scope.$apply(() => {
                                            if (scope.updateOrders === false) {
                                                scope.list.unshift(response.data);
                                            } else {
                                                scope.list[scope.updateOrders] = response.data;
                                            }
                                        });
                                    } else toastr.error(response.message);
                                }).fail(function(jqXHR, textStatus, errorThrown) {
                                    toastr.error(jqXHR.responseJSON.message);
                                }).always(function() {
                                    $(form).find('button').prop('disabled', false);
                                });
                            })

                            $(function() {
                                $("#filterOrderDate").datetimepicker($.extend({}, dtp_opt, {
                                    showTodayButton: false,
                                    format: "YYYY-MM-DD",
                                }));
                            });

                            $(function() {
                                $("#orderDate").datetimepicker($.extend({}, dtp_opt, {
                                    showTodayButton: false,
                                    format: "YYYY-MM-DD",
                                }));
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="edit_disc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-ng-repeat="details in orderDetails">
                                    <th scope="row"></th>
                                    <td data-ng-bind="details.product_name"></td>
                                    <td data-ng-bind="details.orderItem_qty"></td>
                                    <td data-ng-bind="details.orderItem_subtotal"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">
                                        Total amount
                                    </th>
                                    <th data-ng-bind="orDe.order_subtotal"></th>
                                </tr>
                            </tfoot>
                        </table>
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
            $('.loading-spinner').hide();
            $scope.statusObject = {
                name: ['غير مفعل', 'مفعل'],
                color: ['danger', 'success']
            };
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
            $scope.dataLoader = function(reload = false) {
                if (reload) {
                    $scope.list = [];
                    $scope.last_id = 0;
                    $scope.noMore = false;
                }

                if ($scope.noMore) return;
                $scope.loading = true;

                $('.loading-spinner').show();
                var request = {
                    date: $('#filter-date').val(),
                    c_name: $('#filter-name').val(),
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
                        if (ln) {
                            $scope.noMore = ln < limit;
                            $scope.list.push(...data);
                            $scope.last_id = data[ln - 1].order_id;
                            console.log(data)
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
                    $.post('/orders/change_status', {
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
                                    scope.dataLoader(true);
                                } else {
                                    scope.list[scope.updateOrders] = response.data;
                                }
                            });
                        } else toastr.error(response.message);
                    }, 'json');
                });
            }

            $scope.viewDetails = (order) => {
                $.get("/orders/view/" + order.order_id, function(data) {
                    $('.perm').show();
                    scope.$apply(() => {

                        scope.orderDetails = data.items;
                        scope.orDe = data.order;
                        console.log(data)
                        $('#edit_disc').modal('show');
                    });
                }, 'json');
            }

            $scope.delProduct = (index) => $scope.products.splice(index, 1);

            $scope.clTotal = function() {
                var total = 0;
                $scope.products.map(p => total += p.product_mincolorqty * p.prodsize_wsp);
                var totals = total - (total * $scope.orderDisc / 100);
                return totals.toFixed();
            }

            $scope.to = function(product_price, pecAmount) {
                return (pecAmount * product_price).toFixed();
            };

            $scope.dataLoader();
            scope = $scope;
        });

        $(function() {
            $('#nvSearch').on('submit', function(e) {
                e.preventDefault();
                scope.$apply(() => scope.q = $(this).find('input').val());
                scope.dataLoader(true);
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
