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
                return '<a class="activate-api-modal btn btn-sm btn-info"
                    onclick="$scope.openAPIModal(\'' . $user->api_key . '\', \'' . $user->api_active . '\', \'' . e($user->api_quota) . '\', \'' . $user->id . '\')">
                    API info
                </a>';
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

                return '<a class="btn btn-sm status-display' . $btn_color_class . $btn_class . '" onclick="$scope.toggleUserActiveStatus(event, ' . $user->id . ')">' . $active_text . '</a>';
            })
            ->addColumn('change_role', function(User $user) {
                // Add "change role" select box
                // <select> field does not use Angular bindings
                // because of an issue affecting fields with duplicate names.

                $select_role = '<select onchange="$scope.changeUserRole(event, '.$user->id.')"
                    class="form-control"';

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
            ->addColumn('delete', function(User $user) {
                // Add "Delete" action button
                $btn_class = '';
                if (session('username') === $user->username) {
                    $btn_class = 'disabled';
                }
                return '<a onclick="$scope.deleteUser(event, \''. $user->id .'\')" class="btn btn-sm btn-danger ' . $btn_class . '">
                    Delete
                </a>';
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

                return '<a onclick="$scope.toggleLink(event, \'' . e($link->short_url) . '\')" class="btn btn-sm ' . $btn_class . '">
                    ' . $btn_text . '
                </a>';
            })
            ->addColumn('delete', function(Link $link) {
                // Add "Delete" action button
                return '<a onclick="$scope.deleteLink(event, \'' . e($link->short_url)  . '\')"
                    class="btn btn-sm btn-warning delete-link">
                    Delete
                </a>';
            })
            ->editColumn('clicks', function(Link $link) {
                if (env('SETTING_ADV_ANALYTICS')) {
                    return $link->clicks . ' <a target="_blank" class="stats-icon" href="/admin/stats/' . e($link->short_url) . '">
                        <i class="fa fa-area-chart" aria-hidden="true"></i>
                    </a>';
                }
                else {
                    return $link->clicks;
                }
            })
            ->editColumn('long_url', function(Link $link) {
                return '<a target="_blank" title="' . e($link->long_url) . '" href="'. e($link->long_url) .'">' . e(Str::limit($link->long_url, 50)) . '</a>
                    <a class="btn btn-primary btn-xs edit-long-link-btn" onclick="$scope.editLongLink(\'' . e($link->short_url) . '\', \'' . e($link->long_url) . '\')"><i class="fa fa-edit edit-link-icon"></i></a>';
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
                    return $link->clicks . ' <a target="_blank" class="stats-icon" href="/admin/stats/' . e($link->short_url) . '">
                        <i class="fa fa-area-chart" aria-hidden="true"></i>
                    </a>';
                }
                else {
                    return $link->clicks;
                }
            })
            ->editColumn('long_url', function(Link $link) {
                return '<a target="_blank" title="' . e($link->long_url) . '" href="'. e($link->long_url) .'">' . e(Str::limit($link->long_url, 50)) . '</a>
                    <a class="btn btn-primary btn-xs edit-long-link-btn" onclick="$scope.editLongLink(\'' . e($link->short_url) . '\', \'' . e($link->long_url) . '\')"><i class="fa fa-edit edit-link-icon"></i></a>';
            })
            ->escapeColumns(['short_url'])
            ->make(true);
    }
}
