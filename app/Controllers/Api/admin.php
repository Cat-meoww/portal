<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;




class admin extends ResourceController
{

    use ResponseTrait;
    public $db;
    public $session;
    public $date;
    public function __construct()
    {
        $this->db = db_connect();
        $this->session = session();
        $this->date = date("Y-m-d H:i:s");
        $config = new \Config\App();
        $this->TIMEZONE = $config->appTimezone;
    }

    public function addemployee()
    {
        $rules = [
            'Name'    => 'required|alpha_numeric|is_unique[users.username]',
            'Firstname' => 'required|trim|alpha_numeric_space',
            'Lastname' => 'required|trim|alpha_numeric_space',
            'Mailid' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[5]|alpha_numeric_punct',
        ];
        $Time = new \CodeIgniter\I18n\Time();
        if ($this->validate($rules)) {
            $username = $this->request->getPost("Name");
            $Firstname = $this->request->getPost("Firstname");
            $Lastname = $this->request->getPost("Lastname");
            $Mailid =  $this->request->getPost("Mailid");
            $password =  $this->request->getPost("password");


            $user = $this->db->table('users');
            $data = [
                'username' => $username,
                'firstname' => $Firstname,
                'lastname' => $Lastname,
                'password' =>$password,
                'email' => $Mailid,
                'user_role' => 2,
                'is_logged' => 0,
                'created_on' => $this->date,
            ];
            if ($user->insert($data)) {
               
            } else {
                return $this->respond($data);
            }




            return $this->respond("success");
        } else {
            return $this->respond($this->validator->listErrors('alert-info-list'));
        }
    }
    public function show_employees()
    {
        $ordercol = array('id');
        $sql = "SELECT * FROM users as e
        WHERE e.id !=0 ";
        $search_value = $this->request->getPost('search[value]');
        $recordsTotal = $this->db->query($sql)->getNumRows();
        if (!empty($search_value)) {
            $sql .= "AND (username LIKE '%$search_value%' OR firstname LIKE '%$search_value%' OR email LIKE '%$search_value%' OR lastname LIKE '%$search_value%' )  ";
        }




        if (isset($_POST["order"])) {
            $colorder = $this->request->getPost('order[0][column]');
            $coldir = $this->request->getPost('order[0][dir]');
            $columnname = isset($ordercol[$colorder]) ? $ordercol[$colorder] : 'id';

            $sql .= " ORDER BY $columnname $coldir";
        } else {
            //default
            $sql .= " ORDER BY id DESC";
        }
        $recordsFiltered = $this->db->query($sql)->getNumRows();
        if ($_POST["length"] != -1) {
            $sql .= " LIMIT " . $_POST["start"] . ", " . $_POST["length"];
        }

        $query = $this->db->query($sql);
        $data = [];

        foreach ($query->getResult() as $row) {
            $subarray = [];
            $subarray[] = $row->username;
            $subarray[] = $row->firstname;
            $subarray[] = $row->lastname;
            $subarray[] = $row->email;
            $subarray[] = $row->created_on;
            $data[] = $subarray;
        }
        $output = [
            "draw"                 => $this->request->getPost('draw'),
            "recordsTotal"         => $recordsTotal,
            "recordsFiltered"    => $recordsFiltered,
            "data"                 => $data,
        ];
        return $this->respond($output);
    }
}
