<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\MedicalConsultancyController;
use App\Http\Controllers\LodgingBookingController;
use App\Http\Controllers\EmergencyDeskController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\DoctorProfileController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\AirAmbulanceController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\PatientStoryController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\FreeConsultationController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::controller(AdmissionController::class)->group(function () {
    Route::post('/add/admission', 'store');
    Route::get('/get/admission/{id?}', 'index');
});

Route::controller(MedicalConsultancyController::class)->group(function () {
    Route::post('/add/medical-consultancy', 'store');
    Route::get('/get/medical-consultancy/{id?}', 'index');
});

Route::controller(LodgingBookingController::class)->group(function () {
    Route::post('/add/lodging-booking', 'store');
    Route::get('/get/lodging-booking/{id?}', 'index');
});

Route::controller(EmergencyDeskController::class)->group(function () {
    Route::post('/add/emergency-desk', 'store');
    Route::get('/get/emergency-desk/{id?}', 'index');
});

Route::controller(CenterController::class)->group(function () {
    Route::get('/get/centers/{slug?}/{id?}', 'index')->name('get.center');
    Route::post('/add/center', 'store');
    Route::post('/update/center/{id}', 'update');
    Route::get('/search/center/{name}', 'search');
});

Route::controller(SpecialtyController::class)->group(function () {
    Route::get('get/specialty', 'index');
    Route::post('add/specialty', 'store');
    Route::get('get/sub/specialty', 'subIndex');
    Route::post('add/sub/specialty', 'subStore');
    Route::get('get/selected/sub/specialty/{specialty}', 'selected');
});

Route::controller(DoctorProfileController::class)->group(function () {
    Route::get('get/doctors', 'index')->name('get.doctor');
    Route::post('add/doctor', 'store');
    Route::post('update/doctor/{id}', 'update');
    Route::get('/search/doctor', 'search');
    Route::get('/search/doctor/{slug}', 'show');
});

Route::controller(PackageController::class)->group(function () {
    Route::get('/get/package/{slug?}/{id?}', 'index')->name('get.package');
    Route::post('/create/package', 'store');
    Route::post('/update/package/{id}', 'update');
    Route::get('/search/package/{name}', 'search');

    Route::get('/get/sub/package/{slug?}/{id?}', 'subIndex')->name('get.sub_package');
    Route::post('/create/sub/package', 'subStore');
    Route::post('/update/sub/package/{id}', 'subUpdate');
    Route::get('/get/sub/packages/{parentSlug}', 'subByParent');
});

Route::controller(AirAmbulanceController::class)->group(function () {
    Route::get('/get/air/ambulance/hubs', 'hubIndex');
    Route::post('/create/air/ambulance/hub', 'hubStore');
    Route::post('/update/air/ambulance/hub/{id}', 'hubUpdate');

    Route::get('/get/air/ambulance/{id?}', 'index')->name('get.air_ambulance');
    Route::post('/add/air/ambulance', 'store');
    Route::get('/delete/air_ambulances/{id}', 'destroy');
});

Route::controller(NewsController::class)->group(function () {
    Route::get('/get/news/{id?}', 'index')->name('get.news');
    Route::post('/add/news', 'store');
    Route::post('/update/news/{id}', 'update');
    Route::get('/delete/news/{id}', 'destroy');
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/get-all-blogs', 'paginated');
    Route::get('/get/blogs/{slug?}', 'index')->name('get.blogs');
    Route::post('/add/blogs', 'store');
    Route::post('/update/blogs/{id}', 'update');
    Route::get('/delete/blogs/{id}', 'destroy');
});

Route::controller(InsuranceController::class)->group(function () {
    Route::get('/get/insurance-providers', 'index');
    Route::post('/create/insurance-provider', 'store');
    Route::post('/update/insurance-provider/{id}', 'update');
    Route::get('/delete/insurance-provider/{id}', 'destroy');
});

