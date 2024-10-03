<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::all();

        return response()->json([
            'status' => 'success',
            'data' => VoucherResource::collection($vouchers),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new VoucherResource($voucher),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData =  $request->validate([
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('vouchers', 'code'),
            ],
            'discount_amount' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $voucher = Voucher::create($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => new VoucherResource($voucher),
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('vouchers', 'code')->ignore($id),
            ],
            'discount_amount' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher not found',
            ], 404);
        }
        $voucher->update($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => new VoucherResource($voucher),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::find($id);

        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher not found',
            ], 404);
        }

        $voucher->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Voucher deleted successfully',
        ]);
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher not found',
            ], 404);
        }

        if (!$voucher->isValid()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or expired voucher',
            ], 400);
        }
        $voucher->increment('redeem_count');

        return response()->json([
            'status' => 'success',
            'message' => 'Voucher redeemed successfully',
            'discount_amount' => $voucher->discount_amount,
        ]);
    }
}
