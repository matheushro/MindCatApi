<?php

namespace App\Http\Controllers;
use App\Models\DiaryEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DiaryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'diary_password' => 'required|string|min:6',
        ]);

        $entry = DiaryEntry::create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
            'diary_password' => Hash::make($request->diary_password),
        ]);

        return response()->json([
            'message' => 'Entrada criada com sucesso!',
            'entry' => $entry,
        ], 201);
    }

    public function index(Request $request)
    {
        $request->validate([
            'diary_password' => 'required|string',
        ]);

        $entries = DiaryEntry::where('user_id', $request->user()->id)->get();

        foreach ($entries as $entry) {
            if (!Hash::check($request->diary_password, $entry->diary_password)) {
                return response()->json(['message' => 'Senha incorreta!'], 403);
            }
        }

        return response()->json($entries);
    }

    public function destroy(Request $request, $id)
    {
        $entry = DiaryEntry::where('user_id', $request->user()->id)->findOrFail($id);

        if (!Hash::check($request->diary_password, $entry->diary_password)) {
            return response()->json(['message' => 'Senha incorreta!'], 403);
        }

        $entry->delete();

        return response()->json(['message' => 'Entrada deletada com sucesso!']);
    }
}
