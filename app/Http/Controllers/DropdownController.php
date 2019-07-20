<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DropdownController extends Controller {
    
    //fix ตาม db เพื่อทำแบบ reuse
    private $area = [
        'geographies' => [
            'fk' => 'geography'
        ],
        'provinces' => [
            'fk' => 'province'
        ],
        'amphures' => [
            'fk' => 'amphure'
        ],
        'districts' => [
            'fk' => 'district'
        ]
    ];

    function index() {
        $list = DB::table('geographies')
                ->orderBy('name')
                ->get();
        return view('regions')->with('list', $list);
    }

    function fetch(Request $req) {  

        $id = $req->get('select');
        $areaName = $req->get('curr_name');
        $areaNextName = $req->get('next_name');
        $result = array();
        $query = DB::table($areaNextName)
            ->join($areaName, $areaName.'.id', '=', $areaNextName.'.'.$this->area[$areaName]['fk'].'_id')
            ->select($areaNextName.'.id', $areaNextName.'.name_th')
            ->where( $areaName.'.id', $id)
            ->groupBy($areaNextName.'.id', $areaNextName.'.name_th')
            ->orderBy('name_th')
            ->get();
        //รูปแบบการแสดงผล
        $output = '';
        foreach ($query as $row) {
            $output .= '<option value="'.$row->id.'">'.$row->name_th.'</option>';
        }
        echo $output;
    }
}