<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h2>
    NAME : <?= isset($details->name) ? $details->name : "" ?>
</h2>
<style>
    .height400 {
        height: 400px;
    }
</style>
<div class="d-flex flex-wrap">
    <?php
    foreach ($images as $row) { ?>
        <img src="<?= base_url($row->image_path) ?>" class="img img-thumbnail height400" alt="userfile">
    <?php }
    ?>
</div>

<h2 class="mt-3">Ratings</h2>

<table id="ss-datatable" class="table table-striped table-bordered mt-4" role="grid" aria-describedby="user-list-page-info">
    <thead>
        <tr>
            <th scope="col">Rating</th>
            <th scope="col">Created On</th>
        </tr>

    </thead>
    <tbody>
        <?php foreach ($ratings as $row) { ?>
            <tr>
                <?= "<td>" . $row->rating . "</td>" ?>
                <?= "<td>" . $row->rated_at . "</td>" ?>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?= $this->endSection() ?>