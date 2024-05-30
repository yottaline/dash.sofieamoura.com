<div class="modal fade" id="retailerForm" tabindex="-1" role="dialog" aria-labelledby="retailerFormLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" action="/retailers/submit">
                    @csrf
                    <input ng-if="updateRetailer !== false" type="hidden" name="_method" value="put">
                    <input type="hidden" name="retailer_id" id="retailer_id"
                        ng-value="list[updateRetailer].retailer_id">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="retailerName">
                                    Full Name <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="name" required
                                    ng-value="list[updateRetailer].retailer_fullName" id="retailerName" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="email">
                                    Email <b class="text-danger">&ast;</b></label>
                                <input type="email" class="form-control" name="email" required
                                    ng-value="list[updateRetailer].retailer_email" id="email" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="password">
                                    Password <b class="text-danger">&ast;</b></label>
                                <input type="password" class="form-control" name="password" id="password" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="phone">
                                    Phone <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="phone" required
                                    ng-value="list[updateRetailer].retailer_phone" id="phone" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="company">
                                    Company Name <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="company" required
                                    ng-value="list[updateRetailer].retailer_company" id="company" />
                            </div>
                        </div>



                        <div class="col-6">
                            <div class="mb-3">
                                <label for="logo">
                                    Company Logo</label>
                                <input type="file" class="form-control" name="logo" id="logo" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="website">
                                    Company Website </label>
                                <input type="text" class="form-control" name="website"
                                    ng-value="list[updateRetailer].retailer_website" id="website" />
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="mb-3">
                                <label for="desc">
                                    Company Description</label>
                                <textarea class="form-control" name="desc" cols="30" rows="7" id="desc"></textarea>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="province">
                                    Province <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="province" required
                                    ng-value="list[updateRetailer].retailer_province" id="province" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="Payment">
                                    Advance Payment </label>
                                <input type="text" class="form-control" name="payment"
                                    ng-value="list[updateRetailer].retailer_adv_payment" id="Payment" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="currency">
                                    Currency <b class="text-danger">&ast;</b></label>
                                <select name="currency" id="currency" class="form-select" required>
                                    <option ng-if="+list[updateRetailer].retailer_currency"
                                        ng-value="list[updateRetailer].currency_id"
                                        ng-bind="list[updateRetailer].currency_name">
                                    <option value="default" ng-if="!+list[updateRetailer].retailer_currency">--
                                        SELECT YOUR CURRENCIES --</option>
                                    <option ng-repeat="currency in currencies" ng-value="currency.currency_id"
                                        ng-bind="currency.currency_name">
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="country">
                                    Country <b class="text-danger">&ast;</b></label>
                                <select name="country" id="country" class="form-select" required>
                                    <option ng-if="+list[updateRetailer].retailer_country"
                                        ng-value="list[updateRetailer].location_id"
                                        ng-bind="list[updateRetailer].location_name">
                                    <option value="default" ng-if="!+list[updateRetailer].retailer_country">--
                                        SELECT YOUR COUNTRY --</option>
                                    <option ng-repeat="country in locations" ng-value="country.location_id"
                                        ng-bind="country.location_name"></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="city">
                                    City <b class="text-danger">&ast;</b></label></label>
                                <input type="text" class="form-control" name="city"
                                    ng-value="list[updateRetailer].retailer_city" id="city" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="address">
                                    Address</label>
                                <input type="text" class="form-control" name="address"
                                    ng-value="list[updateRetailer].retailer_address" id="address" />
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" name="status"
                                    value="1" ng-checked="+list[updateRetailer].retailer_blocked"
                                    id="retailerStatus">
                                <label class="form-check-label" for="retailerStatus">Retailer Status</label>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex">
                        <button type="button" class="btn btn-outline-secondary me-auto"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#retailerForm form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this),
            formData = new FormData(this),
            action = form.attr('action'),
            method = form.attr('method'),
            controls = form.find('button, input'),
            spinner = $('#locationModal .loading-spinner');
        spinner.show();
        controls.prop('disabled', true);
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
                $('#retailerForm').modal('hide');
                scope.$apply(() => {
                    if (scope.updateRetailer === false) {
                        scope.list.unshift(response
                            .data);
                        scope.load();
                        categoyreClsForm()
                    } else {
                        scope.list[scope
                            .updateRetailer] = response.data;
                    }
                });
            } else toastr.error("Error");
        }).fail(function(jqXHR, textStatus, errorThrown) {
            // error msg
        }).always(function() {
            spinner.hide();
            controls.prop('disabled', false);
        });
    })

    function categoyreClsForm() {
        $('#retailer_id').val('');
        $('#retailerName').val('');
        $('#email').val('');
        $('#password').val('');
        $('#phone').val('');
        $('#company').val('');
        $('#logo').val('');
        $('#website').val('');
        $('#desc').val('');
        $('#province').val('');
        $('#Payment').val('');
        $('#currency').val('');
        $('#city').val('');
        $('#shipAdd').val('');
        $('#billAdd').val('');
        $('#address').val('');
    }
</script>


{{-- edit approved --}}
<div class="modal fade" id="editApproved" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" action="/retailers/edit_approved">
                    @csrf
                    <input ng-if="updateRetailer !== false" type="hidden" name="_method" value="put">
                    <input type="hidden" name="id" ng-value="list[updateRetailer].retailer_id">
                    <div class="row">
                        <div class="col-12">
                            <p class="mb-2">Are you sure you want to approved the retailer account ?</p>
                        </div>
                        <div class="d-flex">
                            <button type="button" class="btn btn-outline-secondary me-auto"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-primary">Approved</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#editApproved form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this),
            formData = new FormData(this),
            action = form.attr('action'),
            method = form.attr('method'),
            controls = form.find('button, input'),
            spinner = $('#locationModal .loading-spinner');
        spinner.show();
        controls.prop('disabled', true);
        $.ajax({
            url: action,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
        }).done(function(data, textStatus, jqXHR) {
            var response = JSON.parse(data);
            if (response.status) {
                toastr.success('Actived successfully');
                $('#editApproved').modal('hide');
                scope.$apply(() => {
                    if (scope.updateRetailer === false) {
                        scope.list.unshift(response.data);
                        // scope.load(true);
                    } else {
                        scope.list[scope.updateRetailer] = response.data;
                    }
                });
            } else toastr.error("Error");
        }).fail(function(jqXHR, textStatus, errorThrown) {
            toastr.error("error");
            controls.log(jqXHR.responseJSON.message);
            $('#useForm').modal('hide');
        }).always(function() {
            spinner.hide();
            controls.prop('disabled', false);
        });

    })
</script>
