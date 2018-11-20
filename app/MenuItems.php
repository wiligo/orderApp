<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class MenuItems extends Model
{
	protected $table = 'menu_items';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_item'
    ];
    
    protected $timestamp = [
    	'updated_at'
    ];

    
}

