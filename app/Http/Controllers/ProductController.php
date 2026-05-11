<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ApiService $api)
    {
    }

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $result = $this->api->getProducts($page);

        if (!$result['success']) {
            return back()->with('error', $result['error']);
        }

        // Get pending links count
        $pendingCount = 0;
        $linksResult = $this->api->getLinks();
        if ($linksResult['success']) {
            $pendingCount = collect($linksResult['data']['results'])
                ->whereIn('status', ['pending', 'processing'])
                ->count();
        }

        return view('products.index', [
            'products' => $result['data']['results'],
            'total' => $result['data']['count'],
            'page' => $page,
            'totalPages' => ceil($result['data']['count'] / 20),
            'pendingCount' => $pendingCount,
        ]);
    }

    public function show(string $id)
    {
        $result = $this->api->getProduct($id);

        if (!$result['success']) {
            return redirect()->route('products.index')->with('error', 'Không tìm thấy sản phẩm');
        }

        return view('products.show', [
            'product' => $result['data'],
        ]);
    }

    public function destroy(string $id, Request $request)
    {
        $shopId = $request->input('shop_id');
        $result = $this->api->deleteProduct($id, $shopId);

        if ($result['success']) {
            return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm');
        }

        return back()->with('error', $result['error']);
    }
}
