<div id="page-container" class="flex-grow-1">
    <div class="container-fluid py-4">
        <div class="row">
            <!-- products table -->
            <div class="col-12 col-lg-8">
                <?php $allowUpdate = ($info->order_status == 0 && in_array($user['type'], [20, 21])) || (in_array($info->order_status, [0, 2, 3, 4, 5]) && in_array($user['type'], [10, 11])); ?>

                <?php $totalQty = 0; ?>
                <?php foreach ($products as $product) : ?>
                <?php $total = $productQty = 0; ?>
                <div id="<?= "product-{$product['id']}" ?>" class="product-block" data-min="<?= $product['min'] ?>"
                    data-min-size="<?= $product['minsize'] ?>" data-max="<?= $product['max'] ?>">
                    <div class="row">
                        <div class="col-12 col-sm-4 col-md-3">
                            <a href="/shop/product/<?= $product['ref'] ?>" class="bg-img d-block"
                                style="background-image: url(/media/products/<?= "{$product['brand_id']}/thumbs/{$product['photo']}"
                                ?>)"></a>
                        </div>
                        <div class="col">
                            <div class="clearfix">
                                <h6 class="float-start"><?= $product['name'] ?> <span
                                        class="font-monospace">#<?= $product['code'] ?></span></h6>
                                <?php if ($info->order_status == 0 && in_array($user['type'], [20, 21])) : ?>
                                <a href="#remove" class="product-remove-btn d-block text-center my-1 float-end"
                                    data-id="<?= $product['id'] ?>"><small>REMOVE</small></a>
                                <?php endif ?>
                            </div>

                            <div class="alert alert-danger min-qty-alert d-none my-2 p-1 px-3" role="alert">
                                The minimum order qty for this product is <?= $product['min'] ?>
                            </div>

                            <div class="alert alert-danger size-qty-alert d-none my-2 p-1 px-3" role="alert">
                                The minimum qty for each size is <?= $product['minsize'] ?>
                            </div>

                            <div class="table-responsive">
                                <table class="sizes-table table">
                                    <thead>
                                        <tr>
                                            <td><small>Color</small></td>
                                            <td><small>Size</small></td>
                                            <td width="100"><small>WSP</small></td>
                                            <td width="100"><small>Qty</small></td>
                                            <!-- <td width="70"><small>disc.</small></td> -->
                                            <td width="120"><small>Total</small></td>
                                            <?php if ($allowUpdate) : ?>
                                            <td width="80"></td>
                                            <?php endif ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($product['sizes'] as $s) : ?>
                                        <?php
                                        $total += $s['total'];
                                        $productQty += $s['qty'];
                                        $totalQty += $s['qty'];
                                        ?>
                                        <tr id="<?= "size-{$s['id']}" ?>" class="size-block"
                                            data-id="<?= $s['id'] ?>">
                                            <td class="text-center"><?= $s['color'] ?></td>
                                            <td class="text-center"><?= $s['size'] ?></td>
                                            <td class="size-wsp font-monospace text-center">
                                                <?= number_format($s['wsp'], 2, '.', '') ?></td>
                                            <td class="font-monospace text-center">
                                                <?php if ($allowUpdate) : ?>
                                                <input class="size-qty" type="text" data-qty="<?= $s['qty'] ?>"
                                                    data-size="<?= $s['id'] ?>" data-product="<?= $product['id'] ?>"
                                                    value="<?= $s['qty'] ?>">
                                                <?php else : ?>
                                                <span class="size-qty"><?= $s['qty'] ?></span>
                                                <?php endif ?>
                                            </td>
                                            <!-- <td class="font-monospace text-center text-danger">
                                                        <?php if ($info->order_status == 0 && $user['type'] == 10) : ?>
                                                            <input class="size-disc" type="text" data-size="<?= $s['id'] ?>" value="<?= $s['discountPercent'] ?>">
                                                        <?php else : ?>
                                                            -<span class="size-disc" data-disc="<?= $s['discountPercent'] ?>"><?= number_format($s['discountAmount'], 2, '.', '') ?></span>
                                                        <?php endif ?>
                                                    </td> -->
                                            <td class="size-total font-monospace text-center">
                                                <?= number_format($s['total'], 2, '.', '') ?></td>
                                            <?php if ($allowUpdate) : ?>
                                            <td class="text-center"><a href="#remove" class="size-remove-btn"
                                                    data-product="<?= $product['id'] ?>"
                                                    data-size="<?= $s['id'] ?>"><small>REMOVE</small></a></td>
                                            <?php endif ?>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="text-center"><span
                                                    class="product-qty font-monospace"><?= $productQty ?></span></td>
                                            <td class="text-center"><span
                                                    class="product-total font-monospace"><?= number_format($total, 2, '.', '') ?></span>
                                            </td>

                                        </tr>
                                    </tfoot>
                                </table>
                                <!-- <div class="table-footer fw-bold font-monospace d-flex">
                                        <div class="me-auto">Qty <span class="product-qty"><?= $productQty ?></span></div>
                                        <div><?= $info->currency_code ?> <span class="product-total"><?= number_format($total, 2, '.', '') ?></span></div>
                                    </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>

            <!-- orders details -->
            <div class="col">
                <div>
                    <h5 class="m-0"><?= $info->supplier_biz ?></h5>
                    <h6><?= $info->season_name ?></h6>
                    <hr>
                    <?php if (in_array($user['type'], [10, 11])) : ?>
                    <h6><?= $info->retailer_company ?></h6>
                    <?php endif ?>

                    <h6 class="text-uppercase text-primary">
                        Order <span class="font-monospace">#<?= $info->order_code ?></span><br>
                        <small id="status-txt-elem" class="text-dark"><?= ORDER_STATUS[$info->order_status] ?></small>
                    </h6>

                    <h6><small class="font-monospace text-muted">created:
                            <?= substr($info->order_creation, 0, -3) ?></small></h6>
                    <?php if ($info->order_status > 1 && isset($info->order_submittime)) : ?>
                    <h6><small class="font-monospace text-muted">placed:
                            <?= substr($info->order_submittime, 0, -3) ?></small></h6>
                    <?php endif ?>
                    <h6><small
                            class="font-monospace text-uppercase text-success"><?= $info->order_type == 1 ? 'Immediate' : 'Pre-order' ?></small>
                    </h6>

                    <?php if ($info->order_status == 0 && in_array($user['type'], [20, 21])) : ?>
                    <!-- Billing address -->
                    <div class="form-group mb-3">
                        <label>Billing address<b class="required">&ast;</b></label>
                        <?php if (!empty($_billingAddresses)) : ?>
                        <select class="address-select form-control" data-target='#billing_details'>
                            <?php foreach ($_billingAddresses as $b_add) : ?>
                            <option value="<?= $b_add->address_id ?>"><?= $b_add->address_title ?></option>
                            <?php endforeach ?>
                        </select>
                        <div id="billing_details" class="mt-3">
                            <div class="location mb-1">
                                <li class="fas fa-globe-americas me-2 text-muted"></li>
                                <span><?= "{$_billingAddresses[0]->address_country} {$_billingAddresses[0]->address_province} {$_billingAddresses[0]->address_city}" ?></span>
                            </div>
                            <div class="contact mb-1">
                                <li class="fas fa-phone me-2 text-muted"></li><a
                                    href="tel:<?= $_billingAddresses[0]->address_contact ?>"
                                    class="font-monospace"><?= $_billingAddresses[0]->address_contact ?></a>
                            </div>
                            <div class="zip mb-1">
                                <li class="fas fa-mail-bulk me-2 text-muted"></li><span
                                    class="font-monospace"><?= $_billingAddresses[0]->address_zip ?></span>
                            </div>
                            <div class="line1 mb-1">
                                <li class="fas fa-map-marker-alt me-2 text-muted"></li>
                                <span><?= $_billingAddresses[0]->address_line1 ?></span>
                            </div>
                            <div class="line2 mb-1 d-none">
                                <li class="fas fa-map-marker-alt me-2 text-muted"></li>
                                <span><?= $_billingAddresses[0]->address_line2 ?></span>
                            </div>
                        </div>
                        <?php else : ?>
                        <a href="<?= site_url('retailer/addresses/') ?>" class="text-danger d-block">Click here to add a
                            billing address</a>
                        <?php endif ?>
                    </div>

                    <!-- Shipping address -->
                    <div class="form-group mb-3">
                        <label>Shipping address<b class="required">&ast;</b></label>
                        <?php if (!empty($_shippingAddresses)) : ?>
                        <select class="address-select form-control" data-target='#shipping_details'>
                            <?php foreach ($_shippingAddresses as $s_add) : ?>
                            <option value="<?= $s_add->address_id ?>"><?= $s_add->address_title ?></option>
                            <?php endforeach ?>
                        </select>
                        <div id="shipping_details" class="mt-3">
                            <div class="location mb-1">
                                <li class="fas fa-globe-americas me-2 text-muted"></li>
                                <span><?= "{$_shippingAddresses[0]->address_country} {$_shippingAddresses[0]->address_province} {$_shippingAddresses[0]->address_city}" ?></span>
                            </div>
                            <div class="contact mb-1">
                                <li class="fas fa-phone me-2 text-muted"></li><a
                                    href="tel:<?= $_shippingAddresses[0]->address_contact ?>"
                                    class="font-monospace"><?= $_shippingAddresses[0]->address_contact ?></a>
                            </div>
                            <div class="zip mb-1">
                                <li class="fas fa-mail-bulk me-2 text-muted"></li><span
                                    class="font-monospace"><?= $_shippingAddresses[0]->address_zip ?></span>
                            </div>
                            <div class="line1 mb-1">
                                <li class="fas fa-map-marker-alt me-2 text-muted"></li>
                                <span><?= $_shippingAddresses[0]->address_line1 ?></span>
                            </div>
                            <div class="line2 mb-1 d-none">
                                <li class="fas fa-map-marker-alt me-2 text-muted"></li>
                                <span><?= $_shippingAddresses[0]->address_line2 ?></span>
                            </div>
                        </div>
                        <?php else : ?>
                        <a href="<?= site_url('retailer/addresses/') ?>" class="text-danger d-block">Click here to add
                            a shipping address</a>
                        <?php endif ?>
                    </div>

                    <!-- Note -->
                    <div class="form-group mb-3">
                        <div class="font-bold mb-1">Notes</div>
                        <textarea id='order-note' class='form-control' roes='3' maxlength="255"><?= $info->order_note ?></textarea>
                    </div>
                    <?php elseif ($info->order_status > 1) : ?>
                    <!-- Billing Address -->
                    <div class="form-group mb-3">
                        <div class="font-bold mb-1">Billing Address</div>
                        <div class="font-light">
                            <div class="mb-1">
                                <li class="fas fa-globe-americas me-2 text-muted"></li>
                                <span><?= "{$billing_address->ordaddress_city} {$billing_address->ordaddress_province} {$billing_address->ordaddress_country}" ?></span>
                            </div>
                            <div class="mb-1">
                                <li class="fas fa-map-marker-alt me-2 text-muted"></li>
                                <span><?= $billing_address->ordaddress_line1 ?></span>
                            </div>
                            <?php if ($billing_address->ordaddress_line2) : ?>
                            <div class="mb-1">
                                <li class="fas fa-map-marker-alt me-2 text-muted"></li>
                                <span><?= $billing_address->ordaddress_line2 ?></span>
                            </div>
                            <?php endif ?>
                            <div class="mb-1">
                                <li class="fas fa-mail-bulk me-2 text-muted"></li>
                                <span><?= $billing_address->ordaddress_zip ?></span>
                            </div>
                            <div>
                                <li class="fas fa-phone me-2 text-muted"></li><a
                                    href="tel:<?= $billing_address->ordaddress_contact ?>"><?= $billing_address->ordaddress_contact ?></a>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="form-group mb-3">
                        <div class="font-bold mb-1">Shipping Address</div>
                        <div class="font-light">
                            <div class="mb-1">
                                <li class="fas fa-globe-americas me-2 text-muted"></li>
                                <span><?= "{$shipping_address->ordaddress_city} {$shipping_address->ordaddress_province} {$shipping_address->ordaddress_country}" ?></span>
                            </div>
                            <div class="mb-1">
                                <li class="fas fa-map-marker-alt me-2 text-muted"></li>
                                <span><?= $shipping_address->ordaddress_line1 ?></span>
                            </div>
                            <?php if ($shipping_address->ordaddress_line2) : ?>
                            <div class="mb-1">
                                <li class="fas fa-map-marker-alt me-2 text-muted"></li>
                                <span><?= $shipping_address->ordaddress_line2 ?></span>
                            </div>
                            <?php endif ?>
                            <div class="mb-1">
                                <li class="fas fa-mail-bulk me-2 text-muted"></li>
                                <span><?= $shipping_address->ordaddress_zip ?></span>
                            </div>
                            <div>
                                <li class="fas fa-phone me-2 text-muted"></li><a
                                    href="tel:<?= $shipping_address->ordaddress_contact ?>"><?= $shipping_address->ordaddress_contact ?></a>
                            </div>
                        </div>
                    </div>

                    <!-- Carrier choices -->
                    <?php if ($info->order_status == 3 && !empty($info->order_carrier_info)  && !$fstStage && in_array($user['type'], [10, 11])) : ?>
                    <?php $carriers = json_decode($info->order_carrier_info); ?>
                    <div class="form-group mb-3">
                        <div class="font-bold mb-1">Carrier<b class="required">&ast;</b><small id="carrier-loading"
                                class="text-muted ms-3 font-light d-none">Please wait...</small></div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="order_carrier" id="order_carrier1"
                                value="1" data-cost="<?= $carriers[0]->cost ?>">
                            <label class="form-check-label" for="order_carrier1">Cargo Shipping <span
                                    class="text-info font-monospace ms-2">EUR
                                    <?= number_format($carriers[0]->cost, 2, '.', '') ?></span></label>
                        </div>
                        <div class="ms-4 text-muted">
                            <p><?= "{$carriers[0]->name}: {$carriers[0]->info}" ?></p>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="order_carrier" id="order_carrier2"
                                value="2" data-cost="<?= $carriers[1]->cost ?>">
                            <label class="form-check-label" for="order_carrier2">Courier Shipping <span
                                    class="text-info font-monospace ms-2">EUR
                                    <?= number_format($carriers[1]->cost, 2, '.', '') ?></span></label>
                        </div>
                        <div class="ms-4 text-muted">
                            <p><?= "{$carriers[1]->name}: {$carriers[1]->info}" ?></p>
                        </div>
                    </div>
                    <script>
                        $(function() {
                            $('[name="order_carrier"]').on('change', function(e) {
                                var elem = $('[name="order_carrier"]:checked'),
                                    val = Number(elem.val()),
                                    cost = Number(elem.data('cost'));

                                $('[name="order_carrier"], #checkout-btn').prop('disabled', true);
                                $('#carrier-loading').removeClass('d-none');

                                $.post('<?= site_url('orders/carriersSubmit/') ?>', {
                                    order: order_id,
                                    type: val,
                                    cost: cost
                                }, function(response) {
                                    $('[name="order_carrier"], #checkout-btn').prop('disabled', false);
                                    $('#carrier-loading').addClass('d-none');
                                    if (!response.status) {
                                        var target = val === 1 ? 2 : 1;
                                        $('[name="order_carrier"]').prop('checked', false);
                                        $(`#order_carrier${target}`).prop('checked', true);
                                    }
                                }, 'json');
                            });
                            $('#order_carrier<?= $info->order_carrier ?>').prop('checked', true);
                        });
                    </script>
                    <?php elseif (in_array($info->order_status, [3, 5, 6])  && !$fstStage && !empty($info->order_carrier)) : ?>
                    <?php
                    $carriers = json_decode($info->order_carrier_info);
                    $carrierOption = $info->order_carrier - 1;
                    ?>
                    <div class="form-group mb-3">
                        <div class="font-bold mb-1"><?= $carriersTypes[$carrierOption] ?></div>
                        <p><?= "{$carriers[$carrierOption]->name}: {$carriers[$carrierOption]->info}" ?></p>
                    </div>
                    <?php endif ?>

                    <!-- Notes -->
                    <div class="form-group mb-3">
                        <div class="font-bold mb-1">Notes</div>
                        <p><?= $info->order_note ?></p>
                    </div>
                    <?php endif ?>
                    <hr>

                    <!-- Discount -->
                    <?php if ($orderDiscount > 0 || ($user['type'] == 10 && $info->order_status == 0)) : ?>
                    <!-- orderTotal -->
                    <div class="clearfix">
                        <h6 class="float-start m-0">Subtotal</h6>
                        <h6 class="font-monospace float-end m-0 font-bold"><?= $info->currency_code ?> <span
                                id="ordertotal"><?= number_format($orderTotal, 2, '.', '') ?></span></h6>
                    </div>
                    <hr>
                    <div class="clearfix text-muted">
                        <h6 class="float-start m-0 text-danger"
                            <?= $user['type'] == 10 ? 'style="line-height: 25px"' : '' ?>>Discount</h6>
                        <?php if ($user['type'] == 10) : ?>
                        <input id="total-disc-input" class="font-monospace text-center d-block float-end"
                            style="max-width: 75px" value="<?= $orderDiscount ?>">
                        <script>
                            $(function() {
                                $('#total-disc-input').on('change', function() {
                                    var input = $(this),
                                        val = input.val();
                                    if (isNaN(val) || val < 0 || val > 100) return;

                                    var discount = _total * val / 100;
                                    _subtotal = _total - discount;
                                    input.prop('disabled', true);
                                    $.post('<?= site_url('orders/set_totaldisc/') ?>', {
                                        order: order_id,
                                        val: val,
                                    }, function(response) {
                                        input.prop('disabled', false);
                                        if (response.status) {
                                            $('#totalDiscount').text(discount.toFixed(2));
                                            $('#subtotal').text(_subtotal.toFixed(2));
                                        } else swal.fire({
                                            icon: 'error',
                                            text: 'Error on submitting discount. Try again.'
                                        });
                                    }, 'json');
                                });
                            });
                        </script>
                        <?php else : ?>
                        <?php $totalDiscount = ($orderTotal * $orderDiscount) / 100; ?>
                        <h6 class="font-monospace float-end m-0 fw-bold text-danger"><?= $info->currency_code ?> -<span
                                id="totalDiscount"><?= number_format($totalDiscount, 2, '.', '') ?></span></h6>
                        <?php endif ?>
                    </div>
                    <hr>
                    <?php endif ?>

                    <!-- Shipping -->
                    <?php if (!empty($info->order_carrier)) : ?>
                    <div class="clearfix">
                        <h6 class="float-start m-0">Shipping</h6>
                        <h6 class="font-monospace float-end m-0 font-bold"><?= $info->currency_code ?>
                            <?= number_format($shippingCost, 2, '.', '') ?></h6>
                    </div>
                    <hr>
                    <?php endif ?>

                    <!-- Total -->
                    <div class="clearfix">
                        <h6 class="float-start m-0">Total</h6>
                        <h6 class="font-monospace float-end m-0 font-bold"><?= $info->currency_code ?> <span
                                id="subtotal"><?= number_format($subtotal + $shippingCost, 2, '.', '') ?></span></h6>
                    </div>
                    <hr>

                    <!-- Advance Payment -->
                    <?php if ($info->order_type == 2 && $info->order_status > 1 && !$skipAdv) : ?>
                    <div class="clearfix text-muted">
                        <h6 class="float-start m-0">Advance Payment</h6>
                        <h6 class="font-monospace float-end m-0 font-bold"><?= $info->currency_code ?>
                            <?= number_format($advPayment, 2, '.', '') ?></h6>
                    </div>
                    <hr>
                    <?php endif ?>

                    <!-- Order Qty -->
                    <h5 id="orderQty" class="fw-bold">Order Qty: <span
                            class="font-monospace"><?= $totalQty ?></span></h5>

                    <!-- Skip advance payment -->
                    <?php if ($info->order_type == 2 && $info->order_status == 0 && in_array($user['type'], [10, 11])) : ?>
                    <div class="checkbox checkbox-small checkbox-danger my-3">
                        <input id="skip_adv_opt" type="checkbox" value="1"
                            <?= $info->order_skip_adv ? 'checked' : '' ?>>
                        <label for="skip_adv_opt" class="text-danger">Skip advance payment</label>
                    </div>
                    <script>
                        $('#skip_adv_opt').on('change', function() {
                            var element = $(this),
                                isChecked = element.is(':checked');
                            element.prop('disabled', true);

                            $.post('<?= site_url('orders/skip_adv_toggle/') ?>', {
                                order: order_id,
                                val: isChecked ? 1 : 0,
                            }, function(response) {
                                element.prop('disabled', false);
                                if (!response.status) {
                                    Swal.fire({
                                        icon: 'error',
                                        text: response.error,
                                        timer: 5000
                                    });
                                    element.prop('checked', !isChecked);
                                }
                            }, 'json');
                        });
                    </script>
                    <?php endif ?>

                    <?php if ($info->order_type == 2 && $skipAdv && in_array($user['type'], [20, 21])) : ?>
                    <p class="fw-bold text-danger"><?= $info->season_adv_context ?></p>
                    <?php endif ?>

                    <?php if (strtotime($info->season_end) > time()) : ?>
                    <p><b>Delivery</b><br><?= ((array) $info)["season_delivery_{$info->order_type}"] ?></p>
                    <?php endif ?>

                    <div id="min-order-alert">
                        <p class="fw-bold text-danger">Minimum order amount
                            <?= number_format($info->supplier_minamount, 2) ?> <?= $info->currency_symbol ?></p>
                        <hr>
                    </div>

                    <!-- Review Order & Cancel Btns -->
                    <!-- in_array($info->order_status, [0, 4, 5]) ALLOW CLIENT TO PAY ONLINE -->
                    <?php if ($info->order_status == 0 && in_array($user['type'], [20, 21])) : ?>
                    <?php if (strtotime($info->season_end) < time()) : ?>
                    <div class="text-dark mb-4">
                        The production for this collection started already, by placing the order,
                        you acknowledge that your order will be fulfilled in accordance with availability,
                        for this reason we suggest that you don't include in your order matching sets
                        to avoid receiving a top without bottom or the opposite unless if you don't have a problem with
                        that.
                        <br>After we receive your order, we will process it and we will send you an order confirmation
                        with the delivery date.
                    </div>
                    <?php endif ?>

                    <div class="clearfix">
                        <?php if (in_array($info->order_status, [4, 5]) || (!empty($_billingAddresses) && !empty($_shippingAddresses))) : ?>
                        <button id="checkout-btn" class="btn btn-outline-success float-end">Review Order</button>
                        <?php else : ?>
                        <button class="btn btn-outline-success float-end" disabled>Review Order</button>
                        <?php endif ?>
                        <?php if ($info->order_status == 0) : ?>
                        <button id="cancel-btn" class="btn btn-outline-danger float-start">Cancel</button>
                        <?php endif ?>
                    </div>
                    <?php endif ?>

                    <?php if ($info->order_status > 1) : ?>
                    <!-- Payments -->
                    <?php if (!empty($payments)) : ?>
                    <?php
                    $payStText = ['PENDING', 'REJECTED', 'CONFIRMED'];
                    $payStIcon = ['clock text-info', 'times-circle text-danger', 'check-circle text-success'];
                    ?>
                    <h6 class="mt-5">Payments</h6>
                    <table class="table">
                        <tbody>
                            <?php foreach ($payments as $payment) : ?>
                            <tr>
                                <td width="18" class="px-1 payment-status"><i
                                        class="far fa-<?= $payStIcon[$payment->payment_status] ?>"
                                        title="<?= $payStText[$payment->payment_status] ?>"></i></td>
                                <td class="text-center font-monospace">
                                    <?php if ($payment->payment_status == 0 && in_array($user['type'], [10, 11])) : ?>
                                    <input class="payment-input text-center font-monospace"
                                        value="<?= $payment->payment_amount ?>">
                                    <?php else : ?>
                                    <?= $payment->payment_amount ?>
                                    <?php endif ?>
                                </td>
                                <td class="text-center font-monospace">
                                    <?= date('d-m-Y', strtotime($payment->payment_submit)) ?></td>
                                <td width="18" class="px-1"><a
                                        href="<?= base_url("media/payments/{$info->order_supplier}/{$payment->payment_photo}")
                                        ?>" target="_blank"><i class="far fa-file"></i></a></td>
                                <?php if (in_array($user['type'], [10, 11])) : ?>
                                <?php if ($payment->payment_status == 0) : ?>
                                <td width="56" class="px-1 text-center"><a href="#"
                                        class="text-success confirm-btn" data-id="<?= $payment->payment_id ?>"
                                        data-val="2">confirm</a></td>
                                <td width="44" class="px-1 text-center"><a href="#"
                                        class="text-danger reject-btn" data-id="<?= $payment->payment_id ?>"
                                        data-val="1">reject</a></td>
                                <?php else : ?>
                                <td width="56" class="text-center">-</td>
                                <td width="44" class="text-center">-</td>
                                <?php endif ?>
                                <?php endif ?>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <?php if (in_array($user['type'], [10, 11])) : ?>
                    <script>
                        $(function() {
                            $('a.confirm-btn, a.reject-btn').on('click', function(e) {
                                e.preventDefault();
                                paymentAction($(this));
                            });

                            function paymentAction(elem) {
                                var id = elem.data('id'),
                                    val = +elem.data('val'),
                                    tr = elem.parent().parent(),
                                    amount = Number(tr.find('.payment-input').val()),
                                    action = val === 1 ? 'REJECT' : 'CONFIRM';

                                Swal.fire({
                                    text: `Do you want to ${action} this payment? You will not be able to undo this action.`,
                                    showCancelButton: true,
                                    focusCancel: true,
                                    icon: 'warning',
                                }).then((result) => {
                                    if (result.value) {
                                        tr.find('a.confirm-btn, a.reject-btn').text('wait').off();

                                        $.post("<?= site_url('orders/paymentStatus/') ?>", {
                                            order_id: order_id,
                                            payment_id: id,
                                            payment_amount: amount,
                                            payment_status: val
                                        }, function(response) {
                                            if (response.status) {
                                                var html = val === 1 ?
                                                    '<i class="far fa-times-circle text-danger" title="REJECTED"></i>' :
                                                    '<i class="far fa-check-circle text-success" title="CONFIRMED"></i>';
                                                tr.find('.payment-status').html(html);
                                                tr.find('a.confirm-btn, a.reject-btn').each(function() {
                                                    $(this).parent().text('-');
                                                });
                                                tr.find('.payment-input').parent().text(amount.toFixed(2));
                                                current_status = 4;
                                                $('#status-txt-elem').text(order_status[4]);
                                                $('#status-control').val(4);
                                            } else {
                                                tr.find('a.confirm-btn').text('confirm');
                                                tr.find('a.reject-btn').text('reject');
                                                tr.find('a.confirm-btn, a.reject-btn').on('click', function(e) {
                                                    e.preventDefault();
                                                    paymentAction($(this));
                                                });
                                                Swal.fire({
                                                    icon: 'error',
                                                    text: "Error, please try again.",
                                                    timer: 5000
                                                });
                                            }
                                        }, 'json');
                                    }
                                });
                            }
                        });
                    </script>
                    <?php endif ?>
                    <?php endif ?>

                    <?php if (in_array($user['type'], [10, 11])) : ?>
                    <!-- Order status -->
                    <h6 class="mt-5">Status</h6>
                    <div class="input-group mb-3">
                        <select id="status-control" class="form-control" aria-label="Order Status"
                            aria-describedby="orderstatus-btn">
                            <?php for ($i = 0; $i < count(ORDER_STATUS); $i++) : ?>
                            <?php $selected = $info->order_status == $i ? 'selected' : ''; ?>
                            <option value="<?= $i ?>" <?= $selected ?>><?= ORDER_STATUS[$i] ?></option>
                            <?php endfor ?>
                        </select>
                        <button class="btn btn-outline-secondary" type="button" id="orderstatus-btn"><i
                                class="fas fa-arrow-right"></i></button>
                        <script>
                            $(function() {
                                $('#orderstatus-btn').on('click', function() {
                                    var statusBtn = $(this),
                                        statusElem = $('#status-control'),
                                        status = +statusElem.val();

                                    statusBtn.prop('disabled', true);
                                    $.post(url.order_update, {
                                        order: order_id,
                                        key: 'status',
                                        val: status
                                    }, function(response) {
                                        statusBtn.prop('disabled', false);
                                        if (response.status) {
                                            current_status = status;
                                            $('#status-txt-elem').text(order_status[status]);
                                        } else {
                                            statusElem.val(current_status);
                                            Swal.fire({
                                                icon: 'error',
                                                text: response.error,
                                                timer: 5000
                                            });
                                        }
                                    }, 'json');
                                });
                            });
                        </script>
                    </div>

                    <!-- Get proforma -->
                    <?php if ($info->order_status > 1) : ?>
                    <div>
                        <button id="proforma-btn" class="btn btn-outline-dark w-100">Get Proforma Invoice</button>
                        <script>
                            $('#proforma-btn').on('click', function() {
                                var btn = $(this);
                                btn.prop('disabled', true);
                                $.post('<?= site_url('orders/get_proforma/') ?>', {
                                    order: order_id
                                }, function(response) {
                                    btn.prop('disabled', false);
                                    if (response.status)
                                        Swal.fire({
                                            icon: 'success',
                                            text: 'Proforma invoice sent successfully.'
                                        });
                                    else
                                        Swal.fire({
                                            icon: 'error',
                                            text: response.error
                                        });
                                }, 'json');
                            });
                        </script>
                    </div>
                    <?php endif ?>
                    <!-- Carriers settings -->
                    <?php if ($info->order_status == 3) : ?>
                    <h6 class="mb-0 mt-5">Carriers Options</h6>
                    <small class="text-danger font-bold"><i class="fas fa-info-circle me-1"></i>Submit this options
                        after receiving the advance payment.</small>
                    <h6 class="text-muted mt-3">Cargo Shipping</h6>
                    <div class="row carrier-option">
                        <input type="hidden" name='type' value="1">
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-3">
                                <input type="text" name='name' class="form-control"
                                    placeholder="Carrier Name*"
                                    value="<?= isset($carriers) ? $carriers[0]->name : '' ?>" maxlength="45">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-3">
                                <input type="text" name='cost' class="form-control"
                                    placeholder="Shipping cost*"
                                    value="<?= isset($carriers) ? $carriers[0]->cost : '' ?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <textarea name='info' class="form-control" placeholder="Shipping info*" maxlength="500"><?= isset($carriers) ? $carriers[0]->info : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <h6 class="text-muted">Courier Shipping</h6>
                    <div class="row carrier-option">
                        <input type="hidden" name='type' value="2">
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-3">
                                <input type="text" name='name' class="form-control"
                                    placeholder="Carrier Name*"
                                    value="<?= isset($carriers) ? $carriers[1]->name : '' ?>" maxlength="45">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group mb-3">
                                <input type="text" name='cost' class="form-control"
                                    placeholder="Shipping cost*"
                                    value="<?= isset($carriers) ? $carriers[1]->cost : '' ?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <textarea name='info' class="form-control" placeholder="Shipping info*" maxlength="500"><?= isset($carriers) ? $carriers[1]->info : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="text-end"><button id="carrier-submit" type="button"
                            class="btn btn-outline-dark">Submit</button></div>
                    <script>
                        $('#carrier-submit').on('click', function() {
                            var validate = true,
                                carriers = [];

                            $('.carrier-option').each(function() {
                                var
                                    type = parseInt($(this).find('[name="type"]').val()),
                                    name = $.trim($(this).find('[name="name"]').val()),
                                    cost = parseFloat($(this).find('[name="cost"]').val()),
                                    info = $.trim($(this).find('[name="info"]').val());

                                if (name === "" || info === "" || !cost) {
                                    validate = false;
                                    return;
                                }

                                carriers.push({
                                    type: type,
                                    name: name,
                                    cost: cost,
                                    info: info
                                });
                            });

                            if (!validate) {
                                Swal.fire({
                                    icon: 'info',
                                    text: 'Please, fill all required carriers fields.'
                                });
                                return;
                            }

                            $.post('<?= site_url('orders/carriersSet/') ?>', {
                                order: order_id,
                                carriers: carriers, //JSON.stringify(carriers),
                                type: carriers[0].type,
                                cost: carriers[0].cost
                            }, function(response) {
                                if (response.status)
                                    Swal.fire({
                                        icon: 'success',
                                        text: 'The carriers information updated successfully.'
                                    });
                                else
                                    Swal.fire({
                                        icon: 'error',
                                        text: response.error
                                    });
                            }, 'json');
                        });
                    </script>
                    <?php endif ?>
                    <?php endif ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
