<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(protected ApiService $api)
    {
    }

    public function index(Request $request)
    {
        $query = $request->input('q');
        $results = [];
        $total = 0;

        if ($query) {
            $filters = array_filter([
                'min_price' => $request->input('min_price'),
                'max_price' => $request->input('max_price'),
            ]);

            $result = $this->api->search($query, $filters);

            if ($result['success']) {
                $results = $result['data']['results'];
                $total = $result['data']['count'];
            }
        }

        return view('search.index', compact('query', 'results', 'total'));
    }
}
