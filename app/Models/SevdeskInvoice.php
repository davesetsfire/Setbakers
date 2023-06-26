<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SevdeskInvoice extends Model {

    use HasFactory,
        SoftDeletes;

    protected $fillable = ['user_id', 'order_id', 'sevdesk_invoice_id', 'sevdesk_invoice_number', 'invoice_pdf_file_path', 'invoice_date'];

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {
            $model->created_by = \Auth::user()->id ?? 0;
            $model->updated_by = \Auth::user()->id ?? 0;
        });

        static::updating(function ($model) {
            $model->updated_by = \Auth::user()->id ?? 0;
        });
    }

}
