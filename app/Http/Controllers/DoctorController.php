<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesFileUploads;
use App\Http\Traits\NotifiesAdmin;
use App\Models\Center;
use App\Models\VisaProcessing;
use App\Models\User;
use App\Models\HealthCheckUp;
use App\Models\Doctor;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers;
use App\Models\AirAmbulance;
use App\Models\AirPickup;
use App\Models\AirTicket;
use App\Models\OrderMedicine;
use App\Models\TeleMedicine;
use App\Models\Medicalreport;
use App\Models\Doctorappoinment;
use App\Models\Question;
use App\Models\PackageBooking;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;


class DoctorController extends Controller
{
    use HandlesFileUploads, NotifiesAdmin;

    /*--------------------------------------------------------------------------------------------------*/
    //                                       api functions
    /*--------------------------------------------------------------------------------------------------*/
    // 1-9. specialty, sub specialty, doctors: moved to SpecialtyController / DoctorProfileController.

    // 10-14. packages, sub packages: moved to PackageController.

    // 15-16. add/update/get clinics: moved to CenterController.

    // 17. air ticket
    public function air_ticket(\App\Http\Requests\AirTicketRequest $request)
    {
        $data = $request->validated();
        AirTicket::create($data);
        $this->notifyAdmin('Air Ticket Procurement', $data);

        $res = ['status' => 200, 'msg' => 'Ticket created.'];
        return response()->json($res);
    }

