<?php
require 'Rabbit.php';

class Bunny extends Rabbit {
    
    /**
    * Checking this string is unicode or zawgyi.
    *
    * @param  string $string
    * @return boolean
    */
    public static function is_zawgyi($string)
    {
        $zg_regex  =  "/"; 

        // e+medial ra
        $zg_regex .=  "\x{1031}\x{103b}";
        // beginning e or medial ra
        $zg_regex .= "|^\x{1031}|^\x{103b}";
        // independent vowel, dependent vowel, tone , medial ra wa ha (no ya
        // because of 103a+103b is valid in unicode) , digit ,
        // symbol + medial ra
        $zg_regex .= "|[\x{1022}-\x{1030}\x{1032}-\x{1039}\x{103b}-\x{103d}\x{1040}-\x{104f}]\x{103b}";
        // end with asat
        $zg_regex .= "|\x{1039}[$ ]";
        // medial ha + medial wa
        $zg_regex .= "|\x{103d}\x{103c}";
        // medial ra + medial wa
        $zg_regex .= "|\x{103b}\x{103c}";
        // consonant + asat + ya ra wa ha independent vowel e dot below
        // visarga asat medial ra digit symbol
        $zg_regex .= "|[\x{1000}-\x{1021}]\x{1039}[\x{101a}\x{101b}\x{101d}\x{101f}\x{1022}-\x{102a}\x{1031}\x{1037}-\x{1039}\x{103b}\x{1040}-\x{104f}]";
        // II+I II ae
        $zg_regex .= "|\x{102e}[\x{102d}\x{103e}\x{1032}]";
        // ae + I II
        $zg_regex .= "|\x{1032}[\x{102d}\x{102e}]";
        // I II , II I, I I, II II
        //+ "|[\x{102d}\x{102e}][\x{102d}\x{102e}]"
        // U UU + U UU
        //+ "|[\x{102f}\x{1030}][\x{102f}\x{1030}]" [ FIXED!! It is not so valuable zawgyi pattern ]
        // tall aa short aa
        //+ "|[\x{102b}\x{102c}][\x{102b}\x{102c}]" [ FIXED!! It is not so valuable zawgyi pattern ]
        // shan digit + vowel
        $zg_regex .= "|[\x{1090}-\x{1099}][\x{102b}-\x{1030}\x{1032}\x{1037}\x{103c}-\x{103e}]";
        // consonant + medial ya + dependent vowel tone asat
        $zg_regex .= "|[\x{1000}-\x{102a}]\x{103a}[\x{102c}-\x{102e}\x{1032}-\x{1036}]";
        // independent vowel dependent vowel tone digit + e [ FIXED !!! - not include medial ]
        $zg_regex .= "|[\x{1023}-\x{1030}\x{1032}-\x{1039}\x{1040}-\x{104f}]\x{1031}";
        // other shapes of medial ra + consonant not in Shan consonant
        $zg_regex .= "|[\x{107e}-\x{1084}][\x{1001}\x{1003}\x{1005}-\x{100f}\x{1012}-\x{1014}\x{1016}-\x{1018}\x{101f}]";
        // u + asat
        $zg_regex .= "|\x{1025}\x{1039}";
        // eain-dray
        $zg_regex .= "|[\x{1081}\x{1083}]\x{108f}";
        // short na + stack characters
        $zg_regex .= "|\x{108f}[\x{1060}-\x{108d}]";
        // I II ae dow bolow above + asat typing error
        $zg_regex .= "|[\x{102d}-\x{1030}\x{1032}\x{1036}\x{1037}]\x{1039}";
        // aa + asat awww
        $zg_regex .= "|\x{102c}\x{1039}";
        // ya + medial wa
        $zg_regex .= "|\x{101b}\x{103c}";
        // non digit + zero + 102d (i vowel) [FIXED!!! rules tested zero + i vowel in numeric usage]
        $zg_regex .= "|[^\x{1040}-\x{1049}]\x{1040}\x{102d}";
        // e + zero + vowel
        $zg_regex .= "|\x{1031}?\x{1040}[\x{102b}\x{105a}\x{102e}-\x{1030}\x{1032}\x{1036}-\x{1038}]";
        // e + seven + vowel
        $zg_regex .= "|\x{1031}?\x{1047}[\x{102c}-\x{1030}\x{1032}\x{1036}-\x{1038}]";
        // cons + asat + cons + virama
        //$zg_regex .= "|[\x{1000}-\x{1021}]\x{103a}[\x{1000}-\x{1021}]\x{1039}" [ FIXED!!! REMOVED!!! conflict with Mon's Medial ]
        // U | UU | AI + (zawgyi) dot below
        $zg_regex .= "|[\x{102f}\x{1030}\x{1032}]\x{1094}";
        // virama + (zawgyi) medial ra
        $zg_regex .= "|\x{1039}[\x{107e}-\x{1084}]";
    
        // rules add by thixpin
        // space + e + consonant
        $zg_regex .= "|[ $A-Za-z0-9]\x{1031}[\x{1000}-\x{1021}]";
        // consonant + Visarga 
        $zg_regex .= "|[\x{1000}-\x{1021}]\x{1038}";


        $zg_regex .= "/u";

        $SplittedText = preg_split("/[\s, ]+/", $string);

        foreach ($SplittedText as $key => $value) {           
  
            if(preg_match( $zg_regex , $value))
            {
                return true;
            }
        }
        return false;
    }

    /**
    * Nornalizing the input text.
    *
    * @param  string $text
    * @return string
    */
    public static function mm_pre_normalize($text)
    {
        $text = str_replace("ုု", "ု", $text);
        $text = str_replace("ိိ", "ိ", $text);
        $text = str_replace("ိီ", "ီ", $text);
        $text = str_replace("ီိ", "ီ", $text);
        $text = str_replace("ီီ", "ီ", $text);
        return $text;
    }

    /**
    * Nornalizing the unicode text.
    *
    * @param  string $uni
    * @return string
    */
    public static function mm_normalize($uni)
    {
        $uni = str_replace("ုု", "ု", $uni);
        $uni = str_replace("့်", "့်", $uni);
        $uni = str_replace("၍်", "၍", $uni);
        //$uni .= '_normalized'; // Delete after debugging
        return $uni;
    }


    /**
    * Checking this string is unicode or zawgyi.
    *
    * @param  string $string
    * @return string
    */
    public static function edit_mmtext($string)
    {
        $string = mb_convert_encoding($string, 'UTF-8');

        $string = self::mm_pre_normalize($string);
        if( self::is_zawgyi($string))
        {
            $string = self::zg2uni($string);
            //$string .= "_zawgyi";   // Delete after debugging
        }
        return self::mm_normalize($string);
    }

}


?>