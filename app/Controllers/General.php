<?php

namespace App\Controllers;


use App\Models\UserModel;


class General extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->usermodel = new UserModel();
        $this->data['session'] = $this->session;
        $this->data['uri']  = service('uri');
        helper(['url', 'session', 'custom']);
        $this->date = date("Y-m-d h:i:s");
        $this->session = session();
        if (!$this->session->has('login') || !$this->session->login) {
            redirect('auth/login', 'refresh');
        }
        $this->db = db_connect();
    }
}