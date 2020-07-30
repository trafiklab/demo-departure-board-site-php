<?php


namespace App\Http\Controllers;


use App\Http\Models\SidebarContext;

class HomeController extends Controller
{

    public function show()
    {
        $sidebarContext = new SidebarContext(false, url()->current());
        return view('content', ['content' => 'home', 'sidebarContext' => $sidebarContext]);
    }

}
