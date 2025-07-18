<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    /**
     * Apply authentication middleware to all methods
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get notes query filtered by authenticated user
     */
    private function getUserNotesQuery()
    {
        return Note::where('user_id', auth()->id());
    }
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            // Log for debugging
            Log::info('Fetching notes for user', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
            
            $notes = $this->getUserNotesQuery()
                        ->orderBy('created_at', 'desc')
                        ->get();
            
            // Log the notes being returned
            Log::info('Notes fetched', [
                'user_id' => $user->id,
                'notes_count' => $notes->count(),
                'note_ids' => $notes->pluck('id')->toArray()
            ]);
            
            return response()->json($notes);
        } catch (\Exception $e) {
            Log::error('Error fetching notes', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'Error fetching notes'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = $request->user();
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'body' => 'nullable|string',
            ]);

            // Log for debugging
            Log::info('Creating note for user', [
                'user_id' => $user->id,
                'title' => $validated['title']
            ]);

            $note = $user->notes()->create($validated);
            
            // Log the created note
            Log::info('Note created', [
                'note_id' => $note->id,
                'user_id' => $note->user_id,
                'title' => $note->title
            ]);
            
            return response()->json($note, 201);
        } catch (\Exception $e) {
            Log::error('Error creating note', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'Error creating note'], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $note = $this->getUserNotesQuery()->findOrFail($id);
            
            Log::info('Note accessed', [
                'note_id' => $note->id,
                'user_id' => $user->id,
                'note_owner_id' => $note->user_id
            ]);
            
            return response()->json($note);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Note not found or access denied', [
                'note_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'Note not found or access denied'], 404);
        } catch (\Exception $e) {
            Log::error('Error accessing note', [
                'error' => $e->getMessage(),
                'note_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'Error accessing note'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $note = $this->getUserNotesQuery()->findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'body' => 'nullable|string',
            ]);

            $note->update($validated);
            
            Log::info('Note updated', [
                'note_id' => $note->id,
                'user_id' => $user->id,
                'title' => $note->title
            ]);
            
            return response()->json($note);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Note not found or access denied for update', [
                'note_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'Note not found or access denied'], 404);
        } catch (\Exception $e) {
            Log::error('Error updating note', [
                'error' => $e->getMessage(),
                'note_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'Error updating note'], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            $note = $this->getUserNotesQuery()->findOrFail($id);
            
            Log::info('Note deleted', [
                'note_id' => $note->id,
                'user_id' => $user->id,
                'title' => $note->title
            ]);
            
            $note->delete();
            
            return response()->json(['message' => 'Note deleted successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Note not found or access denied for deletion', [
                'note_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'Note not found or access denied'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting note', [
                'error' => $e->getMessage(),
                'note_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'Error deleting note'], 500);
        }
    }
}