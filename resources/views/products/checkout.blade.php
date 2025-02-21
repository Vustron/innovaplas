@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@push('css')

@endpush

@section('content')
    <div class="content mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('user.product.checkout.store', $product->id) }}" method="post" id="checkoutForm" enctype="multipart/form-data">
                        @csrf
    
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0"><i class="nc-icon nc-basket me-3"></i>Checkout - <span class="fw-bold">{{ $product->name }}</span></h4>
                                <div class="text-left">Required Fields (<span class="text-danger">*</span>)</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="{{ Storage::url($product->file->path) }}" alt="{{ $product->file->file_name }}" class="w-100"  style="object-fit: cover;">
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Description</h6>
                                        <p>{{ $product->description }}</p>
                                        {{-- @if (!$product->is_customize) --}}
                                        <h6>Available Quantity</h6>
                                        <p>{{ $product->quantity }}</p>
                                        {{-- @endif --}}
                                        <h6>Price</h6>
                                        <p>₱{{ number_format($product->price, 2) }}</p>
                                        @if ($product->is_customize)
                                            <h6>Raw Materials</h6>
                                            <ul class="list-group">
                                                @foreach ($product->raw_materials as $material)
                                                    <li class="list-group-item mb-0">({{ $material->count }}) {{ $material->material->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
    
                                @if ($product->is_customize)
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="thickness">Thickness <span class="text-danger">*</span></label>
                                                <select class="form-control" id="thickness" name="thickness" placeholder="Thickness" required>
                                                    <option value="">Select an option</option>
                                                    <option value="20+8 x 28 w/ hole">20+8 x 28 w/ hole</option>
                                                    <option value="20+8 x 28 w/out hole">20+8 x 28 w/out hole</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="size">Sizes <span class="text-danger">*</span></label>
                                                <select class="form-control" id="size" name="size" placeholder="Size" required>
                                                    <option value="">Select an option</option>
                                                    <option value="Tiny">Tiny</option>
                                                    <option value="Small">Small</option>
                                                    <option value="Medium">Medium</option>
                                                    <option value="Large">Large</option>
                                                    <option value="XL">XL</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="">
                                                <label for="design">Design (optional)</label>
                                                <input type="file" class="form-control" id="design" name="design" placeholder="Design (optional)" />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="note">Note</label>
                                                <textarea class="form-control" id="note" name="note" placeholder="Note"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif
    
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6>Delivery Address</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="region">Region <span class="text-danger">*</span></label>
                                            <select name="region" id="region" class="form-select" data-value="{{ auth()->user()->profile->region }}" required>
                                                <option value="">Select an option</option>
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->region_code }}">{{ $region->region_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="province">Province <span class="text-danger">*</span></label>
                                            <select name="province" id="province" class="form-select" data-value="{{ auth()->user()->profile->province }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="city">City <span class="text-danger">*</span></label>
                                            <select name="city" id="city" class="form-select" data-value="{{ auth()->user()->profile->city }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="barangay">Barangay <span class="text-danger">*</span></label>
                                            <select name="barangay" id="barangay" class="form-select" data-value="{{ auth()->user()->profile->barangay }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-left mb-3">
                                            <label for="street">Purok/Street/Subd. <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="street" id="street" placeholder="Street / Subd." value="{{ auth()->user()->profile->street ?? '' }}" required />
                                        </div>
                                    </div>
                                    <div class="col-12 mt-5">
                                        <div class="ms-auto w-25">
                                            <div class="d-flex gap-2 justify-content-end align-items-end">
                                                <p class="mb-0"><i>Avialable Quantity:</i></p>
                                                <h5 class="mb-0">{{ $product->quantity }}</h5>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder ="Minimum of 300 pcs" value="300" max="{{ $product->quantity }}" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <hr>
                                <div class="d-flex gap-3 align-items-center justify-content-end">
                                    <h5 class="mb-0"><span class="fw-bold">Total: </span>₱<span class="total">{{ number_format($product->price * 300, 2) }}</span></h5>
                                    <button type="submit" class="btn btn-primary" id="btn-checkout">Checkout</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('products.modal')
@endsection

@push('scripts')
    <script>
        const regions = {{ Js::from($regions) }};
        const provinces = {{ Js::from($provinces) }};
        const cities = {{ Js::from($cities) }};
        const barangays = {{ Js::from($barangays) }};

        $(document).ready(function () {
            $('#btn-checkout').on('click', function () {
                $('#quantity').prop('min', 300);
            });
            $('#quantity').on('input', function () {
                $(this).prop('min', '');

                var price = {{ Js::from(number_format($product->price, 2)) }}
                var quantity = $(this).val();
                
                if (quantity && quantity > $(this).attr('max')) {
                    quantity = 0;
                }
                var total = parseFloat(price) * parseFloat(quantity ? quantity : 0);
                
                $('.card-footer .total').text(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $('#total').val(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            });

            
            $('#region').on('change', function () {
                var region_code = $(this).val();

                $('#province').val('').trigger('change');
                $('#province').html('<option value="">Select an option</option>');
                if (region_code !== '') {
                    var options = provinces.filter((province) => {
                        return province.region_code == region_code;
                    });

                    $.each(options, function (key, option) {
                        $('#province').append(`<option value="${option.province_code}">${option.province_name}</option>`)
                    });
                }
            });
            if ($('#region').data('value')) {
                var region = regions.find((region) => {
                    return region.region_name == $("#region").data('value');
                });
                $('#region').val(region.region_code).trigger('change');
            }

            $('#province').on('change', function () {
                var province_code = $(this).val();

                $('#city').val('').trigger('change');
                $('#city').html('<option value="">Select an option</option>');
                if (province_code !== '') {
                    var options = cities.filter((city) => {
                        return city.province_code == province_code;
                    });
                    $.each(options, function (key, option) {
                        $('#city').append(`<option value="${option.city_code}">${option.city_name}</option>`)
                    });
                }
            });
            if ($('#province').data('value')) {
                var province = provinces.find((province) => {
                    return province.province_name == $("#province").data('value') && province.region_code == $('#region').val();
                });
                $('#province').val(province.province_code).trigger('change');
            }
            
            $('#city').on('change', function () {
                var city_code = $(this).val();

                $('#barangay').val('').trigger('change');
                $('#barangay').html('<option value="">Select an option</option>');
                if (city_code !== '') {
                    var options = barangays.filter((barangay) => {
                        return barangay.city_code == city_code;
                    });

                    $.each(options, function (key, option) {
                        $('#barangay').append(`<option value="${option.brgy_code}">${option.brgy_name}</option>`)
                    });
                }
            });
            if ($('#city').data('value')) {
                var city = cities.find((city) => {
                    return city.city_name == $("#city").data('value') && city.province_code == $('#province').val();
                });
                $('#city').val(city.city_code).trigger('change');
            }

            if ($('#barangay').data('value')) {
                var barangay = barangays.find((barangay) => {
                    return barangay.brgy_name == $("#barangay").data('value') && barangay.city_code == $('#city').val();
                });
                $('#barangay').val(barangay.brgy_code);
            }
        });
    </script>
@endpush