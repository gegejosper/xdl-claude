<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use App\Models\Province;
use App\Models\Citymunicipality;
use App\Models\Barangay;
use App\Models\Branch;
use App\Models\Branchuser;
use Response;
use Validator;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\GlobalController;
use Carbon\Carbon;

class CustomerController extends Controller
{
    //
        //
    public function show_customers(){
        $customers = Customer::with('branch_details', 'transaction_details')
                        ->when(Auth::user()->hasRole(['cashier']), function($query){
                            $branch_user = Branchuser::where('user_id', Auth::user()->id)->first();
                            return $query->where('branch_id', $branch_user->branch_id);
                        })
                        ->paginate(50);
        //dd($customers);
        $provinces = Province::orderBy('provDesc', 'asc')->get();
        $branches = Branch::where('status', 'active')->get();
        $page_name="Customers";
        return view('panel.customers.customers', compact('page_name', 'customers', 'provinces', 'branches'));
    }

    public function add_customer(Request $req){
        $user_id = GlobalController::get_user_id();
        //dd($req);
        if(isset($req->branch)){
            $branch_id = $req->branch;   
        }
        else {
            $get_branch = Branchuser::where('user_id', $user_id)->first();
            $branch_id = $get_branch->branch_id; 
        }
        
        $branch = Branch::find($branch_id);
        $validator = Validator::make($req->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'mobile_num' => 'required',
            'barangay' => 'required',
            'province' => 'required',
            'city_municipality' => 'required'
        ]);
        if ($validator->fails()) {    
            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400); // 400 being the HTTP code for an invalid request.
            //return response()->json(['errors' => $validator->messages(), 'status' => 422], 200);
        }
        else {
            $year = date('y');
            $province = Province::where('provCode',$req->province)->first();
            $citymunicipality = Citymunicipality::where('citymunCode',$req->city_municipality)->first();
            $barangay = Barangay::where('brgyCode',$req->barangay)->first();
            $customer_count = Customer::where('branch_id', $branch_id)->whereYear('created_at', date('Y'))->count();
            $padded_count_Number = str_pad($customer_count + 1, 4, '0', STR_PAD_LEFT);
            $data = new Customer();
            $data->branch_id = $req->branch;
            $data->first_name = strtoupper($req->first_name);
            $data->last_name = strtoupper($req->last_name);
            $data->address = strtoupper($req->address);
            $data->brgy = $barangay->brgyDesc;
            $data->province = $province->provDesc;
            $data->city_num = $citymunicipality->citymunDesc;
            $data->mobile_num = $req->mobile_num;
            $data->status = 'active';
            $data->save();
            return response()->json($data);
        }

    }

    public function edit_customer(Request $req){
        //dd($req);
        $province = Province::where('provCode',$req->province)->first();
        $citymunicipality = Citymunicipality::where('citymunCode',$req->city_municipality)->first();
        $barangay = Barangay::where('brgyCode',$req->barangay)->first();
        $customer = Customer::find($req->customer_id);
        $customer->first_name = strtoupper($req->first_name);
        $customer->last_name = strtoupper($req->last_name);
        $customer->address = strtoupper($req->address);
        $customer->brgy = $barangay->brgyDesc;
        $customer->province = $province->provDesc;
        $customer->mobile_num = $req->mobile_num;
        $customer->save();
        if (Auth::check())
        {
            $name = Auth::user()->name;
        }
        Log::info($name.' modified '.$customer->last_name.', '.$customer->first_name);
        return response()->json($customer);
    }

    public function view_customer($customer_id){
        $provinces = Province::orderBy('provDesc', 'asc')->get();
        $customer = Customer::find($customer_id);
        $transactions = Transaction::where('customer_id', $customer_id)->orderBy('id', 'desc')->get();
        $transaction_payment = TransactionPayment::with('transaction_details')->where('customer_id', $customer_id)->orderBy('id', 'desc')->get();
        $page_name="Customer";
        return view('panel.customers.customer', compact('page_name', 'customer', 'provinces', 'transactions', 'transaction_payment'));
    }

    /*
    Search Customer functions used in 
    /panel/cashier/customer/search
    /panel/branches/customer/search
    */
    public function search_customers(Request $req){
        
        $searchTerm = '%'.$req->search_query.'%';
        $isAdmin = Auth::user()->hasRole(['admin']);
        if(isset($req->branch_id)){
            $branch_id = $req->branch_id;
        }
        else {
            if($isAdmin){
                $branch_id = '';
            }
            else {
                $user_id = GlobalController::get_user_id();
                $get_branch = Branchuser::where('user_id', $user_id)->first();
                $branch_id = $get_branch->branch_id; 
            }
        }
        
        $customers_result = Customer::with('branch_details','transaction_details')
            ->where(function($query) use ($searchTerm){
                $query->where('first_name','LIKE', $searchTerm)
                ->orWhere('last_name','LIKE', $searchTerm);
            })
            ->when(!$isAdmin, function($query) use ($branch_id){
                return $query->where('branch_id', $branch_id);
            })
            ->take(20)->get();
            //dd($customers_result);
        return response()->json($customers_result);
    }
    public function filter_by_branch(Request $req){
        $customers = Customer::with('branch_details')->where('branch_id', $req->branch)->get();
        $provinces = Province::orderBy('provDesc', 'asc')->get();
        $branches = Branch::where('status', 'active')->get();
        $page_name="Customers";
        return view('panel.customers.customers', compact('page_name', 'customers', 'provinces', 'branches'));
    }
}
