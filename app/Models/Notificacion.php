<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = ['titulo', 'descripcion', 'tipo', 'leido', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
