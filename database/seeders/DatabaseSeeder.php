<?php

namespace Database\Seeders;

use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@hyperionlatam.com');
        $adminPassword = env('ADMIN_PASSWORD', 'CambiaEstaClave123!');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('ADMIN_NAME', 'Administrador Nova Atelier'),
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        if (Service::count() === 0) {
            $services = [
                [
                    'title' => 'Diseño',
                    'eyebrow' => 'Espacios con intención',
                    'items' => ['Interiorismo residencial', 'Diseño arquitectónico', 'Diseño de mobiliario'],
                    'description' => 'Convertimos tus ideas en una propuesta espacial coherente, sensible y funcional. Diseñamos cada decisión —desde la luz hasta el último material— para que tu hogar se sienta propio.',
                ],
                [
                    'title' => 'Remodelación',
                    'eyebrow' => 'Transformar lo existente',
                    'items' => ['Cocinas y baños', 'Ampliaciones', 'Acabados y styling'],
                    'description' => 'Rescatamos el potencial de lo que ya tienes y lo llevamos a un nuevo nivel. Coordinamos materiales, tiempos y equipos para que el proceso de transformar sea claro y disfrutable.',
                ],
                [
                    'title' => 'Construcción',
                    'eyebrow' => 'La idea toma forma',
                    'items' => ['Obra nueva', 'Dirección de obra', 'Supervisión técnica'],
                    'description' => 'Cuidamos la ejecución con criterio arquitectónico y control de calidad. Cada detalle construido responde a una visión integral, con cronogramas y decisiones transparentes.',
                ],
                [
                    'title' => 'Consultoría',
                    'eyebrow' => 'Decidir mejor',
                    'items' => ['Diagnóstico espacial', 'Asesoría de materiales', 'Presupuesto y alcance'],
                    'description' => 'Una mirada experta puede ahorrarte meses de dudas. Evaluamos tu espacio, tus prioridades y tu inversión para entregarte un camino concreto antes de empezar.',
                ],
            ];

            foreach ($services as $index => $service) {
                Service::create($service + [
                    'image' => null,
                    'published' => true,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        if (PortfolioProject::count() === 0) {
            $projects = [
                [
                    'title' => 'Casa Nido',
                    'location' => 'Chapinero · Bogotá',
                    'category' => 'Remodelación integral',
                    'description' => 'Una casa familiar que encuentra su nueva identidad en la luz natural, la madera y una circulación más abierta. El proyecto equilibra intimidad y conexión con el jardín.',
                    'cover_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80',
                    'details' => ['240 m² intervenidos', '2024', 'Residencial'],
                ],
                [
                    'title' => 'Línea de Tierra',
                    'location' => 'Usaquén · Bogotá',
                    'category' => 'Diseño arquitectónico',
                    'description' => 'Volúmenes limpios y patios silenciosos construyen una vivienda donde la materialidad local se encuentra con una forma de habitar contemporánea.',
                    'cover_image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80',
                    'details' => ['380 m² construidos', '2023', 'Obra nueva'],
                ],
                [
                    'title' => 'Materia Viva',
                    'location' => 'La Floresta · Bogotá',
                    'category' => 'Interiorismo',
                    'description' => 'Un apartamento de ciudad editado con precisión: texturas minerales, luz cálida y piezas diseñadas para acompañar la vida cotidiana sin imponerse.',
                    'cover_image' => 'https://images.unsplash.com/photo-1615529182904-14819c35db37?w=1200&q=80',
                    'details' => ['128 m² intervenidos', '2022', 'Interiorismo'],
                ],
            ];

            foreach ($projects as $index => $project) {
                PortfolioProject::create($project + [
                    'long_description' => null,
                    'images' => [$project['cover_image']],
                    'published' => true,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
