<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApiLog extends Model
{
    protected $fillable = ['method', 'url', 'duration', 'query_count', 'ip'];

    protected $casts = [
        'duration' => 'float',
        'query_count' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s', 
    ];
    /**
     * get the 10 first url
     * @param mixed $query
     * @param mixed $limit
     */
    public function scopeSlowest($query, $limit = 10)
    {
        return $query->select('url', 'method', 
                DB::raw('AVG(duration) as avg_duration'), 
                DB::raw('COUNT(*) as request_count'))
            ->groupBy('url', 'method')
            ->orderByDesc('avg_duration')
            ->limit($limit);
    }
    /**
     * get The most consuming of the database
     * @param mixed $query
     * @param mixed $limit
     */
    public function scopeHeavyQueries($query, $limit = 10)
    {
        return $query->select('url', 'method', 
                DB::raw('AVG(query_count) as avg_queries'))
            ->groupBy('url', 'method')
            ->orderByDesc('avg_queries')
            ->limit($limit);
    }
}
