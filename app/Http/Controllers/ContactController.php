<?php

namespace App\Http\Controllers;

use App\Models\ContactLead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:200'],
            'email' => ['required', 'string', 'email', 'max:320'],
            'phone' => ['required', 'string', 'min:6', 'max:40'],
            'projectType' => ['required', 'string', 'min:2', 'max:100'],
            'budgetRange' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        ContactLead::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'project_type' => $validated['projectType'],
            'budget_range' => $validated['budgetRange'] ?? null,
            'message' => $validated['message'],
        ]);

        return back()->with('contactSent', true);
    }
}
