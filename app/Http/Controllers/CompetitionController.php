<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Member;
use App\Models\PolicyProposal;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class CompetitionController extends Controller {
    public function index() {
        return Inertia::render('CompetitionForm', [
            'teams' => Team::all()
        ]);
    }

    public function searchTeam(Request $request) {
        $search = $request->query('q');
        $teams = Team::where('name', 'like', "%$search%")->get();
        return response()->json($teams);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'team_id' => 'nullable|exists:teams,id',
            'new_team_name' => 'nullable|string|max:255|unique:teams,name',
            'full_name' => 'required|string',
            'email' => 'required|email|unique:members,email',
            'phone_number' => 'required|string',
            'institution_name' => 'required|string',
            'department_level' => 'required|string',
            'matric_number' => 'required|string|unique:members,matric_number',
            'proof_document' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'document_path' => 'required|file|mimes:pdf,docx|max:5120',
        ]);

        // Handle team selection or creation
        $team = null;
        if ($request->team_id) {
            $team = Team::find($request->team_id);
        } elseif ($request->new_team_name) {
            $team = Team::create(['name' => $validated['new_team_name']]);
        } else {
            return back()->withErrors(['team' => 'Please select an existing team or enter a new team name.']);
        }

        // Upload documents
        $proofPath = $request->file('proof_document')->store('public/proofs');
        // $documentPath = $request->file('document_path')->store('public/proposals');
        $documentPath = $request->file('document_path')->store('proposals', 'public');

        return redirect()->back()->with('success', 'Document uploaded successfully!');


        // Store member data
        $member = Member::create([
            'team_id' => $team->id,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'institution_name' => $validated['institution_name'],
            'department_level' => $validated['department_level'],
            'matric_number' => $validated['matric_number'],
            'proof_document' => $proofPath,
            'document_path' => $documentPath,
        ]);

        // Store policy proposal
        PolicyProposal::create([
            'team_id' => $team->id,
            'member_id' => $member->id,
        ]);

        return redirect()->route('competition.index')->with('success', 'Submission successful!');
    }    
}
