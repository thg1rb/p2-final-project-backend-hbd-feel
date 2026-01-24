<?php

namespace App\Http\Controllers;

use App\Http\Requests\AwardRegistration\Step2\ActivityRequest;
use Illuminate\Http\Request;

class AwardRegistrationController extends Controller
{
    public function create(Request $request)
    {
        $step = (int) $request->query('step', 1);

        if (!in_array($step, [1, 2, 3])) {
            abort(404);
        }

        return view("award-registrations.steps.step{$step}", [
            'step' => $step
        ]);
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

        $action = $request->input('action', 'next');
        if ($action === 'back') {
            return redirect()->route('award-registrations.create', [
                'step' => $step - 1
            ]);
        }
        if ($step < 3) {
            return redirect()->route('award-registrations.create', [
                'step' => $step + 1
            ]);
        }

        session()->forget('award_registration');
        return redirect()->route('award-registrations.index');
    }


    private function storeStep1(Request $request)
    {
        $type = session('award_registration.step1.award_type');
        if(isset($type) && $request->award_type !== $type) {
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
        if ($action === 'next') {
            match ($type) {
                'activity' => app(ActivityRequest::class)->validated(),
                default => abort(400, 'Invalid award type'),
            };
        }

        session([
            'award_registration.step2' => $request->all()
        ]);
    }
}

