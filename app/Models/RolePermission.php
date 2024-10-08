<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = "tr_role_permissions";
    protected $guarded = ['id'];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
