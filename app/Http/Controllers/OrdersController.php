<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function index()
    {
        $product = Products::all();

        if (!$product) {
            return response()->json([
                'success' => false,
                'massage' => 'Gagal mengambil pesanan',
            ]);
        } else {
            return response()->json([
                'success' => true,
                'massage' => ''
            ]);
        }
    }
}