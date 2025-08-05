<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use DB;
use Log;

use App\Models\Click;
use App\Models\Link;

class StatsHelper {
    function __construct($link_id, $type, $left_bound, $right_bound) {
        $this->link_id = $link_id;

        if ($type) {
            // If type is set, parse the left and right bounds
            $this->left_bound_parsed = Carbon::parse($left_bound);
            $this->right_bound_parsed = Carbon::parse($right_bound);
            $this->type = $type;
        } else {
            // If type is not set, default to current date bounds
            $this->left_bound_parsed = Carbon::now()->startOfDay();
            $this->right_bound_parsed = Carbon::now()->endOfDay();
            $this->type = 'custom_range'; // Default type
        }        

        if (!$this->left_bound_parsed->lte($this->right_bound_parsed)) {
            // If left bound is not less than or equal to right bound
            throw new \Exception('Invalid bounds.');
        }

        $days_diff = $this->left_bound_parsed->diffInDays($this->right_bound_parsed);
        $max_days_diff = env('_ANALYTICS_MAX_DAYS_DIFF') ?: 365;

        if ($days_diff > $max_days_diff) {
            throw new \Exception('Bounds too broad.');
        }
    }

    public function getBaseRows() {
        /**
        * Fetches base rows given left date bound, right date bound, and link id
        *
        * @param integer $link_id
        * @param string $left_bound
        * @param string $right_bound
        *
        * @return DB rows
        */

        return DB::table('clicks')
            ->where('link_id', $this->link_id)
            ->where('created_at', '>=', $this->left_bound_parsed)
            ->where('created_at', '<=', $this->right_bound_parsed);
    }

    public function getDayStats() {
        // Return stats by day from the last 30 days
        // date => x
        // clicks => y
        $stats = $this->getBaseRows()
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') AS x, count(*) AS y"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
            ->orderBy('x', 'asc')
            ->get();

        return $stats;
    }

    public function getHourStats() {
        // Return stats by hour
        // date => x
        // clicks => y
        $stats = $this->getBaseRows()
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %H') AS x, count(*) AS y"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %H')"))
            ->orderBy('x', 'asc')
            ->get();

