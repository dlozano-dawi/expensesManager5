<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['userID', 'subject', 'price', 'paid', 'date', 'category'];
}
