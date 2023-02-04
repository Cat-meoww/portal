<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<script>
    $(document).ready(function() {
        window.call_datatable = function() {
            if ($("#ss-datatable").length) {
                $('#ss-datatable').dataTable().fnDestroy()
            }

            var dbtable = $('#ss-datatable').DataTable({
                language: {
                    search: "",
                    searchPlaceholder: "search",
                },
                search: {
                    return: true,
                },
                ordering: false,
                dom: "Bfrtip",
                "processing": true,
                "serverSide": true,
                "autoWidth": false,

                "order": [],
                "ajax": {
                    url: '<?= base_url("api/datatable/show-employee") ?>',
                    type: "POST",
                },
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }, ],
            });
        }

        call_datatable();
    });
</script>
<div class="d-flex px-3 pt-2">
    <div>
        <ul class="list-group">
            <li class="list-group-item btn-success btn bg-green-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Add users
            </li>
        </ul>
    </div>
</div>
<div class="content-page  flex-fill">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 px-3">
                <div class="card">

                    <div class="card-body">

                        <div class="table-responsive pb-3">
                            <table id="ss-datatable" class="table table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
                                <thead>
                                    <tr>
                                        <th scope="col">Username</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Emailid</th>
                                        <th scope="col">Create On</th>
                                    </tr>

                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
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
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Users</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="allmasterform" action=" <?= base_url('all-master-save/' . $uri->getSegment(2)) ?>" autocomplete="off" enctype="multipart/form-data" class="needs-validation">
                    <div class="row">
                        <div class="col-4 mb-3 ">
                            <label for="Productty">Username</label>
                            <input type="text" name="Name" class="form-control" id="Username" value="" autocomplete="off" required>

                        </div>
                        <div class="col-4 mb-3 ">
                            <label for="Productty">First Name</label>
                            <input type="text" name="Firstname" class="form-control" id="Firstname" value="" autocomplete="off" required>
                        </div>
                        <div class="col-4 mb-3 ">
                            <label for="Productty">Last Name</label>
                            <input type="text" name="Lastname" class="form-control" id="Lastname" value="" autocomplete="off" required>
                        </div>


                        <div class="col-4 mb-3">
                            <label for="Mailid">Mail Id</label>
                            <input type="email" name="Mailid" class="form-control" id="Mailid" value="" autocomplete="off" required>

                        </div>
                        <div class="col-4 mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" value="" autocomplete="off" required>

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



<?= $this->endSection() ?>