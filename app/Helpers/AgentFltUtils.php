<?php
namespace knet\Helpers;

class AgentFltUtils
{
    public static function checkSpecialRules($fltAgents)
    {
        if(RedisUser::get('ditta_DB')=='kNet_it'){
            //Gestione CKDESIGN --> Procacciatore CKDesign
            if(in_array("A29", $fltAgents)){
                array_push($fltAgents, "A13");
            }
            //Gestione EXFURIO --> DE LUCA
            if(in_array("005", $fltAgents)){
                array_push($fltAgents, "003");
            }
            //Gestione EXMIOTTO --> DE LUCA
            if(in_array("005", $fltAgents)){
                array_push($fltAgents, "18");
            }
        }

        return $fltAgents;
    }
}
