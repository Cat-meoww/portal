<?php

namespace App\Controllers\User;

use App\Controllers\General;


class user extends General
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        return $this->dashboard();
    }
    public function dashboard()
    {
        $this->data['title'] = "Users | Dashboard";
        return view('users/dashboard', $this->data);
    }
    
}