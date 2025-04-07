<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Condition extends Model
{
    use HasFactory;

    // protected $connection = 'tenant';
    protected $table = 'conditions';

    protected $fillable = [
        'name',
        'sql_where_clause',
    ];

    /**
     * The condition groups that belong to the condition.
     */
    public function conditionGroups(): BelongsToMany
    {
        return $this->belongsToMany(ConditionGroup::class, 'condition_condition_group');
    }
}
