<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Get all products with pagination
     *
     * @return \Illuminate\Http\Response
     */
    public function products()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Update product details
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function updateProduct(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'image' => 'sometimes|string|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $product->update($validator->validated());

        return response()->json([
            'success' => true,
            'data' => $product->load('category')
        ]);
    }

    /**
     * Delete a product
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function deleteProduct(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    /**
     * Get all orders with pagination
     *
     * @return \Illuminate\Http\Response
     */
    public function orders()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Update order status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'data' => $order->load(['user', 'items.product'])
        ]);
    }

    /**
     * Get all users with pagination
     *
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        $users = User::latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }
}
