<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use App\Models\TrainingModule;
use App\Models\Assignment;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HRController extends Controller
{
    // ========== Dashboard ==========
    public function dashboard()
    {
        $now = now();

        $totalModules = TrainingModule::count();

        $activeTraining = TrainingModule::where('datestart', '<=', $now)
            ->where('dateend', '>=', $now)
            ->count();

        $usersInTraining = Assignment::whereHas(
            'module',
            fn($q) =>
            $q->where('datestart', '<=', $now)->where('dateend', '>=', $now)
        )->distinct('user_id')->count('user_id');

        $modules = TrainingModule::withCount('assignments')->latest()->get();

        return view('pages.hr.dashboard', compact(
            'modules',
            'totalModules',
            'activeTraining',
            'usersInTraining'
        ));
    }

    // ========== Training Module Management ==========
    public function modulesIndex(Request $request)
    {
        $search = $request->get('search');
        $showArchived = false;

        $trainingModules = TrainingModule::withCount('assignments')
            ->with('assignments:id,module_id,user_id,employee_name')
            ->notArchived()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('venue', 'like', "%{$search}%")
                        ->orWhere('conductedby', 'like', "%{$search}%")
                        ->orWhereHas(
                            'assignments',
                            fn($q) => $q->where('employee_name', 'like', "%{$search}%")
                        );
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $moduleIds = $trainingModules->pluck('id');
        $modules = TrainingModule::whereIn('id', $moduleIds)->get()->keyBy('id');
        $assignments = Assignment::with('module:id,title')
            ->whereIn('module_id', $moduleIds)
            ->get();

        $users = User::where('user_type', 'user')
            ->select('id', 'first_name', 'last_name', 'user_type')
            ->orderBy('last_name')
            ->get();

        return view('pages.hr.modules.index', compact('trainingModules', 'users', 'assignments', 'showArchived')); // ← pass it
    }

    public function storeModule(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:1000',
            'hours' => 'required|string|max:1000',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:100',
            'registration_fee' => 'string|max:100',
        ]);

        TrainingModule::create($validated);

        return redirect()->route('hr.modules.index')
            ->with('success', 'Training module created successfully.');
    }

    public function updateModule(Request $request, TrainingModule $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:1000',
            'hours' => 'required|string|max:1000',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:100',
            'registration_fee' => 'string|max:100',
        ]);

        $module->update($validated);

        return redirect()->route('hr.modules.index')
            ->with('success', 'Training module updated successfully.');
    }

    public function archiveModule(TrainingModule $module): RedirectResponse
    {
        $module->update(['archived_at' => now()]);

        return redirect()->route('hr.modules.index')
            ->with('success', 'Training module archived successfully.');
    }

    public function destroyModule(TrainingModule $module): RedirectResponse
    {
        $module->delete();

        return redirect()->route('hr.modules.index')
            ->with('success', 'Training module deleted successfully.');
    }

    public function restoreModule(Module $module)
    {
        $module->update(['archived_at' => null]); // or whatever your archive field is
        return back()->with('success', 'Module restored successfully.');
    }

    // ========== Assignment Management ==========
    public function storeAssignment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'required|exists:users,id',
            'module_id'  => 'required|exists:training_module,id',
        ]);

        $module = TrainingModule::findOrFail($validated['module_id']);

        $users = User::whereIn('id', $validated['user_ids'])
            ->select('id', 'first_name', 'last_name')
            ->get()
            ->keyBy('id');

        $existing = Assignment::where('module_id', $module->id)
            ->whereIn('user_id', $validated['user_ids'])
            ->pluck('user_id')
            ->flip();

        $inserts = [];
        $now = now();

        foreach ($validated['user_ids'] as $userId) {
            if ($existing->has($userId)) continue;

            $inserts[] = [
                'user_id'         => $userId,
                'module_id'       => $module->id,
                'employee_name'   => $users[$userId]->full_name,
                'training_module' => $module->title,
                'status'          => 'assigned',
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }

        if (!empty($inserts)) {
            Assignment::insert($inserts);
        }

        return redirect()->route('hr.modules.index')
            ->with('success', 'Training assigned successfully.');
    }

    // ========== Document Archive Management ==========
    public function archiveDocument(Document $document): RedirectResponse
    {
        $document->update(['isArchived' => true]);

        return redirect()
            ->route('hr.modules.index')
            ->with('success', 'Learning journal archived successfully.');
    }

    public function archiveIndex(Request $request): View
    {
        $showArchived = $request->boolean('archived');

        $documents = Document::with(['user', 'module'])
            ->where('isArchived', $showArchived)
            ->latest()
            ->get();

        return view('pages.hr.modules.index', compact('documents', 'showArchived'));
    }

    // ========== Monitoring ==========
    public function modulesArchive(Request $request): View
    {
        $showArchived = true;

        $trainingModules = TrainingModule::withCount('assignments')
            ->with('assignments:id,module_id,user_id,employee_name')
            ->archived()
            ->latest()
            ->paginate(15);

        $moduleIds = $trainingModules->pluck('id');
        $assignments = Assignment::with('module:id,title')
            ->whereIn('module_id', $moduleIds)
            ->get();

        $users = User::where('user_type', 'user')
            ->select('id', 'first_name', 'last_name')
            ->orderBy('last_name')
            ->get();

        return view('pages.hr.modules.index', compact('trainingModules', 'users', 'assignments', 'showArchived'));
    }

    public function monitoringIndex(Request $request)
    {
        $search = $request->get('search');
        $year   = $request->get('year', now()->year);
        $now    = now();

        $allModules = TrainingModule::with([
            'assignments.user.position',
            'documents',
        ])
            ->whereYear('datestart', $year)
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('title',       'like', "%{$search}%")
                    ->orWhere('venue',       'like', "%{$search}%")
                    ->orWhere('conductedby', 'like', "%{$search}%")
                    ->orWhereHas(
                        'assignments',
                        fn($q) =>
                        $q->where('employee_name', 'like', "%{$search}%")
                    );
            }))
            ->orderBy('datestart')
            ->get()
            ->each(function ($module) use ($now) {
                $module->status = match (true) {
                    $module->datestart > $now => 'upcoming',
                    $module->dateend   < $now => 'completed',
                    default                   => 'ongoing',
                };
                $module->documentsByUser = $module->documents->keyBy('user_id');
            });

        $quarters = [
            1 => ['label' => 'Quarter 1', 'range' => 'Jan - Mar', 'modules' => collect()],
            2 => ['label' => 'Quarter 2', 'range' => 'Apr - Jun', 'modules' => collect()],
            3 => ['label' => 'Quarter 3', 'range' => 'Jul - Sep', 'modules' => collect()],
            4 => ['label' => 'Quarter 4', 'range' => 'Oct - Dec', 'modules' => collect()],
        ];

        foreach ($allModules as $module) {
            $q = (int) ceil($module->datestart->month / 3);
            $quarters[$q]['modules']->push($module);
        }

        $oldestYear     = TrainingModule::min(DB::raw('YEAR(datestart)')) ?? now()->year;
        $availableYears = range(now()->year, $oldestYear);

        return view('pages.hr.monitoring.index', compact('quarters', 'year', 'availableYears'));
    }

    public function previewDocument(Document $document): \Illuminate\Http\Response
    {
        $document->load([
            'user.position',
            'user.divisionUnit',
            'user.signature',
            'module',
        ]);

        $user   = $document->user;
        $module = $document->module;
        $date   = now()->format('Y-m-d');

        $html = view('pages.user.documents.print', compact('document', 'user', 'module', 'date'))->render();

        $pdf = new \App\Pdf\LearningJournalPDF(
            PDF_PAGE_ORIENTATION,
            PDF_UNIT,
            PDF_PAGE_FORMAT,
            true,
            'UTF-8',
            false
        );

        $pdf->SetProtection([], '', env('PDF_OWNER_PASSWORD', 'changeme'), 0);

        $pdf->SetCreator(config('app.name'));
        $pdf->SetAuthor($document->fullname);
        $pdf->SetTitle(($module?->title ?? 'Learning Journal') . ' - ' . $date);
        $pdf->SetSubject('Learning Journal');
        $pdf->SetKeywords('');

        $pdf->setHeaderImagePath(public_path('documents/header.PNG'));
        $pdf->setFooterImagePath(public_path('documents/footer.PNG'));

        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);

        $pdf->SetMargins(10, 30, 10);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(25);
        $pdf->SetAutoPageBreak(true, 30);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('dejavusans', '', 10);

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        $filename = ($module?->title ?? 'Learning Journal')
            . ' - ' . $date
            . ' - ' . $document->fullname
            . '.pdf';

        return response($pdf->Output($filename, 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-store, no-cache');
    }
}