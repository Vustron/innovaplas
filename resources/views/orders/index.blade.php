@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12 d-flex align-items-center justify-content-center bg-primary" style="height: 200px">
                    <div class="text-center">
                        <h3 class="text-white mb-2">My Orders</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mb-4 justify-content-md-end">
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-select" id="order-filter">
                            <option value="*">Show All</option>
                            @foreach ($statuses as $status)
                                @php
                                    $slug = strtolower(str_replace(' ', '-', $status->name));
                                @endphp
                                <option value=".{{ $slug }}">
                                    {{ $status->name }} 
                                    {!! !empty($orders_count[str_replace('-', '_', $slug)]) ? "(".$orders_count[str_replace('-', '_', $slug)].")" : '' !!}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row order-list">
                @foreach ($orders as $order)
                    <div class="col-lg-4 col-md-6 order-item {{ strtolower(str_replace(' ', '-', $order->status)) }}">
                        <a href="{{ route('user.order.show', $order->id) }}" class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">
                                    {{ $order->product_name }} <span class="fw-bold fs-6">({{ $order->status }})</span>
                                </h4>
                                <hr>
                            </div>
                            <div class="card-body">
                                <div class="image rounded">
                                    <img src="{{ Storage::url($order->file_path) }}" alt="{{ $order->product_name }}" class="w-100" style="height: 200px; object-fit: cover; transition: all 0.5s ease;">
                                </div>
                                <p class="my-3" 
                                    style="display: -webkit-box;
                                           max-width: 100%;
                                           -webkit-line-clamp: 3;
                                           -webkit-box-orient: vertical;
                                           overflow: hidden;
                                        ">{{ $order->product_description }}</p>
                                <hr>
                                <div class="text-center mb-2">
                                    <small class="text-primary fw-bold">View Details</small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/isotope-3.0.6/isotope.pkgd.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var $list = $('.order-list').isotope({
                // options
                itemSelector: '.order-item',
                masonry: true
            });

            $('#order-filter').on('change', function () {
                var filterValue = $(this).val();
                $list.isotope({ filter: filterValue });
            });
        });
    </script>
@endpush