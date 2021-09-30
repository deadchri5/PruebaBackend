<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    use HasFactory;

    protected $table = 'task';

    protected $fillable = [
        'ID',
        'title',
        'description',
        'date',
        'FK_user',
        'done'
    ];

    //relation with users
    public function user() {
        return $this->belongsTo('App\Models\User', 'FK_user');
    }

}
