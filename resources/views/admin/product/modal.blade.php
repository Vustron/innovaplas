<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="addProductLabel">Add Product</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.product.store') }}" method="post" id="addProduct" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-3">
                        <div class="form-check">
                             <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" value="1" id="is_customize-add" name="is_customize" />
                                <span class="form-check-sign"></span>
                                Customizable
                            </label>
                        </div>
                    </div>
                    <div class="design-form mb-3">
                        <label for="design">Design</label>
                        <input type="file" class="form-control" id="design" name="design" placeholder="Design" accept="image/*" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Product Name" required />
                    </div>
                    <div class="form-group mb-3 product-quantity">
                        <label for="quantity">Product Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" min="1" placeholder="Product Quantity" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="price">Product Price (₱)</label>
                        <input type="number" step="0.01" class="form-control" name="price" id="price" min="1" placeholder="Product Price (₱)" required />
                    </div>
                    <div class="form-group mb-5">
                        <label for="description">Product Desciption</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Product Desciption" required></textarea>
                    </div>

                    <div class="materials-group d-none">
                        <h6>Raw Materials</h6>
                        <div class="materials-list">
                            <div class="row material-item mb-3">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="">
                                            <div class="float-end d-flex gap-2">
                                                <h6 class="mb-0">Stock: </h6>
                                                <span class="material_quantity">0</span>
                                            </div>
                                            <label for="materials_id[]">Material Type</label>
                                        </div>
                                        <select class="form-select material_type_select" name="materials_id[]" id="materials_id[]" placeholder="Material Type">
                                            <option value="">Select an option</option>
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="materials_count[]">Material per Product</label>
                                        <input type="number" class="form-control" name="materials_count[]" id="materials_count[]" min="1" placeholder="Material per Product" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-danger btn-remove-material d-none">Remove Material</button>
                            <button type="button" class="btn btn-add-material">Add Material</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="addProduct">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title mb-0" id="editProductLabel">Edit Product</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" id="editProduct" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <div class="form-check">
                             <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" value="1" id="is_customize-edit" name="is_customize" />
                                <span class="form-check-sign"></span>
                                Customizable
                            </label>
                        </div>
                    </div>
                    <div class="design-form mb-3">
                        <label for="design">Design (optional)</label>
                        <input type="file" class="form-control" id="design" name="design" placeholder="Design (optional)" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Product Name" required />
                    </div>
                    <div class="form-group mb-3 product-quantity">
                        <label for="quantity">Product Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" min="1" placeholder="Product Quantity" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="price">Product Price (₱)</label>
                        <input type="number" step="0.01" class="form-control" name="price" id="price" min="1" placeholder="Product Price (₱)" required />
                    </div>
                    <div class="form-group mb-3">
                        <label for="description">Product Desciption</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Product Desciption" required></textarea>
                    </div>

                    <div class="materials-group d-none">
                        <h6>Raw Materials</h6>
                        <div class="materials-list">
                            <div class="row material-item">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <div class="">
                                            <div class="float-end d-flex gap-2">
                                                <h6 class="mb-0">Stock: </h6>
                                                <span class="material_quantity">0</span>
                                            </div>
                                            <label for="materials_id[]">Material Type</label>
                                        </div>
                                        <select class="form-select" name="materials_id[]" id="materials_id[]" placeholder="Material Type">
                                            <option value="">Select an option</option>
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="materials_count[]">Material per Product</label>
                                        <input type="number" class="form-control" name="materials_count[]" id="materials_count[]" placeholder="Material Type" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-danger btn-remove-material d-none">Remove Material</button>
                            <button type="button" class="btn btn-add-material">Add Material</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="editProduct">Save changes</button>
            </div>
        </div>
    </div>
</div>