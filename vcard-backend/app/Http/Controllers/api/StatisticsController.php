<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function getFinancial(Request $request)
    {
        //How much weeks/months.. want to see
        if ($request->has("labelSize")) {
            $labelSize = $request->labelSize;
        }else {
            $labelSize = 6;
        }
        //Week, Month, Year
        if ($request->has("labelCategory")) {
            $labelCategory = $request->labelCategory;
        }else {
            $labelCategory = 'month';
        }

        $labelMiniCategory = [];
        switch ($labelCategory) {
            case 'week':
                $labelMiniCategory[0] = 'W';
                $labelMiniCategory[1] = '%V';
                break;
            case 'year':
                $labelMiniCategory[0] = 'Y';
                $labelMiniCategory[1] = '%Y';
                break;
            case 'day':
                $labelMiniCategory[0] = 'd, l';
                $labelMiniCategory[1] = '%d';
                break;
            default: //'month'
                $labelMiniCategory[0] = 'F';
                $labelMiniCategory[1] = '%M';
                $labelSize -= 1; //When i chose 'month' is giving one less, i dont know why, so i tried to hard coded
                break;
        }

        $labels = [];

        $datasets = [];


        if (Auth::user()->user_type == 'V') {
            $data = DB::table('transactions')->select(DB::raw("count(*) as `count`, DATE_FORMAT(datetime, '".$labelMiniCategory[1]."') as `group`"))->whereRaw(DB::raw("DATE(datetime) >= '".date('Y-m-d',strtotime("-".$labelSize ." ".strtolower($labelCategory)."s"))."'"))->where('vcard', Auth::user()->vcard_ref->phone_number)->groupBy('group')->pluck('count','group');
        }else {
            $data = DB::table('transactions')->select(DB::raw("count(*) as `count`, DATE_FORMAT(datetime, '".$labelMiniCategory[1]."') as `group`"))->whereRaw(DB::raw("DATE(datetime) >= '".date('Y-m-d',strtotime("-".$labelSize ." ".strtolower($labelCategory)."s"))."'"))->groupBy('group')->pluck('count','group');
        }
        $labels = array_keys($data->toArray());

        $data = array_values($data->toArray());

        $datasets[0] = ["label"=> 'Financial', "data" => $data, "fill"=> false, "borderColor"=> '#42A5F5', "tension"=> .4];

        return response()->json(["labels" => $labels, "datasets"=>$datasets]);

        return $labels;
    }
}
