<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function index()
    {
        $ideas = Idea::withCount('votes')->get();
        return view('index', compact('ideas'));
    }

    /**
     * Store a new vote for the given idea using a voter name.
     */
    public function store(Request $request, Idea $idea): \Illuminate\Http\JsonResponse
    {
        // Validasi input nama pemilih (opsional)
        $data = $request->validate([
            'voter_name' => 'nullable|string|max:255',
        ]);

        // Simpan vote tanpa memeriksa duplikat
        $idea->votes()->create([
            'voter_name' => $data['voter_name'] ?? null,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
