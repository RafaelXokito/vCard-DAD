<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payment_types';

    //Primary Key Settings (code:string)
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [ 'code', 'name', 'description', 'validation_rules', 'custom_options', 'custom_data'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'vcard', 'code')->withTrashed();
    }

    public function pair_vcards()
    {
        return $this->hasMany(Transaction::class, 'pair_vcard', 'code')->withTrashed();
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'vcard', 'code')->withTrashed();
    }
}
