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
            'Name'    => 'required|alpha_numeric',
            'Employeeid' => 'required|trim|alpha_numeric_space',
            'Mailid' => 'required|valid_email|is_unique[employees.emp_id]',
            'DOB' => 'required|valid_date',
            'Phno' => 'required|min_length[5]|alpha_numeric',
            'Salary' => 'required|decimal|greater_than[0]',
            'Startdate' => 'required|valid_date',
        ];
        $Time = new \CodeIgniter\I18n\Time();
        if ($this->validate($rules)) {
            $Name = $this->request->getPost("Name");
            $Employeeid = $this->request->getPost("Employeeid");
            $Mailid =  $this->request->getPost("Mailid");
            $DOB = date("Y-m-d h:i:s", strtotime($this->request->getPost("DOB")));
            $Phno = $this->request->getPost("Phno");
            $Salary = $this->request->getPost("Salary");
            $Startdate = date("Y-m-d h:i:s", strtotime($this->request->getPost("Startdate")));
            $avatar = $this->request->getFiles("userfile");
            $parse = $Time::parse($this->request->getPost("DOB"), $this->TIMEZONE);

            $parse = $parse->addYears(60);
            $employee = $this->db->table('employees');
            $data = [
                'name' => $Name,
                'dob' => $DOB,
                'emp_id' => $Employeeid,
                'email' => $Mailid,
                'mobile_no' => $Phno,
                'start_date' => $Startdate,
                'end_date' => $parse,
                'salary' => $Salary,
                'created_on' => $this->date,
            ];
            if ($employee->insert($data)) {
            } else {
                return $this->respond($data);
            }

            $builder = $this->db->table('images');
            $error = false;
            if ($this->request->getFileMultiple('userfile')) {
                foreach ($this->request->getFileMultiple('userfile') as $file) {
                    $newName = $file->getRandomName();

                    if ($file->move('uploads', $newName)) {

                        // $data = [
                        //     'name' =>  $file->getClientName(),
                        //     "newName" => $newName,
                        //     'type'  => $file->getClientMimeType(),
                        //     'path' => 'uploads/' . $file->getClientName()
                        // ];
                        $upper = [
                            'employee_id' => $Employeeid,
                            'image_path' => 'uploads/' . $newName
                        ];
                        $builder->insert($upper);
                    } else {
                        $error = true;
                    }
                }
            }
            if ($error) {
                return $this->respond("File Upload error");
            }


            return $this->respond("success");
        } else {
            return $this->respond($this->validator->listErrors('alert-info-list'));
        }
    }
    public function show_employees()
    {
        $ordercol = array('name');
        $sql = "SELECT CAST(AVG(rating) AS DECIMAL(10,2)) as avgrating, 
        e.*, TIMESTAMPDIFF(YEAR, e.dob, CURDATE()) as age
        FROM employees as e 
        LEFT JOIN ratings as r ON  e.emp_id=r.employee_id 
        WHERE e.id !=0 ";
        $search_value = $this->request->getPost('search[value]');
        $recordsTotal = $this->db->query($sql)->getNumRows();
        if (!empty($search_value)) {
            $sql .= "AND (e.name LIKE '%$search_value%' OR e.mobile_no LIKE '%$search_value%' OR e.email LIKE '%$search_value%' OR TIMESTAMPDIFF(YEAR, e.dob, CURDATE())= '$search_value'   )  ";
        }

        $sql .= " GROUP BY e.emp_id";


        if (isset($_POST["order"])) {
            $colorder = $this->request->getPost('order[0][column]');
            $coldir = $this->request->getPost('order[0][dir]');
            $columnname = isset($ordercol[$colorder]) ? $ordercol[$colorder] : 'e.id';

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
            $subarray[] = "<div class='d-flex flex-row'>
            <button data-name='$row->name' data-eid='$row->emp_id' data-uid='$row->id' class='ratingbtn gap-2 btn btn-success text-nowrap me-2'>Add Rating</button>
            <button data-name='$row->name' data-eid='$row->emp_id' data-uid='$row->id' class='deleteemp btn btn-danger me-2'>Delete</button>
            <a href='" . base_url("admin/profile/$row->emp_id") . "'  role='button' class='btn btn-secondary'>view</a>
            </div>";
            $subarray[] = $row->emp_id;
            $subarray[] = $row->name;
            $subarray[] = $row->mobile_no;
            $subarray[] = $row->dob;
            $subarray[] = $row->age;
            $subarray[] = $row->salary;
            $subarray[] = $row->start_date;
            $subarray[] = $row->end_date;
            $subarray[] = ($row->avgrating >= 0) ? $row->avgrating : 0;

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

    public function add_rating()
    {
        $rules = [
            'eid'    => 'required|alpha_numeric',
            'uid' => 'required|trim|alpha_numeric_space',
            'rating' => 'required|trim|is_natural|less_than_equal_to[5]',
        ];
        if ($this->validate($rules)) {
            $rating = $this->db->table('ratings');

            $Employeeid = $this->request->getPost("eid");
            $rate = $this->request->getPost("rating");
            $data = [
                'employee_id' => $Employeeid,
                'rating' => $rate,
                'rated_at' => $this->date,
            ];
            if ($rating->insert($data)) {
                $mail = $this->sendmsgratings($Employeeid);
                return $this->respond("success");
            } else {
                return $this->respond("fail");
            }
        } else {
            return $this->respond($this->validator->listErrors('alert-info-list'));
        }
    }
    public function delete_emp()
    {
        $rules = [
            'eid'    => 'required|alpha_numeric',
            'uid' => 'required|trim|alpha_numeric_space',
        ];
        if ($this->validate($rules)) {

            $Employeeid = $this->request->getPost("eid");
            $rating = $this->db->table('ratings')->where('employee_id', $Employeeid)->delete();
            $rating = $this->db->table('images')->where('employee_id', $Employeeid)->delete();
            if ($rating = $this->db->table('employees')->where('emp_id', $Employeeid)->delete()) {
                return $this->respond("success");
            } else {
                return $this->respond("failed to delete");
            }
        } else {
            return $this->respond($this->validator->listErrors('alert-info-list'));
        }
    }
    private function sendmsgratings($Employeeid)
    {

        $email = \Config\Services::email();
        $email->setFrom('your@example.com', 'Your Name');


        $email->setSubject('EMPLOYEE MANAGEMENT SYSTEM');


        $rating = $this->db->query("SELECT CAST(AVG(rating) AS DECIMAL(10,2)) as avgrating, 
        e.*, TIMESTAMPDIFF(YEAR, e.dob, CURDATE()) as age
        FROM employees as e 
        LEFT JOIN ratings as r ON  e.emp_id=r.employee_id 
        WHERE e.id !=0 and e.emp_id='$Employeeid'  GROUP BY e.emp_id");
        $row = $rating->getRow();

        if (isset($row)) {
            $email->setTo($row->email);

            $msg = "AVERAGE RATING {$row->avgrating}";
            $email->setMessage($msg);
            if ($email->send()) {
                return true;
            }
        }
        return false;
    }
}
