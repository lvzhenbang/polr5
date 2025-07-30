<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use DB;

use App\Models\Click;
use App\Models\Link;

class StatsHelper {
    function __construct($link_id, $left_bound, $right_bound) {
        $this->link_id = $link_id;
        $this->left_bound_parsed = Carbon::parse($left_bound);
        $this->right_bound_parsed = Carbon::parse($right_bound);

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
