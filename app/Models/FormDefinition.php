<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FormDefinition extends Model
{
    use HasFactory;

    // protected $connection = 'tenant';
    protected $table = 'form_definitions';

    protected $fillable = [
        'name',
        'parent_form_id',
    ];

    /**
     * Get the parent form definition that this form belongs to (if any).
     */
    public function parentForm(): BelongsTo
    {
        return $this->belongsTo(FormDefinition::class, 'parent_form_id');
    }

    /**
     * Get the child form definitions for this form definition.
     */
    public function childForms(): HasMany
    {
        return $this->hasMany(FormDefinition::class, 'parent_form_id');
    }

    /**
     * Get the fields for the form definition.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('order');
    }

    /**
     * The apps that this form definition belongs to.
     */
    public function apps(): BelongsToMany
    {
        return $this->belongsToMany(AppDefinition::class, 'app_definition_form_definition');
    }

    /**
     * The condition groups that belong to the form definition.
     */
    public function conditionGroups(): BelongsToMany
    {
        return $this->belongsToMany(ConditionGroup::class, 'condition_group_form_definition');
    }
}
