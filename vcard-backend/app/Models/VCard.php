<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vcards';

    //Primary Key Settings (phone_number:string)
    protected $primaryKey = 'phone_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [ 'phone_number', 'name', 'email', 'photo_url', 'password', 'confirmation_code',
                            'blocked', 'balance', 'max_debit', 'custom_options', 'custom_data'];

    protected $casts = [
        'custom_data' => 'json',
        'custom_options' => 'json',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'vcard', 'phone_number')->withTrashed();
    }

    public function paired_vcard()
    {
        return $this->hasMany(Transaction::class, 'pair_vcard', 'phone_number')->withTrashed();
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'vcard', 'phone_number')->withTrashed();
    }

}
