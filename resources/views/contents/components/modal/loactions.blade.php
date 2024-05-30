<div class="modal fade" id="locationForm" tabindex="-1" role="dialog" aria-labelledby="locationFormLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" action="/locations/submit">
                    @csrf
                    <input ng-if="updaetLocation !== false" type="hidden" name="_method" value="put">
                    <input type="hidden" name="location_id" id="location_id"
                        ng-value="list[updaetLocation].location_id">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="locationName">
                                    Location Name<b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="name" required
                                    ng-value="list[updaetLocation].location_name" id="locationName" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="locationCode">
                                    Location Code<b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="code" required
                                    ng-value="list[updaetLocation].location_code" id="locationCode" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="isoCode2">
                                    Location ISO code 2<b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="iso_code_2" required
                                    ng-value="list[updaetLocation].location_iso_2" id="isoCode2" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="isoCode3">
                                    Location ISO code 3<b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="iso_code_3" required
                                    ng-value="list[updaetLocation].location_iso_3" id="isoCode3" />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="active"
                                    value="1" ng-checked="+list[updaetLocation].location_visible" id="locationS">
                                <label class="form-check-label" for="locationS">Location Status</label>
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
    $('#locationForm form').on('submit', function(e) {
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
                $('#locationForm').modal('hide');
                scope.$apply(() => {
                    if (scope.updaetLocation === false) {
                        scope.list.unshift(response
                            .data);
                        scope.load();
                        categoyreClsForm()
                    } else {
                        scope.list[scope
                            .updaetLocation] = response.data;
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
        $('#location_id').val('');
        $('#locationName').val('');
        $('#locationCode').val('');
        $('#isoCode2').val('');
        $('#isoCode3').val('');
    }
</script>
