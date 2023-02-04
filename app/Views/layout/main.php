<!doctype html>
<html lang="en">

<?php
// $session = session();

$username = $session->username;
$firstname = $session->firstname;
$firstletter = $firstname[0];
$lastname = $session->lastname;
$email = $session->email;
$token = $session->token;
$user_role = $session->user_role;
$uri = service('uri', 'cookie');



?>


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?></title>
    <meta name="theme-color" content="#6c71e3" />
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <style>
        /* .bg-green-100 {
            background-color: ;
        } */
    </style>

</head>

<body class=" color-light ">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= base_url('admin/dashboard') ?>" role="button" aria-controls="offcanvasExample">Employee Mangement</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">



            </div>
            <a href="<?= base_url('auth/logout') ?>" id="appsignout" class="btn btn-success">Sign Out</a>
        </div>
    </nav>

    <div class="wrapper d-flex">
        <div class="d-flex px-3 pt-2">
            <div>
                <ul class="list-group">
                    <li class="list-group-item btn-success btn bg-green-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add employee


                    </li>
                </ul>
            </div>
        </div>
        <div class="content-page  flex-fill">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 px-3">
                        <?= $this->renderSection('content') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="allmasterform" action=" <?= base_url('all-master-save/' . $uri->getSegment(2)) ?>" autocomplete="off" enctype="multipart/form-data" class="needs-validation">
                        <div class="row">
                            <div class="col-4 mb-3 ">
                                <label for="Productty">Full Name</label>
                                <input type="text" name="Name" class="form-control" id="Username" value="" autocomplete="off" required>

                            </div>


                            <div class="col-4 mb-3">
                                <label for="Mailid">Mail Id</label>
                                <input type="email" name="Mailid" class="form-control" id="Mailid" value="" autocomplete="off" required>

                            </div>
                            <div class="col-4 mb-3">
                                <label for="Employeeid">Employee Id</label>
                                <input type="text" name="Employeeid" class="form-control" id="Employeeid" value="" autocomplete="off" required>

                            </div>

                            <div class="col-4 mb-3">
                                <label for="DOB">DOB</label>
                                <input type="date" name="DOB" class="form-control" id="DOB" value="" autocomplete="off" required>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="phno">Phone Number</label>
                                <input type="text" name="Phno" class="form-control" id="phno" value="" autocomplete="off" required>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="Salary">Salary</label>
                                <input type="number" name="Salary" class="form-control" id="Salary" value="" autocomplete="off" required>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="startdate">Start Date</label>
                                <input type="date" name="Startdate" class="form-control" id="startdate" value="" autocomplete="off" required>
                            </div>
                            <div class="input-group col-12 mb-3">
                                <input type="file" class="form-control" id="inputGroupFile02" name="userfile[]" accept="image/*" multiple required>
                                <label class="input-group-text" for="inputGroupFile02">Upload</label>
                            </div>

                            <div class="col-12 mb-3">
                                <button class="btn btn-primary" type="submit">Submit form</button>
                            </div>
                        </div>
                    </form>
                    <script>
                        $(document).ready(function() {
                            const form = document.querySelector('#allmasterform');
                            async function add_data(parameter) {
                                const url = "<?= base_url('api/add-employee') ?>";
                                const options = {
                                    method: 'POST',
                                    mode: 'cors',
                                    cache: 'no-cache',
                                    credentials: 'same-origin',
                                    body: new FormData(form),
                                }
                                const response = await fetch(url, options);
                                $('#create-type').modal('toggle');
                                let res = await response.text();
                                if (res == 'success') {
                                    toastr.success("Added Employee successfully");
                                    $("#allmasterform").trigger('reset');
                                    window.call_datatable();
                                } else {
                                    toastr.error(res);
                                }
                            }
                            $("#allmasterform").on('submit', function(event) {
                                event.preventDefault();
                                add_data($(this).serialize());
                            });

                        });
                    </script>
                </div>

            </div>
        </div>
    </div>

    <!-- Dynamic alerts -->
    <script>
        $(document).ready(function() {
            <?php
            $alert_data = $session->getFlashdata('alert');
            if (is_array($alert_data)) {
                foreach ($alert_data as  $value) {
                    if (is_array($value)) {
                    } else {
                        echo "toastr.success('$value');";
                    }
                }
            } else {
                echo "toastr.success('$alert_data');";
            }
            ?>
        });
    </script>




    <script src="<?= base_url() ?>/assets/js/toast/toastr.js"></script>




    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Title</h4>
                    <div>
                        <a class="btn" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="resolte-contaniner" style="height: 500px;" class="overflow-auto">
                        File not found
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>


</html>