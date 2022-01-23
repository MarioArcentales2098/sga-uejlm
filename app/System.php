<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class System extends Model{
    
    protected function nameApp(){return "SGA-UEJLM";}
    
    protected function countAsingatura(){
        $conteo = (DB::table('asignaturas')->count() + 1);
        $codigo = '';
        if($conteo < 10){ $codigo = '-00'.$conteo;}
        if($conteo >= 10 && $conteo < 100){ $codigo = '-0'.$conteo;}
        if($conteo >= 100){ $codigo = '-'.$conteo;}
        return $codigo;
    }

    //PERIODO ACTIVO
    protected static function periodoActual(){
        $consulta = DB::table('periodolectivo')->where('estado', 1)->first();
        if($consulta){
            $periodo = $consulta->nombre;
        }else{
            $periodo = "____ - ____";
        }
        return $periodo;
    }   

    //PERIODO ACTIVO SIDEBAR
    protected static function periodoActivo(){
        $consulta = DB::table('periodolectivo')->where('estado', 1)->count();
        if($consulta > 0){
            return true;
        }else{
            return false;
        }        
    }

    //COLOR ALEATORIO
    protected static function randomColor(){
        $str = "#";
        for($i = 0 ; $i < 6 ; $i++){
            $randNum = rand(0, 15);
            switch ($randNum) {
                case 10: $randNum = "A"; 
                break;
                case 11: $randNum = "B"; 
                break;
                case 12: $randNum = "C"; 
                break;
                case 13: $randNum = "D"; 
                break;
                case 14: $randNum = "E"; 
                break;
                case 15: $randNum = "F"; 
                break; 
            }
            $str .= $randNum;
        }
        return $str;
    }
}
