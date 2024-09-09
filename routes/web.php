<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailFromRegistration;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/link/{slug}', [App\Http\Controllers\Participant\LinkController::class, 'show'])->name('link.participant');

Route::group(['middleware' => ['guest:participant']], function () {
    Route::get('/', function(){
        return redirect('/login');
    });

    Route::get('/login', [App\Http\Controllers\Participant\AuthController::class, 'index']);
    Route::post('/login', [App\Http\Controllers\Participant\AuthController::class, 'login'])->name('login.participant');

    Route::get('/register', [App\Http\Controllers\Participant\AuthController::class, 'register']);
    Route::post('/register', [App\Http\Controllers\Participant\AuthController::class, 'store'])->name('register.participant');

    Route::get('/verify-email/{token}', App\Http\Controllers\Participant\VerifyEmailController::class)->name('participant.verify-email');
    // Route::get('/resend-email/{token}', App\Http\Controllers\Participant\ResendEmailController::class)->name('participant.resend-email');

    Route::get('/forget-password', [App\Http\Controllers\Participant\ForgetPasswordController::class, 'index'])->name('index.forget-password.participant');
    Route::post('/forget-password', [App\Http\Controllers\Participant\ForgetPasswordController::class, 'sendEmail'])->name('store.forget-password.participant');

    Route::get('/change-password/{token}', [App\Http\Controllers\Participant\ChangePasswordController::class, 'index'])->name('index.change-password.participant');
    Route::post('/change-password/{token}', [App\Http\Controllers\Participant\ChangePasswordController::class, 'store'])->name('store.change-password.participant');

    Route::get('/provider/{provider}', [App\Http\Controllers\Participant\Vendor\AuthController::class, 'redirectToProvider'])->name('login.provider');
    Route::get('/provider/{provider}/callback', [App\Http\Controllers\Participant\Vendor\AuthController::class, 'handleProvideCallback']);
});

Route::group(['middleware' => ['auth:participant']], function () {
    Route::get('/', App\Http\Controllers\Participant\DashboardController::class)->name('participant.index');

    Route::get('/registrations/{event_id}', [\App\Http\Controllers\Participant\RegistrationController::class, 'create'])->name('create.registrations.participant');
    Route::post('/registrations', [\App\Http\Controllers\Participant\RegistrationController::class, 'store'])->name('store.registrations.participant');

    Route::post('/receipt/{event_id}/{no_registration}', [ \App\Http\Controllers\Participant\ReceiptController::class, 'store' ])->name('store.registrations.receipt');
    Route::delete('/receipt/{id}/{event_id}/{no_registration}', [ \App\Http\Controllers\Participant\ReceiptController::class, 'destroy' ])->name('destroy.registrations.receipt');

    Route::get('/transactions', [\App\Http\Controllers\Participant\TransactionController::class, 'index'])->name('index.transactions.participant');
    Route::get('/transactions/show/{event_id}/{no_registration}', [\App\Http\Controllers\Participant\TransactionController::class, 'show'])->name('show.transactions.participant');
    Route::get('/transactions/download/{event_id}/{no_registration}', [\App\Http\Controllers\Participant\TransactionController::class, 'download'])->name('download.transactions.participant');
    Route::get('/transactions/finish/{event_id}/{no_registration}', [\App\Http\Controllers\Participant\TransactionController::class, 'finish'])->name('finish.transactions.participant');
    Route::delete('/transactions/{event_id}/{no_registration}', [\App\Http\Controllers\Participant\TransactionController::class, 'destroy'])->name('destroy.transactions.participant');

    Route::get('/term-condition', [ \App\Http\Controllers\Participant\TermConditionController::class, 'index'])->name('term-condition');

    Route::post('/logout', [App\Http\Controllers\Participant\AuthController::class, 'logout'])->name('logout.participant');
});


