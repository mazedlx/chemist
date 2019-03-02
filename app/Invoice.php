<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function getAmountAttribute($value)
    {
        return str_replace(',', null, $value);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value;
    }

    public function getMoneyAttribute()
    {
        return number_format($this->amount/100, 2, ',', '.');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
