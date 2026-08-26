<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('Admin/Services', [
            'isAdmin' => $user->isAdmin(),
            'services' => $user->isAdmin()
                ? Service::orderBy('sort_order')->orderBy('id')->get()
                : [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        Service::create($this->validated($request));

        return back()->with('success', 'Servicio creado y guardado.');
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $service->update($this->validated($request));

        return back()->with('success', 'Cambios guardados correctamente.');
    }

    public function destroy(Request $request, Service $service): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $service->delete();

        return back()->with('success', 'Servicio eliminado.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->isAdmin(), 403);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:2', 'max:120'],
            'eyebrow' => ['required', 'string', 'min:2', 'max:160'],
            'items' => ['array', 'max:10'],
            'items.*' => ['string', 'min:1', 'max:120'],
            'description' => ['required', 'string', 'min:20', 'max:3000'],
            'image' => ['nullable', 'string', 'url', 'max:2048'],
            'published' => ['boolean'],
            'sortOrder' => ['integer', 'min:0', 'max:9999'],
        ], [
            'title.required' => 'Indica el título del servicio.',
            'eyebrow.required' => 'Indica una frase breve.',
            'description.min' => 'Añade una descripción de al menos 20 caracteres.',
            'image.url' => 'Usa una URL de imagen válida.',
            'items.max' => 'Máximo 10 elementos.',
        ]);

        return [
            'title' => $data['title'],
            'eyebrow' => $data['eyebrow'],
            'items' => $data['items'] ?? [],
            'description' => $data['description'],
            'image' => $data['image'] ?: null,
            'published' => $data['published'],
            'sort_order' => $data['sortOrder'],
        ];
    }
}
