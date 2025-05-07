<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StickyWallController extends Controller
{
    public function index()
    {
        $notes = Auth::user()->notes()->orderBy('position')->get();
        return view('stickywall.index', compact('notes'));
    }

    public function updateOrder(Request $request)
    {
        $noteIds = $request->input('note_ids');

        DB::transaction(function () use ($noteIds) {
            foreach ($noteIds as $index => $id) {
                \App\Models\Note::where('id', $id)->update(['position' => $index]);
            }
        });

        return response()->json(['success' => true]);
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:notes,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        if (isset($validated['id'])) {
            $note = \App\Models\Note::where('id', $validated['id'])->where('user_id', auth()->id())->firstOrFail();
            $note->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
            ]);
        } else {
            $note = \App\Models\Note::create([
                'user_id' => auth()->id(), // MUY IMPORTANTE
                'title' => $validated['title'],
                'content' => $validated['content'],
                'position' => (\App\Models\Note::where('user_id', auth()->id())->max('position') ?? 0) + 1,
            ]);
        }

        return response()->json(['success' => true, 'note' => $note]);
    }

}
