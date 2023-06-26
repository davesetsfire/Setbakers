<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectActivityLog extends Model {

    use HasFactory;

    protected $fillable = ['project_id', 'activity', 'subscription_end_date', 'created_by', 'updated_by'];

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
