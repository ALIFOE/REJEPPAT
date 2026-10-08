<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('messages_contact')]
#[Fillable(['nom', 'telephone', 'email', 'objet', 'message', 'lu_le'])]
class MessageContact extends Model
{
    protected function casts(): array
    {
        return [
            'lu_le' => 'datetime',
        ];
    }

    public function scopeNonLu($query): void
    {
        $query->whereNull('lu_le');
    }
}
