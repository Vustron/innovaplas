<?php

namespace App\Http\Controllers\Admin;

use App\Models\File;
use App\Models\Product;
use App\Models\Feedback;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\ProductRawMaterial;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->search['value'];
            $sales_type = $request->sales_type;

            $products = Product::where(function ($query) use ($sales_type) {
                    if (!empty($sales_type)) {
                        $query->where('is_customize', $sales_type !== 'generic');
                    }    
                });
            $datatables = datatables()::of($products);

            $datatables->editColumn('price', function ($item) {
                return '₱' . number_format($item->price, 2);
            })->addColumn('edit_route', function ($item) {
                return route('admin.product.update', $item->id);
            })->addColumn('actions', function ($item) {
                $btn = '';

                $edit_id = "btn-edit-" . $item->id;
                $btn .= "<a href='#' class='text-muted me-3 btn-edit' id='$edit_id'><i class='fa-regular fa-pen-to-square me-2'></i>Edit</a>";

                $delete_route = route('admin.product.delete', $item->id);
                $btn .= "<a href='#' class='text-muted btn-remove me-3 ' data-target='#product-$item->id'><i class='fa-solid fa-trash me-2'></i>Remove</a>
                            <form action='$delete_route' method='post' class='d-none' id='product-$item->id'>". csrf_field() .' '. method_field('delete') ."</form>";
                            
                $feedbacks_route = route('admin.product.feedbacks', $item->id);
                $btn .= "<a href='$feedbacks_route' class='text-muted'><i class='fa-regular fa-comments me-2'></i>Feedback</a>";

                return $btn;
            })->addColumn('materials', function ($item) {
                return ProductRawMaterial::where('product_id', $item->id)->get()->toArray();
            })->addColumn('design', function ($item) {
                return !empty($item->file_id) ? "<img src='". Storage::url($item->file->path) ."' width='100px' />" : '';
            })->addColumn('customize', function ($item) {
                return $item->is_customize ? "Yes" : "No";
            })->editColumn('quantity', function ($item) {
                if (!$item->is_customize) return $item->quantity;

                $quantity = null;
                foreach ($item->raw_materials as $raw_material) {
                    $available = $raw_material->material->quantity;
                    $needed = $raw_material->count;

                    $possible = floor($available / $needed);

                    if ($quantity == null || $possible < $quantity) {
                        $quantity = $possible;
                    }
                }

                return $quantity ?? 0;
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where("id", $keyword)
                        ->orWhere('name', 'like', "%$keyword%")
                        ->orWhere('quantity', 'like', "%$keyword%")
                        ->orWhere(DB::raw("CONCAT('₱ ', FORMAT(price, 2))"), 'like', "%$keyword%");
                });
            }
            
            return $datatables->rawColumns(['actions', 'design'])->make(true);
        }

        $materials = RawMaterial::all();
        
        return view('admin.product.index', compact('materials'));
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create([
            'name' => $request->input('name'),
            'quantity' => !empty($request->input('is_customize')) ? 0 : $request->input('quantity'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'is_customize' => $request->input('is_customize') ?? false,
        ]);

        if ($request->hasFile('design')) {
            $design = $request->file('design');
            $path = Storage::disk('public')->put('/attachments/design', $design);
            
            $file = File::create([
                'file_name' => $design->getClientOriginalName(),
                'file_mime' => $design->getClientMimeType(),
                'path' => $path,
                'user_id' => auth()->user()->id
            ]);

            $product->file_id = $file->id;
            $product->save();
        }

        if ($request->input('materials_id') && $product->is_customize) {
            foreach ($request->input('materials_id') as $key => $material_id) {
                $product->raw_materials()->create([
                    'raw_material_id' => $material_id,
                    'count' => $request->input('materials_count')[$key]
                ]);
            }
        }

        return redirect()->back()->with('message', 'Product successfully added.');
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            abort(404);
        }

        $product->name = $request->input('name');
        $product->quantity = !empty($request->input('is_customize')) ? 0 : $request->input('quantity');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->is_customize = $request->input('is_customize') ?? false;
        $product->save();

        if ($request->hasFile('design')) {
            $design = $request->file('design');
            $path = Storage::disk('public')->put('/attachments/design', $design);
            
            $file = File::create([
                'file_name' => $design->getClientOriginalName(),
                'file_mime' => $design->getClientMimeType(),
                'path' => $path,
                'user_id' => auth()->user()->id
            ]);

            $product->file_id = $file->id;
            $product->save();
        }

        $product->raw_materials()->delete();

        if ($request->input('materials_id') && $product->is_customize) {
            foreach ($request->input('materials_id') as $key => $material_id) {
                $product->raw_materials()->create([
                    'raw_material_id' => $material_id,
                    'count' => $request->input('materials_count')[$key]
                ]);
            }
        }

        return redirect()->back()->with('message', 'Product successfully updated.');
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            abort(404);
        }

        $product->delete();

        return redirect()->back()->with('message', 'Product successfully deleted.');
    }

    public function feedbacks(Request $request, $id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            abort(404);
        }

        if ($request->ajax()) {
            $keyword = $request->search['value'];

            $feedbacks = Feedback::leftJoin('users as u', 'u.id', 'feedback.user_id')
                                ->leftJoin('profiles as p', 'p.user_id', 'u.id')
                                ->select([
                                    'feedback.*',
                                    DB::raw('CONCAT(p.name, " ", p.surname) as full_name')
                                ]);
            $datatables = datatables()::of($feedbacks);

            $datatables->addColumn('actions', function ($item) {
                $btn = '';
                
                if (auth()->user()->is_admin) {
                    $delete_route = route('admin.product.feedback.delete', ['id' => $item->product->id, 'feedback_id' => $item->id]);
                    $btn .= "<a href='#' class='text-muted btn-remove' data-target='#feedback-$item->id'><i class='fa-solid fa-trash me-2'></i>Remove</a>
                                <form action='$delete_route' method='post' class='d-none' id='feedback-$item->id'>". csrf_field() .' '. method_field('delete') ."</form>";
                }

                return $btn;
            })->addColumn('image', function ($item) {
                return !empty($item->file_id) ? "<img src='". Storage::url($item->file->path) ."' width='100px' />" : '';
            })->editColumn('created_at', function ($item) {
                return $item->created_at->format('M d, Y');
            });

            if (!empty($keyword)) {
                $datatables->filter(function ($query) use ($keyword) {
                    $query->where('feedback.rate', 'like', "%$keyword%")
                        ->orWhere('feedback.message', 'like', "%$keyword%")
                        ->orWhere(DB::raw("CONCAT(p.name, ' ', p.surname)"), 'like', "%$keyword%")
                        ->orWhere(DB::raw("DATE_FORMAT(feedback.created_at, '%b %d, %Y')"), 'like', "%$keyword%");
                });
            }
            
            return $datatables->rawColumns(['actions', 'image'])->make(true);
        }

        return view('admin.product.feedbacks', compact('product'));
    }

    public function deleteFeedback($id, $feedback_id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            abort(404);
        }

        $feedback = $product->feedbacks->where('id', $feedback_id)->first();
        if (empty($feedback)) {
            abort(404);
        }

        $feedback->delete();

        return redirect()->back()->with('message', 'Feedback successfully deleted.');
    }
}
