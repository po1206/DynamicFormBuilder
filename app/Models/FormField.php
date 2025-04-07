<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    use HasFactory;

    // protected $connection = 'tenant';
    protected $table = 'form_fields';

    protected $fillable = [
        'form_definition_id',
        'name',
        'field_type',
        'validation_rules',
        'error_message',
        'order',
    ];

    protected $casts = [
        'validation_rules' => 'array', // Store validation rules as JSON
    ];

    /**
     * Get the form definition that owns the field.
     */
    public function formDefinition(): BelongsTo
    {
        return $this->belongsTo(FormDefinition::class);
    }
}
