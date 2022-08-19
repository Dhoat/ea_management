<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class AttendenceRequest extends Model
{
    protected $table = 'attendence_request';

    const STATUS_PENDING = 0;
    const STATUS_APPROVE = 1;
    const STATUS_REJECT  = 2;

    const REQUEST_TYPE_LEAVE   = 1;
    const REQUEST_TYPE_HALFDAY = 2;
    const REQUEST_TYPE_CHECK_IN_MISS = 3;
    CONST REQUEST_TYPE_CHECK_OUT_MISS = 4;
    CONST REQUEST_TYPE_CHECK_IN_OUT_MISS = 5;
    CONST REQUEST_TYPE_CHECK_IN_TECHNICAL_ERROR = 6;
    CONST REQUEST_TYPE_CHECK_OUT_TECHNICAL_ERROR = 7;
    CONST REQUEST_TYPE_CHECK_IN_OUT_TECHNICAL_ERROR = 8;


    const STATUS = [
        self::STATUS_PENDING => 'pending',
        self::STATUS_APPROVE => 'approved',
        self::STATUS_REJECT  => 'rejected',
    ];

}
