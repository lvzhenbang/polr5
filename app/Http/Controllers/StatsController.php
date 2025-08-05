<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Illuminate\Support\Carbon;

use Validator;
use DB;

use App\Models\Link;
use App\Models\Clicks;
use App\Helpers\StatsHelper;

class StatsController extends Controller {
    public function displayStats(Request $request, $short_url) {
        $validator = Validator::make($request->all(), [
            'left_bound' => 'date',
            'right_bound' => 'date'
        ]);

        if ($validator->fails() && !session('error')) {
            // Do not flash error if there is already an error flashed
            return redirect()->back()->with('error', 'Invalid date bounds.');
        }

        $user_left_bound = $request->input('left_bound');
        $user_right_bound = $request->input('right_bound');
        $type = $request->input('type');

        if (!$this->isLoggedIn()) {
            return redirect(route('login'))->with('error', 'Please login to view link stats.');
        }

        $link = Link::where('short_url', $short_url)
            ->first();

        // Return 404 if link not found
        if ($link == null) {
            return redirect(route('admin'))->with('error', 'Cannot show stats for nonexistent link.');
        }
        if (!env('SETTING_ADV_ANALYTICS')) {
            return redirect(route('login'))->with('error', 'Please enable advanced analytics to view this page.');
        }

        $link_id = $link->id;

        if ( (session('username') != $link->creator) && !self::currIsAdmin() ) {
            return redirect(route('admin'))->with('error', 'You do not have permission to view stats for this link.');
        }

        try {
            // Initialize StatHelper
            $stats = new StatsHelper($link_id, $type, $user_left_bound, $user_right_bound);
        }
        catch (\Exception $e) {
            if (!session('error')) {
                // Do not flash error if there is already an error flashed
                return redirect()->back()->with('error', 'Invalid date bounds. The right date bound must be more recent than the left bound.');
            }
        }

        $date_stats = $stats->getDateStats();
        $country_stats = $stats->getCountryStats();
        $referer_stats = $stats->getRefererStats();
        $browser_stats = $stats->getBrowserStats();
        $os_stats = $stats->getOsStats();
        $device_stats = $stats->getDeviceStats();

        return view('link_stats', [
            'link' => $link,
            'date_stats' => $date_stats['stats'],
            'country_stats' => $country_stats,
            'referer_stats' => $referer_stats,
            'browser_stats' => $browser_stats,
            'os_stats' => $os_stats,
            'device_stats' => $device_stats,

            'left_bound' => $date_stats['left_bound'],
            'right_bound' => $date_stats['right_bound'],
            'type' => $date_stats['type'],
            'date_type' => $date_stats['date_type'],

            'no_div_padding' => true
        ]);
    }

}
