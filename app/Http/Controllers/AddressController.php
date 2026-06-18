<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AddressController extends Controller
{
    //
    public function search_town(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }

        $validated = $request->validate([
            'search' => ['required', 'string', 'max:10', 'regex:/^\d+$/'],
        ]);

        $municipalities = DB::table('citymunicipalities')
            ->where('prov_code', $validated['search'])
            ->orderBy('citymun_desc', 'asc')
            ->get(['citymun_code', 'citymun_desc']);

        if ($municipalities->isEmpty()) {
            return response('', 204);
        }

        $output = '';
        foreach ($municipalities as $municipality) {
            $output .= '<option value="' . e($municipality->citymun_code) . '">'
                . e($municipality->citymun_desc)
                . '</option>';
        }

        return response($output);
    }

    public function search_barangay(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }

        $validated = $request->validate([
            'search' => ['required', 'string', 'max:10', 'regex:/^\d+$/'],
        ]);

        $barangays = DB::table('barangays')
            ->where('citymun_code', $validated['search'])
            ->orderBy('brgy_desc', 'asc')
            ->get(['brgy_code', 'brgy_desc']);

        if ($barangays->isEmpty()) {
            return response('', 204);
        }

        $output = '';
        foreach ($barangays as $barangay) {
            $output .= '<option value="' . e($barangay->brgy_code) . '">'
                . e($barangay->brgy_desc)
                . '</option>';
        }

        return response($output);
    }
}
