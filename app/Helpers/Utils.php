<?php
namespace knet\Helpers;

use Auth;

class Utils
{
    public static function scontaDel($price, String $discount, $round){
        //Prima di tutto analizzo lo sconto 
        $discount = (substr($discount,0,1)!='+' ? '+'.$discount : $discount);
        $splitDiscount = preg_split("/\-|\+/", $discount, -1, PREG_SPLIT_OFFSET_CAPTURE);

        $nP = 1;
        $lenDiscount = count($splitDiscount);

        for($i=0; $i<$lenDiscount; $i++) {
            $nP1 = floatval($splitDiscount[$i][0]) / 100;
            $nP1 = (substr($discount,((integer)$splitDiscount[$i][1])-1,1)=='+' ? 1-$nP1 : 1+$nP1);
            $nP = $nP*$nP1;
        }

        return (float)round($price*$nP, $round);
    }

}