<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class HelperController extends Controller
{

    public static function trendHandelArr($type, $period, $data)
    {

        $chartColor = collect(self::getColors())->toArray();
        $trendChartData = [];
        foreach ($period as $item) {
            if ($type == 'monthly') {
                $m = Carbon::parse($item)->month;
                $y = Carbon::parse($item)->year;
                $filterData = $data->where('month', '=', $m)->where('year', $y);
            } elseif ($type == 'quarterly') {
                $q = Carbon::parse($item)->quarter;
                $y = Carbon::parse($item)->year;
                $filterData = $data->where('quarter', '=', $q)->where('year', $y);
            } elseif ($type == 'yearly')  {
                $filterData = $data->where('year', $item);
            }

            if (count($filterData) > 0) {

                foreach ($filterData as $value) {

                    if ($type == 'monthly') {
                        $cat = date('M', mktime(0, 0, 0, $value->month, 10)) . ' ' . substr($value->year, -2);
                    } elseif ($type == 'quarterly') {
                        $cat = 'Q' . $value->quarter . '-' . substr($value->year, -2);
                    } else {
                        $cat = $value->year;
                    }
                    $rate = $value->r_rate;
                    $trendChartData[$rate]['color'] =  'rgb' . $chartColor[$rate];
                    $trendChartData[$rate]['data'][] =  $value->count;
                    $trendChartData[$rate]['categories'][] =  $cat;
                }
            } else {

                if ($type == 'monthly') {
                    $cat = date('M', mktime(0, 0, 0, $m, 10)) . ' ' . substr($y, -2);

                } elseif ($type == 'quarterly') {
                    $cat = 'Q' . $q . '-' . substr($y, -2);
                } else {
                    $cat = $item;
                }

                $trendChartData['neutral']['color'] =  'rgb' . $chartColor['neutral'];
                $trendChartData['neutral']['data'][] = 0;
                $trendChartData['neutral']['categories'][] =   $cat;

                $trendChartData['positive']['color'] =  'rgb' . $chartColor['positive'];
                $trendChartData['positive']['data'][] = 0;
                $trendChartData['positive']['categories'][] = $cat;

                $trendChartData['negative']['color'] =  'rgb' . $chartColor['negative'];
                $trendChartData['negative']['data'][] = 0;
                $trendChartData['negative']['categories'][] = $cat;

            }
        }
        // dd($trendChartData);
        return $trendChartData;
    }



    public static function getColors()
    {
        $data = json_decode(DB::table('charts_bgs')->where('bkey', 'rates')->first()->bvals);
        return  $data;
    }
}