        return $stats;
    }
    
    public function getWeekStats() {
        // Return stats by week
        // date => x
        // clicks => y
        $stats = $this->getBaseRows()
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m %u') AS x, count(*) AS y"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m %u')"))
            ->orderBy('x', 'asc')
            ->get();

        return $stats;
    }
    
    public function getMonthStats() {
        // Return stats by month
        // date => x
        // clicks => y
        $stats = $this->getBaseRows()
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') AS x, count(*) AS y"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('x', 'asc')
            ->get();

        return $stats;
    }

    public function getDateStats() {
        
        /**
         * Returns date stats based on the type and bounds
         *
         * @return array
         */

        if ($this->type == 'last_30_days') {
            $this->left_bound_parsed = Carbon::now()->subDays(30);
            $this->right_bound_parsed = Carbon::now();
            return [
                'date_type' => 'day', // Assuming 'day' is the type for daily stats
                'stats' => $this->getDayStats(), // Fetch daily stats
                'type' => $this->type,
                'left_bound' => $this->left_bound_parsed,
                'right_bound' => $this->right_bound_parsed,
            ];
        } elseif ($this->type == 'last_7_days') {
            $this->left_bound_parsed = Carbon::now()->subDays(7);
            $this->right_bound_parsed = Carbon::now();
            return [
                'date_type' => 'day', // Assuming 'day' is the type for daily stats
                'stats' => $this->getDayStats(), // Fetch daily stats
                'type' => $this->type,
                'left_bound' => $this->left_bound_parsed,
                'right_bound' => $this->right_bound_parsed,
            ];
        } elseif ($this->type == 'last_24_hours') {
            $this->left_bound_parsed = Carbon::now()->subHours(24);
            $this->right_bound_parsed = Carbon::now();
            return [
                'date_type' => 'hour', // Assuming 'hour' is the type for hourly stats
                'stats' => $this->getHourStats(), // Fetch hourly stats
                'type' => $this->type,
                'left_bound' => $this->left_bound_parsed,
                'right_bound' => $this->right_bound_parsed,
            ];
        } elseif ($this->type == 'this_month') {
            $this->left_bound_parsed = Carbon::now()->startOfMonth();
            $this->right_bound_parsed = Carbon::now();
            return [
                'date_type' => 'day', // Assuming 'day' is the type for daily stats
                'stats' => $this->getDayStats(), // Fetch daily stats
                'type' => $this->type,
                'left_bound' => $this->left_bound_parsed,
                'right_bound' => $this->right_bound_parsed,
            ];
        } elseif ($this->type == 'last_month') {
            $this->left_bound_parsed = Carbon::now()->subMonth()->startOfMonth();
            $this->right_bound_parsed = Carbon::now()->subMonth()->endOfMonth();
            return [
                'date_type' => 'day', // Assuming 'day' is the type for daily stats
                'stats' => $this->getDayStats(), // Fetch daily stats
                'type' => $this->type,
                'left_bound' => $this->left_bound_parsed,
                'right_bound' => $this->right_bound_parsed,
            ];
        } elseif ($this->type == 'this_year') {
            $this->left_bound_parsed = Carbon::now()->startOfYear();
            $this->right_bound_parsed = Carbon::now();
            return [
                'date_type' => 'month', // Assuming 'month' is the type for monthly stats
                'stats' => $this->getMonthStats(), // Fetch monthly stats
                'type' => $this->type,
                'left_bound' => $this->left_bound_parsed,
                'right_bound' => $this->right_bound_parsed,
            ];
        } elseif ($this->type == 'last_year') {
            $this->left_bound_parsed = Carbon::now()->subYear()->startOfYear();
            $this->right_bound_parsed = Carbon::now()->subYear()->endOfYear();
            return [
                'date_type' => 'month', // Assuming 'month' is the type for monthly stats
                'stats' => $this->getMonthStats(), // Fetch monthly stats
                'type' => $this->type,
                'left_bound' => $this->left_bound_parsed,
                'right_bound' => $this->right_bound_parsed,
            ];
        } elseif ($this->type == 'today') {
            $this->left_bound_parsed = Carbon::now()->startOfDay();
            $this->right_bound_parsed = Carbon::now()->endOfDay();
            return [
                'date_type' => 'hour', // Assuming 'hour' is the type for hourly stats
                'stats' => $this->getHourStats(), // Fetch hourly stats
                'type' => $this->type,
                'left_bound' => $this->left_bound_parsed,
                'right_bound' => $this->right_bound_parsed,
            ];
        } elseif ($this->type == 'yesterday') {
            $this->left_bound_parsed = Carbon::now()->subDay()->startOfDay();
            $this->right_bound_parsed = Carbon::now()->subDay()->endOfDay();
            return [
                'date_type' => 'hour', // Assuming 'hour' is the type for hourly stats
                'stats' => $this->getHourStats(), // Fetch hourly stats
                'type' => $this->type,
                'left_bound' => $this->left_bound_parsed,
                'right_bound' => $this->right_bound_parsed,
            ];
        } else {
            $this->right_bound_parsed = $this->right_bound_parsed->endOfDay();
            $days_diff = $this->left_bound_parsed->diffInDays($this->right_bound_parsed);

            if ($days_diff <= 1) {
                if ($this->left_bound_parsed->diffInHours($this->right_bound_parsed) <= 24) {
                    return [
                        'date_type' => 'hour', // Assuming 'hour' is the type for hourly stats
                        'stats' => $this->getHourStats(), // Fetch hourly stats
                        'type' => 'custom_range',
                        'left_bound' => $this->left_bound_parsed,
                        'right_bound' => $this->right_bound_parsed,
                    ];
                } else {
                    return [
                        'date_type' => 'day', // Assuming 'day' is the type for daily stats
                        'stats' => $this->getDayStats(), // Fetch daily stats
                        'type' => 'custom_range',
                        'left_bound' => $this->left_bound_parsed,
                        'right_bound' => $this->right_bound_parsed,
                    ];
                }
            } elseif ($days_diff <= 7) {
                return [
                    'date_type' => 'day', // Assuming 'day' is the type for daily stats
                    'stats' => $this->getDayStats(), // Fetch daily stats
                    'type' => 'custom_range',
                    'left_bound' => $this->left_bound_parsed,
                    'right_bound' => $this->right_bound_parsed,
                ];
            } elseif ($days_diff <= 30) {
                return [
                    'date_type' => 'day', // Assuming 'day' is the type for daily stats
                    'stats' => $this->getDayStats(), // Fetch daily stats
                    'type' => 'custom_range',
                    'left_bound' => $this->left_bound_parsed,
                    'right_bound' => $this->right_bound_parsed,
                ];
            } elseif ($days_diff <= 90) {
                return [
                    'date_type' => 'week', // Assuming 'week' is the type for weekly stats
                    'stats' => $this->getWeekStats(), // Fetch monthly stats
                    'type' => 'custom_range',
                    'left_bound' => $this->left_bound_parsed,
                    'right_bound' => $this->right_bound_parsed,
                ];
            } else {
                return [
                    'date_type' => 'month', // Assuming 'month' is the type for monthly stats
                    'stats' => $this->getMonthStats(), // Fetch monthly stats
                    'type' => 'custom_range',
                    'left_bound' => $this->left_bound_parsed,
                    'right_bound' => $this->right_bound_parsed,
                ];
            }
        }
    }

    public function getCountryStats() {
        $stats = $this->getBaseRows()
            ->select(DB::raw("country AS label, count(*) AS clicks"))
            ->groupBy('country')
            ->orderBy('clicks', 'desc')
            ->whereNotNull('country')
            ->get();

        return $stats;
    }

    public function getBrowserStats() {
        $stats = $this->getBaseRows()
            ->select(DB::raw("browser as label, count(*) as clicks"))
            ->groupBy('browser')
            ->orderBy('clicks', 'desc')
            ->get();

        return $stats;
    }

    public function getOsStats() {
        $stats = $this->getBaseRows()
            ->select(DB::raw("os as label, count(*) as clicks"))
            ->groupBy('os')
            ->orderBy('clicks', 'desc')
            ->get();

        return $stats;
    }

    public function getDeviceStats() {
        $stats = $this->getBaseRows()
            ->select(DB::raw("device as label, count(*) as clicks"))
            ->groupBy('device')
            ->orderBy('clicks', 'desc')
            ->get();

        return $stats;
    }
    

    public function getRefererStats() {
        $stats = $this->getBaseRows()
            ->select(DB::raw("COALESCE(referer_host, 'Direct') as label, count(*) as clicks"))
            ->groupBy('referer_host')
            ->orderBy('clicks', 'desc')
            ->get();

        return $stats;
    }
}
