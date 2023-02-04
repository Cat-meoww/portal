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


        let rateform = {
            uid: null,
            eid: null,
            name: null,

            init: () => {
                $(document).on('click', 'button.ratingbtn', function() {
                    rateform.uid = $(this).data('uid');
                    rateform.eid = $(this).data('eid');
                    rateform.name = $(this).data('name');
                    $('#rateto').html(rateform.name);
                    $('#Rater').modal('show');
                });
            },
            empty: () => {
                rateform.uid = null
                rateform.eid = null
                rateform.name = null
                $('#rateranger').val(0)
            },
            submit: () => {
                console.log(rateform);
                let rate = $('#rateranger').val();

                let form = new FormData();
                form.set('eid', rateform.eid)
                form.set('uid', rateform.uid)
                form.set('rating', rate)
                rateform.pingserver(form)
            },
            pingserver: async (parameter) => {
                const url = "<?= base_url('api/add-rating') ?>";
                const options = {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    'Content-Type': 'application/x-www-form-urlencoded',
                    body: parameter,
                }
                const response = await fetch(url, options);
                let res = await response.text();
                if (res === "success") {
                    toastr.success("Rated success");
                    call_datatable();
                    rateform.empty();

                } else {
                    toastr.warning(res);
                }
                $('#Rater').modal('hide');

            }

        }
        let deleteform = {
            uid: null,
            eid: null,
            name: null,

            init: () => {
                $(document).on('click', 'button.deleteemp', function() {
                    deleteform.uid = $(this).data('uid');
                    deleteform.eid = $(this).data('eid');
                    deleteform.name = $(this).data('name');
                    deleteform.submit();
                });
            },
            empty: () => {
                deleteform.uid = null
                deleteform.eid = null
                deleteform.name = null
            },
            submit: () => {
                let form = new FormData();
                form.set('eid', deleteform.eid)
                form.set('uid', deleteform.uid)
                deleteform.pingserver(form)
            },
            pingserver: async (parameter) => {
                const url = "<?= base_url('api/delete-emp') ?>";
                const options = {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    credentials: 'same-origin',
                    'Content-Type': 'application/x-www-form-urlencoded',
                    body: parameter,
                }
                const response = await fetch(url, options);
                let res = await response.text();
                if (res == "success") {
                    toastr.success("DELETED success");
                    call_datatable();
                    deleteform.empty();

                } else {
                    toastr.warning(res);
                }

            }

        }

        rateform.init();
        deleteform.init();


        $('#ratersubmit').on('click', rateform.submit);







    });
</script>

<div class="card">

    <div class="card-body">

        <div class="table-responsive pb-3">
            <table id="ss-datatable" class="table table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
                <thead>
                    <tr>
                        <th scope="col">Rate</th>
                        <th scope="col">EMP ID</th>
                        <th scope="col">Employee</th>
                        <th scope="col">Mobile Number</th>
                        <th scope="col">DOB</th>
                        <th scope="col">Age</th>
                        <th scope="col">Salary</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">Ending Date</th>
                        <th scope="col">Average Rating</th>
                    </tr>

                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class=" modal" id="Rater" tabindex=" -1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rateting to <span id="rateto"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="input-group">
                        <label for="customRange2" class="form-label">Rate this</label>
                        <input type="range" class="form-range" min="0" value="0" max="5" step="1" id="rateranger">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="ratersubmit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>