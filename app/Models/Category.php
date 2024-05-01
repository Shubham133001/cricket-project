<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    // get children from patent_id
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }
}
