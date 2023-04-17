<?php $icon = 'fa-bell'; ?>
<?php $headTitle = 'Disaster' ?>
<?php $mainTitle = "Disaster" ?>
<?php $breadcrumbs = "Calamity" ?>
<?php $menuSection = "calamity" ?>

<?php

require_once 'connection.php';


$isSave = false;
if (isset($_POST['save'])) {

    $calamity = $_POST['calamity'];
    $date = $_POST['date'];
    $type = $_POST['type'];

    $conn->query("INSERT INTO `calamity` (id, name, date_added, type) VALUES(
    NULL, '$calamity', '$date', $type)");

    $isSave = true;
}

$brgyQuery = $conn->query("SELECT * FROM calamity");


?>

<?php include 'main.php' ?>

<section class="content">

    <div class="container-fluid">

        <?php if ($isSave) { ?>
            <div class="alert alert-success"> <i class="fas fa-check"> </i> Successfully Saved </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                         New Disaster
                    </div>
                    <form method="POST" action="">

                        <div class="card-body">
                            <div class="form-group">
                                <label>Disaster Name</label>
                                <input type="text" required="" name="calamity" class="form-control" placeholder="Disaster name">
                            </div>
                            <div class="form-group">
                                <label>Date Occurred</label>
                                <input type="date" required="" name="date" class="form-control" placeholder="Date">
                            </div>

                            <div class="form-group">
                                <label>Disaster Type</label>
                                <select class="form-control" name="type" required="">
                                    <option value="1">NATURAL</option>
                                    <option value="2">MAN MADE</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <a href="./calamity.php" class="btn btn-secondary text-sm px-3">Cancel</a>
                            <button type="submit" name="save" class="btn btn-primary px-3 ml-2">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                        Manage Disaster's
                    </div>
                    <div class="card-body">
                    <table id="barangayList" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Disaster Name</th>
                            <th>Date Occur</th>
                            <th>TYPE</th>
                            <th class="">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while($fetch = $brgyQuery->fetch_array()) {
                            $date = date('m/d/Y', strtotime($fetch['date_added']));
                            ?>
                            <tr class="id_<?php echo $fetch['id'] ?>">
                                <td><?php echo $fetch['name'] ?></td>
                                <td><?php echo $date ?></td>
                                <td><?php echo $fetch['type'] == 1 ? 'NATURAL' : 'MAN MADE' ?></td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-success edit"
                                       data-id="<?php echo $fetch['id'] ?>"
                                       data-name="<?php echo $fetch['name'] ?>"
                                       data-type="<?php echo $fetch['type'] ?>"
                                       data-date="<?php echo $date ?>"
                                       href="#">
                                        <i class="fa fa-edit"></i> edit
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
</section>


<div id="modal-default" class="modal animated rubberBand delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="../asset/img/sent.png" alt="" width="50" height="46">
                <h3>Are you sure want to delete this Barangay?</h3>
                <div class="m-t-20"> <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-danger delete-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="modal-edit" class="modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header text-white pt-3" style="background-color: rgba(62,88,113);">
                <h4 class="modal-title"><span class="fa fa-pencil-alt"></span> Edit Calamity</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <input type="hidden" id="eid" name="calamity_id">
                    <div class="form-group">
                        <label for="ecalamity">Calamity Name</label>
                        <input type="text" required="" id="ecalamity" name="calamity" class="form-control" placeholder="Calamity name">
                    </div>
                    <div class="form-group">
                        <label for="edate">Date</label>
                        <input type="date" required="" id="edate" name="date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="type">Disaster Type</label>
                        <select class="form-control" name="type" id="type" required="">
                            <option value="1">NATURAL</option>
                            <option value="2">MAN MADE</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" id="save_edit" class="btn btn-primary ml-2">Save</button>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php' ?>

<script type="text/javascript">

    $('#save_edit').on('click', function(e) {

        e.preventDefault();

        $.ajax({
            type: 'post',
            url: 'save_calamity.php',
            data: {
                id: $('#eid').val(),
                calamity: $('#ecalamity').val(),
                date: $('#edate').val(),
                type: $('#type').val(),
            },
            success: function (data) {
                window.location = 'calamity.php'
            }

        });
    });



    $('.edit').on('click', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let name = $(this).data('name');
        let type = $(this).data('type');
        let date = $(this).data('date');

        // convert date format to yyyy-mm-dd
        let isoDate = new Date(date).toISOString().substr(0, 10);

        $('#eid').val(id)
        $('#ecalamity').val(name)
        $('#edate').val(isoDate)
        $('#type option[value="' + type +'"]').prop("selected", true);

        $('#modal-edit').modal();
    });



    $('#barangayList').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
</script>