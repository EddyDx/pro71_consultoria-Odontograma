<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Odontogram;

class OdontogramController extends Controller
{
    /**
     * Muestra la vista principal del módulo de odontograma.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('tenant.odontogram.index'); // O la vista que corresponda
    }

}

