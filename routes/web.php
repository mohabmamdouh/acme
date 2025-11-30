<?php

use App\Models\Skill;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $x = Skill::find('1')->attachments;
    dd($x);
    return view('welcome');
});
