<div class="modal fade" id="wproductForm" tabindex="-1" role="dialog" aria-labelledby="wproductFormLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" id="wProductF" action="/ws_products/submit">
                    @csrf
                    <input ng-if="updateWProduct !== false" type="hidden" name="_method" value="put">
                    <input type="hidden" name="id" id="product_id" ng-value="list[updateWProduct].product_id">
                    <div class="row">

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="productName">
                                    Product Name <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="name"
                                    ng-value="list[updateWProduct].product_name" id="productName" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="productReference">
                                    Product Reference <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="reference"
                                    ng-value="list[updateWProduct].product_ref" id="productReference" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="season">
                                    Season <b class="text-danger">&ast;</b></label>
                                <select name="season" id="season" class="form-select" required>
                                    <option value="default">-- SELECT SEASON --</option>
                                    <option ng-repeat="season in seasons" ng-value="season.season_id"
                                        ng-bind="season.season_name">
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="category">
                                    Category <b class="text-danger">&ast;</b></label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="default">-- SELECT CATEGORY --</option>
                                    <option ng-repeat="category in categories" ng-value="category.category_id"
                                        ng-bind="category.category_name">
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="desc">
                                    Product Description <b class="text-danger">&ast;</b></label>
                                <textarea class="form-control" name="description" id="desc" cols="30" rows="5"><%list[updateWProduct].product_desc%></textarea>
                            </div>
                        </div>

                        <div class="col-6">
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
                                <label for="productMinQty">
                                    Product Min Qty <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="minqty"
                                    ng-value="list[updateWProduct].product_minqty" id="productMinQty" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="productMaxQty">
                                    Product Max Qty <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="maxqty"
                                    ng-value="list[updateWProduct].product_maxqty" id="productMaxQty" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="productMinOrder">
                                    Product Min Order </label>
                                <input type="text" class="form-control" name="minorder"
                                    ng-value="list[updateWProduct].product_minorder" id="productMinOrder" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="productDiscount">
                                    Product Discount <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="discount"
                                    ng-value="list[updateWProduct].product_discount" id="productDiscount" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="productDelivery">
                                    Product Delivery Details </label>
                                <input type="text" class="form-control" name="delivery"
                                    ng-value="list[updateWProduct].product_delivery" id="productDelivery" />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="productRelated">
                                    Product Related <b class="text-danger">&ast;</b></label>
                                <textarea class="form-control" name="related" id="productRelated" cols="30" rows="5"><%list[updateWProduct].product_related%></textarea>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" name="freeshipping"
                                    value="1" ng-checked="+list[updateWProduct].product_freeshipping">
                                <label class="form-check-label">Free Shipping </label>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" name="status"
                                    value="1" ng-checked="+list[updateWProduct].product_published">
                                <label class="form-check-label">product status </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <button type="button" class="btn btn-outline-secondary me-auto"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
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
                                    $('#wproductForm').modal('hide');
                                    scope.$apply(() => {
                                        if (scope.updateWProduct === false) {
                                            scope.list.unshift(response
                                                .data);
                                            categoyreClsForm()
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

                    function categoyreClsForm() {
                        $('#product_id').val('');
                        $('#productName').val('');
                        $('#desc').val('');
                        $('#productReference').val('');
                        $('#productMinQty').val('');
                        $('#productMaxQty').val('');
                        $('#productMinOrder').val('');
                        $('#productDiscount').val('');
                        $('#productRelated').val('');
                        $('#productDelivery').val('');
                        $('#productReference').val('');
                        $('#productReference').val('');
                    }
                </script>
            </div>
        </div>
    </div>
</div>
