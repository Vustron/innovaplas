@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .swal-wide {
            width: 850px !important;
        }

        .swal-wide img {
            width: 100%;
            max-width: 100% !important;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        @include('layouts.alert')
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.orders.store') }}" method="POST" id="checkoutForm" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">
                                <div class="d-flex">
                                    <i class="nc-icon nc-basket me-3"></i>
                                    <div class="">
                                        <div class="">
                                            Create Order
                                        </div>
                                    </div>
                                </div>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <h6>Product Information</h6>
                                <div class="col-md-6 text-left">
                                    <div class="form-group text-left mb-3">
                                        <label for="product_id">Product <span class="text-danger">*</span></label>
                                        <select name="product_id" id="product_id" class="form-select" required>
                                            <option value="">Select an option</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 product-info d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="" class="w-100"  style="object-fit: cover;">
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Description</h6>
                                            <p class="description"></p>
                                            <h6>Available Quantity</h6>
                                            <p class="quantity"></p>
                                            <h6>Price</h6>
                                            <p class="price"></p>
                                            <div class="raw-mat-list d-none">
                                                <h6>Raw Materials</h6>
                                                <ul class="list-group">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <div class="form-group">
                                        <label for="size">Sizes <span class="text-danger">*</span></label>
                                        <select class="form-control js-select2" data-tags="true" id="size" name="size" placeholder="Size" required>
                                            <option value="">Select an option or Input preferred size...</option>
                                        </select>
                                        @if ($errors->has('size'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('size') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 is_customize-section d-none">
                                    <div class="col-md-6 text-left">
                                        <div class="form-group">
                                            <label for="thickness">Thickness <span class="text-danger">*</span></label>
                                            <select class="form-control" id="thickness" name="thickness" placeholder="Thickness">
                                                <option value="">Select an option</option>
                                            </select>
                                            @if ($errors->has('thickness'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('thickness') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="">
                                            <label for="design">Design (optional)</label>
                                            <input type="file" class="form-control" id="design" name="design" placeholder="Design (optional)" accept="image/*" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="note">Note (optional)</label>
                                            <textarea class="form-control" id="note" name="note" placeholder="Note (optional)">{{ old('note') }}</textarea>
                                            @if ($errors->has('note'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('note') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>Delivery Address</h6>
                                </div>
                                <div class="col-md-6 text-left">
                                    <div class="form-group text-left mb-3">
                                        <label for="region">Region <span class="text-danger">*</span></label>
                                        <select name="region" id="region" class="form-select" data-value="{{ old('region', auth()->user()->profile->region) }}" required>
                                            <option value="">Select an option</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region->region_code }}">{{ $region->region_name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('region'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('region') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 text-left">
                                    <div class="form-group text-left mb-3">
                                        <label for="province">Province <span class="text-danger">*</span></label>
                                        <select name="province" id="province" class="form-select" data-value="{{ old('province', auth()->user()->profile->province) }}" required>
                                            <option value="">Select an option</option>
                                        </select>
                                        @if ($errors->has('province'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('province') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 text-left">
                                    <div class="form-group text-left mb-3">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <select name="city" id="city" class="form-select" data-value="{{ old('city', auth()->user()->profile->city) }}" required>
                                            <option value="">Select an option</option>
                                        </select>
                                        @if ($errors->has('city'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('city') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 text-left">
                                    <div class="form-group text-left mb-3">
                                        <label for="barangay">Barangay <span class="text-danger">*</span></label>
                                        <select name="barangay" id="barangay" class="form-select" data-value="{{ old('barangay', auth()->user()->profile->barangay) }}" required>
                                            <option value="">Select an option</option>
                                        </select>
                                        @if ($errors->has('barangay'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('barangay') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group text-left mb-3">
                                        <label for="street">Purok/Street/Subd. <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="street" id="street" placeholder="Street / Subd." value="{{ old('street', auth()->user()->profile->street ?? '') }}" required />
                                        @if ($errors->has('street'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('street') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 mt-5">
                                    <div class="ms-auto w-25">
                                        <div class="payment-not-cash d-none">
                                            <div class="design-form mb-3">
                                                <label for="payment">Payment</label>
                                                <input type="file" class="form-control" id="payment" name="payment" placeholder="Payment" accept="image/*" />
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="payment_reference">Reference No.</label>
                                                <input type="text" class="form-control" id="payment_reference" name="payment_reference" placeholder="Enter Reference no." value="{{ old('payment_reference', $order->payment_reference ?? '') }}" />
                                                @if ($errors->has('payment_reference'))
                                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                                        <strong>{{ $errors->first('payment_reference') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="payment_type">Payment Type (Sent From) <span class="text-danger">*</span></label>
                                            <select name="payment_type" id="payment_type" class="form-control js-select2" style="width: 100%;" data-tags="true" data-dropdown-parent="#uploadPayment" required>
                                                <option value="">Select a Payment Type</option>
                                                <option value="Cash">Cash</option>
                                                <option value="G-Cash">G-Cash</option>
                                                <option value="BDO">BDO Unibank, Inc.</option>
                                                <option value="Metrobank">Metropolitan Bank and Trust Company (Metrobank)</option>
                                                <option value="BPI">Bank of the Philippine Islands (BPI)</option>
                                                <option value="PNB">Philippine National Bank (PNB)</option>
                                                <option value="Landbank">Land Bank of the Philippines (Landbank)</option>
                                                <option value="SecurityBank">Security Bank Corporation</option>
                                                <option value="Chinabank">China Banking Corporation (Chinabank)</option>
                                                <option value="UnionBank">Union Bank of the Philippines (UnionBank)</option>
                                                <option value="DBP">Development Bank of the Philippines (DBP)</option>
                                                <option value="RCBC">Rizal Commercial Banking Corporation (RCBC)</option>
                                                <option value="PSBank">Philippine Savings Bank (PSBank)</option>
                                                <option value="ChinaBankSavings">China Bank Savings</option>
                                                <option value="CitySavingsBank">City Savings Bank</option>
                                                <option value="Maybank">Maybank Philippines, Inc.</option>
                                                <option value="EastWestBank">EastWest Bank</option>
                                                <option value="BDONetworkBank">BDO Network Bank</option>
                                                <option value="FICO">First Isabela Cooperative Bank (FICO Bank)</option>
                                                <option value="CebuanaLhuillierBank">Cebuana Lhuillier Rural Bank, Inc.</option>
                                                <option value="RuralBankAngeles">Rural Bank of Angeles, Inc.</option>
                                                <option value="RuralBankSanLeonardo">Rural Bank of San Leonardo (N.E.) Inc.</option>
                                                <option value="Maya">Maya</option>
                                                <option value="UnionDigitalBank">UnionDigital Bank</option>
                                                <option value="GoTymeBank">GoTyme Bank</option>
                                                <option value="Tonik">Tonik</option>
                                                <option value="UNO">UNO Digital Bank</option>
                                                <option value="OFBank">OFBank</option>
                                            </select>
                                            @if ($errors->has('payment_type'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('payment_type') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2 justify-content-end align-items-end">
                                            <p class="mb-0"><i>Avialable Quantity:</i></p>
                                            <h5 class="mb-0 quantity"></h5>
                                        </div>
                                        <div class="form-group text-left mb-3">
                                            <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder ="Minimum of 300 pcs" value="{{ old('quantity', '300') }}" required />
                                            @if ($errors->has('quantity'))
                                                <span class="invalid-feedback" style="display: block;" role="alert">
                                                    <strong>{{ $errors->first('quantity') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="d-flex gap-3 align-items-center justify-content-end">
                                <h5 class="mb-0"><span class="fw-bold">Total: </span>₱<span class="total">0.00</span></h5>
                                <button type="submit" class="btn btn-primary" id="btn-checkout">Checkout</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#product_id').on('change', function () {
                var $this = $(this);
                var product_info = $('.product-info');
                    product_info.addClass('d-none');

                $('.is_customize-section').addClass('d-none');
                $('#size').html('<option value="">Select an option or Input preferred size...</option>');
                $('#thickness').html('<option value="">Select an option</option>').prop('required', false);
                $('#quantity').attr('max', "");

                if ($this.val()) {
                    $this.prop('disabled', true);
                    $.ajax({
                        url: {{ Js::from(route('admin.orders.get.product')) }},
                        method: "GET",
                        data: {
                            product_id: $this.val()
                        },
                        success: function (data) {
                            $('#quantity').attr('max', data.product.quantity);
                            product_info.find('img').attr('src', data.product.image_route);
                            product_info.find('.description').html(data.product.description);
                            product_info.find('.quantity').html(data.product.quantity.toLocaleString() + " pcs");
                            product_info.find('.price').html('₱ ' + data.product.price.toFixed(2));
                            product_info.find('.raw-mat-list').addClass('d-none');
                            product_info.find('.raw-mat-list').find('.list-group').html('');

                            var sizes = data.product.sizes ? JSON.parse(data.product.sizes) : [];
                            $.each(sizes, function (key, val) {
                                $('#size').append(`<option value="${val}">${val}</option>`);
                            });

                            if (data.product.is_customize) {
                                $.each(data.product.raw_materials, function (key, val) {
                                    product_info.find('.raw-mat-list').find('.list-group').append(`
                                        <li class="list-group-item mb-0">(${ val.count }) ${ val.material.name }</li>
                                    `);
                                });

                                var thickness = data.product.thickness ? JSON.parse(data.product.thickness) : [];
                                $.each(thickness, function (key, val) {
                                    $('#thickness').append(`<option value="${val}">${val}</option>`);
                                });
                                $('#thickness').prop('required', true);

                                $('.is_customize-section').removeClass('d-none');
                            }

                            $('#quantity').trigger('input');
                            product_info.removeClass('d-none');
                            $this.prop('disabled', false);
                        },
                        error: function (data) {
                            Swal.fire({
                                title: data.status ?? '400',
                                text: data.responseJSON.error ?? "Something went wrong!",
                                icon: 'error',
                                confirmButtonText: 'Confirm',
                            }).then((result) => {
                                if (result.value) {
                                    $($this.data('target')).submit();
                                }
                            });

                            $this.prop('disabled', false);
                        }
                    });
                }
            });
            
            $('#btn-checkout').on('click', function () {
                var quantity = $('#quantity')[0];
                if (quantity.value < 300) {
                    quantity.setCustomValidity('Order quantity must be at least 300.');
                } else {
                    quantity.setCustomValidity('');
                }
            });

            $('#quantity').on('input', function () {
                $(this)[0].setCustomValidity('');

                var price = {{ Js::from(number_format($product->price, 2)) }}
                var quantity = $(this).val();

                if (quantity && (parseFloat(quantity) > $(this).attr('max'))) {
                    quantity = 0;
                    $('#btn-checkout').prop('disabled', true);
                } else {
                    $('#btn-checkout').prop('disabled', false);
                }
                var total = parseFloat(price) * parseFloat(quantity ? quantity : 0);
                
                $('.card-footer .total').text(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#total').val(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            });

            $('#payment_type').on('change', function () {
                $('.payment-not-cash').addClass('d-none');
                $('#payment').prop('required', false);
                $('#payment_reference').prop('required', false);

                if ($(this).val() && $(this).val() !== 'Cash') {
                    $('.payment-not-cash').removeClass('d-none');
                    $('#payment').prop('required', true);
                    $('#payment_reference').prop('required', true);
                }
            });
        });
    </script>
    @include('components.address-js')
@endpush
