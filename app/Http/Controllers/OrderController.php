<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Feedback;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DefaultNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $orders = Order::leftJoin('products as p', 'p.id', 'orders.product_id')
                       ->leftJoin('files as f', 'f.id', 'p.file_id')
                       ->leftJoin('order_statuses as os', 'os.id', 'orders.order_status_id')
                       ->where('orders.user_id', auth()->user()->id)
                       ->select([
                            'orders.*',
                            'p.name as product_name',
                            'p.description as product_description',
                            'f.path as file_path',
                            'os.name as status'
                       ])->get();
        $statuses = OrderStatus::all();
        return view('orders.index', compact('statuses', 'orders'));
    }

    public function show($id)
    {
        $order = Order::with(['product' => function ($query) {
                $query->withTrashed()
                    ->with(['feedbacks']);
            }, 'status'])->find($id);
        if (empty($order) || $order->user_id !== auth()->user()->id) {
            abort(404);
        }

        $products = Product::where('id', '!=', $order->product_id)->inRandomOrder()->limit(6)->get();
        return view('orders.show', compact('order', 'products'));
    }

    public function uploadPayment(Request $request, $id)
    {
        $validate = $request->validate([
            'payment' => 'required',
            'payment_reference' => 'required'
        ]);

        $order = Order::find($id);
        if (empty($order) || $order->user_id !== auth()->user()->id) {
            abort(404);
        }

        $payment = $request->file('payment');
        $path = Storage::disk('public')->put('/attachments/payment', $payment);
        
        $file = File::create([
            'file_name' => $payment->getClientOriginalName(),
            'file_mime' => $payment->getClientMimeType(),
            'path' => $path,
            'user_id' => auth()->user()->id
        ]);

        $status = OrderStatus::where('name', 'To Review Payment')->first();

        $order->order_status_id = $status->id;
        $order->payment = $file->id;
        $order->payment_reference = $request->input('payment_reference');
        $order->save();

        // DB Notification
        $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
        $message = "A customer has uploaded a payment.";
        $link = route('admin.order.show', $order->id);

        Notification::send(
            $users, 
            new DefaultNotification($message, $link)
        );

        return redirect()->back()->with('message', 'Payment successfully uploaded.');
    }

    public function cancel($id)
    {
        $order = Order::find($id);
        if (empty($order) || $order->user_id !== auth()->user()->id) {
            abort(404);
        }

        $status = OrderStatus::where('name', 'Cancelled')->first();

        $order->order_status_id = $status->id;
        $order->save();

        // DB Notification
        $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
        $message = "A customer has canceled the order.";
        $link = route('admin.order.show', $order->id);

        Notification::send(
            $users, 
            new DefaultNotification($message, $link)
        );

        return redirect()->back()->with('message', 'Order cancelled successfully.');
    }

    public function receive($id)
    {
        $order = Order::find($id);
        if (empty($order) || $order->user_id !== auth()->user()->id) {
            abort(404);
        }

        $status = OrderStatus::where('name', 'Completed')->first();

        $order->order_status_id = $status->id;
        $order->save();
        
        // DB Notification
        $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
        $message = "A customer has received the order.";
        $link = route('admin.order.show', $order->id);

        Notification::send(
            $users, 
            new DefaultNotification($message, $link)
        );

        return redirect()->back()->with('message', 'Order received successfully.');
    }

    public function feedback(Request $request, $id)
    {
        $validate = $request->validate([
            'img' => 'nullable',
            'rate' => 'required',
            'message' => 'nullable'
        ]);

        $order = Order::find($id);
        if (empty($order) || $order->user_id !== auth()->user()->id) {
            abort(404);
        }

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $path = Storage::disk('public')->put('/attachments/feedback', $img);
            
            $file = File::create([
                'file_name' => $img->getClientOriginalName(),
                'file_mime' => $img->getClientMimeType(),
                'path' => $path,
                'user_id' => auth()->user()->id
            ]);
        }

        Feedback::create([
            'user_id' => auth()->user()->id,
            'product_id' => $order->product_id,
            'file_id' => $file->id ?? null,
            'rate' => $validate['rate'],
            'message' => $validate['message'] ?? null
        ]);

        
        // DB Notification
        $users = User::where('is_admin', 1)->orWhere('is_staff', 1)->get();
        $message = "A customer has added a feedback.";
        $link = route('admin.product.feedbacks', $order->product->id);

        Notification::send(
            $users, 
            new DefaultNotification($message, $link)
        );
        
        return redirect()->back()->with('message', 'Feedback successfully added.');
    }
}
