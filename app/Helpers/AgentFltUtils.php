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
        }

        return $fltAgents;
    }
}
