<?php

namespace App\Controllers\admin;

use App\Controllers\General;
use App\Models\Product_type;
use App\Models\Product_category;
use App\Models\Product_sub_category;
use Config\App;

class Admin extends General
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
        $this->data['title'] = "Dashboard";
        return view('admin/dashboard', $this->data);
    }
    public function profile($empid)
    {


        $this->data['title'] = "Profile";

        $this->data['ratings'] = $this->db->table('ratings')->where('employee_id', $empid)->get()->getResult();
        $this->data['details'] = $this->db->table('employees')->where('emp_id', $empid)->get()->getRow();
        $this->data['images'] = $this->db->table('images')->where('employee_id', $empid)->get()->getResult();
        if (isset($this->data['details'])) {
            return view('admin/ratings', $this->data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
