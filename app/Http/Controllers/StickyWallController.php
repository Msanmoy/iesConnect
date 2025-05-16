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

        $usuarioId = auth()->id(); // seguimos usando auth()

        if (!$usuarioId) {
            return response()->json(['success' => false, 'message' => 'Usuario no autenticado'], 403);
        }

        if (!empty($validated['id'])) {
            $note = Note::where('id', $validated['id'])
                ->where('usuario_id', $usuarioId)
                ->firstOrFail();

            $note->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
            ]);
        } else {
            $note = Note::create([
                'usuario_id' => $usuarioId,
                'title' => $validated['title'],
                'content' => $validated['content'],
                'position' => Note::where('usuario_id', $usuarioId)->max('position') + 1 ?? 1,
            ]);
        }

        return response()->json(['success' => true, 'note' => $note]);
    }

    public function destroy($id)
    {
        $note = Note::where('id', $id)->where('usuario_id', auth()->id())->firstOrFail();
        $note->delete();

        return response()->json(['success' => true]);
    }





}
