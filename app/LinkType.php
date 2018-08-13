<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkType extends Model
{
    protected $table = 'link_types';
    protected $fillable = [
        'type'
    ];

    public function contents() {
        return $this->hasMany('App/Content');
    }
}
