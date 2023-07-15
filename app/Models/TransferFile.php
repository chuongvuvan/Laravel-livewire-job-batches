<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'disk',
        'path',
        'size'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'disk' => 'string',
        'path' => 'string',
        'size' => 'integer'
    ];

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(Transfer::class);
    }
}
