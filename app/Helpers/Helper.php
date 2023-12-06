<?php

namespace App\Helpers;

use App\Models\User;
use DB,Auth,File,Mail;


class Helper
{

    public static function settings($key){
        $settings = Setting::where('key',$key)->pluck('value')->first();
        return $settings;
    }

    public static function slug($table,$name){
        $slug = str_replace(' ', '-', $name);
        $i=1;
        while($i > 0){
            $check_slug = DB::table($table)->where('slug',$slug)->first();
            if($check_slug){
                $slug = str_replace(' ', '-', $name).'-'.$i;
                $i++;
                continue;
            }else{
                break;
            }
        }
        return $slug;

    }   

    public static function getLogo() {
        $logo = asset('assets/images/logo.png');
        return $logo;
    }

    public static function memberId(){
        $statement = DB::select("SHOW TABLE STATUS LIKE 'users'");
        $nextId = $statement[0]->Auto_increment;
        $member_id = 'de'.$nextId;
        return $member_id;
    }
    
    

}