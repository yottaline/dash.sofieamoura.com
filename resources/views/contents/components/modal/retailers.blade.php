{{-- edit approved --}}
<div class="modal fade" id="editApproved" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog">
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
        <script>
            $(function() {
                $('#editApproved form').on('submit', function(e) {
                    e.preventDefault();
                    var form = $(this),
                        formData = new FormData(this),
                        action = form.attr('action'),
                        method = form.attr('method');

                    scope.$apply(() => scope.submitting = true);
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
                                scope.submitting = false;
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
                    });

                })
            });
        </script>
    </div>
</div>
