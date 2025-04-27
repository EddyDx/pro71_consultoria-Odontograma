<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Odontogram extends Model
{
    // Si la tabla ya existe y se llama 'odontograms', Laravel la usará por defecto
    protected $table = 'odontograms';  

    
    protected $fillable = [
        'id',
        'name',  // Solo 'name' porque es lo que tenemos en la tabla
    ];

    
}
