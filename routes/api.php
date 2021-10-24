<?php
use App\Http\Controllers\InvitedUsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('invite/user', [InvitedUsersController::class, 'invite_process']);
#Route::post('/users/invite', 'InvitedUsersController@pinvite_process')->name('invite_process');
Route::post('/invite/registration', [InvitedUsersController::class, 'registration']);
Route::post('login', [InvitedUsersController::class, 'login']);
Route::post('user/{id}', [InvitedUsersController::class, 'update']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
