@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .swal-wide {
            width:850px !important;
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
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title"><i class="nc-icon nc-basket me-3"></i>Order - <span class="fw-bold">{{ $order->product->name }} ({{ $order->status->name }})</span></h4>
                        <div>
                            @if ($order->status->name == "Pending")
                                <button type="button" class="btn btn-primary me-3 swal-button" data-title="Proceed to Payment?" data-icon="info" data-target="#toPayForm">Proceed to Pay</button>
                                <form action="{{ route('admin.order.change.status', ['status' => 'to-pay', 'id' => $order->id]) }}" id="toPayForm" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <button type="button" class="btn btn-danger swal-button" data-title="Rejection Message" data-icon="warning" data-input="textarea" data-target="#rejectForm">Reject</button>
                                <form action="{{ route('admin.order.change.status', ['status' => 'rejected', 'id' => $order->id]) }}" id="rejectForm" method="POST" class="d-none">
                                    @csrf
                                    <input type="hidden" name="rejection_message" id="rejection_message">
                                </form>
                            @elseif ($order->status->name == 'To Review Payment')
                                <button type="button" class="btn btn-primary me-3 swal-button" data-title="Approve Payment?" data-icon="info" data-target="#approvePayment">Approve Payment</button>
                                <form action="{{ route('admin.order.change.status', ['status' => 'on-process', 'id' => $order->id]) }}" id="approvePayment" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <button type="button" class="btn btn-danger swal-button" data-title="Rejection Message" data-icon="warning" data-input="textarea" data-target="#rejectForm">Reject</button>
                                <form action="{{ route('admin.order.change.status', ['status' => 'rejected', 'id' => $order->id]) }}" id="rejectForm" method="POST" class="d-none">
                                    @csrf
                                    <input type="hidden" name="rejection_message" id="rejection_message">
                                </form>
                            @elseif ($order->status->name == 'On Process')
                                <button type="button" class="btn btn-primary me-3 swal-button" data-title="Deliver Item?" data-icon="info" data-target="#toDeliver">Deliver Item</button>
                                <form action="{{ route('admin.order.change.status', ['status' => 'to-deliver', 'id' => $order->id]) }}" id="toDeliver" method="POST" class="d-none">
                                    @csrf
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{ Storage::url($order->product->file->path) }}" alt="{{ $order->product->file->file_name }}" class="w-100"  style="object-fit: cover;">
                            </div>
                            <div class="col-md-6">
                                <h6>Description</h6>
                                <p>{{ $order->product->description }}</p>
                                <h6>Price</h6>
                                <p>₱ {{ number_format($order->product->price, 2) }}</p>
                                @if ($order->product->is_customize)
                                    <h6>Raw Materials</h6>
                                    <ul class="list-group">
                                        @foreach ($order->product->raw_materials as $material)
                                            <li class="list-group-item mb-0">({{ $material->count }}) {{ $material->material->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h5><span class="fw-bold">Order Information</h5>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="d-flex gap-2 align-items-end">
                                    <h6 class="mb-0">Order Reference: </h6>
                                    <p class="mb-0">{{ $order->reference }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Delivery Address</h6>
                                <p>{{ $order->street .', '. $order->city .', '. $order->province .', '. $order->region }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Quantity</h6>
                                <p>{{ $order->quantity }}</p>
                            </div>
                            @if ($order->product->is_customize)
                                <div class="col-md-6">
                                    <h6>Thickness</h6>
                                    <p>{{ $order->thickness ?? '' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Size</h6>
                                    <p>{{ $order->size ?? '' }}</p>
                                </div>
                                @if (!empty($order->note))
                                    <div class="col-md-6">
                                        <h6>Note</h6>
                                        <p>{{ $order->note}}</p>
                                    </div>
                                @endif
                                @if (!empty($order->design))
                                    <div class="col-md-6">
                                        <h6>Custom Design</h6>
                                        <img src="{{ Storage::url($order->design->path) }}" alt="" style="max-width: 300px;">
                                    </div>
                                @endif
                            @endif
                        </div>
                        @if (!empty($order->payment_reference) || !empty($order->payment))
                            <hr>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5><span class="fw-bold">Payment Information</h5>
                                </div>
                                @if (!empty($order->payment_reference))
                                    <div class="col-md-12">
                                        <h6>Reference No.</h6>
                                        <p>{{ $order->payment_reference }}</p>
                                    </div>
                                @endif
                                @if (!empty($order->payment))
                                    <div class="col-md-12">
                                        <h6>Payment</h6>
                                        <a href="#" class="btn-image"><img src="{{ Storage::url($order->payment_file->path) }}" style="max-width: 300px;"></a>
                                    </div>
                                @endif
                                @if ($order->status->name == "Rejected")
                                    <div class="col-md-12">
                                        <h6>Rejection Message</h6>
                                        <p>{{ $order->rejection_message ?? '' }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <hr>
                        <div class="d-flex gap-3 align-items-center justify-content-end">
                            <h5 class="mb-0"><span class="fw-bold">Total: </span>₱<span class="total">{{ number_format($order->total, 2) }}</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.swal-button').on('click', function () {
                var $this = $(this);
                Swal.fire({
                    title: $this.data('title'),
                    icon: $this.data('icon'),
                    input: $this.data('input') || null,
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#rejection_message').val(result.value || '');
                        $($this.data('target')).submit();
                    }
                });
            });

            $('.btn-image').on('click', function () {
                var $this = $(this);
                Swal.fire({
                    html: $this.html(),
                    confirmButtonText: 'Close',
                    customClass: 'swal-wide'
                });
            });
        });
    </script>
@endpush