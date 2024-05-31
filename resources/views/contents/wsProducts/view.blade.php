@extends('index')
@section('title', 'Product #{{ $data->product_code }}')
@section('search')
    <form id="nvSearch" role="search">
        <input type="search" name="q" class="form-control my-3 my-md-0 rounded-pill" placeholder="Search...">
    </form>
@endsection
@section('content')
    <div class="container-fluid" ng-app="ngApp" ng-controller="ngCtrl">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-12 col-sm-8 col-lg-11">
                <div class="card card-box">
                    <div class="card-body">
                        {{-- ref name --}}
                        <h3 class="text-body-tertiary">#<%data.product_ref%></h3>
                        <hr>
                        {{-- start form data --}}
                        <form method="POST" id="wProductF" action="/ws_products/submit">
                            @csrf
                            <input type="hidden" name="_method" value="put">
                            <input type="hidden" name="id" id="product_id" ng-value="data.product_id">
                            <div class="row">

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="productName">
                                            Product Name <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="name"
                                            ng-value="data.product_name" id="productName" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="productCode">
                                            Product Code <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="code"
                                            ng-value="data.product_code" id="productCode" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="season">
                                            Season <b class="text-danger">&ast;</b></label>
                                        <select name="season" id="season" class="form-select" required>
                                            <option ng-value="data.season_id" ng-bind="data.season_name"></option>
                                            <option ng-repeat="season in seasons" ng-value="season.season_id"
                                                ng-bind="season.season_name">
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="productMinQty">
                                            Product Min Qty <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="minqty"
                                            ng-value="data.product_minqty" id="productMinQty" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="productMaxQty">
                                            Product Max Qty <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="maxqty"
                                            ng-value="data.product_maxqty" id="productMaxQty" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="productMinOrder">
                                            Product Min Order </label>
                                        <input type="text" class="form-control" name="minorder"
                                            ng-value="data.product_minorder" id="productMinOrder" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="productDiscount">
                                            Product Discount <b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="discount"
                                            ng-value="data.product_discount" id="productDiscount" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="productDelivery">
                                            Product Delivery Details </label>
                                        <input type="text" class="form-control" name="delivery"
                                            ng-value="data.product_delivery" id="productDelivery" />
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="category">
                                            Category <b class="text-danger">&ast;</b></label>
                                        <select name="category" id="category" class="form-select" required>
                                            <option ng-value="data.category_id" ng-bind="data.category_name"></option>
                                            <option ng-repeat="category in categories" ng-value="category.category_id"
                                                ng-bind="category.category_name">
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="orderType">
                                            Product Order Type <b class="text-danger">&ast;</b></label>
                                        <select name="order_type" id="orderType" class="form-select" required>
                                            <option value="default">-- SELECT ORDER TYPE --</option>
                                            <option value="1">IN-STOCK</option>
                                            <option value="2">PRE-ORDER</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="desc">
                                            Product Description <b class="text-danger">&ast;</b></label>
                                        <textarea class="form-control" name="description" id="desc" cols="30" rows="5"><%data.product_desc%></textarea>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="productRelated">
                                            Product Related <b class="text-danger">&ast;</b></label>
                                        <textarea class="form-control" name="related" id="productRelated" cols="30" rows="5"><%data.product_related%></textarea>
                                    </div>
                                </div>


                                <div class="col-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            name="freeshipping" value="1" ng-checked="+data.product_freeshipping">
                                        <label class="form-check-label">Free Shipping </label>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" name="status"
                                            value="1" ng-checked="+data.product_published">
                                        <label class="form-check-label">product status </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex">
                                <button type="submit" class="btn btn-outline-primary text-justify-start">Update</button>
                            </div>
                        </form>
                        <script>
                            $('#wProductF').on('submit', e => e.preventDefault()).validate({
                                rules: {
                                    name: {
                                        required: true
                                    },
                                    reference: {
                                        required: true
                                    },
                                    season: {
                                        required: true
                                    },
                                    category: {
                                        required: true
                                    },
                                    description: {
                                        required: true
                                    },
                                    order_type: {
                                        required: true
                                    },
                                    minqty: {
                                        required: true,
                                        digits: true,
                                    },
                                    maxqty: {
                                        digits: true,
                                        required: true
                                    },
                                    minorder: {
                                        digits: true,
                                    },
                                    discount: {
                                        digits: true,
                                        max: 100
                                    },
                                    delivery: {
                                        required: true
                                    },
                                    related: {
                                        required: true
                                    }
                                },
                                submitHandler: function(form) {
                                    console.log(form);
                                    var formData = new FormData(form),
                                        action = $(form).attr('action'),
                                        method = $(form).attr('method');

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
                                            toastr.success('Data processed successfully');
                                            scope.$apply(() => {
                                                if (scope.updateWProduct === false) {
                                                    scope.list.unshift(response
                                                        .data);
                                                } else {
                                                    scope.list[scope
                                                        .updateWProduct] = response.data;
                                                }
                                            });
                                        } else toastr.error(response.message);
                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                        console.log(textStatus)
                                        toastr.error("error");
                                    }).always(function() {
                                        $(form).find('button').prop('disabled', false);
                                    });
                                }
                            });
                        </script>
                        {{-- end form --}}
                        <hr class="mt-4 text-body-tertiary">

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
            $scope.statusObject = {
                name: ['Un visible', 'Visible'],
                color: ['danger', 'success']
            };
            $scope.currentObject = {
                name: ['Not current', 'Current'],
                color: ['danger', 'success']
            };
            $('.loading-spinner').hide();
            $scope.noMore = false;
            $scope.loading = false;
            $scope.q = '';
            $scope.updateSeason = false;
            $scope.list = [];
            $scope.last_id = 0;

            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.data = <?= json_encode($data) ?>;
            $scope.seasons = <?= json_encode($seasons) ?>;
            $scope.categories = <?= json_encode($categories) ?>;
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
                    q: $scope.q,
                    last_id: $scope.last_id,
                    limit: limit,
                    _token: '{{ csrf_token() }}'
                };

                $.post("/seasons/load", request, function(data) {
                    $('.loading-spinner').hide();
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
