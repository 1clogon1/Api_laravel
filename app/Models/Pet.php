<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pet extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pets';
    protected $fillable = [
        'id',
        'kind',
        'photos1',
        'photos2',
        'photos3',
        'photos4',
        'description',
        'mark',
        'id_user'
    ];
}
