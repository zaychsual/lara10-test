<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SoalKeDuaController extends Controller
{
    public function index()
    {
        $temp = [];
        $json1 = file_get_contents(public_path().'/json/data1.json');
        $json2 = file_get_contents(public_path().'/json/data2.json');

        $data1 = json_decode($json1, true);
        $data2 = json_decode($json2, true);
        // dd($data1['data'],$data2['data']);

        foreach ($data1['data'] as $i => $key) {
            $temp[$i]['name'] = $key['name'];
            $temp[$i]['email'] = $key['email'];
            $temp[$i]['booking_number'] = $key['booking']['booking_number'];
            $temp[$i]['ahass_code'] = $key['booking']['workshop']['code'];
            $temp[$i]['ahass_name'] = $key['booking']['workshop']['name'];
            $temp[$i]['motorcycle_ut_code'] = $key['booking']['motorcycle']['ut_code'];
            $temp[$i]['motorcycle'] = $key['booking']['motorcycle']['name'];
        }
        foreach($data2['data'] as $j => $yek) {
            $temp2[$j]['ahass_code'] = $yek['code'];
            $temp2[$j]['ahass_address'] = $yek['address'];
            $temp2[$j]['ahass_contact'] = $yek['phone_number'];
            $temp2[$j]['ahass_distance'] = $yek['distance'];
        }
        $arrMerge = $this->merge_two_arrays($temp,$temp2);

        foreach($arrMerge as $k => $ke) {
            $fix[$k]['name'] = $ke['name'];
            $fix[$k]['email'] = $ke['email'];
            $fix[$k]['booking_number'] = $ke['booking_number'];
            $fix[$k]['ahass_code'] = $ke['ahass_code'];
            $fix[$k]['ahass_name'] = $ke['ahass_name'] ;
            $fix[$k]['ahass_address'] = $ke['ahass_address'] ?? '';
            $fix[$k]['ahass_contact'] = $ke['ahass_contact'] ?? '';
            $fix[$k]['ahass_distance'] = $ke['ahass_distance'] ?? 0;
            $fix[$k]['motorcycle_ut_code'] = $ke['motorcycle_ut_code'];
            $fix[$k]['motorcycle'] = $ke['motorcycle'];
        };
        //sort asc based on distance
        array_multisort(
            array_map(
                static function ($el) {
                    return $el['ahass_distance'];
                },
                $fix
            ),
            SORT_ASC,
            $fix
        );
        return response()->json([
            'status' => 1,
            'message' => 'Data Successfully Retrieved',
            'data'    => $fix,
        ], 200);
    }

    private function merge_two_arrays($array1,$array2)
    {
        $data = array();
        $arrayAB = array_merge($array1,$array2);
        foreach ($arrayAB as $value) {
            $id = $value['ahass_code'];
            if (!isset($data[$id])) {
                $data[$id] = array();
            }
            $data[$id] = array_merge($data[$id],$value);
        }
        return $data;
    }
}
