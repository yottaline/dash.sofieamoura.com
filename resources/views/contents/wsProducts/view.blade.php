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
            <div class="col-12 col-sm-8 col-lg-12">
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

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="desc">
                                            Product Description <b class="text-danger">&ast;</b></label>
                                        <textarea class="form-control" name="description" id="desc" cols="30" rows="5"><%data.product_desc%></textarea>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <button type="submit"
                                        class="btn btn-outline-primary text-justify-start">Update</button>
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
                                                    scope.siezs.unshift(response
                                                        .data);
                                                } else {
                                                    scope.siezs[scope
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

            {{-- start siezs section --}}
            <div class="mt-4">
                <div class="card card-box">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">
                                <span class="loading-spinner spinner-border spinner-border-sm text-warning me-2"
                                    role="status"></span><span>SIZES</span>
                            </h5>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus-lg"
                                    ng-click="setSiez(false)"></button>
                                <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                    ng-click="load(true)"></button>
                            </div>
                        </div>

                        <div ng-if="siezs.length" class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Size Name</th>
                                        <th class="text-center">Color Code</th>
                                        <th class="text-center">Color Name</th>
                                        <th class="text-center">Size Cost</th>
                                        <th class="text-center">Wholesale Price</th>
                                        <th class="text-center">Recommanded Retail Price</th>
                                        <th class="text-center">Size Qty </th>
                                        <th class="text-center">AVAILABLE QUANTITY</th>
                                        <th class="text-center">Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="si in siezs track by $index">
                                        <td ng-bind="si.prodcolor_ref"
                                            class="text-center small font-monospace text-uppercase">
                                        </td>
                                        <td class="text-center">
                                            <span ng-if="si.size_name" ng-bind="si.size_name"></span>
                                            <span ng-if="si.size_name == null" class="text-warning">Not added
                                                yet</span>
                                        </td>
                                        <td class="text-center" ng-bind="si.prodcolor_code"></td>
                                        <td class="text-center" ng-bind="si.prodcolor_name"></td>
                                        <td class="text-center">
                                            <span ng-if="si.prodsize_cost" ng-bind="si.prodsize_cost"></span>
                                            <span ng-if="si.prodsize_cost == null" class="text-warning">Not added
                                                yet</span>
                                        </td>
                                        <td class="text-center">
                                            <span ng-if="si.prodsize_wsp" ng-bind="si.prodsize_wsp"></span>
                                            <span ng-if="si.prodsize_wsp == null" class="text-warning">Not added
                                                yet</span>
                                        </td>
                                        <td class="text-center">
                                            <span ng-if="si.prodsize_rrp" ng-bind="si.prodsize_rrp"></span>
                                            <span ng-if="si.prodsize_rrp == null" class="text-warning">Not added
                                                yet</span>
                                        </td>
                                        <td class="text-center">
                                            <span ng-if="si.prodsize_qty" ng-bind="si.prodsize_qty"></span>
                                            <span ng-if="si.prodsize_qty == null" class="text-warning">Not added
                                                yet</span>
                                        </td>
                                        <td class="text-center">
                                            <span ng-if="si.prodsize_stock" ng-bind="si.prodsize_stock"></span>
                                            <span ng-if="si.prodsize_stock == null" class="text-warning">Not added
                                                yet</span>
                                        </td>
                                        <td class="text-center">
                                            <span ng-if="si.prodsize_visible"
                                                class="badge bg-<%statusObject.color[si.prodsize_visible]%> rounded-pill font-monospace p-2"><%statusObject.name[si.prodsize_visible]%></span>

                                            <span ng-if="si.prodsize_visible == null" class="text-warning">Not added
                                                yet</span>
                                        </td>
                                        <td class="col-fit">
                                            <button class="btn btn-outline-primary btn-circle bi bi-pencil-square"
                                                ng-click="setSiez($index)"></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div ng-if="!siezs.length" class="py-5 text-center text-secondary">
                            <i class="bi bi-exclamation-circle display-3"></i>
                            <h5>No Data</h5>
                        </div>
                    </div>

                    {{-- start size model --}}
                    <div class="modal fade" id="sizeModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form method="post" id="sizeForm" action="/product_sizes/submit">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="p_id" ng-value="data.product_id">
                                            <input ng-if="updateSize !== false" type="hidden" name="_method"
                                                value="put">
                                            <input type="hidden" name="id" id="prodsizeId"
                                                ng-value="siezs[updateSize].prodsize_id">
                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="colorCode">Color Code<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control font-monospace"
                                                        name="code" id="colorCode"
                                                        ng-value="siezs[updateSize].prodcolor_code">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="colorName">Color Name<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control"
                                                        ng-value="siezs[updateSize].prodcolor_name" name="name"
                                                        id="colorName">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-4">
                                                <div class="mb-3">
                                                    <label for="sizeCost">Size Cost<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control"
                                                        ng-value="siezs[updateSize].prodsize_cost" name="cost"
                                                        id="sizeCost">
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="minQtyForColor">
                                                        Mini order quantity for color <b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="mincolorqty"
                                                        ng-value="siezs[updateSize].prodcolor_mincolorqty"
                                                        id="minQtyForColor" />
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="productMinQty">
                                                        Product Min Qty <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="minqty"
                                                        ng-value="siezs[updateSize].prodcolor_minqty"
                                                        id="productMinQty" />
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="productMaxQty">
                                                        Product Max Qty <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="maxqty"
                                                        ng-value="siezs[updateSize].prodcolor_maxqty"
                                                        id="productMaxQty" />
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="productMinOrder">
                                                        Product Min Order </label>
                                                    <input type="text" class="form-control" name="minorder"
                                                        ng-value="siezs[updateSize].prodcolor_minorder"
                                                        id="productMinOrder" />
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="productDiscount">
                                                        Product Discount <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="discount"
                                                        ng-value="siezs[updateSize].prodcolor_discount"
                                                        id="productDiscount" />
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="orderType">
                                                        Order Type <b class="text-danger">&ast;</b></label>
                                                    <select name="order_type" id="orderType" class="form-select"
                                                        required>
                                                        <option value="default">-- SELECT ORDER TYPE --</option>
                                                        <option value="1">IN-STOCK</option>
                                                        <option value="2">PRE-ORDER</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="mb-3">
                                                    <label for="colorOrder">
                                                        Color Order <b class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control" name="order"
                                                        ng-value="siezs[updateSize].prodcolor_order" id="colorOrder" />
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-4">
                                                <div class="mb-3">
                                                    <label for="Wholesale">Size Wholesale Price<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control"
                                                        ng-value="siezs[updateSize].prodsize_wsp" name="wholesale"
                                                        id="Wholesale">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-4">
                                                <div class="mb-3">
                                                    <label for="Qty">Size Quantity<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control"
                                                        ng-value="siezs[updateSize].prodsize_qty" name="qty"
                                                        id="Qty">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-4">
                                                <div class="mb-3">
                                                    <label for="QUANTITY">Available Quantity<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control"
                                                        ng-value="siezs[updateSize].prodsize_stock" name="stock"
                                                        id="QUANTITY">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-4">
                                                <div class="mb-3">
                                                    <label for="Recommanded">Recommanded Retail Price<b
                                                            class="text-danger">&ast;</b></label>
                                                    <input type="text" class="form-control"
                                                        ng-value="siezs[updateSize].prodsize_rrp" name="rrp"
                                                        id="Recommanded">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <div class="mb-3">
                                                    <label for="productRelated">
                                                        Product Related <b class="text-danger">&ast;</b></label>
                                                    <textarea class="form-control" name="related" id="productRelated" cols="30" rows="5"><%siezs[updateSize].prodcolor_related%></textarea>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-12">
                                                <label for="size">Sizes<b class="text-danger">&ast;</b></label>
                                                <div class="form-check form-switch mb-5" style="display: inline-block"
                                                    name=""ng-repeat="s in allsizes">
                                                    <input type="checkbox" name="size[]" ng-value="s.size_id">
                                                    <label for="size" ng-bind="s.size_name">Size<b
                                                            class="text-danger">&ast;</b></label>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="freeshipping" value="1"
                                                        ng-checked="+siezs[updateSize].prodsize_freeshipping">
                                                    <label class="form-check-label">Free Shipping </label>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="color_status" value="1"
                                                        ng-checked="+siezs[updateSize].prodsize_published">
                                                    <label class="form-check-label">Color product status </label>
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="visible" value="1"
                                                        ng-checked="+siezs[updateSize].prodsize_visible">
                                                    <label class="form-check-label">Size Status </label>
                                                </div>
                                            </div>

                                        </div>

                                </div>

                                <div class="modal-footer d-flex">
                                    <div class="me-auto">
                                        <button type="submit" form="sizeForm" class="btn btn-outline-primary btn-sm"
                                            ng-disabled="submitting">Submit</button>
                                    </div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        data-bs-dismiss="modal" ng-disabled="submitting">Close</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script>
                        $('#sizeForm').on('submit', e => e.preventDefault()).validate({
                            rules: {
                                name: {
                                    required: true
                                },
                                code: {
                                    required: true,
                                },
                                cost: {
                                    digits: true,
                                    required: true,
                                },
                                mincolorqty: {
                                    digits: true,
                                    required: true,
                                },
                                minqty: {
                                    digits: true,
                                    required: true
                                },
                                maxqty: {
                                    digits: true,
                                    required: true
                                },
                                minorder: {
                                    digits: true,
                                    required: true
                                },
                                discount: {
                                    digits: true
                                },
                                order: {
                                    required: true
                                },
                                size: {
                                    required: true
                                },
                                wholesale: {
                                    digits: true,
                                },
                                qty: {
                                    digits: true,
                                },
                                stock: {
                                    digits: true,
                                },
                                rrp: {
                                    digits: true,
                                }
                            },
                            submitHandler: function(form) {
                                var formData = new FormData(form),
                                    action = $(form).attr('action'),
                                    method = $(form).attr('method');

                                scope.$apply(() => scope.submitting = true);
                                $.ajax({
                                    url: action,
                                    type: method,
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                }).done(function(data, textStatus, jqXHR) {
                                    var response = JSON.parse(data);
                                    console.log(response);
                                    scope.$apply(function() {
                                        scope.submitting = false;
                                        if (response.status) {
                                            toastr.success('Data processed successfully');
                                            $('#sizeModal').modal('hide');
                                            scope.$apply(() => {
                                                if (scope.updateSize === false) {
                                                    scope.siezs.unshift(response
                                                        .data);
                                                    scope.load(true);
                                                } else {
                                                    scope.siezs[scope
                                                        .updateSize] = response.data;
                                                    scope.load(true);
                                                }
                                            });
                                            $('#sizeModal').modal('hide');
                                        } else toastr.error(response.message);
                                    });
                                }).fail((jqXHR, textStatus, errorThrown) => toastr.error("Request failed!"));
                            }
                        });
                    </script>
                </div>
                {{-- end  size model --}}
            </div>
        </div>
        {{-- end siezs section --}}

        {{-- start media section --}}
        <div class="mt-5">
            <div class="card card-box">
                <div class="card-body">
                    <div class="d-flex">
                        <h5 class="card-title fw-semibold pt-1 me-auto mb-3 text-uppercase">MEDIAS</h5>
                        <div>
                            <button type="button" class="btn btn-outline-primary btn-circle bi bi-plus"
                                data-bs-toggle="modal" data-bs-target="#mediaModal"></button>
                            <button type="button" class="btn btn-outline-dark btn-circle bi bi-arrow-repeat"
                                ng-click="loadProductMedia(true)"></button>
                        </div>
                    </div>

                    <div ng-if="medails.length" class="row">
                        <div ng-repeat="m in medails" class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="mb-3 text-center">
                                <img src="{{ asset('media/product/') }}/<%m.media_product%>/<%m.media_file%>"
                                    class="card-img-top">
                                <div class="card-body">
                                    <h6 class="card-title" ng-bind="m.media_color"></h6>
                                    <h6 class="small font-monospace" ng-bind="m.product_code"></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div ng-if="!medails.length" class="py-5 text-center text-secondary">
                        <i class="bi bi-exclamation-circle display-3"></i>
                        <h5>No Data</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mediaModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="post" id="mediaForm" action="/product_medias/submit">
                            @csrf
                            <input type="hidden" name="product_id" ng-value="data.product_id">
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="color">Color</label>
                                        <input type="text" class="form-control font-monospace" name="color"
                                            id="color">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        <label for="order">Order<b class="text-danger">&ast;</b></label>
                                        <input type="text" class="form-control" name="order" id="order">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="media">Name<b class="text-danger">&ast;</b></label>
                                        <input type="file" class="form-control" name="media[]" multiple
                                            id="media">
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="modal-footer d-flex">
                        <div class="me-auto">
                            <button type="submit" form="mediaForm" class="btn btn-outline-primary btn-sm"
                                ng-disabled="submitting">Submit</button>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"
                            ng-disabled="submitting">Close</button>
                    </div>
                </div>
            </div>
            <script>
                $('#mediaForm').on('submit', e => e.preventDefault()).validate({
                    submitHandler: function(form) {
                        var formData = new FormData(form),
                            action = $(form).attr('action'),
                            method = $(form).attr('method');

                        scope.$apply(() => scope.submitting = true);
                        $.ajax({
                            url: action,
                            type: method,
                            data: formData,
                            processData: false,
                            contentType: false,
                        }).done(function(data, textStatus, jqXHR) {
                            var response = JSON.parse(data);
                            scope.$apply(function() {
                                scope.submitting = false;
                                if (response.status) {
                                    toastr.success('Data processed successfully');
                                    $('#mediaModal').modal('hide');
                                    scope.$apply(() => {
                                        if (scope.updateMedails === false) {
                                            scope.medails.unshift(response
                                                .data);
                                            scope.load();
                                        } else {
                                            scope.siezs[scope
                                                .updateMedails] = response.data;
                                        }
                                    });
                                } else toastr.error(response.message);
                            });
                        }).fail((jqXHR, textStatus, errorThrown) => toastr.error("Request failed!"));
                    }
                });
            </script>
        </div>
        {{-- end media section --}}
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
            $('.loading-spinner').hide();
            $scope.q = '';
            $scope.updateSize = false;
            $scope.siezs = [];

            $scope.medails = [];
            $scope.updateMedails = false;

            $scope.jsonParse = (str) => JSON.parse(str);
            $scope.data = <?= json_encode($data) ?>;
            $scope.seasons = <?= json_encode($seasons) ?>;
            $scope.categories = <?= json_encode($categories) ?>;
            $scope.allsizes = <?= json_encode($sizes) ?>;
            $scope.load = function(reload = false) {
                $('.loading-spinner').show();
                var request = {
                    q: $scope.q,
                    product_id: $scope.data.product_id,
                    _token: '{{ csrf_token() }}'
                };

                $.post("/product_sizes/load", request, function(data) {
                    $('.loading-spinner').hide();
                    var ln = data.length;
                    $scope.$apply(() => {
                        $scope.loading = false;
                        if (ln) {
                            $scope.siezs = data;
                            console.log(data)
                        }
                    });
                }, 'json');
            }

            $scope.setSiez = (indx) => {
                $scope.updateSize = indx;
                $('#sizeModal').modal('show');
            };

            $scope.loadProductMedia = function(reload = false) {
                $('.loading-spinner').show();
                var request = {
                    product_id: $scope.data.product_id,
                    _token: '{{ csrf_token() }}'
                };
                $.post("/product_medias/load", request, function(data) {
                    $('.loading-spinner').hide();
                    var ln = data.length;
                    $scope.$apply(() => {
                        if (ln) {
                            $scope.medails = data;
                        }
                    });
                }, 'json');
            }

            $scope.load();
            $scope.loadProductMedia();
            scope = $scope;
        });

        $('#nvSearch').on('submit', function(e) {
            e.preventDefault();
            scope.$apply(() => scope.q = $(this).find('input').val());
            scope.load(true);
        });
    </script>
@endsection
