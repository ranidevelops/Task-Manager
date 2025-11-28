<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // list
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Task::with('assignee','creator');

        // non-admins see tasks they created or assigned to
        if (!method_exists($user,'hasRole') || ! $user->hasRole('admin')) {
            $query->where(function($q) use ($user) {
                $q->where('creator_id', $user->id)
                  ->orWhere('assignee_id', $user->id);
            });
        }

        if ($q = $request->query('q')) {
            $query->where(function($q2) use ($q) {
                $q2->where('title','like',"%{$q}%")
                   ->orWhere('description','like',"%{$q}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $tasks = $query->latest()->paginate(12);

        return view('tasks.index', compact('tasks'));
    }

    // show create form
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('tasks.create', compact('users'));
    }

    // store
   public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|nullable|string',
            'priority' => ['required', Rule::in(['low','medium','high'])],
            'deadline' => 'required|nullable|date|after:now',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $validated['creator_id'] = $request->user()->id;
        $validated['status'] = 'pending';

        $task = Task::create($validated);

        return redirect()->route('tasks.show', $task)->with('success','Task created.');
    }


    // show single
    public function show(Task $task)
    {
        // permission: admin / creator / assignee can view
        $user = auth()->user();
        if (method_exists($user,'hasRole') && $user->hasRole('admin')) {
            // allowed
        } else if ($task->creator_id !== $user->id && $task->assignee_id !== $user->id) {
            abort(403);
        }

        $task->load('creator','assignee');

        return view('tasks.show', compact('task'));
    }

    // edit form
    public function edit(Task $task)
    {
        $user = auth()->user();
        // only admin or creator can edit
        if (!(method_exists($user,'hasRole') && $user->hasRole('admin')) && $task->creator_id !== $user->id) {
            abort(403);
        }

        $users = User::orderBy('name')->get();
        return view('tasks.edit', compact('task','users'));
    }

    // update
    public function update(Request $request, Task $task)
    {
        $user = $request->user();
        if (!(method_exists($user,'hasRole') && $user->hasRole('admin')) && $task->creator_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => ['required', Rule::in(['low','medium','high'])],
            'deadline' => 'nullable|date|after:now',
            'assignee_id' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(['pending','in_progress','completed','cancelled'])],
        ]);

        $task->update($validated);

        return redirect()->route('tasks.show', $task)->with('success','Task updated.');
    }

    // delete
    public function destroy(Task $task)
    {
        $user = auth()->user();
        if (!(method_exists($user,'hasRole') && $user->hasRole('admin')) && $task->creator_id !== $user->id) {
            abort(403);
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success','Task deleted.');
    }
}
