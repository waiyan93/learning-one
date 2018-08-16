<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    protected $table = 'ebooks';
    protected $fillable = [
        'title', 'original', 'edited', 'updated'
    ];

    public function contents() {
        return $this->hasMany('App/Content');
    }
}
