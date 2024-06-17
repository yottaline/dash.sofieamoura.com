<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Location;
use App\Models\Retailer;
use App\Models\Retailer_address;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class RetailerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $locations = Location::fetch(0, [['location_visible', 1]]);
        $currencies = Currency::fetch(0, [['currency_visible', 1]]);
        return view('contents.retailers.index', compact('locations', 'currencies'));
    }

    function load(Request $request)
    {
        $param  = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;
        if ($request->status)  $param[]  = ['retailer_approved', $request->status -1];
        if ($request->country) $param[] = ['retailer_country', '=', $request->country];
        if ($request->currecy) $param[] = ['retailer_currency', '=', $request->currecy];

        echo json_encode(Retailer::fetch(0, $param, null, $limit, $lastId));
    }

    public function submit(Request $request)
    {
        $request->validate([
            // 'name'       => 'required',
            'email'      => 'required|email',
            'company'    => 'required',
        ]);

        $id    = $request->retailer_id;
        $email = $request->email;
        $phone = $request->phone;


        if(count(Retailer::fetch(0, [['retailer_id', '!=', $id], ['retailer_phone', $phone]])))
        {
            echo json_encode(['status' => false, 'message' => __('Phone number already exists'),]);
            return;
        }

        if($email &&  count(Retailer::fetch(0, [['retailer_id', '!=', $id], ['retailer_email', $email]])))
        {
            echo json_encode(['status' => false, 'message' => __('Email already exists'),]);
            return;
        }

        $param = [
            'retailer_code'         => uniqidReal(8),
            'retailer_fullName'     => $request->name,
            'retailer_email'        => $email,
            'retailer_phone'        => $phone,
            'retailer_company'      => $request->company,
            'retailer_desc'         => $request?->desc,
            'retailer_website'      => $request?->website,
            'retailer_country'      => $request->country,
            'retailer_province'     => $request->province ?? 'default',
            'retailer_city'         => $request->city,
            'retailer_address'      => $request?->address,
            'retailer_currency'     => 1,
            // 'retailer_adv_payment'  => $request?->payment,
            'retailer_blocked'      => intval($request->status)
        ];

        if(!$id)
        {
          $param['retailer_password'] = Hash::make($request->password);
          $param['retailer_created'] = Carbon::now();
        }else{
            $param['retailer_modified'] = Carbon::now();
        }

        $logo = $request->file('logo');
        if($logo)
        {
            $logoName = $this->uniqidReal(rand(4, 18));
            $logo->move('images/retailers/', $logoName);
            $param['retailer_logo'] = $logoName;
        }

        if($id){
            $record = Retailer::fetch($id);
            if ($logo && $record->retailer_logo) {
                File::delete('images/retailers/' . $record->retailer_logo);
            }
        }

        $result = Retailer::submit($param, $id);

        if($result){
            $paramAddress = [
                'address_retailer'  => $result,
                'address_country'   => 1,
                'address_province'  => $request->province ?? 'default',
                'address_city'      => $request->city,
                'address_zip'       => $request->zip ?? '44',
                'address_line1'     => $request->address,
                'address_line2'     => $request->address,
                'address_phone'     => $phone,
                'address_note'      => $request->address ?? 'default',
            ];

            Retailer_address::submit($paramAddress, null);
        };
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Retailer::fetch($result) : []
        ]);
    }

    public function editApproved(Request $request)
    {
        $id = $request->id;
        $param = [
            'retailer_approved'     => Carbon::now(),
            'retailer_approved_by'  => auth()->user()->id
        ];

        $result = Retailer::submit($param, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Retailer::fetch($result) : []
        ]);
    }

}