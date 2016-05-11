<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiData extends JqGridBase
{
    protected $primaryKey = 'id';
    protected $table      = 'api_datas';
    protected $connection = 'mysql';
    //
}
