@extends('layouts.minimal')

@section('title')
Setup
@endsection

@section('css')
<link rel='stylesheet' href='/css/bootstrap.min.css'>
<link rel='stylesheet' href='/css/setup.css'>
@endsection

@section('content')
<div class="navbar bg-dark sticky-top">
    <a class="navbar-brand text-white" href="/">Polr</a>
</div>

<div class="container my-4">

    <div class='col-lg-6 p-3 mx-auto rounded bg-white'>
        <div class='text-center'>
            <img class='setup-logo' src='/img/logo.png'>
        </div>

        <form class='setup-form' method='POST' action='/setup'>
            <h4>Database Configuration</h4>

            <div class="mb-4">
                <label for="db-host" class="form-label">Database Host:</label>
                <input type='text' id="db-host" class='form-control' name='db:host' value='localhost'>
            </div>
            <div class="mb-4">
                <label for="db-port" class="form-label">Database Port:</label>
                <input type='text' id="db-port" class='form-control' name='db:port' value='3306'>
            </div>
            <div class="mb-4">
                <label for="db-username" class="form-label">Database Username:</label>
                <input type='text' id="db-username" class='form-control' name='db:username' value='root'>
            </div>
            <div class="mb-4">
                <label for="db-password" class="form-label">Database Password:</label>
                <input type='password' id="db-password" class='form-control' name='db:password' value='password'>
            </div>
            <div class="mb-4">
                <label for="db-name" class="form-label">Database Name:
                <span data-bs-title="Name of existing database. You must create the Polr database manually."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span></label>
                <input type='text' id="db-name" class='form-control' name='db:name' value='polr'>
            </div>


            <h4>Application Settings</h4>

            <div class="mb-4">
                <label for="app-name" class="form-label">Application Name:</label>
                <input type='text' id=="app-name" class='form-control' name='app:name' value='Polr'>
            </div>
            <div class="mb-4">
                <label for="app-protocol" class="form-label">Application Protocol:</label>
                <input type='text' id="app-protocol" class='form-control' name='app:protocol' value='http://'>
            </div>
            <div class="mb-4">
                <label for="app-url" class="form-label">Application URL (path to Polr; do not include http:// or trailing slash):</label>
                <input type='text' id="app-url" class='form-control' name='app:external_url' value='yoursite.com'>
            </div>

            <div class="mb-4">
                <label for="analytic" class="form-label">Advanced Analytics:
                    <span data-bs-title="Enable advanced analytics to collect data such as referers, geolocation, and clicks over time. Enabling advanced analytics reduces performance and increases disk space usage."
                        data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span>
                </label>
                <select id="analytic" name='setting:adv_analytics' class='form-select'>
                    <option value='false' selected='selected'>Disable advanced analytics</option>
                    <option value='true'>Enable advanced analytics</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="maxmind-key" class="form-label">MaxMind GeoIP License Key (required for advanced analytics only):</label>
                <input type='text' id="maxmind-key" class='form-control' name='maxmind:license_key' value=''>
            </div>
            <p class='text-muted'>
                To obtain a free MaxMind GeoIP license key, follow <a href="https://docs.polrproject.org/en/latest/user-guide/maxmind-license">these instructions</a> on Polr's documentation website.
            </p>

            <div class="mb-4">
                <label for="short-permission" class="form-label">Shortening Permissions:</label>
                <select name='setting:shorten_permission' id="short-permission" class='form-select'>
                    <option value='false' selected='selected'>Anyone can shorten URLs</option>
                    <option value='true'>Only logged in users may shorten URLs</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="public-interface" class="form-label">Public Interface:</label>
                <select name='setting:public_interface' id="public-interface" class='form-select'>
                    <option value='true' selected='selected'>Show public interface (default)</option>
                    <option value='false'>Redirect index page to redirect URL</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="disabled-short" class="form-label">404s and Disabled Short Links:</label>
                <select name='setting:redirect_404' id=="disabled-short" class='form-select'>
                    <option value='false' selected='selected'>Show an error message (default)</option>
                    <option value='true'>Redirect to redirect URL</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="redirect-url" class="form-label">Redirect URL:
                <span data-bs-title="Required if you wish to redirect the index page or 404s to a different website. To use Polr, login by directly heading to yoursite.com/login first."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span></label>
                <input type='text' id="redirect-url" class='form-control' name='setting:index_redirect' placeholder='http://your-main-site.com'>
            </div>
            <p class='text-muted'>
                If a redirect is enabled, you will need to go to
                http://yoursite.com/login before you can access the index
                page.
            </p>
            
            <div class="mb-4">
                <label for="ending-type" class="form-label">Default URL Ending Type:
                <span data-bs-title="If you choose to use pseudorandom strings, you will not have the option to use a counter-based ending."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span></label>
                <select name='setting:pseudor_ending' id="ending-type" class='form-select'>
                    <option value='false' selected='selected'>Use base62 or base32 counter (shorter but more predictable, e.g 5a)</option>
                    <option value='true'>Use pseudorandom strings (longer but less predictable, e.g 6LxZ3j)</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="ending-base" class="form-label">URL Ending Base:
                <span data-bs-title="This will have no effect if you choose to use pseudorandom endings."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span></label>
                <select name='setting:base' id="ending-base" class='form-select'>
                    <option value='32' selected='selected'>32 -- lowercase letters & numbers (default)</option>
                    <option value='62'>62 -- lowercase, uppercase letters & numbers</option>
                </select>
            </div>

            <h4>
                Admin Account Settings
                <span data-bs-title="These credentials will be used for your admin account in Polr."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span>
            </h4>

            <div class="mb-4">
                <label for="admin-username" class="form-label">Admin Username:</label>
                <input type='text' id="admin-username" class='form-control' name='acct:username' value='polr'>
            </div>
            <div class="mb-4">
                <label for="admin-email" class="form-label">Admin Email:</label>
                <input type='text' id="admin-email" class='form-control' name='acct:email' value='polr@admin.tld'>
            </div>
            <div class="mb-4">
                <label for="admin-password" class="form-label">Admin Password:</label>
                <input type='password' id="admin-password" class='form-control' name='acct:password' value='polr'>
            </div>

            <h4>
                SMTP Settings
                <span data-bs-title="Required only if the email verification or password recovery features are enabled."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span>
            </h4>

            <div class="mb-4">
                <label for="smtp-server" class="form-label">SMTP Server:</label>
                <input type='text' id="smtp-server" class='form-control' name='app:smtp_server' placeholder='smtp.gmail.com'>
            </div>
            <div class="mb-4">
                <label for="smtp-port" class="form-label">SMTP Port:</label>
                <input type='text' id="smtp-port" class='form-control' name='app:smtp_port' placeholder='25'>
            </div>
            <div class="mb-4">
                <label for="smtp-username" class="form-label">SMTP Username:</label>
                <input type='text' id="smtp-username" class='form-control' name='app:smtp_username' placeholder='example@gmail.com'>
            </div>
            <div class="mb-4">
                <label for="smtp-password" class="form-label">SMTP Password:</label>
                <input type='password' id="smtp-password" class='form-control' name='app:smtp_password' placeholder='password'>
            </div>
            <div class="mb-4">
                <label for="smtp-from" class="form-label">SMTP From:</label>
                <input type='text' id="smtp-from" class='form-control' name='app:smtp_from' placeholder='example@gmail.com'>
            </div>
            <div class="mb-4">
                <label for="smtp-fromname" class="form-label">SMTP From Name:</label>
                <input type='text' id="smtp-fromname" class='form-control' name='app:smtp_from_name' placeholder='noreply'>
            </div>


            <h4>API Settings</h4>
            
            <div class="mb-4">
                <label for="api-stauts" class="form-label">Anonymous API:</label>
                <select name='setting:anon_api' id="api-stauts" class='form-select'>
                    <option selected value='false'>Off -- only registered users can use API</option>
                    <option value='true'>On -- empty key API requests are allowed</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="api-quota" class="form-label">Anonymous API Quota:
                <span data-bs-title="API quota for non-authenticated users per minute per IP."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span></label>
                <input type='text' id="api-quota" class='form-control' name='setting:anon_api_quota' placeholder='10'>
            </div>
            <div class="mb-4">
                <label for="api-key" class="form-label">Automatic API Assignment:</label>
                <select name='setting:auto_api_key' id="api-key" class='form-select'>
                    <option selected value='false'>Off -- admins must manually enable API for each user</option>
                    <option value='true'>On -- each user receives an API key on signup</option>
                </select>
            </div>


            <h4>Other Settings</h4>

            <div class="mb-4">
                <label for="register-status" class="form-label">Registration:
                <span data-bs-title="Enabling registration allows any user to create an account."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span></label>
                <select name='setting:registration_permission' id="register-status" class='form-select'>
                    <option value='none'>Registration disabled</option>
                    <option value='email'>Enabled, email verification required</option>
                    <option value='no-verification'>Enabled, no email verification required</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="email-domain" class="form-label">Restrict Registration Email Domains:
                <span data-bs-title="Restrict registration to certain email domains."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span></label>
                <select name='setting:restrict_email_domain' id="email-domain" class='form-select'>
                    <option value='false'>Allow any email domain to register</option>
                    <option value='true'>Restrict email domains allowed to register</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="email-domain-status" class="form-label">Permitted Email Domains:
                <span data-bs-title="A comma-separated list of emails permitted to register."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span></label>
                <input type='text' id="email-domain-status" class='form-control' name='setting:allowed_email_domains' placeholder='company.com,company-corp.com'>
            </div>
            <div class="mb-4">
                <label for="password-recovery" class="form-label">Password Recovery:
                <span data-bs-title="Password recovery allows users to reset their password through email."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span></label>
                <select name='setting:password_recovery' id="password-recovery" class='form-select'>
                    <option value='false'>Password recovery disabled</option>
                    <option value='true'>Password recovery enabled</option>
                </select>
            </div>
            <p class='text-muted'>
                Please ensure SMTP is properly set up before enabling password recovery.
            </p>

            <div class="mb-4">
                <label for="recaptcha-status" class="form-label">Require reCAPTCHA for Registrations
                <span data-bs-title="You must provide your reCAPTCHA keys to use this feature."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span></label>
                <select name='setting:acct_registration_recaptcha' id="recaptcha-status" class='form-select'>
                    <option value='false'>Do not require reCAPTCHA for registration</option>
                    <option value='true'>Require reCATPCHA for registration</option>
                </select>
            </div>

            <p>
                reCAPTCHA Configuration:
                <span data-bs-title="You must provide reCAPTCHA keys if you intend to use any reCAPTCHA-dependent features."
                    data-bs-toggle="tooltip" class="btn btn-sm btn-dark">?</span>
            </p>

            <div class="mb-4">
                <label for="recaptcha-site-key" class="form-label">reCAPTCHA Site Key:</label>
                <input type='text' id="recaptcha-site-key" class='form-control' name='setting:recaptcha_site_key'>
            </div>
            <div class="mb-4">
                <label for="recaptcha-secret-key" class="form-label">reCAPTCHA Secret Key:</label>
                <input type='text' id="recaptcha-secret-key" class='form-control' name='setting:recaptcha_secret_key'>
            </div>
            <p class='text-muted'>
                You can obtain reCAPTCHA keys from <a href="https://www.google.com/recaptcha/admin">Google's reCAPTCHA website</a>.
            </p>


            <div class='text-center'>
                <input type='submit' class='btn btn-success' value='Install'>
                <input type='reset' class='btn btn-warning' value='Clear Fields'>
            </div>
            <input type="hidden" name='_token' value='{{csrf_token()}}' />
        </form>
    </div>

</div>

<div class='p-3 text-center bg-white'>
    Polr is <a class='link-secondary' href='https://opensource.org/osd' target='_blank'>open-source
    software</a> licensed under the <a class='link-secondary' href='//www.gnu.org/copyleft/gpl.html'>GPLv2+
    License</a>.

    Polr Version {{env('VERSION')}} released {{env('VERSION_RELMONTH')}} {{env('VERSION_RELDAY')}}, {{env('VERSION_RELYEAR')}} -
    <a class='link-secondary' href='//github.com/cydrobolt/polr' target='_blank'>Github</a>

    <div class='mt-3'>
        &copy; Copyright {{env('VERSION_RELYEAR')}}
        <a class='link-secondary' href='//cydrobolt.com' target='_blank'>Chaoyi Zha</a> &amp;
        <a class='link-secondary' href='//github.com/Cydrobolt/polr/graphs/contributors' target='_blank'>other Polr contributors</a>
    </div>
</div>

@endsection

@section('js')
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/SetupCtrl.js"></script>
@endsection
