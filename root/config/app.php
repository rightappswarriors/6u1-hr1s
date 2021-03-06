<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => env('TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /* -Added 6-25-2019 by Paolo- */
        Maatwebsite\Excel\ExcelServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,

        /* --Additional aliases-- */
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        /* --Additional aliases-- */

        /* --Beyond this line is custom aliases created by the developers-- */
        'Account' => App\Account::class,
        'BusinessUnits' => App\BusinessUnits::class,
        'ContributionRemittance' => App\ContributionRemittance::class,
        'Core' => App\Core::class,
        'Department' => App\Department::class,
        'DepartmentSection' => App\DepartmentSection::class,
        'DTR' => App\DTR::class,
        'Employee' => App\Employee::class,
        'EmployeeDeduction' => App\EmployeeDeduction::class,
        'EmployeeEarning' => App\EmployeeEarnings::class,
        'EmployeeFlag' => App\EmployeeFlag::class,
        'EmployeeLeaveCount' => App\EmployeeLeaveCount::class,
        'EmployeeShiftSchedule' => App\EmployeeShiftSchedule::class,
        'EmployeeStatus' => App\EmployeeStatus::class,
        'ErrorCode' => App\ErrorCode::class,
        'Export2_1' => App\Exports\Export2_1::class,
        'Exports\ExportBlade' => App\Exports\ExportBlade::class,
        'HDMF' => App\HDMF::class,
        'Holiday' => App\Holiday::class,
        'JobTitle' => App\JobTitle::class,
        'Leave' => App\Leave::class,
        'LeaveType' => App\LeaveType::class,
        'Loan' => App\Loan::class,
        'LoanType' => App\LoanType::class,
        'Notification_N' =>App\Notification_N::class,
        'Office' => App\Office::class,
        'OtherEarnings' => App\OtherEarnings::class,
        'OtherDeductions' => App\OtherDeductions::class,
        'Pagibig' => App\Pagibig::class,
        'Payroll' => App\Payroll::class,
        'PayrollPeriod' => App\PayrollPeriod::class,
        'PayrollRegister' => App\PayrollRegister::class,
        'Philhealth' => App\Philhealth::class,
        'Position' => App\Position::class,
        'RATA' => App\RATA::class,
        'ServiceRecord' => App\ServiceRecord::class,
        'SSS' => App\SSS::class,
        'ShiftSchedule' => App\ShiftSchedule::class,
        'Timelog' => App\Timelog::class,
        'Wtax' => App\Wtax::class,
        'X05' => App\X05::class,
        'X05S' => App\X05_sub::class,
        'X07' => App\X07::class,
        'X08' => App\X08::class,
        /* --Added aliases-- */
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        /* --Above this line is custom aliases created by the developers-- */
        'TestExport' => App\Exports\TestExport::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Values
    |--------------------------------------------------------------------------
    |
    | Beyond this portion of the file are the custom values that are added
    | by the developers.
    | 
    */
    
    /*
    |--------------------------------------------------------------------------
    | Currency Symbols
    |--------------------------------------------------------------------------
    */

    'c-symbols-def' => [
        'gcs' => '&#164;',
        'dollar' => '&#36;',
        'cent' => '&#162;',
        'pound' => '&#163;',
        'yen' => '&#165;',
        'franc' => '&#8355;',
        'lira' => '&#8356;',
        'peseta' => '&#8359;',
        'euro' => '&#128;',
        'rupee' => '&#x20B9;',
        'won' => '&#8361;',
        'hryvnia' => '&#8372;',
        'drachma' => '&#8367;',
        'tugrik' => '&#8366;',
        'german-penny' => '&#8368;',
        'guarani' => '&#8370;',
        'peso' => '&#8369;',
        'austral' => '&#8371;',
        'cedi' => '&#8373;',
        'kip' => '&#8365;',
        'new-sheqel' => '&#8362;',
        'dong' => '&#8363;',
        'percent' => '&#37;',
        'per-million' => '&#137;'
    ],

    'currency' => env('CURRENCY', 'peso'),

];
