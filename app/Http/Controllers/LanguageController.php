<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{

    //Change the language of the whole app
    public function switchLanguage(){
        $lang = request()->post('language');
        session(['language' => $lang]);

        return redirect()->back();
    }
}
