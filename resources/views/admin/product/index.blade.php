@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="content">
        @include('layouts.alert')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Products</h4>

                        <div class="d-flex gap-2 align-items-center">
                            <div class="d-flex gap-2">
                                <select id="sales_type" class="form-select">
                                    <option value="">Select an Option</option>
                                    <option value="generic">Generic Product</option>
                                    <option value="customized">Customized</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="product-table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Design</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Customize</th>
                                    <th>Price</th>
                                    <th style="width: 190px">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.product.modal', ['materials' => $materials])
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        const materials = {{ Js::from($materials) }};

        $(document).ready(function () {
            var table = $('#product-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: window.location.href,
                    data: function (d) {
                        return $.extend({}, d, {
                            sales_type: $('#sales_type').val()
                        });
                    }
                },
                columns: [
                    {data: 'design', name: 'design', orderable: false, searchable: false},
                    {data: 'name', name: 'name', orderable: true, searchable: false},
                    {data: 'quantity', name: 'quantity', orderable: true, searchable: false},
                    {data: 'customize', name: 'customize', orderable: false, searchable: false},
                    {data: 'price', name: 'price', orderable: false, searchable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false, width: 300}
                ],
                order: [[ 2, "asc" ]],
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

                    const fragment = window.location.hash;
                    if (fragment) {
                        $('.btn-edit' + fragment).trigger('click');
                    }
                },
            });

            $('#sales_type').on('change', function () {
                table.draw();
            });

            table.on('click', '.btn-edit', function (e) {
                let data = table.row(e.target.closest('tr')).data();
                console.log(data);
                var modal = $('#editProductModal');
                    modal.find('#is_customize-edit').prop('checked', data.is_customize);
                    modal.find('#editProduct').attr('action', data.edit_route);
                    modal.find('#name').val(data.name);
                    modal.find('#quantity').val(data.quantity);
                    modal.find('#price').val(data.price.replace("₱", ""));
                    modal.find('#description').val(data.description);
                    modal.find('.design-form').append(data.design);

                    if (data.materials.length) {
                        $.each(data.materials, function (key, value) {
                            var material = materials.find((item) => value.raw_material_id == item.id);
                            if (key == 0) {
                                var item = modal.find('.materials-list .material-item');
                                    item.find('.form-select').val(value.raw_material_id);
                                    item.find('.form-control').val(value.count);
                                if (material) {
                                    item.find('.material_quantity').text(material.quantity);
                                }
                            } else {
                                var item = modal.find('.materials-list .material-item:last-child').clone();
                                    item.find('.form-select').val(value.raw_material_id);
                                    item.find('.form-control').val(value.count);
                                if (material) {
                                    item.find('.material_quantity').text(material.quantity);
                                }

                                modal.find('.materials-list').append(item);
                                modal.find('.btn-remove-material').removeClass('d-none');
                            }
                        });
                        modal.find('.materials-group').removeClass('d-none');
                        modal.find('.materials-group').find('.form-control, .form-select').prop('required', true);
                        modal.find('.product-quantity').addClass('d-none');
                        modal.find('.product-quantity').find('.form-control').prop('required', false).prop('min', 0);
                    }
                    
                    modal.modal('show');
            });

            $('body').on('click', '.btn-remove', function (e) {
                e.preventDefault();
                var $this = $(this);
                Swal.fire({
                    title: '',
                    text: 'Are you sure you want to remove this product?',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.value) {
                        $($this.data('target')).submit();
                    }
                });
            });

            $('.btn-add-material').on('click', function () {
                var form = $(this).closest('form');
                var list = form.find('.materials-list');
                var item = list.find('.material-item:last-child').clone();
                    item.find('.form-control').val('');
                    item.find('.form-select').val('');
                    item.find('.material_quantity').text(0);
                
                list.append(item);

                form.find('.btn-remove-material').removeClass('d-none');
            });

            $('.btn-remove-material').on('click', function () {
                if ($('.materials-list .material-item').length == 1) {
                    return;
                }

                $('.materials-list .material-item:last-child').remove();
                if ($('.materials-list .material-item').length == 1) {
                    $(this).addClass('d-none');
                }
            });

            $('#editProductModal, #addProductModal').on('hidden.bs.modal', function () {
                $(this).find('.form-control').val('');
                $(this).find('#is_customize-add').prop('checked', false);
                $(this).find('#is_customize-edit').prop('checked', false);
                $(this).find('.design-form img').remove();
                $(this).find('.materials-group').addClass('d-none');
                $(this).find('.product-quantity').removeClass('d-none');
                $(this).find('.product-quantity').find('.form-control').prop('required', true).prop('min', 1);
                var list = $(this).find('.materials-list');
                    list.find('.material-item:not(:first-child)').remove();
                    list.find('.material-item').find('.form-select, .form-control').val('').prop('required', false);
                    list.find('.material-item').find('.material_quantity').text(0);
                $(this).find('.btn-remove-material').addClass('d-none');
            });

            $('#is_customize-add, #is_customize-edit').on('change', function () {
                if ($(this).prop('checked')) {
                    $(this).closest('form').find('.materials-group').removeClass('d-none');
                    $(this).closest('form').find('.materials-group').find('.form-control, .form-select').prop('required', true);
                    $(this).closest('form').find('.product-quantity').addClass('d-none');
                    $(this).closest('form').find('.product-quantity').find('.form-control').prop('required', false).prop('min', 0);
                } else {
                    $(this).closest('form').find('.materials-group').addClass('d-none');
                    $(this).closest('form').find('.materials-group').find('.form-control, .form-select').prop('required', false);
                    $(this).closest('form').find('.product-quantity').removeClass('d-none');
                    $(this).closest('form').find('.product-quantity').find('.form-control').prop('required', true).prop('min', 1);
                }
            });

            $(document).on('change', '.material_type_select', function () {
                var $this = $(this);
                
                if ($this.val() !== '') {
                    var material = materials.find((item) => $this.val() == item.id);
                    if (material) {
                        $this.closest('.material-item').find('.material_quantity').text(material.quantity);
                        return false;
                    }
                }

                $this.closest('.material-item').find('.material_quantity').text(0);
            });
        });
    </script>
@endpush