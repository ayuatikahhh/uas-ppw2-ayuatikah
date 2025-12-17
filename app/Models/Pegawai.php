<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use SoftDeletes;

    protected $table = 'pegawai';

    protected $dates = ['deleted_at'];

    protected $fillable = ['nama', 'email', 'telepon', 'tanggal_lahir', 'jenis_kelamin', 'pekerjaan_id'];

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class);
    }
}
