<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppDefinition extends Model
{
    use HasFactory;

    // Assuming tenant connection is handled by multi-tenancy package or middleware
    // protected $connection = 'tenant';

    protected $table = 'app_definitions';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The forms that belong to the app definition.
     */
    public function forms(): BelongsToMany
    {
        return $this->belongsToMany(FormDefinition::class, 'app_definition_form_definition');
    }
}
