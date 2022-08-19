<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    protected $table = 'attendence';

    const ABSENT   = 1;
    const PRESENT  = 2;
    const LEAVE    = 3;
    const CHECK_IN = 4;
    const HALFDAY  = 5;

    const STATUS = [
        self::ABSENT   => 'Absent',
        self::PRESENT  => 'Present',
        self::CHECK_IN => 'check_in',
        self::LEAVE    => 'leave',
        self::HALFDAY  => 'halfday',
    ];  


    protected $fillable = ['employee_id', 'date'];
    
}
