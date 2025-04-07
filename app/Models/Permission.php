<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    // protected $connection = 'tenant'; // Or potentially 'landlord' if permissions are global
    protected $table = 'permissions';

    protected $fillable = [
        'name', // e.g., 'form_view', 'form_create', 'app_manage'
        'description',
    ];

    /**
     * The user groups that belong to the permission.
     */
    public function userGroups(): BelongsToMany
    {
        return $this->belongsToMany(UserGroup::class, 'permission_user_group');
    }
}
