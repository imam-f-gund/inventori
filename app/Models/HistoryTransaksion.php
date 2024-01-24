<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTransaksion extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $table = 'history_transaksion';
    protected $fillable = [
        'product_id',
        'user_id',
        'qty',
        'date_input',
        'type',
        'note',
        'status'
    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
