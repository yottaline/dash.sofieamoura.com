<div class="modal fade" id="categoryForm" tabindex="-1" role="dialog" aria-labelledby="categoryFormLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" action="/categories/submit">
                    @csrf
                    <input ng-if="updateCategory !== false" type="hidden" name="_method" value="put">
                    <input type="hidden" name="id" id="category_id" ng-value="list[updateCategory].category_id">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="categoryName">
                                    Category Name <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="name" required
                                    ng-value="list[updateCategory].category_name" id="categoryName" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="type">
                                    Category Type <b class="text-danger">&ast;</b></label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="default">--
                                        SELECT CATEGORY TYPE --</option>
                                    <option value="0">All</option>
                                    <option value="1">Babies</option>
                                    <option value="2">Kids</option>
                                    <option value="3">Teens</option>
                                    <option value="4">Adults</option>
                                    </option>
                                </select>
                            </div>
                        </div>


                        <div class="col-6">
                            <div class="mb-3">
                                <label for="gender">
                                    Category Gender <b class="text-danger">&ast;</b></label>
                                <select name="gender" id="gender" class="form-select" required>
                                    <option value="default">--
                                        SELECT CATEGORY GENDER --</option>
                                    <option value="2">Boy</option>
                                    <option value="1">Girl</option>
                                    <option value="0">Both</option>
                                    </option>
                                </select>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" name="visible"
                                    value="1" ng-checked="+list[updateCategory].category_visible" id="categoryV">
                                <label class="form-check-label" for="categoryV">Category Status</label>
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
    <script>
        $(function() {
            $('#categoryForm form').on('submit', function(e) {
                e.preventDefault();
                var form = $(this),
                    formData = new FormData(this),
                    action = form.attr('action'),
                    method = form.attr('method'),
                    controls = form.find('button, input');
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
                        $('#categoryForm').modal('hide');
                        scope.$apply(() => {
                            if (scope.updateCategory === false) {
                                scope.list.unshift(response
                                    .data);
                                scope.load();
                                categoyreClsForm()
                            } else {
                                scope.list[scope
                                    .updateCategory] = response.data;
                            }
                        });
                    } else toastr.error("Error");
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // error msg
                }).always(function() {
                    spinner.hide();
                    controls.prop('disabled', false);
                });
            });
        });

        function categoyreClsForm() {
            $('#category_id').val('');
            $('#categoryName').val('');
        }
    </script>
</div>
