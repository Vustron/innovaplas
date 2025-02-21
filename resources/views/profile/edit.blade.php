@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@section('content')
    <div class="content mt-4">
        <div class="container">
            @include('layouts.alert')
            <div class="row">
                <div class="col-md-12 text-center">
                    <form class="col-md-12" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header">
                                <h5 class="title">{{ __('Edit Profile') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <h6 class="text-left mb-0">Personal Information</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input  type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ $profile->name ?? '' }}" required />
                                        </div>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="surname">Surname <span class="text-danger">*</span></label>
                                            <input  type="text" class="form-control" name="surname" id="surname" placeholder="Surname" value="{{ $profile->surname ?? '' }}" required />
                                        </div>
                                        @if ($errors->has('surname'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('surname') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                                            <input  type="text" class="form-control" name="contact_number" id="contact_number" placeholder="Contact Number" value="{{ $profile->contact_number ?? '' }}" required />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="email">Email</label>
                                            <input  type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ auth()->user()->email ?? '' }}" readonly />
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <h6 class="text-left">Address</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="region">Region <span class="text-danger">*</span></label>
                                            <select name="region" id="region" class="form-select" data-value="{{ $profile->region ?? '' }}" required>
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
                                            <select name="province" id="province" class="form-select" data-value="{{ $profile->province ?? '' }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="city">City <span class="text-danger">*</span></label>
                                            <select name="city" id="city" class="form-select" data-value="{{ $profile->city ?? '' }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group text-left mb-3">
                                            <label for="barangay">Barangay <span class="text-danger">*</span></label>
                                            <select name="barangay" id="barangay" class="form-select" data-value="{{ $profile->barangay ?? '' }}" required>
                                                <option value="">Select an option</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group text-left mb-3">
                                            <label for="street">Purok/Street/Subd. <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="street" id="street" placeholder="Street / Subd." value="{{ $profile->street ?? '' }}" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-info btn-round">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-center">
                    <form class="col-md-12" action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header">
                                <h5 class="title">{{ __('Change Password') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <label class="col-md-3 col-form-label">{{ __('Old Password') }}</label>
                                    <div class="col-md-9">
                                        <div class="form-group position-relative">
                                            <input type="password" name="old_password" class="form-control" placeholder="Old password" required>
                                            <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                                        </div>
                                        @if ($errors->has('old_password'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('old_password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-3 col-form-label">{{ __('New Password') }}</label>
                                    <div class="col-md-9">
                                        <div class="form-group position-relative">
                                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                                            <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                                        </div>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-3 col-form-label">{{ __('Password Confirmation') }}</label>
                                    <div class="col-md-9">
                                        <div class="form-group position-relative">
                                            <input type="password" name="password_confirmation" class="form-control" placeholder="Password Confirmation" required>
                                            <i class="fa fa-eye position-absolute top-50 translate-middle-y btn-password" style="right: 10px;"></i>
                                        </div>
                                        @if ($errors->has('password_confirmation'))
                                            <span class="invalid-feedback" style="display: block;" role="alert">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-info btn-round">{{ __('Save Changes') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const regions = {{ Js::from($regions) }};
        const provinces = {{ Js::from($provinces) }};
        const cities = {{ Js::from($cities) }};
        const barangays = {{ Js::from($barangays) }};

        $(document).ready(function () {
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