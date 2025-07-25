$scope = {};

/* Initialize $scope variables */
$scope.datatables = {};
$scope.modals = {
    editLongLink: null,
    editUserApiInfo: null
};
$scope.newUserParams = {
    username: '',
    userPassword: '',
    userEmail: '',
    userRole: ''
};

$scope.syncHash = function() {
    var url = document.location.toString();
    if (url.match('#')) {
        $('.admin-nav a[href="#' + url.split('#')[1] + ']"').tab('show');
    }
};

// Initialise Datatables elements
$scope.initTables = function() {
    var datatables_config = {
        'autoWidth': false,
        'processing': true,
        'serverSide': true,

        'drawCallback': function () {
            // Compile Angular bindings on each draw
            // $compile($(this))($scope);
        }
    };

    if ($('#admin_users_table').length) {
        $scope.datatables['admin_users_table'] = $('#admin_users_table').DataTable($.extend({
            "ajax": BASE_API_PATH + 'admin/get_admin_users',

            "columns": [
                {className: 'wrap-text', data: 'username', name: 'username'},
                {className: 'wrap-text', data: 'email', name: 'email'},
                {data: 'created_at', name: 'created_at'},

                {data: 'toggle_active', name: 'toggle_active', orderable: false, searchable: false},
                {data: 'api_action', name: 'api_action', orderable: false, searchable: false},
                {data: 'change_role', name: 'change_role', orderable: false, searchable: false},
                {data: 'delete', name: 'delete', orderable: false, searchable: false}
            ]
        }, datatables_config));
    }
    if ($('#admin_links_table').length) {
        $scope.datatables['admin_links_table'] = $('#admin_links_table').DataTable($.extend({
            "ajax": BASE_API_PATH + 'admin/get_admin_links',

            "columns": [
                {className: 'wrap-text', data: 'short_url', name: 'short_url'},
                {className: 'wrap-text', data: 'long_url', name: 'long_url'},
                {data: 'clicks', name: 'clicks'},
                {data: 'created_at', name: 'created_at'},
                {data: 'creator', name: 'creator'},

                {data: 'disable', name: 'disable', orderable: false, searchable: false},
                {data: 'delete', name: 'delete', orderable: false, searchable: false}

            ]
        }, datatables_config));
    }

    $scope.datatables['user_links_table'] = $('#user_links_table').DataTable($.extend({
        "ajax": BASE_API_PATH + 'admin/get_user_links',

        "columns": [
            {className: 'wrap-text', data: 'short_url', name: 'short_url'},
            {className: 'wrap-text', data: 'long_url', name: 'long_url'},
            {data: 'clicks', name: 'clicks'},
            {data: 'created_at', name: 'created_at'}
        ]
    }, datatables_config));
};

$scope.reloadLinkTables = function () {
    // Reload DataTables for affected tables
    // without resetting page
    if ('admin_links_table' in $scope.datatables) {
        $scope.datatables['admin_links_table'].ajax.reload(null, false);
    }

    $scope.datatables['user_links_table'].ajax.reload(null, false);
};

$scope.reloadUserTables = function () {
    $scope.datatables['admin_users_table'].ajax.reload(null, false);
};


/*
    Initialisation
*/

