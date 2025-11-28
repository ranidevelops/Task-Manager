<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class TaskDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store uploaded document for a task.
     */
    public function store(Request $request, Task $task)
    {
        // authorization: only task creator, assignee or admin can upload/update
        $user = $request->user();
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            // allowed
        } elseif ($task->creator_id !== $user->id && $task->assignee_id !== $user->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'document' => 'required|file|max:51200', // max 50MB; adjust as needed
            // optionally restrict mime types:
            // 'document' => 'required|file|mimes:pdf,doc,docx,xlsx,png,jpg,jpeg|max:51200',
        ]);

        $file = $validated['document'];
        $original = $file->getClientOriginalName();
        $mime = $file->getClientMimeType();
        $size = $file->getSize();
        $ext = $file->getClientOriginalExtension();

        // create a safe random filename and keep extension
        $filename = Str::random(40) . '.' . $ext;
        $path = "tasks/{$task->id}/{$filename}";

        // store on private disk
        Storage::disk('private')->putFileAs("tasks/{$task->id}", $file, $filename);

        // create DB record
        $doc = TaskDocument::create([
            'task_id' => $task->id,
            'filename' => $path,
            'original_name' => $original,
            'mime' => $mime,
            'size' => $size,
            'uploaded_by' => $user->id,
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    /**
     * Download an uploaded document.
     */
    public function download(TaskDocument $document)
    {
        $task = $document->task;
        $user = auth()->user();

        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            
        } elseif ($task->creator_id !== $user->id && $task->assignee_id !== $user->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (! Storage::disk('private')->exists($document->filename)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Storage::disk('private')->download($document->filename, $document->original_name);
    }

    public function destroy(TaskDocument $document)
    {
        $task = $document->task;
        $user = auth()->user();

        $allowed = (method_exists($user,'hasRole') && $user->hasRole('admin'))
                    || $document->uploaded_by === $user->id
                    || $task->creator_id === $user->id;

        if (! $allowed) {
            abort(Response::HTTP_FORBIDDEN);
        }

        // delete file if exists
        if (Storage::disk('private')->exists($document->filename)) {
            Storage::disk('private')->delete($document->filename);
        }

        $document->delete();

        return back()->with('success', 'Document deleted.');
    }
}
