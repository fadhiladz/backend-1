<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\RegisterSellerRequest;
use App\Http\Requests\Seller\UpdateSellerRequest;
use App\Http\Resources\SellerProfileResource;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function show(Request $request): SellerProfileResource
    {
        $seller = $request->user()->sellerProfile()->firstOrFail();
        return new SellerProfileResource($seller);
    }

    public function register(RegisterSellerRequest $request): SellerProfileResource
    {
        if ($request->user()->sellerProfile) {
            abort(422, 'Already registered as a seller.');
        }
        $seller = $request->user()->sellerProfile()->create($request->validated());
        return new SellerProfileResource($seller);
    }

    public function update(UpdateSellerRequest $request): SellerProfileResource
    {
        $seller = $request->user()->sellerProfile()->firstOrFail();
        $seller->update($request->validated());
        return new SellerProfileResource($seller);
    }
}
