<?php

namespace App\Models\video_1;

use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
    //use HasFactory;
    protected $table = "country_lang";

    public $timestamps = false;//Для отключения дефолтных полей в laravel, чтобы можно было добавить запись в бд и чтобы CountryModel не подключала дефолтные поля

    protected $fillable = [
        'id',
        'alias',
        'name',
        'name_en'
    ];

}
