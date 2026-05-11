<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function __construct(protected ApiService $api)
    {
    }

    public function create()
    {
        return view('links.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $userId = session('user.id', 'default');
        $result = $this->api->createLink($request->input('url'), $userId);

        if ($result['success']) {
            // Return JSON for AJAX requests
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'link_id' => $result['data']['id']
                ]);
            }
            // Fallback: redirect for non-AJAX
            return redirect()->route('links.status', $result['data']['id']);
        }

        // Return error
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 400);
        }
        
        return back()->withErrors(['url' => $result['error']])->withInput();
    }

    public function status(string $id)
    {
        $result = $this->api->getLink($id);

        if (!$result['success']) {
            return redirect()->route('links.create')->with('error', 'Link không tồn tại');
        }

        $link = $result['data'];

        if (request()->wantsJson()) {
            return response()->json($link);
        }

        return view('links.status', compact('link'));
    }
}
