<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveSolutionRequest;
use App\Http\Requests\RejectComplaintRequest;
use App\Http\Requests\RejectSolutionRequest;
use App\Http\Requests\SubmitSolutionRequest;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Services\AiSolutionService;
use App\Services\ComplaintResponseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComplaintResponseController extends Controller
{
    public function store(
        Complaint $complaint,
        SubmitSolutionRequest $request,
        ComplaintResponseService $service
    ): RedirectResponse {
        $this->authorize(
            'create',
            ComplaintResponse::class
        );

        $service->submitSolution(
            $complaint,
            $request->user(),
            $request->validated()['solution']
        );

        return back()->with(
            'success',
            'Solusi berhasil diajukan.'
        );
    }

    public function approve(
        ComplaintResponse $response,
        ApproveSolutionRequest $request,
        ComplaintResponseService $service
    ): RedirectResponse {
        $this->authorize(
            'approve',
            $response
        );

        $service->approve(
            $response,
            $request->user(),
            $request->validated()['note']
        );

        return back()->with(
            'success',
            'Solusi berhasil disetujui.'
        );
    }

    public function reject(
        ComplaintResponse $response,
        RejectSolutionRequest $request,
        ComplaintResponseService $service
    ): RedirectResponse {
        $this->authorize(
            'reject',
            $response
        );

        $service->reject(
            $response,
            $request->user(),
            $request->validated()['note']
        );

        return back()->with(
            'success',
            'Solusi berhasil ditolak.'
        );
    }

    public function rejectComplaint(
        Complaint $complaint,
        RejectComplaintRequest $request,
        ComplaintResponseService $service
    ): RedirectResponse {
        $this->authorize(
            'reject',
            $complaint
        );

        $service->rejectComplaint(
            $complaint,
            $request->user(),
            $request->validated()['note']
        );

        return back()->with(
            'success',
            'Pengaduan berhasil ditolak.'
        );
    }

    public function solve(
        Complaint $complaint,
        ComplaintResponseService $service
    ): RedirectResponse {
        $this->authorize(
            'solve',
            $complaint
        );

        $service->solve(
            $complaint,
            request()->user()
        );

        return back()->with(
            'success',
            'Pengaduan berhasil diselesaikan.'
        );
    }

    public function aiSuggestion(
        Complaint $complaint,
        AiSolutionService $ai
    ): JsonResponse {

        $suggestion = $ai->generate($complaint);

        return response()->json([
            'success' => true,
            'suggestion' => $suggestion,
        ]);
    }

    public function aiChat(
        Complaint $complaint,
        Request $request,
        AiSolutionService $ai
    ): JsonResponse {
        $request->validate([
            'message' => 'required|string|max:1500',
        ]);

        $reply = $ai->chatConversation($complaint, $request->input('message'));

        return response()->json([
            'success' => true,
            'reply' => $reply,
        ]);
    }
}