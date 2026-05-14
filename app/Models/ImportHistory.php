<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportHistory extends Model
{
    protected $table = 'import_history';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'file_name',
        'total_rows',
        'success_count',
        'failed_count',
        'skipped_count',
        'imported_by',
        'import_date',
    ];

    protected $casts = [
        'import_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
