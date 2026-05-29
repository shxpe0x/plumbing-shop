<?php

namespace App\Http\Controllers\Public;

class PageController extends BaseController
{
    public function about()
    {
        return view('public.pages.about');
    }

    public function delivery()
    {
        return view('public.pages.delivery');
    }

    public function warranty()
    {
        return view('public.pages.warranty');
    }

    public function contacts()
    {
        return view('public.pages.contacts');
    }
}
