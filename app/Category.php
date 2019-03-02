<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
