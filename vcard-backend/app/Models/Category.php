<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [ 'vcard', 'type', 'name'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id')->withTrashed();
    }

    public function vcard()
    {
        return $this->belongsTo(VCard::class, 'vcard', 'phone_number')->withTrashed();
    }
}
