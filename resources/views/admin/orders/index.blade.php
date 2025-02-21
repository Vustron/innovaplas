@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
@endpush

@section('content')
    <div class="content">
        @include('layouts.alert')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ !empty($status) ? ucwords(str_replace('-', ' ', $status)) : 'All' }} Orders</h4>
                    </div>
                    <div class="card-body">
                        <table class="table" id="order-table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Order Reference</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Customer Name</th>
                                    <th>Status</th>
                                    <th>Ordered At</th>
                                    <th style="width: 190px">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            var table = $('#order-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: [
                    {data: 'reference', name: 'reference', orderable: true, searchable: false},
                    {data: 'product_name', name: 'product_name', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: true, searchable: false},
                    {data: 'total', name: 'total', orderable: true, searchable: false},
                    {data: 'full_name', name: 'full_name', orderable: false, searchable: false},
                    {data: 'status', name: 'status', orderable: true, searchable: false},
                    {data: 'created_at', name: 'created_at', orderable: true, searchable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, width: 190}
                ],
                order: [[ 6 , "desc" ]],
                orderCellsTop: true,
                scrollY: 700,
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                language: {
                infoFiltered: ""
                },
                initComplete: function() {
                $('.dataTables_filter input').unbind();
                $('.dataTables_filter input').bind('keyup', function(e) {
                    if(e.keyCode == 13) {
                        table.search(this.value).draw();
                    }
                });
                },
            });
        });
    </script>
@endpush