// Initialise AdminCtrl
$scope.init = function() {
    $('.admin-nav a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $scope.syncHash();

    $(window).on('hashchange', function() {
        $scope.syncHash();
    });

    $('a[href^="#"]').on("click", function(e) {
        history.pushState({}, '', this.href);
    });

    $scope.initTables();
};

$scope.init();

/*
    User Management
*/
$scope.toggleUserActiveStatus = function(event, user_id) {
    var el = $(event.target);

    apiCall('admin/toggle_user_active', {
        'user_id': user_id,
    }, function(new_status) {
        var text = (new_status == 1) ? 'Active' : 'Inactive';
        el.text(text);
        if (el.hasClass('btn-success')) {
            el.removeClass('btn-success').addClass('btn-danger');
        }
        else {
            el.removeClass('btn-danger').addClass('btn-success');
        }
        toastr.success('User-status successfully changed.', 'Success');
    }, function(err) {
        toastr.error('User-status fails changed.', 'Error');
    });
}

// Generate new API key for user_id
$scope.generateNewAPIKey = function(event, user_id, is_dev_tab) {
    var el = $(event.target);
    var status_display_elem = el.prevAll('.status-display');

    if (is_dev_tab) {
        status_display_elem = el.parent().prev().children();
    }

    apiCall('admin/generate_new_api_key', {
        'user_id': user_id,
    }, function(new_status) {
        if (status_display_elem.is('input')) {
            status_display_elem.val(new_status);
        } else {
            status_display_elem.text(new_status);
        }
        toastr.success('Api-key successfully reseted.', 'Success');
    }, function(err) {
        toastr.error('Api-key fails reseted.', 'Error');
    });
};

$scope.checkNewUserFields = function() {
    var response = true;

    $('.new-user-fields input').each(function () {
        if ($(this).val().trim() == '' || response == false) {
            response = false;
        }
    });

    return response;
}

$scope.addNewUseModal = function(event) {
    $('.angular-modals').html(`<div id="edit-user-modal" class="modal fade in" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Long URL</h4>
                </div>
                <div class="modal-body">
                    Username: <input type="text" name="userName" class="form-control form-field" placeholder="Username">
                    Password: <input type="password" name="userPassword" class="form-control form-field" placeholder="Password">
                    Email: <input type="email" name="userEmail" class="form-control form-field" placeholder="Email">
                    role: <select name="userRole" class="form-control">
                            <option value="">default</option>
                            <option value="admin" selected="">admin</option>
                        </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Save">Save Changes</button>
                </div>
            </div>
        </div>
    </div>`);

    setTimeout(function () {
        $('#edit-user-modal').modal('show');
    });
}

$scope.addNewUser = function(event) {
    // Allow admins to add new users
    var el = $(event.target);
    if (!$scope.checkNewUserFields()) {
        toastr.error("Fields cannot be empty.", "Error");
        return false;
    }

    apiCall('admin/add_new_user', {
        'username': el.find('input [name="userName"]').val(),
        'user_password': el.find('input [name="userPassword"]').val(),
        'user_email': el.find('input [name="userEmail"]').val(),
        'user_role': el.find('input [name="userRole"]').val(),
    }, function(result) {
        toastr.success("User " + el.find('input [name="userName"]').val() + " successfully created.", "Success");
        $scope.reloadUserTables();
    }, function () {
        toastr.error("An error occured while creating the user.", "Error");
    });
}

// Delete user
$scope.deleteUser = function(event, user_id) {
    var delete_user_confirm = window.confirm('Are you sure you would like to delete this user?');

    if (delete_user_confirm) {
        apiCall('admin/delete_user', {
            'user_id': user_id,
        }, function(new_status) {
            toastr.success('User successfully deleted.', 'Success');
            $scope.reloadUserTables();
        }, function(err) {
            toastr.error('User fails deleted.', 'Error');
        });
    }
};

$scope.changeUserRole = function(event, user_id) {
    var el = $(event.target);
    apiCall('admin/change_user_role', {
        'user_id': user_id,
        'role': el.val(),
    }, function(result) {
        toastr.success("User role successfully changed.", "Success");
    }, function(err) {
        toastr.error('Quota fails changed.', 'Error');
    });
};

// Open user API settings menu
$scope.openAPIModal = function(api_key, api_active, api_quota, user_id) {

    $('.angular-modals').html(`<div class="modal fade in" id="edit-user-api-info-modal" tabindex="-1" role="dialog" style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Edit User API Settings</h4>
                </div>
                <div class="modal-body">
                    <p>
                        <span>API Active</span>:
                        <code class="status-display">${res_value_to_text(api_active)}</code>
                        <a onclick="$scope.toggleAPIStatus(${user_id})" class="btn btn-xs btn-success">toggle</a>
                    </p>

                    <p>
                        <span>API Key: </span><code class="apikey-display">${api_key ? api_key : ''}</code>
                        <a onclick="$scope.parentGenerateNewAPIKey(${user_id})" class="btn btn-xs btn-danger">reset</a>
                    </p>

                    <p>
                        <span>API Quota (req/min, -1 for unlimited):</span> <input type="number" class="form-control api-quota" value="${api_quota ? api_quota : 60}">
                        <a onclick="$scope.updateAPIQuota(${user_id})" class="btn btn-xs btn-warning">change</a>
                    </p>
                </div>
            </div>
        </div>
    </div>`);

    setTimeout(function () {
        $('#edit-user-api-info-modal').modal('show');
    });
};

// Toggle API access status
$scope.toggleAPIStatus = function(userId) {
    apiCall('admin/toggle_api_active', {
        'user_id': userId,
    }, function(new_status) {
        $('#edit-user-api-info-modal .status-display').text(res_value_to_text(new_status));
        toastr.success('Api-status successfully changed.', 'Success');
    }, function(err) {
        toastr.error('Api-status fails changed.', 'Error');
    });
};

// Generate new API key for user_id
$scope.parentGenerateNewAPIKey = function(userId) {    
    apiCall('admin/generate_new_api_key', {
        'user_id': userId,
    }, function(new_status) {
        $('#edit-user-api-info-modal .apikey-display').text(new_status);
        toastr.success('Api-key successfully reseted.', 'Success');
    }, function(err) {
        toastr.error('Api-key fails reseted.', 'Error');
    });
};

// Update user API quotas
$scope.updateAPIQuota = function(userId) {
    apiCall('admin/edit_api_quota', {
        'user_id': userId,
        'new_quota': $('#edit-user-api-info-modal').find('input').val()
    }, function(next_action) {
        toastr.success("Quota successfully changed.", "Success");
    }, function(err) {
        toastr.error('Quota fails changed.', 'Error');
    });
};

/*
    Link Management
*/

// Delete link
$scope.deleteLink = function(event, link_ending) {
    var delete_link_confirm = window.confirm('Are you sure you would like to delete this link?');

    if (delete_link_confirm) {
        apiCall('admin/delete_link', {
            'link_ending': link_ending,
        }, function(new_status) {
            toastr.success('Link successfully deleted.', 'Success');
            $scope.reloadLinkTables();
        }, function(err) {
            toastr.error('Link fails deleted.', 'Error');
        });
    }
};

// Disable and enable links
$scope.toggleLink = function(event, link_ending) {
    var el = $(event.target);
    var curr_action = el.text();

    apiCall('admin/toggle_link', {
        'link_ending': link_ending,
    }, function(next_action) {
        toastr.success(curr_action + " was successful.", "Success");
        if (next_action == 'Disable') {
            el.removeClass('btn-success');
            el.addClass('btn-danger');
        } else {
            el.removeClass('btn-danger');
            el.addClass('btn-success');
        }

        el.text(next_action);
    }, function(err) {
        toastr.error(curr_action + ' was fails.', 'Error');
    });
};

// Edit links' long_url
$scope.editLongLink = function(link_ending, old_long_link) {
    $('.angular-modals').html(`<div id="edit-long-link-modal" class="modal fade in" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Long URL</h4>
                </div>
                <div class="modal-body">
                    <input type="url" value="${old_long_link}" placeholder="Long URL..." class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="$scope.updateLongLink(event, ${link_ending})" class="btn btn-default" data-dismiss="modal" aria-label="Save">Save Changes</button>
                </div>
            </div>
        </div>
    </div>`);

    setTimeout(() => {
        $(`#edit-long-link-modal`).modal("show");
    });
    
}

$scope.updateLongLink = function(event, link_ending) {    
    apiCall('admin/edit_link_long_url', {
        'link_ending': link_ending,
        'new_long_url': $('#edit-long-link-modal').find('input').val()
    }, function(data) {
        toastr.success('The link successfully updated.', 'Success');
        $scope.reloadLinkTables();
    }, function(err) {
        toastr.error('The link fails updated.', 'Error');
    });
}