Route::prefix('admin')->group(function() {
    Route::group(['middleware' => ['guest:admin']], function () {
        Route::get('/', function(){
             return redirect('/admin/login');
        });

        Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'index']);
        Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login');
    });

    Route::group(['middleware' => ['auth:admin']], function () {
        Route::get('/dashboard', App\Http\Controllers\Admin\DashboardController::class)
            ->middleware('permission:dashboard.index')->name('dashboard.index');

        Route::prefix('master')->group(function() {
            Route::resource('/group-seats', \App\Http\Controllers\Admin\Master\GroupSeatController::class, [ 'except' => ['show'] ])
            ->middleware('permission:master.group-seats.index|master.group-seats.create|master.group-seats.edit|master.group-seats.delete');

            Route::resource('/seats', \App\Http\Controllers\Admin\Master\SeatController::class, [ 'except' => ['show'] ])
            ->middleware('permission:master.seats.index|master.seats.create|master.seats.edit|master.seats.delete');

            Route::resource('/schedules', \App\Http\Controllers\Admin\Master\ScheduleController::class, [ 'except' => ['show'] ])
            ->middleware('permission:master.schedules.index|master.schedules.create|master.schedules.edit|master.schedules.delete');

            Route::resource('/events', \App\Http\Controllers\Admin\Master\EventController::class, [ 'except' => ['show'] ])
            ->middleware('permission:master.events.index|master.events.create|master.events.edit|master.events.delete');
        });

        Route::prefix('transaction')->group(function() {
            Route::resource('/registrations', \App\Http\Controllers\Admin\Transaction\RegistrationController::class, [ 'only' => ['index', 'show'] ])
            ->middleware('permission:transaction.registrations.index')->names([
                'index' => 'transaction.registrations.index',
                'show' => 'transaction.registrations.show',
            ]);

            Route::get('/registration/{event_id}/{registration_number}', [ \App\Http\Controllers\Admin\Transaction\RegistrationController::class, 'detail' ])->name('transaction.registrations.detail');

            Route::post('/seat', [ \App\Http\Controllers\Participant\SeatController::class, 'store' ])->name('transaction.seat.store');

            Route::resource('/participants', \App\Http\Controllers\Admin\Transaction\ParticipantController::class, [ 'only' => ['index'] ])
            ->middleware('permission:transaction.participants.index')->names([
                'index' => 'transaction.participants.index'
            ]);

            Route::get('/receipt/{event_id}/{registration_number}', [ \App\Http\Controllers\Admin\Transaction\ReceiptController::class, 'show' ])->name('transaction.receipt.show');
            Route::patch('/receipt/{event_id}/{registration_number}', [ \App\Http\Controllers\Admin\Transaction\ReceiptController::class, 'update' ])->name('transaction.receipt.update');

            Route::get('/registration-manual/create/{event_id}', [ \App\Http\Controllers\Admin\Transaction\RegistrationController::class, 'create' ])->name('transaction.registrations.create');
            Route::post('/registration-manual/{event_id}', [ \App\Http\Controllers\Admin\Transaction\RegistrationController::class, 'store' ])->name('transaction.registrations.store');

            Route::delete('/registration/{event_id}/{registration_number}', [ \App\Http\Controllers\Admin\Transaction\RegistrationController::class, 'destroy' ])->name('transaction.registrations.delete');
            Route::patch('/registration/{event_id}/{registration_number}', [ \App\Http\Controllers\Admin\Transaction\RegistrationController::class, 'update' ])->name('transaction.registrations.update');

            Route::get('/trash/{event_id}', [ \App\Http\Controllers\Admin\Transaction\TrashController::class, 'show' ])->name('transaction.trash.show');
            Route::post('/trash/{event_id}/{registration_number}', [ \App\Http\Controllers\Admin\Transaction\TrashController::class, 'restore' ])->name('transaction.trash.restore');
        });

        Route::prefix('report')->group(function() {
            Route::resource('/registrations', \App\Http\Controllers\Admin\Report\RegistrationController::class, [ 'only' => ['index', 'show'] ])
            ->middleware('permission:transaction.registrations.index')->names([
                'index' => 'report.registrations.index',
                'show' => 'report.registrations.show',
            ]);

            Route::get('/export/{event_id}',  [ \App\Http\Controllers\Admin\Report\UtilityController::class, 'export' ])->name('report.registrations.export');
        });

        Route::prefix('setting')->group(function() {
            Route::resource('/users', \App\Http\Controllers\Admin\Setting\UserController::class)
            ->middleware('permission:setting.users.index|setting.users.create|setting.users.edit|setting.users.delete');

            Route::resource('/form-fields', \App\Http\Controllers\Admin\Setting\FormFieldController::class)
            ->middleware('permission:setting.form_fields.index|setting.form_fields.create|setting.form_fields.edit|setting.form_fields.delete');

            Route::resource('/permissions', \App\Http\Controllers\Admin\Setting\PermissionController::class, [ 'only' => [ 'index', 'create', 'store' ] ])
            ->middleware('permission:setting.permissions.index');

            Route::resource('/roles', \App\Http\Controllers\Admin\Setting\RoleController::class, [ 'except' => [ 'show' ] ])
            ->middleware('permission:setting.roles.index|setting.roles.create|setting.roles.edit|setting.roles.delete');
        });

        Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    });
});

Route::prefix('testing')->group(function() {
    Route::get('/', [App\Http\Controllers\Sandbox\TestingController::class, 'index'])->name('testing.index');
});

