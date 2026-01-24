<?php

namespace App\Http\Controllers;

use App\Http\Requests\AwardRegistration\Step2\ActivityRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AwardRegistrationController extends Controller
{
    public function create(Request $request)
    {
        $step = (int) $request->query('step', 1);

        if (!in_array($step, [1, 2, 3])) {
            abort(404);
        }

        return view("award-registrations.steps.step{$step}", compact('step'));
    }

    public function store(Request $request)
    {
        $step = (int) $request->query('step', 1);

        if (!in_array($step, [1, 2, 3])) {
            abort(404);
        }

        match ($step) {
            1 => $this->storeStep1($request),
            2 => $this->storeStep2($request),
            3 => null,
        };

        if ($request->input('action') === 'back') {
            return redirect()->route('award-registrations.create', ['step' => $step - 1]);
        }

        if ($step < 3) {
            return redirect()->route('award-registrations.create', ['step' => $step + 1]);
        }

        session()->forget('award_registration');
        return redirect()->route('award-registrations.index');
    }

    private function storeStep1(Request $request)
    {
        $oldType = session('award_registration.step1.award_type');

        if ($oldType && $oldType !== $request->award_type) {
            session()->forget('award_registration');
        }

        session([
            'award_registration.step1' => [
                'award_type' => $request->award_type,
            ]
        ]);
    }

    private function storeStep2(Request $request)
    {
        $type = session('award_registration.step1.award_type');
        $action = $request->input('action', 'next');

        if ($action === 'back') {
            session([
                'award_registration.step2' => array_merge(
                    session('award_registration.step2', []),
                    $request->except('documents')
                )
            ]);
            return;
        }

        $data = match ($type) {
            'activity' => app(ActivityRequest::class)->validated(),
            'good-conduct' => $request->validate([
                'approver' => 'required|string|max:255',
            ]),
            'innovation' => $request->validate([
                'award' => 'required|string|max:255',
            ]),
        };

        $documents = session('award_registration.step2.documents', []);

        if ($request->hasFile('documents')) {

            foreach ($documents as $oldPath) {
                Storage::delete($oldPath);
            }

            $documents = [];

            foreach ($request->file('documents') as $file) {
                $filename = now()->timestamp . '_' . $file->getClientOriginalName();

                $documents[] = $file->storeAs(
                    'award-registrations',
                    $filename
                );
            }
        }

        session([
            'award_registration.step2' => [
                ...$data,
                'documents' => $documents,
            ]
        ]);
    }
}
