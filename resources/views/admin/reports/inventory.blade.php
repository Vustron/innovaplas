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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Raw Materials Inventory</h4>

                        <div class="d-flex gap-2 align-items-center">
                            <form action="{{ route('admin.reports.inventory.export') }}" target="_blank">
                                <input type="hidden" name="data" id="data">
                                <button class="btn btn-primary">Export</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="product-table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Batch Number</th>
                                    <th>Material Type</th>
                                    <th>Quantity</th>
                                    <th>Last Update</th>
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
            var table = $('#product-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: [
                    {data: 'batch_number', name: 'batch_number', orderable: false, searchable: false},
                    {data: 'name', name: 'name', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: true, searchable: false},
                    {data: 'updated_at', name: 'updated_at', orderable: false, searchable: false},
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
                    $('.dataTables_filter input').unbind();
                    $('.dataTables_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            table.search(this.value).draw();
                        }
                    });
                },
            });

            table.on('draw', function () {
                var data = table.rows().data().toArray();
                $('#data').val(JSON.stringify(data));
            });
        });
    </script>
@endpush