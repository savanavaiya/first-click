<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/login', [HomeController::class,'login'])->name('login');

Route::get('/logout', [HomeController::class,'logout'])->name('logout');

Route::post('/form_submit', [HomeController::class,'form_submit'])->name('form_submit');

Route::get('/', [HomeController::class,'index'])->name('index')->middleware('Logincheck');

// Route::get('/gleichnerllc', [HomeController::class,'gleichnerllc'])->middleware('Logincheck');

Route::get('/contributor/{filter}', [HomeController::class,'contributor'])->name('contributor')->middleware('Logincheck');
Route::get('/contributor/approveuser/{id}', [HomeController::class,'approveuser'])->name('approveuser')->middleware('Logincheck');
Route::post('/contributor/rejectuser', [HomeController::class,'rejectuser'])->name('rejectuser')->middleware('Logincheck');
Route::post('/contributor/edituser', [HomeController::class,'edituser'])->name('edituser')->middleware('Logincheck');
Route::post('/contributor/removeuser', [HomeController::class,'removeuser'])->name('removeuser')->middleware('Logincheck');
Route::post('/contributor/createnewuser', [HomeController::class,'createnewuser'])->name('createnewuser')->middleware('Logincheck');



Route::get('/adsmanagement', [HomeController::class,'adsmanagement'])->name('adsmanagement')->middleware('Logincheck');
Route::post('/topadimage', [HomeController::class,'topadimage'])->name('topadimage')->middleware('Logincheck');
Route::get('/removetopadimage', [HomeController::class,'removetopadimage'])->name('removetopadimage')->middleware('Logincheck');
Route::post('/bottomadimage', [HomeController::class,'bottomadimage'])->name('bottomadimage')->middleware('Logincheck');
Route::get('/removebottomadimage', [HomeController::class,'removebottomadimage'])->name('removebottomadimage')->middleware('Logincheck');

Route::post('/storeadimage', [HomeController::class,'storeadimage'])->name('storeadimage')->middleware('Logincheck');
Route::get('/removestoreadimage', [HomeController::class,'removestoreadimage'])->name('removestoreadimage')->middleware('Logincheck');

Route::get('/totalstation', [HomeController::class,'totalstation'])->name('totalstation')->middleware('Logincheck');

Route::post('/totalstation/add/new', [HomeController::class,'addnewstation'])->name('addnewstation')->middleware('Logincheck');

Route::post('/totalstation/update', [HomeController::class,'updatestationdata'])->name('updatestationdata')->middleware('Logincheck');

Route::post('/totalstation/delete', [HomeController::class,'deletestationdata'])->name('deletestationdata')->middleware('Logincheck');

Route::get('/storepageright', [HomeController::class,'storepageright'])->name('storepageright')->middleware('Logincheck');

Route::get('/storepageright/for/approval', [HomeController::class,'storepagerightforapp'])->name('storepagerightforapp')->middleware('Logincheck');


Route::post('/updatesuperadmin/profile', [HomeController::class,'updatesuperadminprofile'])->name('updatesuperadminprofile')->middleware('Logincheck');

Route::get('/sync/spreadsheet', [HomeController::class,'sync'])->name('sync')->middleware('Logincheck');

Route::get('/getbrandlogo/portal', [HomeController::class,'getbrandlogopor'])->name('getbrandlogopor')->middleware('Logincheck');

Route::get('/brandsmanagement', [HomeController::class,'brandsmanagement'])->name('brandsmanagement')->middleware('Logincheck');
Route::post('/brandmanagement/add/brand/logo', [HomeController::class,'addnewbrandfromportal'])->name('addnewbrandfromportal')->middleware('Logincheck');
Route::post('/brandmanagement/edit/brand', [HomeController::class,'editnewbrandfromportal'])->name('editnewbrandfromportal')->middleware('Logincheck');
Route::post('/brandmanagement/remove/brand', [HomeController::class,'removebrandfromportal'])->name('removebrandfromportal')->middleware('Logincheck');

Route::get('/priceforapproval', [HomeController::class,'priceforapproval'])->name('priceforapproval')->middleware('Logincheck');
Route::post('/reject/price/request/id', [HomeController::class,'rejectpricereq'])->name('rejectpricereq')->middleware('Logincheck');
Route::post('/approve/price/request/id', [HomeController::class,'approvepricereq'])->name('approvepricereq')->middleware('Logincheck');
Route::get('/priceforapppageright', [HomeController::class,'priceforapppageright'])->name('priceforapppageright')->middleware('Logincheck');

Route::post('/update/priceforapp/req', [HomeController::class,'updatepriceforappreq'])->name('updatepriceforappreq')->middleware('Logincheck');


Route::post('/contributor/changes/need_approval_change', [HomeController::class,'need_approval_change'])->name('need_approval_change')->middleware('Logincheck');

Route::post('/reject/station/for/approval', [HomeController::class,'rejectforapprovastation'])->name('rejectforapprovastation')->middleware('Logincheck');
Route::post('/approve/station/for/approval', [HomeController::class,'approveforapprovastation'])->name('approveforapprovastation')->middleware('Logincheck');
Route::post('/update/station/for/approval', [HomeController::class,'updateforapprovalstationdata'])->name('updateforapprovalstationdata')->middleware('Logincheck');



Route::get('/admin/page', [HomeController::class,'semisuperadmin'])->name('semisuperadmin')->middleware('Logincheck');
Route::post('/add/semi_super_admin', [HomeController::class,'addsemisuperadmin'])->name('addsemisuperadmin')->middleware('Logincheck');
Route::post('/delete/semi_super_admin', [HomeController::class,'removesemisuperadmin'])->name('removesemisuperadmin')->middleware('Logincheck');


Route::get('/storefrontimageforapproval', [HomeController::class,'storeimageforapp'])->name('storeimageforapp')->middleware('Logincheck');
Route::get('/storeimgforapppageright', [HomeController::class,'storeimgforapppageright'])->name('storeimgforapppageright')->middleware('Logincheck');
Route::post('/reject/storefrontimage/request/id', [HomeController::class,'rejectstoreimgreq'])->name('rejectstoreimgreq')->middleware('Logincheck');
Route::post('/approve/storefrontimage/request/id', [HomeController::class,'approvestoreimgreq'])->name('approvestoreimgreq')->middleware('Logincheck');

Route::get('/mark/all/not_updated', [HomeController::class,'allnotupdated'])->name('allnotupdated')->middleware('Logincheck');

Route::post('/current/app/version/submit', [HomeController::class,'appversion_submit'])->name('appversion_submit')->middleware('Logincheck');
