<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ServicesController extends Controller
{
    public function indexAdmin(): View
    {

        return view('services.services-admin');
    }

    public function indexMember(): View
    {
        return view('services.services-member');
    }
}
