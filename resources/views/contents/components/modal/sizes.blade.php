<div class="modal fade" id="sizeForm" tabindex="-1" role="dialog" aria-labelledby="sizeFormLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" id="sizeF" action="/sizes/submit">
                    @csrf
                    <input ng-if="updateSize !== false" type="hidden" name="_method" value="put">
                    <input type="hidden" name="id" id="size_id" ng-value="list[updateSize].size_id">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="sizeName">
                                    Size Name <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="name"
                                    ng-value="list[updateSize].size_name" id="sizeName" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="sizeOrder">
                                    Size Order </label>
                                <input type="text" class="form-control" name="order" max="100"
                                    ng-value="list[updateSize].size_order" id="sizeOrder" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" name="visible"
                                    value="1" ng-checked="+list[updateSize].size_visible" id="sizeS">
                                <label class="form-check-label" for="sizeS">Size Status</label>
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
                    $('#sizeF').on('submit', e => e.preventDefault()).validate({
                        rules: {
                            order: {
                                digits: true,
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
                                    $('#sizeForm').modal('hide');
                                    scope.$apply(() => {
                                        if (scope.updateSize === false) {
                                            scope.list.unshift(response
                                                .data);
                                            categoyreClsForm()
                                        } else {
                                            scope.list[scope
                                                .updateSize] = response.data;
                                        }
                                    });
                                } else toastr.error(response.message);
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                toastr.error("error");
                            }).always(function() {
                                $(form).find('button').prop('disabled', false);
                            });
                        }
                    });

                    function categoyreClsForm() {
                        $('#size_id').val('');
                        $('#sizeName').val('');
                        $('#sizeOrder').val('');
                    }
                </script>
            </div>
        </div>
    </div>
</div>
