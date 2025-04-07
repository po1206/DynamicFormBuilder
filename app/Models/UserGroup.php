<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User; // Assuming central User model for now

class UserGroup extends Model
{
    use HasFactory;

    // protected $connection = 'tenant';
    protected $table = 'user_groups';

    protected $fillable = [
        'name',
    ];

    /**
     * The users that belong to the user group.
     * Note: This relationship might change based on the multi-tenancy user setup.
     */
    public function users(): BelongsToMany
    {
        // Adjust App\Models\User if using a tenant-specific user model
        return $this->belongsToMany(User::class, 'user_user_group');
    }

    /**
     * The permissions that belong to the user group.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user_group');
    }
}
