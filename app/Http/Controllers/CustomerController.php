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
use App\Models\DailySales;
use App\Models\BranchUser;
use Response;
use Validator;
use DB;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\GlobalController;
use App\Helpers\GlobalHelpers;
use Carbon\Carbon;

class CustomerController extends Controller
{
    //
        //
    public function show_customers(){
        $default_province  = '0973';
        $default_citymun   = '097322';

        $provinces = DB::table('provinces')
            ->orderBy('prov_desc', 'asc')
            ->get(['prov_code', 'prov_desc']);

        $municipalities = DB::table('citymunicipalities')
            ->where('prov_code', $default_province)
            ->orderBy('citymun_desc', 'asc')
            ->get(['citymun_code', 'citymun_desc']);

        $barangays = DB::table('barangays')
            ->where('citymun_code', $default_citymun)
            ->orderBy('brgy_desc', 'asc')
            ->get(['brgy_code', 'brgy_desc']);
        $customers = Customer::with('branch_details')
                        // ->when(Auth::user()->hasRole(['staff']), function($query){
                        //     $branch_user = BranchUser::where('user_id', Auth::user()->id)->first();
                        //     return $query->where('branch_id', $branch_user->branch_id);
                        // })
                        ->paginate(50);
        //dd($customers);
        //$provinces = Province::orderBy('prov_desc', 'asc')->get();
        $branches = Branch::where('status', 'active')->get();
        $page_name="Customers";
        return view('customers.customers', compact('page_name', 'customers', 'provinces', 'branches', 'municipalities', 'barangays', 'default_province', 'default_citymun'));
    }

    public function add_customer(Request $req){
        $user_id = GlobalHelpers::get_user_id();
        //dd($req);
        if (isset($req->branch)) {
            $branch_id = $req->branch;
        } else {
            $get_branch = BranchUser::where('user_id', $user_id)->first();
            $branch_id  = $get_branch
                ? $get_branch->branch_id
                : Branch::where('status', 'active')->value('id');
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
            return response()->json([
                'success' => false,
                'errors'  => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        $province         = Province::where('prov_code', $req->province)->first();
        $citymunicipality = DB::table('citymunicipalities')->where('citymun_code', $req->city_municipality)->first();
        $barangay         = Barangay::where('brgy_code', $req->barangay)->first();

        if (!$province || !$citymunicipality || !$barangay) {
            return response()->json([
                'success' => false,
                'errors'  => ['address' => ['Invalid province, city, or barangay selection.']],
            ], 422);
        }

        $data             = new Customer();
        $data->branch_id  = $branch_id;
        $data->first_name = strtoupper($req->first_name);
        $data->last_name  = strtoupper($req->last_name);
        $data->address    = strtoupper($req->address);
        $data->brgy       = $barangay->brgy_desc;
        $data->province   = $province->prov_desc;
        $data->city_num   = $citymunicipality->citymun_desc;
        $data->mobile_num = $req->mobile_num;
        $data->status     = 'active';
        $data->save();

        Log::info(Auth::user()->name . ' added customer: ' . $data->last_name . ', ' . $data->first_name);

        return response()->json(['success' => true, 'customer' => $data]);

    }

    public function edit_customer(Request $req){
        $validator = Validator::make($req->all(), [
            'customer_id'      => 'required|exists:customers,id',
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'address'          => 'required|string|max:255',
            'mobile_num'       => 'required|string|max:20',
            'province'         => 'nullable|string',
            'city_municipality'=> 'nullable|string',
            'barangay'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        $customer = Customer::findOrFail($req->customer_id);
        $customer->first_name = strtoupper($req->first_name);
        $customer->last_name  = strtoupper($req->last_name);
        $customer->address    = strtoupper($req->address);
        $customer->mobile_num = $req->mobile_num;

        // Only update address components when all three are provided
        if ($req->filled('province') && $req->filled('city_municipality') && $req->filled('barangay')) {
            $province         = Province::where('prov_code', $req->province)->first();
            $citymunicipality = DB::table('citymunicipalities')->where('citymun_code', $req->city_municipality)->first();
            $barangay         = Barangay::where('brgy_code', $req->barangay)->first();

            if ($province)         $customer->province  = $province->prov_desc;
            if ($citymunicipality) $customer->city_num  = $citymunicipality->citymun_desc;
            if ($barangay)         $customer->brgy      = $barangay->brgy_desc;
        }

        $customer->save();

        Log::info(Auth::user()->name . ' edited customer: ' . $customer->last_name . ', ' . $customer->first_name);

        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function view_customer($customer_id){
        $customer = Customer::findOrFail($customer_id);

        $provinces = DB::table('provinces')->orderBy('prov_desc', 'asc')->get(['prov_code', 'prov_desc']);

        $transactions = Transaction::with(['items', 'cashier'])
            ->where('customer_id', $customer_id)
            ->orderBy('id', 'desc')
            ->get();

        $transaction_payments = TransactionPayment::with('transaction')
            ->where('customer_id', $customer_id)
            ->where('status', 'accepted')
            ->orderBy('id', 'desc')
            ->get();

        // Summary stats — exclude canceled orders from financial totals
        $active_txns   = $transactions->where('payment_status', '!=', 'canceled');
        $total_orders  = $transactions->count();
        $total_amount  = $active_txns->sum('total_amount');
        $total_paid    = $transaction_payments->sum('amount_paid');
        $total_balance = $active_txns->sum('balance');
        $unpaid_count  = $active_txns->where('payment_status', 'unpaid')->count();
        $branch_user  = BranchUser::where('user_id', Auth::user()->id)->first();
        $today_closed = $branch_user
            ? DailySales::where('branch_id', $branch_user->branch_id)
                ->where('sales_date', Carbon::today())->exists()
            : false;
        $page_name = 'Customer';
        return view('customers.customer', compact(
            'page_name', 'customer', 'provinces',
            'transactions', 'transaction_payments',
            'total_orders', 'total_amount', 'total_paid', 'total_balance', 'unpaid_count', 'today_closed'
        ));
    }

    public function modify_customer(Request $req){
        $validator = Validator::make($req->all(), [
            'customer_id'     => 'required|exists:customers,id',
            'customer_status' => 'required|in:active,blocked',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        $customer         = Customer::findOrFail($req->customer_id);
        $customer->status = $req->customer_status;
        $customer->save();

        Log::info(Auth::user()->name . ' set customer #' . $customer->id . ' status to ' . $customer->status);

        return response()->json(['success' => true, 'id' => $customer->id, 'status' => $customer->status]);
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
                $user_id = GlobalHelpers::get_user_id();
                $get_branch = BranchUser::where('user_id', $user_id)->first();
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
        $provinces = Province::orderBy('prov_desc', 'asc')->get();
        $branches = Branch::where('status', 'active')->get();
        $page_name="Customers";
        return view('customers.customers', compact('page_name', 'customers', 'provinces', 'branches'));
    }
}
