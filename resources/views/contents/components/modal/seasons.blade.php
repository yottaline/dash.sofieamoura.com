<div class="modal fade" id="seasonForm" tabindex="-1" role="dialog" aria-labelledby="seasonFormLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" id="seasonF" action="/seasons/submit">
                    @csrf
                    <input ng-if="updateSeason !== false" type="hidden" name="_method" value="put">
                    <input type="hidden" name="id" id="season_id" ng-value="list[updateSeason].season_id">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="seasonName">
                                    Season Name <b class="text-danger">&ast;</b></label>
                                <input type="text" class="form-control" name="name"
                                    ng-value="list[updateSeason].season_name" id="seasonName" />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="AdvancePayment">
                                    Season Advance Payment </label>
                                <input type="text" class="form-control" name="adv_payment" max="100"
                                    ng-value="list[updateSeason].season_adv_payment" id="AdvancePayment" />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description">
                                    Season Advance Description <b class="text-danger">&ast;</b></label>
                                <textarea class="form-control" name="description" id="description" cols="30" rows="7"><%list[updateSeason].season_adv_context%></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="inStock">
                                    Season Delivery details for in-stock <b class="text-danger">&ast;</b></label>
                                <textarea class="form-control" name="in_stock" id="inStock" cols="30" rows="5"><%list[updateSeason].season_delivery_1%></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="perOrder">
                                    Season Delivery details for pre-orders <b class="text-danger">&ast;</b></label>
                                <textarea class="form-control" name="per_order" id="perOrder" cols="30" rows="5"><%list[updateSeason].season_delivery_2%></textarea>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label>Season Start<b class="text-danger">&ast;</b></label>
                                <input id="seasonStart" type="text" class="form-control text-center" name="start"
                                    maxlength="10" ng-value="list[updateSeason].season_start" />
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label>Season End<b class="text-danger">&ast;</b></label>
                                <input id="seasonEnd" type="text" class="form-control text-center" name="end"
                                    maxlength="10" ng-value="list[updateSeason].season_end" />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="Lookbook">
                                    Season Lookbook</label>
                                <input type="text" class="form-control" name="Lookbook"
                                    ng-value="list[updateSeason].season_lookbook" id="Lookbook" />
                            </div>
                        </div>


                        <div class="col-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" name="current"
                                    value="1" ng-checked="+list[updateSeason].season_current" id="seasonC">
                                <label class="form-check-label" for="seasonC">Season Current</label>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" name="visible"
                                    value="1" ng-checked="+list[updateSeason].season_visible" id="seasonS">
                                <label class="form-check-label" for="seasonS">Category Status</label>
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
                    $('#seasonF').on('submit', e => e.preventDefault()).validate({
                        rules: {
                            name: {
                                required: true
                            },
                            adv_payment: {
                                digits: true,
                            },
                            description: {
                                required: true,
                            },
                            start: {
                                required: true,
                            },
                            end: {
                                required: true,
                            },
                            in_stock: {
                                required: true,
                            },
                            per_order: {
                                required: true,
                            },
                            Lookbook: {
                                required: true
                            },
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
                                    $('#seasonForm').modal('hide');
                                    scope.$apply(() => {
                                        if (scope.updateSeason === false) {
                                            scope.list.unshift(response
                                                .data);
                                            scope.load();
                                            categoyreClsForm()
                                        } else {
                                            scope.list[scope
                                                .updateSeason] = response.data;
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
                        $('#season_id').val('');
                        $('#seasonName').val('');
                        $('#AdvancePayment').val('');
                        $('#description').val('');
                        $('#perOrder').val('');
                        $('#inStock').val('');
                        $('#seasonStart').val('');
                        $('#seasonEnd').val('');
                        $('#Lookbook').val('');
                    }

                    $("#seasonStart").datetimepicker($.extend({}, dtp_opt, {
                        showTodayButton: false,
                        format: "YYYY-MM-DD",
                    }));

                    $("#seasonEnd").datetimepicker($.extend({}, dtp_opt, {
                        showTodayButton: false,
                        format: "YYYY-MM-DD",
                    }));
                </script>
            </div>
        </div>
    </div>
</div>
