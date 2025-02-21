@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="content mt-4">
        <div class="container">
            @include('layouts.alert')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title"><i class="nc-icon nc-basket me-3"></i>Order - <span class="fw-bold">{{ $order->product->name }} ({{ $order->status->name }})</span></h4>
                            <div>
                                @if ($order->status->name == 'Pending')
                                    <button type="button" class="btn btn-danger me-3 swal-button" data-title="Cancel Order?" data-icon="warning" data-target="#cancel">Cancel Order</button>
                                    <form action="{{ route('user.order.cancel', $order->id) }}" id="cancel" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                @elseif ($order->status->name == 'To Pay')
                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadPayment">Upload Payment</a>
                                    <button type="button" class="btn btn-danger me-3 swal-button" data-title="Cancel Order?" data-icon="warning" data-target="#cancel">Cancel Order</button>
                                    <form action="{{ route('user.order.cancel', $order->id) }}" id="cancel" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                @elseif ($order->status->name == 'To Deliver')
                                    <button type="button" class="btn btn-primary me-3 swal-button" data-title="Receive Item?" data-icon="info" data-target="#receive">Receive Item</button>
                                    <form action="{{ route('user.order.receive', $order->id) }}" id="receive" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                @elseif ($order->status->name == 'Completed' && !in_array(auth()->user()->id, $order->product->feedbacks->pluck('user_id')->toArray()))
                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeedback">Rate</a>
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
                                    <p>₱{{ number_format($order->product->price, 2) }}</p>
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
                                @if ($order->status->name == "Rejected")
                                    <div class="col-md-12">
                                        <h6>Rejection Message</h6>
                                        <p>{{ $order->rejection_message ?? '' }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="d-flex gap-3 align-items-center justify-content-end">
                                <h5 class="mb-0"><span class="fw-bold">Total: </span>₱<span class="total">{{ number_format($order->total, 2) }}</span></h5>
                            </div>
                        </div>
                    </div>

                    @if ($feedback = $order->product->feedbacks->where('user_id', auth()->user()->id)->first())
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><li class="nc-icon nc-chat-33 me-3"></li><span class="fw-bold">Your Feedback</span></h4>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-sm-2">
                                        @if (!empty($feedback->file))
                                            <img src="{{ Storage::url($feedback->file->path) }}" alt="{{ $feedback->file->file_name }}" class="w-100"  style="object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <h6 class="mb-0">User</h6>
                                            <p class="mb-0">{{ $feedback->user->profile->name .' '. $feedback->user->profile->surname }}</p>
                                        </div>
                                        <div class="d-flex gap-3 align-items-center mb-3">
                                            <h6 class="mb-0">Rate</h6>
                                            <p class="mb-0">{{ $feedback->rate }}/10</p>
                                        </div>
                                        <h6>Message</h6>
                                        <p class="mb-0">{{ $feedback->message ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <h4 class="fw-bold">Products you might like</h4>
            <div class="row product-list">
                @foreach ($products as $product)
                    <div class="col-lg-4 col-md-6 product-item">
                        <a href="{{ route('product.show', $product->id) }}" class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">{{ $product->name }}</h4>
                                <span class="fw-bold">₱ {{ number_format($product->price, 2) }}</span>
                                <hr>
                            </div>
                            <div class="card-body">
                                <div class="image rounded">
                                    <img src="{{ Storage::url($product->file->path) }}" alt="{{ $product->file->file_name }}" class="w-100" style="height: 200px; object-fit: cover; transition: all 0.5s ease;">
                                </div>
                                <p class="mb-0 mt-3" 
                                    style="display: -webkit-box;
                                           max-width: 100%;
                                           -webkit-line-clamp: 3;
                                           -webkit-box-orient: vertical;
                                           overflow: hidden;
                                        ">{{ $product->description }}</p>
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

    @if ($order->status->name == 'To Pay')
        <div class="modal fade" id="uploadPayment" tabindex="-1" aria-labelledby="uploadPaymentLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mb-0" id="uploadPaymentLabel">
                            <span class="fw-bold">Total: </span>₱<span class="total">{{ number_format($order->total, 2) }}</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.order.upload.payment', $order->id) }}" method="POST" id="paymentForm" enctype="multipart/form-data">
                            @csrf
                            <div class="design-form mb-3">
                                <label for="payment">Payment</label>
                                <input type="file" class="form-control" id="payment" name="payment" placeholder="Payment" accept="image/*" required />
                            </div>
                            <div class="design-form mb-3">
                                <label for="payment_reference">Reference No.</label>
                                <input type="text" class="form-control" id="payment_reference" name="payment_reference" placeholder="Enter Reference no." required />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" form="paymentForm">Upload Payment</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($order->status->name == 'Completed' && !in_array(auth()->user()->id, $order->product->feedbacks->pluck('user_id')->toArray()))
        <div class="modal fade" id="addFeedback" tabindex="-1" aria-labelledby="addFeedbackLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mb-0" id="addFeedbackLabel">
                            <span class="fw-bold">Feedback</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.order.feedback', $order->id) }}" method="POST" id="feedbackForm" enctype="multipart/form-data">
                            @csrf

                            <div class="rating-container mb-3">
                                <div class="stars">
                                    <span class="star" data-value="1"></span>
                                    <span class="star" data-value="2"></span>
                                    <span class="star" data-value="3"></span>
                                    <span class="star" data-value="4"></span>
                                    <span class="star" data-value="5"></span>
                                </div>
                                <p>Rating: <span id="ratingValue">0</span> / 10</p>
                            </div>
                            
                            <div class="mb-3">
                                <label for="img">Image</label>
                                <input type="file" class="form-control" id="img" name="img" placeholder="Image" accept="image/*" required />
                            </div>
                            <input type="hidden" name="rate" id="rating-value" value="0">
                            <div class="mb-3">
                                <label for="message">Message</label>
                                <textarea class="form-control px-2" style="height: 250px; max-height: 100%;" id="message" name="message" placeholder="Message" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" form="feedbackForm">Add Feedback</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('plugins/isotope-3.0.6/isotope.pkgd.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var $list = $('.product-list').isotope({
                // options
                itemSelector: '.product-item',
                masonry: true
            });

            $('.swal-button').on('click', function () {
                var $this = $(this);
                Swal.fire({
                    title: $this.data('title'),
                    icon: $this.data('icon'),
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.value) {
                        $($this.data('target')).submit();
                    }
                });
            });
            
            let savedRating = 0;

            $(".star").on("mousemove", function (e) {
                const starIndex = parseInt($(this).data("value"));
                const isHalf = (e.pageX - $(this).offset().left) < ($(this).width() / 2);
                const hoverRating = isHalf ? starIndex - 0.5 : starIndex;

                // Call the function to highlight stars based on hover rating
                highlightStars(hoverRating);
            });

            $(".star").on("mouseleave", function () {
                // Revert to saved rating on mouse leave
                highlightStars(savedRating);
            });

            $(".star").on("click", function (e) {
                const starIndex = parseInt($(this).data("value"));
                const isHalf = (e.pageX - $(this).offset().left) < ($(this).width() / 2);
                savedRating = isHalf ? starIndex - 0.5 : starIndex;

                // Display saved rating and call the highlight function
                $("#ratingValue").text(savedRating * 2);
                $("#rating-value").val(savedRating * 2);
                highlightStars(savedRating);
            });

            function highlightStars(rating) {
                // Remove previous highlights
                $(".star").removeClass("active half-active");
                
                // Add new highlights based on the current rating
                $(".star").each(function (index) {
                    const starValue = index + 1;
                    if (starValue <= rating) {
                        $(this).addClass("active"); // Full star
                    } else if (starValue - 0.5 === rating) {
                        $(this).addClass("half-active"); // Half star
                    }
                });
            }
        });
    </script>
@endpush