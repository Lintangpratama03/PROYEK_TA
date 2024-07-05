<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahTa extends Model
{
    protected $connection = 'pgsql_pbb'; // Database connection name

    protected $table = 'data.wilayah_ta'; // Schema and table name

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['kecamatan', 'kelurahan', 'geometry'];

    protected $casts = [
        'geometry' => 'array',
    ];
}
