<?php

use Carbon\Carbon;

if (! function_exists('getDateCode_H')) {
    function getDateCode_H() {
        $currentMonth =  sprintf("%02d",Carbon::now()->month);
        $currentYear = substr(Carbon::now()->year, 2);
        $currentDate = sprintf("%02d" ,Carbon::now()->day);
        return $currentYear.$currentMonth.$currentDate; //240328
    }
}


if (! function_exists('automaticCode_H')) {
    function automaticCode_H ($keyWord, $model ,$dateType =true,$columnName) :string{

        $newCode = "";
        if ($model){ //Jika $model bukan null
            $code = $model->getAttribute($columnName); //PRC2403280001 OR TYPE001


            if ($dateType){
                // $digit=  substr($code, $subString);
                $digit =  str_replace($keyWord , "" , $code);// str_replace("PRC" , "" , PRC2403280001)  =>   2403280001

                $digitDate = substr($digit , 0 ,strlen($digit) -3); // substr(2403280001 , 0 , 6) =>240328
                $incrementDigit = intval(substr($digit, (strlen($digit) - 3))) + 1;  //intval(substr(2403280001 ,6)) + 1 ==> 2
                $decimalCode = sprintf("%03d" , $incrementDigit); // sprintf("%03d" , 2) ==> 002

                if ($digitDate == getDateCode_H()){

                    $newCode = $keyWord . $digitDate . $decimalCode ;

                } else {
                    $newCode = $keyWord . getDateCode_H() . $decimalCode;
                }


            } else {
                $incrementDigit =  intval(str_replace($keyWord , "" , $code)) + 1;
                $decimalCode= sprintf("%03d",$incrementDigit);

                $newCode = $keyWord . $decimalCode;

            }


        }else { //Jika $model null
            if ($dateType){
                $newCode = $keyWord . getDateCode_H() . "001";

            } else {

                $newCode = $keyWord . "001";

            }
        }
        return $newCode;
    }
}

if (! function_exists('getConditionRouteActions')) {
    function getConditionRouteActions($route, $ls_action) {

        $ls_rotes_actions = ['index', 'create', 'store', 'edit', 'update', 'destroy', 'users', 'getDataUsers', 'updateDataUser', 'addDataUsers', 'deleteDataUser', 'editUsers', 'getTableItemSearch', 'CustomerGetForModal', 'SupplierGetForModal', 'getroles', 'getsuppliers', 'getcustomers', 'getcategorys', 'getitems', 'getUnits', 'coalist', 'getTreeCOA', 'getCOATableSearch', 'addCoa', 'editcoa', 'deletecoa', 'deletecoasub'];

        if ($route == 'admin') {

            foreach ($ls_action as $act) {
                $ls_r_a[] = $route.'.'.$ls_rotes_actions[$act];
            }

        } elseif ($route == 'r_unit') {

            foreach ($ls_action as $act) {
                $ls_r_a[] = $route.'.'.$ls_rotes_actions[$act];
            }
        } elseif ($route == 'r_item') {

            foreach ($ls_action as $act) {
                $ls_r_a[] = $route.'.'.$ls_rotes_actions[$act];
            }
        } elseif ($route == 'r_category') {

            foreach ($ls_action as $act) {
                $ls_r_a[] = $route.'.'.$ls_rotes_actions[$act];
            }
        } elseif ($route == 'r_customer') {

            foreach ($ls_action as $act) {
                $ls_r_a[] = $route.'.'.$ls_rotes_actions[$act];
            }
        } elseif ($route == 'r_supplier') {

            foreach ($ls_action as $act) {
                $ls_r_a[] = $route.'.'.$ls_rotes_actions[$act];
            }
        } elseif ($route == 'r_role') {

            foreach ($ls_action as $act) {
                $ls_r_a[] = $route.'.'.$ls_rotes_actions[$act];
            }
        } elseif ($route == 'r_upah') {

            foreach ($ls_action as $act) {
                $ls_r_a[] = $route.'.'.$ls_rotes_actions[$act];
            }
        }

        return $ls_r_a;
    }
}



if (! function_exists('getRouteChecker_MDLR')) {
    function getRouteChecker_MDLR($role, $route) {

        $ls_rotes_murni = ['admin', 'r_unit', 'r_item', 'r_category', 'r_customer', 'r_supplier', 'r_role', 'r_upah'];

        // $ls_rotes_actions = ['index', 'create', 'store', 'edit', 'update', 'destroy', 'users', 'getDataUsers', 'updateDataUser', 'addDataUsers', 'deleteDataUser', 'editUsers', 'getTableItemSearch', 'CustomerGetForModal', 'SupplierGetForModal', 'getroles', 'getsuppliers', 'getcustomers', 'getcategorys', 'getitems', 'getUnits', 'coalist', 'getTreeCOA', 'getCOATableSearch', 'addCoa', 'editcoa', 'deletecoa', 'deletecoasub'];

        // 1 iki ignore
        // iki block kecuali Admin 2,4,5 8,9,10 24,26,27

        $allow_route = [];
        switch ($role) {
            case 1: // Admin
                $ls_action = [0,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27];
                if (in_array($route, $ls_rotes_murni)) {
                    $allow_route = getConditionRouteActions($route, $ls_action);
                }
                break;
            case 2: // Keuangan
                if (in_array($route, $ls_rotes_murni)) {
                    if ($route == 'admin') {
                        $ls_action = [6,7,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_unit') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_item') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_category') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_customer') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_supplier') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_role') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_upah') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    }
                }
                break;
            case 3: // Logistik
                if (in_array($route, $ls_rotes_murni)) {
                    if ($route == 'admin') {
                        $ls_action = [0,2,3,4,5,6,7,12,13,14,15,16,17,18,19,20,21,22,23];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_unit') {
                        $ls_action = [0,2,3,4,5];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_item') {
                        $ls_action = [0,2,3,4,5];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_category') {
                        $ls_action = [0,2,3,4,5];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_customer') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_supplier') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_role') {
                        $ls_action = [0,2,3,4,5];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_upah') {
                        $ls_action = [0,2,3,4,5];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    }
                }
                break;
            case 4: // Direktur
                if (in_array($route, $ls_rotes_murni)) {
                    if ($route == 'admin') {
                        $ls_action = [6,7,12,13,14,15,16,17,18,19,20,21,22,23];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_unit') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_item') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_category') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_customer') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_supplier') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_role') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    } elseif ($route == 'r_upah') {
                        $ls_action = [0,3];
                        $allow_route = getConditionRouteActions($route, $ls_action);
                    }
                }
                break;

            default:
                # code...
                break;
        }

        return $allow_route;

    }
}
