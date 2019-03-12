<?php

namespace YellowProject\Http\Controllers\API\v1\Report;

use Illuminate\Http\Request;
use YellowProject\Http\Controllers\Controller;
use YellowProject\ShareLocation\UserShareLocation;
use Excel;

class LocationSharingController extends Controller
{
    public function getData(Request $request)
    {
    	$startDate = $request->start_date;
        $endDate = $request->end_date;
        $shareLocationDatas = [];
        $userShareLocations = UserShareLocation::whereBetween('created_at', array($startDate, $endDate))->select('*', \DB::raw('count(*) as countLocation'))
        	->groupBy('address','line_user_id')->orderByDesc('created_at')->get();
        foreach ($userShareLocations as $key => $userShareLocation) {
        	$shareLocationDatas[$key]['line_display_name'] = $userShareLocation->lineUserProfile->name;
        	$shareLocationDatas[$key]['province'] = null;
        	$shareLocationDatas[$key]['address'] = $userShareLocation->address;
        	$shareLocationDatas[$key]['count'] = $userShareLocation->countLocation;
        	$shareLocationDatas[$key]['date_time'] = $userShareLocation->created_at->format('Y-m-d H:i:s');
        }

        return response()->json([
            'datas' => $shareLocationDatas,
        ]);
    }

    public function exportData(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $dataExports = [];

        $dateNow = \Carbon\Carbon::now()->format('dmY_His');
        $userShareLocations = UserShareLocation::whereBetween('created_at', array($startDate, $endDate))->orderByDesc('created_at')->get();
        $count = 1;
        foreach ($userShareLocations as $key => $userShareLocation) {
            $dataExports[$key]['No.'] = $count;
            $dataExports[$key]['LINE userID'] = $userShareLocation->lineUserProfile->id;
            $dataExports[$key]['Address'] = $userShareLocation->address;
            $dataExports[$key]['Date/time'] = $userShareLocation->created_at->format('Y-m-d H:i:s');
            $count++;
        }

        Excel::create('share_location_'.$dateNow, function($excel) use ($dataExports) {
            $excel->sheet('subscriber_data', function($sheet) use ($dataExports)
            {
                $sheet->fromArray($dataExports);
            });
        })->download('xls');
    }
}
