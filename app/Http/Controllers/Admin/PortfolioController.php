<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('Admin/Portfolio', [
            'isAdmin' => $user->isAdmin(),
            'projects' => $user->isAdmin()
                ? PortfolioProject::orderBy('sort_order')->orderByDesc('created_at')->get()
                : [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        PortfolioProject::create($this->validated($request));

        return back()->with('success', 'Proyecto creado y guardado.');
    }

    public function update(Request $request, PortfolioProject $portfolioProject): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $portfolioProject->update($this->validated($request));

        return back()->with('success', 'Cambios guardados correctamente.');
    }

    public function destroy(Request $request, PortfolioProject $portfolioProject): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $portfolioProject->delete();

        return back()->with('success', 'Proyecto eliminado del portafolio.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->isAdmin(), 403);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:2', 'max:200'],
            'location' => ['required', 'string', 'min:2', 'max:200'],
            'category' => ['required', 'string', 'min:2', 'max:100'],
            'description' => ['required', 'string', 'min:20', 'max:3000'],
            'longDescription' => ['nullable', 'string', 'max:6000'],
            'coverImage' => ['required', 'string', 'url', 'max:2048'],
            'images' => ['array', 'max:10'],
            'images.*' => ['string', 'url', 'max:2048'],
            'details' => ['array', 'max:8'],
            'details.*' => ['string', 'min:1', 'max:120'],
            'published' => ['boolean'],
            'sortOrder' => ['integer', 'min:0', 'max:9999'],
        ], [
            'title.required' => 'Indica el título del proyecto.',
            'location.required' => 'Indica la ubicación.',
            'category.required' => 'Indica la categoría.',
            'description.min' => 'Añade una descripción breve de al menos 20 caracteres.',
            'coverImage.url' => 'Usa una URL de imagen válida.',
            'images.max' => 'Máximo 10 imágenes por proyecto.',
            'details.max' => 'Máximo 8 datos destacados.',
        ]);

        return [
            'title' => $data['title'],
            'location' => $data['location'],
            'category' => $data['category'],
            'description' => $data['description'],
            'long_description' => $data['longDescription'] ?: null,
            'cover_image' => $data['coverImage'],
            'images' => $data['images'] ?? [],
            'details' => $data['details'] ?? [],
            'published' => $data['published'],
            'sort_order' => $data['sortOrder'],
        ];
    }
}
