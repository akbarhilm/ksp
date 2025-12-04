<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutupBuku extends Model
{
    protected $table = 'tmtutupbuku';
    protected $primaryKey = 'id_tutupbuku';

    protected $fillable = [
        'tanggal',
       
        'id_entry'
    ];
     
    
}