    // 18. get all air tickets
    public function get_air_ticket($id = '')
    {
        if ($id != '') {
            $air_tickets = AirTicket::where('id', $id)->first();
            if ($air_tickets) {
                $data = ['status' => 200, 'data' => $air_tickets];
            }
        } else {
            $air_tickets = AirTicket::get();
            if ($air_tickets->count() > 0) {
                $data = ['status' => 200, 'data' => $air_tickets];
            }
        }

        if (isset($data)) {
            return response()->json($data);
        } else {
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
    }

    // 19. air pickup
    public function air_pickup(\App\Http\Requests\AirPickupRequest $request)
    {
        $data = $request->validated();
        AirPickup::create($data);
        $this->notifyAdmin('Airport Pick & Drop', $data);

        $res = ['status' => 200, 'msg' => 'Airpickup created.'];
        return response()->json($res);
    }

    // 20. get all air tickets
    public function get_air_pickup($id = '')
    {
        if ($id != '') {
            $air_pickup = AirPickup::where('id', $id)->first();
            if ($air_pickup) {
                $data = ['status' => 200, 'data' => $air_pickup];
            }
        } else {
            $air_pickup = AirPickup::get();
            if ($air_pickup->count() > 0) {
                $data = ['status' => 200, 'data' => $air_pickup];
            }
        }

        if (isset($data)) {
            return response()->json($data);
        } else {
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
    }

    // 21-22. air ambulance: moved to AirAmbulanceController.

    // 23. order medicine
    public function order_medicine(\App\Http\Requests\OrderMedicineRequest $request)
    {
        $data = $request->validated();
        OrderMedicine::create($data);
        $this->notifyAdmin('Order Medicine', $data);

        $res = ['status' => 200, 'msg' => 'Order medicine created.'];
        return response()->json($res);
    }

    // 24. get all tele medicines
    public function get_order_medicine($id = '')
    {
        if ($id != '') {
            $order_medicine = OrderMedicine::where('id', $id)->first();
            if ($order_medicine) {
                $order_medicine->medicines = array_values((array) json_decode($order_medicine->medicines));
                $order_medicine->quantity = array_values((array) json_decode($order_medicine->quantity));
                
                // $order_medicine->medicines = json_decode($order_medicine->medicines);
                // $order_medicine->quantity = json_decode($order_medicine->quantity);
                $data = ['status' => 200, 'data' => $order_medicine];
            }
        } else {
            $order_medicine = OrderMedicine::get();
            if ($order_medicine->count() > 0) {
                $order_medicine->each(function ($item) {
                    
                     $item->medicines = array_values((array) json_decode($item->medicines));
                     $item->quantity = array_values((array) json_decode($item->quantity));
                    // $item->medicines = json_decode($item->medicines);
                    // $item->quantity = json_decode($item->quantity);
                });
                $data = ['status' => 200, 'data' => $order_medicine];
            }
        }

        if (isset($data)) {
            return response()->json($data);
        } else {
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
    }

    // 25. tele medicines
    public function tele_medicines(\App\Http\Requests\TeleMedicineRequest $request)
    {
        $data = $request->validated();
        $relPath = 'assets/docs/telemedicine/';
        $data['passport'] = $this->upload_file($request, $relPath, 'passport');

        TeleMedicine::create($data);

        $attachments = $data['passport'] ? [public_path($relPath . basename($data['passport']))] : [];
        $this->notifyAdmin('Telemedicine', $data, $attachments);

        $res = ['status' => 200, 'msg' => 'Tele medicine created.'];
        return response()->json($res);
    }

    // 26. get all tele medicines
    public function get_tele_medicine($id = '')
    {
        if ($id != '') {
            $tele_medicine = TeleMedicine::where('id', $id)->first();
            if ($tele_medicine) {
                $data = ['status' => 200, 'data' => $tele_medicine];
            }
        } else {
            $tele_medicine = TeleMedicine::get();
            if ($tele_medicine->count() > 0) {
                $data = ['status' => 200, 'data' => $tele_medicine];
            }
        }

        if (isset($data)) {
            return response()->json($data);
        } else {
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
    }
    
    // 27. post medical report
    public function medical_report(\App\Http\Requests\MedicalReportRequest $request)
    {
        $data = $request->validated();
        $relPath = 'assets/docs/medicalreport/';
        $data['passport'] = $this->upload_file($request, $relPath, 'passport');

        Medicalreport::insert($data);

        $attachments = $data['passport'] ? [public_path($relPath . basename($data['passport']))] : [];
        $this->notifyAdmin('Medical Record', $data, $attachments);

        $res = ['status' => 200, 'msg' => 'Medical report created.'];
        return response()->json($res);
    }
    
    // 28. get all medical report
    public function get_medical_report($id = '')
    {
        if ($id != '') {
            $medicalreport = Medicalreport::where('id', $id)->first();
            if ($medicalreport) {
                $data = ['status' => 200, 'data' => $medicalreport];
            }
        } else {
            $medicalreport = Medicalreport::get();
            if ($medicalreport->count() > 0) {
                $data = ['status' => 200, 'data' => $medicalreport];
            }
        }

        if (isset($data)) {
            return response()->json($data);
        } else {
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
    }
    
    // 29. Doctor appointment add
    public function doctor_appointment(Request $request)
    {
        $path = 'assets/docs/doctorappointment/';
        $investigationDocument = $this->upload_file($request, $path, 'passport');
        $investigationDocument1 = $this->upload_file($request, $path, 'medicalReport1');
        $investigationDocument2 = $this->upload_file($request, $path, 'medicalReport2');
        $investigationDocument3 = $this->upload_file($request, $path, 'medicalReport3');

        $data = $request->all();
        $data['passport'] = $investigationDocument;
        $data['medicalReport1'] = $investigationDocument1;
        $data['medicalReport2'] = $investigationDocument2;
        $data['medicalReport3'] = $investigationDocument3;

        Doctorappoinment::create($data);

        $attachments = collect([$investigationDocument, $investigationDocument1, $investigationDocument2, $investigationDocument3])
            ->filter()
            ->map(fn ($url) => public_path($path . basename($url)))
            ->all();
        $this->notifyAdmin('Doctor Appointment', $data, $attachments);

        if (!empty($data['PataientEmail'])) {
            try {
                Mail::to($data['PataientEmail'])->send(new SendMail($data));
            } catch (\Throwable $e) {
                \Log::warning('Doctor appointment patient confirmation email failed: ' . $e->getMessage());
            }
        }

        $res = ['status' => 200, 'msg' => 'Doctor appoinment created.'];
        return response()->json($res);
    }
    
    // 29. get Doctor appointment
    public function get_doctor_appointment($id = '')
    {
        if ($id != '') {
            $doctorappoinment = Doctorappoinment::where('id', $id)->first();
            if ($doctorappoinment) {
                $data = ['status' => 200, 'data' => $doctorappoinment];
            }
        } else {
            $doctorappoinment = Doctorappoinment::get();
            if ($doctorappoinment->count() > 0) {
                $data = ['status' => 200, 'data' => $doctorappoinment];
            }
        }

        if (isset($data)) {
            return response()->json($data);
        } else {
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
    }
    
    // 31. Question quary add
    public function add_question(Request $request)
    {
        $data = $request->all();
        Question::insert($data);
        $this->notifyAdmin('Send Query', $data);
        $res = ['status' => 200, 'msg' => 'Question created.'];
        return response()->json($res);
    }
    
    // 32. get Question quary
    public function get_question($id='')
    {
        if ($id != '') {
            $question = Question::where('id', $id)->first(); 
            
            if ($question) {
                $data = ['status' => 200, 'data' => $question];
            }
        } else {
            $question = Question::get();
            if ($question->count() > 0) {
                $data = ['status' => 200, 'data' => $question];
            }
        }
        if (isset($data)) {
            return response()->json($data);
        } else {
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
    }
    
    // 33. get child package by parent: moved to PackageController::subByParent.

    // 34. delete record
    public function delete_record($param, $id)
    {
        $data = ['status' => 200, 'msg' => 'Data not found.'];
        if($param != '' && ($id != '' && $id > 0)){
            $query = DB::table($param)->where('id', $id);
            
            if($query->exists()){
                $delete = $query->delete();
                if($delete){
                    if($param == 'packages'){
                        DB::table('sub_packages')->where('parent_id', $id)->delete();
                    }
                    $data = ['status' => 200, 'msg' => 'Record deleted'];
                }
            }
            return response()->json($data);
        }
    }
    
    // 35. healty check up
    public function add_health_checkup(Request $request)
    {
        // $request->all() never included the uploaded files (they only exist
        // via $request->file()), so passport/other_doc were silently dropped
        // before — save them properly now that we're attaching them to mail.
        $relPath = 'assets/docs/checkup/';
        $data = $request->all();
        $data['passport'] = $this->upload_file($request, $relPath, 'passport');
        $data['other_doc'] = $this->upload_file($request, $relPath, 'other_doc');

        HealthCheckUp::insert($data);

        $attachments = collect([$data['passport'], $data['other_doc']])
            ->filter()
            ->map(fn ($url) => public_path($relPath . basename($url)))
            ->all();
        $this->notifyAdmin('Check Up', $data, $attachments);

        $res = ['status' => 200, 'msg' => 'Health checkup created.'];
        return response()->json($res);
    }
    
    // 36. get healty check up
    public function get_health_checkup()
    {
        $res = ['status' => 404, 'msg' => 'Data not found.'];
        
        $checkup = HealthCheckUp::get();
        if($checkup->count() > 0){
            $res = ['status' => 200, 'data' => $checkup];
        }
        return response()->json($res);
    }
    
    // 37. get healty check up by id
    public function get_health_checkup_by($id)
    {
        $res = ['status' => 404, 'msg' => 'Data not found.'];
        
        $checkup = HealthCheckUp::where('id', $id)->first();
        if($checkup->count() > 0){
            $res = ['status' => 200, 'data' => $checkup];
        }
        return response()->json($res);
    }
    
    // 38. get users
    public function get_users($id = '')
    {
        if ($id != '') {
            $users = User::where('id', $id)->first();
            if ($users) {
                $data = ['status' => 200, 'data' => $users];
            }
        } else {
            $users = User::get();
            if ($users->count() > 0) {
                $data = ['status' => 200, 'data' => $users];
            }
        }

        if (isset($data)) {
            return response()->json($data);
        } else {
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
    }
    
    // 39. review appointment
    public function review_appointment($id)
    {
        $data = ['status' => 404, 'msg' => 'Data not found.'];
        if($id != '' && $id > 0){
            $query = Doctorappoinment::where('id', $id)->where('status', 0);
            if($query->exists()){
                $query->update(['status' => 1]);
                $data = ['status' => 200, 'msg' => 'Appointment reviewed.'];
            }
        }
        return response()->json($data);
    }
    
    // 40. add package booking
    public function add_package_booking(Request $request)
    {
        $data = $request->all();
        PackageBooking::insert($data);
        $this->notifyAdmin('Package Booking', $data);
        $res = ['status' => 200, 'msg' => 'Package booking created.'];
        return response()->json($res);
    }
    
    // 41. get package booking
    public function get_package_booking($id='')
    {
        $data = ['status' => 200, 'msg' => 'Data not found.'];
        
        if($id != '' && $id > 0){
            $package_booking = PackageBooking::where('id', $id)->first();
            if($package_booking){
                $data = ['status' => 200, 'data' => $package_booking];
            }
        }else{
            $package_booking = PackageBooking::get();
            if($package_booking->count() > 0){
                $data = ['status' => 200, 'data' => $package_booking];
            }
        }
        return response()->json($data);
    }
    
    // 42. personal appointment
    public function personal_appointment($id)
    {
        if($id != '' && $id >0){
            $appointment = Doctorappoinment::where('user_id', $id)->get();
            
            $data = ['status' => 404, 'msg' => 'Data not found.'];
            if($appointment->count() > 0){
                $data = ['status' => 200, 'data' => $appointment];
            }
            return response()->json($data);
        }
    }
    
    // 43. visa processing
    public function add_visa_processing(\App\Http\Requests\VisaProcessingRequest $request)
    {
        $data = $request->validated();
        $data['mediicalCorncern'] = $data['specificConcern'];
        unset($data['specificConcern']);
        $relPath = 'assets/docs/visa-processing/';
        $data['passport'] = $this->upload_file($request, $relPath, 'passport');

        VisaProcessing::create($data);

        $attachments = $data['passport'] ? [public_path($relPath . basename($data['passport']))] : [];
        $this->notifyAdmin('Visa Processing', $data, $attachments);

        $res = ['status' => 200, 'msg' => 'Visa processing added.'];
        return response()->json($res);
    }
    
    // 44. get visa processing
    public function get_visa_processing($id ='')
    {
        $data = ['status' => 404, 'msg' => 'Data not found.'];
        if($id != '' && $id >0){
            $visa = VisaProcessing::where('id', $id)->first();
            if($visa != ''){
                $data = ['status' => 200, 'data' => $visa];
            }
        }else{
            $visa = VisaProcessing::get();
            if($visa->count() > 0){
                $data = ['status' => 200, 'data' => $visa];
            }
        }
        return response()->json($data);
    }
    
    // 45. mark appointment as success
    public function appointment_success($id)
    {
        $data = ['status' => 404, 'msg' => 'Data not found.'];
        if($id != '' && $id > 0){
            $query = Doctorappoinment::where('id', $id)->where('status', 1);
            if($query->exists()){
                $query->update(['status' => 2]);
                $data = ['status' => 200, 'msg' => 'Appointment reviewed success.'];
            }
        }
        return response()->json($data);
    }
    
    // 46. news 
    // 46-49. news, blog: moved to NewsController / BlogController.

    // all category length
    public function category_length()
    {
        $categories = [
            'doctor' => $this->getCategoryData(Doctor::class, 'doctor', '/home/doctors-list'),
            'doctor_appointment' => $this->getCategoryData(Doctorappoinment::class, 'doctor_appointment', '/home/add-appointMent'),
            'health_package' => $this->getCategoryData(PackageBooking::class, 'health_package', '/home/health-Package'),
            'patient' => $this->getCategoryData(User::class, 'patient', '/home/users'),
            'package' => $this->getCategoryData(Package::class, 'package', '/home/get-packages'),
            'center' => $this->getCategoryData(Center::class, 'center', '/home/centers-list'),
            'air_ticket' => $this->getCategoryData(AirTicket::class, 'air_ticket', '/home/airTicket'),
            'air_pickup' => $this->getCategoryData(AirPickup::class, 'air_pickup', '/home/airPickup'),
            'air_ambulance' => $this->getCategoryData(AirAmbulance::class, 'air_ambulance', '/home/airAmbulance'),
            'order_medicine' => $this->getCategoryData(OrderMedicine::class, 'order_medicine', '/home/medicineOrder'),
            'tele_medicine' => $this->getCategoryData(TeleMedicine::class, 'tele_medicine', '/home/teleMedicine'),
            'medical_record' => $this->getCategoryData(MedicalReport::class, 'medical_record', '/home/medicalRecord'),
            'health_checkup' => $this->getCategoryData(HealthCheckup::class, 'health_checkup', '/home/check-up'),
            'visa' => $this->getCategoryData(VisaProcessing::class, 'visa', '/home/visa_processing'),
            'client_query' => $this->getCategoryData(Question::class, 'query', '/home/seeQuery'),
        ];

        return response()->json(['status' => 200, 'data' => $categories]);
    }
    
    private function getCategoryData($modelClass, $routeKey, $link="")
    {
        return [
            'name' => ucfirst(str_replace('_', ' ', $routeKey)),
            'length' => $modelClass::count(),
            'link' => $link,
        ];
    }
    
    // 50. search package by name: moved to PackageController.

    // 51. search center by name
    // search_center moved to CenterController.
}
