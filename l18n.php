<?php
/*
* @name l18n
* @description A small l18n class for easy localization.
* @author Kevin (http://www.xn--fundstcke-im-netz-72b.de/)
* @version 1.1
* @source https://github.com/Reflic/l18n
*/

class l18n {
    private static $langcode = 'en';
    private static $path = 'lang/';
    public static $lines = array();

    private static $log = false;  
    private static $debug = false;

    public static function set_path($path){
        self::$path = $path;
    }

    
    public static function set_lang($langcode){
        self::$langcode = $langcode;
    }

    public static function get_langcode(){
        return self::$langcode;
    }

    private static function path($section){
        $path = self::$path.self::$langcode.'/'.$section.'.php';
        return $path;
    }

    private static function load($section){
        if(file_exists(self::path($section))){
            self::$lines[self::$langcode][$section] = require(self::path($section));
            return true;
        }
        else {
            return false;
        }
    }

    public static function line($key, $default){
        $key_part = explode('.', $key);
        $section = $key_part[0];
        $line = $key_part[1];

        if(isset(self::$lines[self::$langcode][$section][$line])){
            if(self::$debug){
                return '[['.self::$lines[self::$langcode][$section][$line].']]';
            }
            else{
                return self::$lines[self::$langcode][$section][$line];
            }
                        
        }
        else {
            if(self::load($section) == true){
                if(isset(self::$lines[self::$langcode][$section][$line])){
                    if(self::$debug){
                        return '[['.self::$lines[self::$langcode][$section][$line].']]';
                    }
                    else{
                        return self::$lines[self::$langcode][$section][$line];
                    }       
                }
                else{
                    //After Load the key is not aviable
                    return $default;
                }
            }
            else {
                #Logging
                // if(self::$log == 'true'){
                //     $file = fopen("l18n_log.txt","a+");
                //     $text = date('d.m.Y H:i:s').' '.$_SERVER['REMOTE_ADDR'].' '.self::$langcode.'-'.$key;
                //     fputs($file, $text."\n");
                //     fclose($file); 
                // }
                return $default;                        
            }
        }
    }

    public static function has_translation($langcode) {
        $dir = self::$path . $langcode;
        if (is_dir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    public static function parse_header() {
        $header = explode(",", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        
        foreach ($header as $entry) {
            $langcode = explode(";", $entry)[0];
            
            if (self::has_translation($langcode)) {
                self::set_lang($langcode);
                return;
            }
        }
    }
}




function __($key, $default){
    l18n::parse_header();
    echo l18n::line($key, $default);
}

?>