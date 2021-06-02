<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\TermService;
use Illuminate\Http\Request;

class TermServiceController extends Controller
{
    public function index($name){
        $term_service = TermService::whereStatus(TermService::STATUS_OPEN)
            ->where('name', $name)->first();
        if(!$term_service){
            abort(404);
        }
        return  view('front.service.index', compact('term_service'));

    }
}
