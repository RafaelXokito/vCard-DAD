<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [ 'vcard', 'date', 'datetime', 'type', 'value', 'old_balance', 'new_balance', 'payment_type',
                            'payment_reference', 'pair_transaction', 'pair_vcard', 'category_id', 'description',
                            'custom_options', 'custom_data'];

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type', 'code')->withTrashed();
    }

    public function vcard()
    {
        return $this->belongsTo(VCard::class, 'vcard', 'phone_number')->withTrashed();
    }

    //A card makes a debit transaction and other the credit transaction
    //this is the id of the other part of transaction
    public function pair_transaction()
    {
        return $this->hasOne(Transaction::class, 'pair_transaction')->withTrashed();
    }

    //A card makes a debit transaction and other the credit transaction
    //this is the phone_number (VCard) of the other part of transaction
    public function pair_vcard()
    {
        return $this->belongsTo(VCard::class, 'pair_vcard', 'phone_number')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }
}
