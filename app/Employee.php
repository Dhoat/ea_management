<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee';

    const LEAVING = 0;
    const WORKING = 1;
    
}
