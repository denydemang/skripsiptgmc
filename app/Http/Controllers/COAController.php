<?php

namespace App\Http\Controllers;

use App\Models\COA;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class COAController extends Controller
{


    public function getCOAView(Request $request){

        $supplyData = [
            'title' => 'Master COA',
            'users' => Auth::user(),
            'sessionRoute' =>  $request->route()->getName(),

        ];

        return response()->view("admin.master.coa", $supplyData);

    }

    public function getTreeCOA(Request $request){

        if($request->ajax()){

            $interfaceTreeCoa = [
                'id' => 0,
                'parent' =>'#',
                'text'=> '<b>Aktiva</b>', //Nama Account
                'type'=> 'H',
                'level'  => '',// H (Header) OR D (Detail)
                'data'=> [
                    'code'=> '',
                    'name'=> '',
                    'type'=> '',
                    'default_dk'=> '',
                    'level'=> '',
                    'description'=> '',
                    'beginning_balance'=> 0,
                    'can_add_sub'=> 1
                ],
                'state'=> [
                    'opened'=> true
                ]
            ];



            $list = [];


            $coa = COA::orderBy("code", "asc")->get();

            $counter = 1;
            if (count($coa) >0){
                foreach ($coa as $x) {

                    if ($counter == 1){

                        $interfaceTreeCoa['id'] =  "1";
                        $interfaceTreeCoa['parent'] = "#";
                        $interfaceTreeCoa['text'] = "<b>$x->name</b>";
                        $interfaceTreeCoa['type'] = $x->description == "Header" ? "H" : "D";
                        $interfaceTreeCoa['level'] = $x->level;
                        $interfaceTreeCoa['data']["code"] = $x->code;
                        $interfaceTreeCoa['data']["name"] = $x->name;
                        $interfaceTreeCoa['data']["type"] = $x->type;
                        $interfaceTreeCoa['data']["default_dk"] = $x->default_dk;
                        $interfaceTreeCoa['data']["level"] = $x->level;
                        $interfaceTreeCoa['data']["description"] = $x->description;
                        $interfaceTreeCoa['data']["beginning_balance"] = $x->beginning_balance;

                        array_push($list, $interfaceTreeCoa);
                    } else {


                        $lastNode =  $list[count($list)-1];

                        $lastLevel = intval($lastNode['data']['level']);
                        $lastType = $lastNode['data']['type'];
                        $currentLevel = intval($x->level);
                        $currentType = strval($x->type);



                        if ($lastLevel < $currentLevel){

                            $interfaceTreeCoa['id'] = strval($lastNode['id'])."01" ;
                            $interfaceTreeCoa['parent'] =  $lastNode['parent'] == "#" ? $lastNode['id']:  substr(strval($lastNode['id'])."01" ,0,-2) ;

                        }


                        if ($lastLevel == $currentLevel ){


                            $interfaceTreeCoa['id'] = strval(intval($lastNode['id']) + 1);
                            $interfaceTreeCoa['parent'] =  substr(strval(intval($lastNode['id']) + 1), 0, -2) ;
                        }

                        if ($lastLevel > $currentLevel ){

                            $filteredArray = collect($list)->filter(function ($item) use ($currentLevel) {
                                return $item['data']['level'] == $currentLevel ;
                            })->values();

                            $lastNode = $filteredArray[count($filteredArray) - 1 ];

                            $interfaceTreeCoa['id'] = $x->level == 0 ? substr($x->code, 0, 1) : strval(intval($lastNode['id']) + 1);
                            $interfaceTreeCoa['parent'] = $x->level == 0  ? "#" : substr(strval(intval($lastNode['id']) + 1), 0, -2);

                        }

                        $interfaceTreeCoa['text'] = "<b>$x->name</b>";
                        $interfaceTreeCoa['type'] = $x->description == "Header" ? "H" : "D";
                        $interfaceTreeCoa['level'] = $x->level;
                        $interfaceTreeCoa['data']["code"] = $x->code;
                        $interfaceTreeCoa['data']["name"] = $x->name;
                        $interfaceTreeCoa['data']["type"] = $x->type;
                        $interfaceTreeCoa['data']["default_dk"] = $x->default_dk;
                        $interfaceTreeCoa['data']["level"] = $x->level;
                        $interfaceTreeCoa['data']["description"] = $x->description;
                        $interfaceTreeCoa['data']["beginning_balance"] = $x->beginning_balance;

                        array_push($list, $interfaceTreeCoa);

                    }

                    $counter++;
                }
            }

            return response()->json($list);
        }


    }
    public function getCOATableSearch(Request $request, DataTables $dataTables){
        if($request->ajax()){

            $COA =COA::query()->where('description', 'detail');

            return $dataTables->of($COA)

            ->addColumn('action', function ($row) {

                return '
                <div class="d-flex justify-content-center">
                <button class="btn btn-sm btn-success btnselectCOA" data-name="'.$row->name.'" data-code="'.$row->code.'" title="Select COA"><i class="fa fa-check"></i> Select</button>
                </div>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);

        } else {
            abort(404);
        }
    }

    public function addCoa(Request $request){
        if ($request->ajax()){
            $data = $request->all();

            try {


                $coa = COA::where("code", $data['code'])->first();
                if ($coa){
                    throw new Exception("Coa With Code '".$data['code']."' Already Exists!");
                }

                $coa = COA::where("name", $data['name'])->first();
                if ($coa){
                    throw new Exception("Coa With Name '".$data['name']."' Already Exists!");
                }

                $coa = new COA();
                $coa->code = $data['code'];
                $coa->name = $data['name'];
                $coa->type = $data['type'];
                $coa->level = $data['level'];
                $coa->default_dk = $data['default_dk'];
                $coa->description = $data['description'];
                $coa->beginning_balance = floatval($data['beginning_balance']);
                $coa->created_by =  Auth::user()->username;
                $coa->save();
                return response()->json(true);

            } catch (\Throwable $th) {
                throw new \Exception($th->getMessage());
            }
        } else {
            abort(404);
        }
    }

    public function editcoa($id,Request $request){

        if ($request->ajax()){

            try {
                $data = $request->all();
                $coa = COA::where("code", $id)->first();

                $coa->name = $data['name'];
                $coa->name = $data['name'];
                $coa->type = $data['type'];
                $coa->level = $data['level'];
                $coa->default_dk = $data['default_dk'];
                $coa->description = $data['description'];
                $coa->beginning_balance = floatval($data['beginning_balance']);
                $coa->updated_by = Auth::user()->username;
                $coa->update();

                return response()->json(true);
                //code...
            } catch (\Throwable $th) {
                throw new \Exception($th->getMessage());
            }


        } else {
            abort(404);
        }

    }

    public function deletecoa($id,Request $request){

        if ($request->ajax()){

            try {

                COA::where("code",'like', $id ."%")->delete();

                // return response()->json(true);
                return response()->json(['msg' => 'Data '.$id.' Successfully Deleted', 'status' => 'success', 'code' => 200]);

            } catch (\Throwable $th) {
                throw new \Exception($th->getMessage());
            }


        } else {
            abort(404);
        }

    }
    public function deletecoasub($id,Request $request){

        if ($request->ajax()){

            try {

                COA::where("code",'like', $id ."%")->where("code", "<>",  $id )->delete();

                // return response()->json(true);
            return response()->json(['msg' => 'Data '.$id.' Successfully Deleted', 'status' => 'success', 'code' => 200]);

            } catch (\Throwable $th) {
                throw new \Exception($th->getMessage());
            }


        } else {
            abort(404);
        }

    }

}
