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
        return $this->hasMany(Transaction::class, 'payment_type', 'code')->withTrashed();
    }
}
