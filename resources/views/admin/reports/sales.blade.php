@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.css') }}">
@endpush

@section('content')
    <div class="content">
        @include('layouts.alert')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Sales</h4>
                        
                        <div class="d-flex gap-2 align-items-center">
                            <div class="d-flex gap-2">
                                <select id="sales_type" class="form-select">
                                    <option value="">Select an Option</option>
                                    <option value="generic">Generic Product</option>
                                    <option value="customized">Customized</option>
                                </select>
                                <input type="text" id="start" class="form-control" placeholder="Start Date">
                                <input type="text" id="end" class="form-control" placeholder="End Date">
                            </div>
                            <form action="{{ route('admin.reports.sales.export') }}" target="_blank">
                                <input type="hidden" name="data" id="data">
                                <button class="btn btn-primary">Export</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="report-table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Thickness</th>
                                    <th>Size</th>
                                    <th>Customer</th>
                                    <th>Ordered At</th>
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
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.js') }}"></script>

    <script>
        $(document).ready(function () {
            var table = $('#report-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: window.location.href,
                    data: function (d) {
                        return $.extend({}, d, {
                            start: $('#start').val(),
                            end: $('#end').val(),
                            sales_type: $('#sales_type').val()
                        });
                    }
                },
                columns: [
                    {data: 'product_name', name: 'product_name', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: true, searchable: false},
                    {data: 'total_price', name: 'total_price', orderable: true, searchable: false},
                    {data: 'thickness', name: 'thickness', orderable: false, searchable: false},
                    {data: 'size', name: 'size', orderable: false, searchable: false},
                    {data: 'customer', name: 'customer', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at', orderable: false, searchable: false},
                ],
                order: [[ 0, "asc" ]],
                orderCellsTop: true,
                scrollY: 700,
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                language: {
                infoFiltered: ""
                },
                initComplete: function() {
                    console.log('test');
                    $('.dataTables_filter input').unbind();
                    $('.dataTables_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            table.search(this.value).draw();
                        }
                    });
                    
                    $('#start, #end').bind('change', function () {
                    });
                },
            });

            $('#sales_type').on('change', function () {
                table.draw();
            });

            var startDatePicker = $("#start");
            startDatePicker.datepicker({
                onSelect: function(selectedDate) {
                    endDatePicker.datepicker("option", "minDate", selectedDate);
                    table.draw();
                }
            });

            var endDatePicker = $("#end");
            endDatePicker.datepicker({
                onSelect: function(selectedDate) {
                    startDatePicker.datepicker("option", "maxDate", selectedDate);
                    table.draw();
                }
            });

            table.on('draw', function () {
                var data = table.rows().data().toArray();
                $('#data').val(JSON.stringify(data));
            });
        });
    </script>
@endpush