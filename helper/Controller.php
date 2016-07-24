<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected $helper;
    protected $request;
    protected $redirect;
    protected $session;
    public function __construct()
    {
        $this->helper = new HelperRepository();
        $this->request = \App::make('Illuminate\Http\Request');
        $this->redirect = \App::make('Illuminate\Routing\Redirector');
        $this->session = null;
    }
}

class HelperRepository {
    public function getFilters() { }
}