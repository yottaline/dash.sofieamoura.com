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
                    ng-bind="list[selectedProduct].product_code"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div ng-if="selectedProduct !== false" class="offcanvas-body">
                <div ng-if="list[selectedProduct].prodcolor_media == null" class="product-img"
                    style="background-image: url(/assets/img/default_product_image.png)"></div>
                <div ng-if="list[selectedProduct].prodcolor_media" class="product-img"
                    style="background-image: url({{ asset('media/product/') }}/<% list[selectedProduct].product_id %>/<% list[selectedProduct].media_file %>)">
                </div>
                <h6 class="fw-bold" ng-bind="list[selectedProduct].product_name"></h6>
                <div ng-if="product === false" class="py-5 text-center">Loading...</div>
                <div ng-if="product !== false">

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
                        console.log(ln, limit, $scope.noMore);
                        if (ln) {
                            $scope.list.push(...data);
                            $scope.offset += ln;
                        }
                    });
                }, 'json');
            }

            $scope.product = false;
            $scope.selectedProduct = false;
            $scope.viewProduct = function(ndx) {
                $scope.selectedProduct = ndx;
                $scope.product = false;
                productCanvas.show();
            }

            $scope.load();
            scope = $scope;
        });

        $(function() {
            // $('.select2').select2();
        });
    </script>
@endsection
