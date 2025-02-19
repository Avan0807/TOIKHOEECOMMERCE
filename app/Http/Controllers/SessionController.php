<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function removeHashRef(Request $request)
    {
        session()->forget('hash_ref');  // Xóa hash_ref khỏi session
        return response()->json(['message' => 'Hash ref removed']);
    }
}
