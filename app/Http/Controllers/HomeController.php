<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('welcome/index', [
            'faq' => 
            [
                [
                    'id' => 'explicacion',
                    'titulo' => '¿Que son las practicas profesionales?',
                    'descripcion' => 'Las practicas profesionales son experiencias formativas que buscan que  el estudiante se enfrente a situaciones reales de trabajo, antes de poder ser considerado un profesional.'
                ],
                [
                    'id' => 'objetivo',
                    'titulo' => '¿Quienes pueden anotarse?',
                    'descripcion' => 'Depende del plande estudio, por lo general se realizan en el ultimo año de cursado.'
                ]

            ]
        ]);
    }
}