Route::controller(PatientStoryController::class)->group(function () {
    Route::get('/get/patient-stories', 'index');
    Route::post('/add/patient-story', 'store');
    Route::get('/get/patient-stories/admin', 'adminIndex');
    Route::post('/update/patient-story/{id}', 'update');
    Route::get('/delete/patient-story/{id}', 'destroy');
});

Route::controller(SiteSettingController::class)->group(function () {
    Route::get('/get/site-settings', 'index');
    Route::post('/update/site-settings', 'update');
});

Route::controller(FreeConsultationController::class)->group(function () {
    Route::post('/add/free-consultation', 'store');
    Route::get('/get/free-consultations', 'index');
});


Route::controller(DoctorController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('personal/appointment/{id}', 'personal_appointment');
    Route::post('/add/tele/medicine', 'tele_medicines');
    Route::post('/add/visa/precessing', 'add_visa_processing')->name('get.visa');
    Route::post('/add/order/medicine', 'order_medicine');
});
Route::controller(DoctorController::class)->group(function () {
    // 1-5. specialty, sub specialty, doctors, packages, sub packages:
    // moved to SpecialtyController / DoctorProfileController / PackageController.

    // 6. clinic and centers: moved to CenterController (see below).
    Route::get('/delete/{center}/{id}', 'delete_record');

    // 7. air ticket
    Route::get('/get/air/ticket/{id?}', 'get_air_ticket')->name('get.air_ticket');
    Route::post('/add/air/ticket', 'air_ticket');

    // 8. air pickup
    Route::get('/get/air/pickup/{id?}', 'get_air_pickup')->name('get.air_pickup');
    Route::post('/add/air/pickup', 'air_pickup');

    // 9. air ambulance: moved to AirAmbulanceController (see above).

    // 10. order medicine
    Route::get('/get/order/medicine/{id?}', 'get_order_medicine')->name('get.order_medicine');

    // 11. tele medicine
    Route::get('/get/tele/medicine/{id?}', 'get_tele_medicine')->name('get.tele_medicine');

    // 12. medical Report
    Route::get('/get/medical/report/{id?}', 'get_medical_report')->name('get.medical_record');
    Route::post('/add/medical/report', 'medical_report');
    
    // 13. medical Report
    Route::get('/get/doctor/appointments/{id?}', 'get_doctor_appointment')->name('get.doctor_appointment');
    Route::post('/add/doctor/appointment', 'doctor_appointment');
    
    // 14. Question quary
    Route::post('/add/question', 'add_question');
    Route::get('/get/questions/{id?}', 'get_question')->name('get.query');
    
    // 15. Health checkups
    Route::get('/get/health/check_up', 'get_health_checkup')->name('get.health_checkup');
    Route::post('/add/health/check_up', 'add_health_checkup');
    Route::get('/get/health/check_up/{id}', 'get_health_checkup_by');
    
    // 16. package booking
    Route::get('get/package_booking/{id?}', 'get_package_booking')->name('get.health_package');
    Route::post('/add/package/booking', 'add_package_booking');
    
    // 17. users data
    Route::get('get/users/{id?}', 'get_users')->name('get.patient');
    Route::get('review/appointment/{id}', 'review_appointment');
    Route::get('appointment/success/{id}', 'appointment_success');
    
    // 18. visa processing
    Route::get('/get/visa/precessing/{id?}', 'get_visa_processing');

    // 19-20. news, blog: moved to NewsController / BlogController (see below).

    // 21. count of all categories
    Route::get('get/category/length', 'category_length');
    
    // 21. must remove later
    Route::get('send_mail/', 'send_mail');
    Route::get('test', function(){
        foreach(DB::table('sub_specialties')->get() as $sub_specialty){
            $new = str_replace('&', 'and', $sub_specialty->specialty);
            echo $new.'<br>';
            DB::table('sub_specialties')->where('id', $sub_specialty->id)->update(['specialty'=>$new]);
            // echo $sub_specialty->sub_specialty.'<br>';
        }
        
    });
});
