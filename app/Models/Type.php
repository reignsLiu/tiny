<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;

class Type extends BaseModel
{
    protected $hasDefaultValuesFields = ['order'];

    protected $fillable = ['name', 'description', 'order', 'model_name'];

    public $timestamps = false;

    public static $modelMapWithType = [
        'link' => Link::class,
        'banner' => Banner::class,
        'setting' => Setting::class,
    ];

    public function scopeByModel(Builder $query, $model)
    {
        if (isset(self::$modelMapWithType[$model])) {
            return $query->where('model_name', self::$modelMapWithType[$model]);
        } else {
            return $query->whereNull('model_name');
        }

    }

    public function __call($method, $args)
    {
        $mapKey = substr($method, 0, -1);
        if (isset(self::$modelMapWithType[$mapKey])) {
            return $this->hasMany(self::$modelMapWithType[$mapKey]);
        } else {
            return parent::__call($method, $args);
        }
    }

    public static function createType(array $data)
    {
        if (isset(self::$modelMapWithType[$data['model_name']])) {
            $data['model_name'] = self::$modelMapWithType[$data['model_name']];
            return static::create($data);
        } else {
            return false;
        }
    }
}