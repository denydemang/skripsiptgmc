<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
  
    protected function getDateCode() :string {
        $currentMonth =  sprintf("%02d",Carbon::now()->month);
        $currentYear = substr(Carbon::now()->year, 2);
        $currentDate = sprintf("%02d" ,Carbon::now()->day);
        return $currentYear.$currentMonth.$currentDate; //240328
    }

    protected function automaticCode ($keyWord, $model ,$dateType =true,$columnName) :string{

        $newCode = "";
        if ($model){ //Jika $model bukan null
            $code = $model->getAttribute($columnName); //PRC2403280001 OR TYPE001 
        

            if ($dateType){
                // $digit=  substr($code, $subString); 
                $digit =  str_replace($keyWord , "" , $code);// str_replace("PRC" , "" , PRC2403280001)  =>   2403280001

                $digitDate = substr($digit , 0 ,strlen($digit) -3); // substr(2403280001 , 0 , 6) =>240328
                $incrementDigit = intval(substr($digit, (strlen($digit) - 3))) + 1;  //intval(substr(2403280001 ,6)) + 1 ==> 2
                $decimalCode = sprintf("%03d" , $incrementDigit); // sprintf("%03d" , 2) ==> 002

                if ($digitDate == $this->getDateCode()){ 
                    
                    $newCode = $keyWord . $digitDate . $decimalCode ; 

                } else {
                    $newCode = $keyWord . $this->getDateCode() . $decimalCode;
                }
            
            
            } else {
                $incrementDigit =  intval(str_replace($keyWord , "" , $code)) + 1; 
                $decimalCode= sprintf("%03d",$incrementDigit);

                $newCode = $keyWord . $decimalCode;
                
            }
            

        }else { //Jika $model null
            if ($dateType){
                $newCode = $keyWord . $this->getDateCode() . "001";
                
            } else {

                $newCode = $keyWord . "001";
                
            }
        }
        return $newCode;
    }
}
