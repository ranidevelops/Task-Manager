<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User; // <<-- make sure this is imported

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    
    public function index(Request $request)
    {
        $user = $request->user();

        $totalUser = 0;
        $totalTasks = 0;
        $pending = 0;
        $completed = 0;

        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            $totalUser = User::count();
            $totalTasks = Task::count();
            $pending = Task::where('status', 'pending')->count();
            $completed = Task::where('status', 'completed')->count();
        } else {
            $totalTasks = Task::where(function ($q) use ($user) {
                $q->where('creator_id', $user->id)
                  ->orWhere('assignee_id', $user->id);
            })->count();

            $pending = Task::where(function ($q) use ($user) {
                $q->where('creator_id', $user->id)
                  ->orWhere('assignee_id', $user->id);
            })->where('status', 'pending')->count();

            $completed = Task::where(function ($q) use ($user) {
                $q->where('creator_id', $user->id)
                  ->orWhere('assignee_id', $user->id);
            })->where('status', 'completed')->count();
        }

        $latest = Task::with('assignee')
            ->when(!(method_exists($user, 'hasRole') && $user->hasRole('admin')), function ($q) use ($user) {
                $q->where(function ($q2) use ($user) {
                    $q2->where('creator_id', $user->id)
                       ->orWhere('assignee_id', $user->id);
                });
            })
            ->latest()
            ->limit(6)
            ->get();

        return view('dashboard', compact('totalTasks', 'pending', 'completed', 'latest', 'totalUser'));
    }
}
