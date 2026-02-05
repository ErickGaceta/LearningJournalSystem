<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingModule;
use App\Models\Document;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get total count of training modules
        $totalModules = TrainingModule::count();
        
        // You can also get other useful counts
        $activeModules = TrainingModule::where('dateend', '>=', now())->count();
        $completedModules = TrainingModule::where('dateend', '<', now())->count();

        $documents = Document::where('user_id', Auth::id())->count();

        return match($user->user_type) {
            'admin' => view('pages.admin.index', compact(
                'totalModules',
                'activeModules',
                'completedModules',
                'documents'
            )),
            'user' => view('pages.users.index', compact(
                'totalModules',
                'activeModules',
                'completedModules'
            )),
            default => abort(403, 'Unauthorized'),
        };
    }
}