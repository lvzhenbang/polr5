<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Str;
use DataTables;

use App\Models\Link;
use App\Models\User;

use App\Helpers\UserHelper;

class AdminPaginationController extends Controller {
    /**
     * Process AJAX Datatables pagination queries from the admin panel.
     *
     * @return Response
     */

    public function paginateAdminUsers(Request $request) {
        self::ensureAdmin();

        $admin_users = User::select(['username', 'email', 'created_at', 'active', 'api_key', 'api_active', 'api_quota', 'role', 'id']);
        return DataTables::of($admin_users)
            ->addColumn('api_action', function (User $user) {
                // Add "API Info" action button
                return '<button class="btn btn-sm btn-info"
                    onclick="$scope.openAPIModal(\'' . $user->api_key . '\', \'' . $user->api_active . '\', \'' . e($user->api_quota) . '\', \'' . $user->id . '\')">
                    API info
                </button>';
            })
            ->addColumn('toggle_active', function(User $user) {
                // Add user account active state toggle buttons
                $btn_class = '';
                if (session('username') === $user->username) {
                    $btn_class = ' disabled';
                }

                if ($user->active) {
                    $active_text = 'Active';
                    $btn_color_class = ' btn-success';
                }
                else {
                    $active_text = 'Inactive';
                    $btn_color_class = ' btn-danger';
                }

                return '<button class="btn btn-sm' . $btn_color_class . $btn_class . '" onclick="$scope.toggleUserActiveStatus(event, ' . $user->id . ')">' . $active_text . '</button>';
            })
            ->addColumn('change_role', function(User $user) {
                $select_role = '<select onchange="$scope.changeUserRole(event, '.$user->id.')"
                    class="form-select"';
                if (session('username') === $user->username) {
                    // Do not allow user to change own role
                    $select_role .= ' disabled';
                }
                $select_role .= '>';

                foreach (UserHelper::$USER_ROLES as $role_text => $role_val) {
                    // Iterate over each available role and output option
                    $select_role .= '<option value="' . e($role_val) . '"';

                    if ($user->role === $role_val) {
                        $select_role .= ' selected';
                    }

                    $select_role .= '>' . e($role_text) . '</option>';
                }

                $select_role .= '</select>';
                return $select_role;
            })
            ->editColumn('created_at', function(User $user) {
                return date('M. d, Y H:i:s', strtotime($user->created_at));
            })
            ->addColumn('delete', function(User $user) {
                $btn_class = '';
                if (session('username') === $user->username) {
                    $btn_class = 'disabled';
                }
                return '<button onclick="$scope.deleteUser(event, \''. $user->id .'\')" class="btn btn-sm btn-danger ' . $btn_class . '">
                    Delete
                </button>';
            })
            ->escapeColumns(['username', 'email'])
            ->make(true);
    }

    public function paginateAdminLinks(Request $request) {
        self::ensureAdmin();

        $admin_links = Link::select(['short_url', 'long_url', 'clicks', 'created_at', 'creator', 'is_disabled']);
        return DataTables::of($admin_links)
            ->addColumn('disable', function(Link $link) {
                // Add "Disable/Enable" action buttons
                $btn_class = 'btn-danger';
                $btn_text = 'Disable';

                if ($link->is_disabled) {
                    $btn_class = 'btn-success';
                    $btn_text = 'Enable';
                }

                return '<button onclick="$scope.toggleLink(event, \'' . e($link->short_url) . '\')" class="btn btn-sm ' . $btn_class . '">
                    ' . $btn_text . '
                </button>';
            })
            ->addColumn('delete', function(Link $link) {
                // Add "Delete" action button
                return '<button onclick="$scope.deleteLink(event, \'' . e($link->short_url)  . '\')" class="btn btn-sm btn-danger delete-link">
                    Delete
                </button>';
            })
            ->editColumn('clicks', function(Link $link) {
                if (env('SETTING_ADV_ANALYTICS')) {
                    return $link->clicks . ' <a target="_blank" class="btn btn-primary btn-sm" href="/admin/stats/' . e($link->short_url) . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1zm1 12h2V2h-2zm-3 0V7H7v7zm-5 0v-3H2v3z"></path>
                        </svg>
                    </a>';
                }
                else {
                    return $link->clicks;
                }
            })
            ->editColumn('long_url', function(Link $link) {
                return '<a target="_blank" class="long-link-url" title="' . e($link->long_url) . '" href="'. e($link->long_url) .'">' . e(Str::limit($link->long_url, 50)) . '</a>
                    <button class="btn btn-primary btn-sm" onclick="$scope.editLongLink(\'' . e($link->short_url) . '\', \'' . e($link->long_url) . '\')">
                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                        <path d="M360.132923 545.083077a7.798154 7.798154 0 0 0-1.496615 2.599385l-47.104 178.176a31.192615 31.192615 0 0 0 7.561846 29.459692 28.908308 28.908308 0 0 0 28.356923 7.719384l171.401846-48.285538 0.630154 0.157538c1.969231 0 3.938462-0.708923 5.356308-2.205538l458.436923-473.009231a76.957538 76.957538 0 0 0 21.031384-54.114461c0-23.630769-9.688615-47.261538-26.781538-64.748308l-43.244308-44.819692a88.536615 88.536615 0 0 0-62.779077-27.569231 72.231385 72.231385 0 0 0-52.460307 21.740308L360.841846 543.507692c-0.472615 0.472615-0.315077 1.102769-0.708923 1.575385z m578.323692-351.704615l-45.528615 46.946461-73.885538-77.351385 44.898461-46.316307c7.089231-7.404308 20.873846-6.301538 28.987077 2.205538l43.323077 44.662154c4.489846 4.726154 7.089231 10.948923 7.089231 17.014154a18.038154 18.038154 0 0 1-4.883693 12.839385zM441.659077 552.487385l330.830769-341.385847 73.728 77.430154-330.043077 340.755693-74.515692-76.8zM381.400615 690.806154l23.945847-90.584616 63.724307 65.851077-87.670154 24.733539z m560.915693-310.035692a33.161846 33.161846 0 0 0-32.689231 32.68923v442.604308a40.566154 40.566154 0 0 1-40.644923 41.038769h-704.984616c-22.528 0-39.148308-17.959385-39.148307-41.038769V127.684923c0-23.158154 16.620308-40.566154 39.069538-40.566154h483.879385c17.408 0 31.586462-15.990154 31.586461-33.949538 0-18.038154-14.178462-33.476923-31.586461-33.476923H159.113846C104.526769 19.692308 59.076923 66.402462 59.076923 122.801231v738.304c0 56.398769 45.371077 103.896615 100.036923 103.896615h714.673231c54.665846 0 101.218462-47.497846 101.218461-103.896615V413.302154a33.083077 33.083077 0 0 0-32.68923-32.452923z" fill="currentColor"></path>
                    </svg>
                </button>';
            })
            ->editColumn('created_at', function(Link $link) {
                return date('M. d, Y H:i:s', strtotime($link->created_at));
            })
            ->escapeColumns(['short_url', '$this->creator'])
            ->make(true);
    }

