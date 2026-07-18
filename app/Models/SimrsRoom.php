<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimrsRoom extends Model
{
    protected $connection = 'simrs';

    protected $table = 'master.ruangan';

    protected $primaryKey = 'ID';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];
}