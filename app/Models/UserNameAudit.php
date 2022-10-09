<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNameAudit extends Model
{
    //
    protected $table = 'user_name_audit';

    protected $fillable = [
        'user_id', 'old_name', 'new_name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id')
            ->select(['id', 'uuid', 'name', 'avatar', 'bio']);
    }

}
