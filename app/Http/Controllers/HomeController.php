<?php

namespace App\Http\Controllers;

use App\Models\PortfolioProject;
use App\Models\Service;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home', [
            'services' => Service::where('published', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'projects' => PortfolioProject::where('published', true)
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }
}
