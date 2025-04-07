<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ConditionGroup extends Model
{
    use HasFactory;

    // protected $connection = 'tenant';
    protected $table = 'condition_groups';

    protected $fillable = [
        'name',
    ];

    /**
     * The conditions that belong to the condition group.
     */
    public function conditions(): BelongsToMany
    {
        return $this->belongsToMany(Condition::class, 'condition_condition_group');
    }

    /**
     * The form definitions that belong to the condition group.
     */
    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(FormDefinition::class, 'condition_group_form_definition');
    }
}
