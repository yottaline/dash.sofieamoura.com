<div class="modal fade" id="currencyForm" tabindex="-1" role="dialog" aria-labelledby="currencyFormLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" action="/currencies/submit">
                    @csrf
                    <input ng-if="updateCurrency !== false" type="hidden" name="_method" value="put">
                    <input type="hidden" name="currency_id" id="currency_id"
                        ng-value="list[updateCurrency].currency_id">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="currencyName">
                                    Currency Name</label>
                                <input type="text" class="form-control" name="name" required
                                    ng-value="list[updateCurrency].currency_name" id="currencyName" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="currencyCode">
                                    Currency Code<b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="code" required
                                    ng-value="list[updateCurrency].currency_code" id="currencyCode" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="currencysymbol">
                                    Currency symbol <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="symbol" required
                                    ng-value="list[updateCurrency].currency_symbol" id="currencysymbol" />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="active"
                                    value="1" ng-checked="+list[updateCurrency].currency_visible" id="currencyS">
                                <label class="form-check-label" for="currencyS">Currency Status</label>
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
    $('#currencyForm form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this),
            formData = new FormData(this),
            action = form.attr('action'),
            method = form.attr('method'),
            controls = form.find('button, input'),
            spinner = $('#currencyForm .loading-spinner');
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
                $('#currencyForm').modal('hide');
                scope.$apply(() => {
                    if (scope.updateCurrency === false) {
                        scope.list.unshift(response
                            .data);
                        scope.load();
                        categoyreClsForm()
                    } else {
                        scope.list[scope
                            .updateCurrency] = response.data;
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
        $('#currency_id').val('');
        $('#currencyName').val('');
        $('#currencyCode').val('');
        $('#currencysymbol').val('');
        $('#isoCode3').val('');
    }
</script>