    public function paginateUserLinks(Request $request) {
        self::ensureLoggedIn();

        $username = session('username');
        $user_links = Link::where('creator', $username)
            ->select(['id', 'short_url', 'long_url', 'clicks', 'created_at']);

        return DataTables::of($user_links)
            ->editColumn('clicks', function(Link $link) {
                if (env('SETTING_ADV_ANALYTICS')) {
                    return $link->clicks . ' <a target="_blank" class="btn btn-primary btn-sm" href="/admin/stats/' . e($link->short_url) . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1zm1 12h2V2h-2zm-3 0V7H7v7zm-5 0v-3H2v3z"></path>
                        </svg>
                    </a>';
                }
                else {
                    return $link->clicks;
                }
            })
            ->editColumn('long_url', function(Link $link) {
                return '<a target="_blank" class="long-link-url" title="' . e($link->long_url) . '" href="'. e($link->long_url) .'">' . e(Str::limit($link->long_url, 50)) . '</a>
                    <button class="btn btn-primary btn-sm" onclick="$scope.editLongLink(\'' . e($link->short_url) . '\', \'' . e($link->long_url) . '\')">
                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
                        <path d="M360.132923 545.083077a7.798154 7.798154 0 0 0-1.496615 2.599385l-47.104 178.176a31.192615 31.192615 0 0 0 7.561846 29.459692 28.908308 28.908308 0 0 0 28.356923 7.719384l171.401846-48.285538 0.630154 0.157538c1.969231 0 3.938462-0.708923 5.356308-2.205538l458.436923-473.009231a76.957538 76.957538 0 0 0 21.031384-54.114461c0-23.630769-9.688615-47.261538-26.781538-64.748308l-43.244308-44.819692a88.536615 88.536615 0 0 0-62.779077-27.569231 72.231385 72.231385 0 0 0-52.460307 21.740308L360.841846 543.507692c-0.472615 0.472615-0.315077 1.102769-0.708923 1.575385z m578.323692-351.704615l-45.528615 46.946461-73.885538-77.351385 44.898461-46.316307c7.089231-7.404308 20.873846-6.301538 28.987077 2.205538l43.323077 44.662154c4.489846 4.726154 7.089231 10.948923 7.089231 17.014154a18.038154 18.038154 0 0 1-4.883693 12.839385zM441.659077 552.487385l330.830769-341.385847 73.728 77.430154-330.043077 340.755693-74.515692-76.8zM381.400615 690.806154l23.945847-90.584616 63.724307 65.851077-87.670154 24.733539z m560.915693-310.035692a33.161846 33.161846 0 0 0-32.689231 32.68923v442.604308a40.566154 40.566154 0 0 1-40.644923 41.038769h-704.984616c-22.528 0-39.148308-17.959385-39.148307-41.038769V127.684923c0-23.158154 16.620308-40.566154 39.069538-40.566154h483.879385c17.408 0 31.586462-15.990154 31.586461-33.949538 0-18.038154-14.178462-33.476923-31.586461-33.476923H159.113846C104.526769 19.692308 59.076923 66.402462 59.076923 122.801231v738.304c0 56.398769 45.371077 103.896615 100.036923 103.896615h714.673231c54.665846 0 101.218462-47.497846 101.218461-103.896615V413.302154a33.083077 33.083077 0 0 0-32.68923-32.452923z" fill="currentColor"></path>
                    </svg>
                </button>';
            })
            ->editColumn('created_at', function(Link $link) {
                return date('M. d, Y H:i:s', strtotime($link->created_at));
            })
            ->escapeColumns(['short_url'])
            ->make(true);
    }
}
