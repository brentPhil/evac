<?php $icon = 'fa-shuttle-van'; ?>
<?php $headTitle = 'Multipurpose Vehicle Information' ?>
<?php $mainTitle = "Multipurpose Vehicle Information" ?>
<?php $breadcrumbs = "Multipurpose Vehicle Information" ?>
<?php $menuSection = "Multipurpose Vehicle Information" ?>
<?php $menuParentSection = "mp" ?>
<?php $menuSection = "addmpv" ?>

<?php

require_once 'connection.php';

$mpvQuery = $conn->query("SELECT * FROM mpv where is_deleted = 0");
?>

<?php include 'main.php' ?>

<section class="content">

    <div class="container-fluid">

        <?php
        if (isset($_SESSION['isSave'])) {
            echo '<div class="alert alert-success"> <i class="fas fa-check"></i> Successfully Updated MPV status </div>';
            unset($_SESSION['isSave']);
        }
        ?>

        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                        <i class="fa fa-shuttle-van"></i> Multipurpose Vehicle status
                    </div>
                    <form method="POST" action="./add_brgy_db.php">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name"><i class="fa fa-car"></i> MPV Name</label>
                                        <input type="text" required="" name="name" class="form-control" placeholder="Multipurpose Vehicle Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="platenumber"><i class="fa fa-list-alt"></i> Plate Number</label>
                                        <input type="text" required="" name="platenumber" class="form-control" placeholder="Platenumber">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date"><i class="fa fa-calendar"></i> Date Added</label>
                                <input type="date" required="" name="date" class="form-control" placeholder="Date">
                            </div>
                            <div class="form-group">
                                <label for="type"><i class="fa fa-check"></i> Status</label>
                                <select class="form-control" name="type" required="">
                                    <option value="1">Ayad/Nadara</option>
                                    <option value="2">Ruba</option>
                                    <option value="3">Bakante</option>
                                    <option value="4">Gingamit</option>
                                    <option value="5">Ginaayad Pa!</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</button>
                            <button type="submit" name="Up_mpv" class="btn btn-primary ml-2"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                        <i class="fa fa-shuttle-van"></i> Manage Multipurpose Vehicle status
                    </div>
                    <div class="card-body">
                        <table id="barangayList" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>MPV Name</th>
                                <th>Platenumber</th>
                                <th>Date Added</th>
                                <th>Status</th>
                                <th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while($fetch = $mpvQuery->fetch_array()) {
                                $date = date('m/d/Y', strtotime($fetch['date']));
                                ?>
                                <tr class="id_<?php echo $fetch['id'] ?>">
                                    <td><?php echo $fetch['name'] ?></td>
                                    <td><?php echo $fetch['platenumber']?></td>
                                    <td><?php echo $date ?></td>
                                    <td>
                                        <?php switch ($fetch['status']) {
                                            case 1;
                                                echo 'Ayad/Nadara';
                                                break;
                                            case 2;
                                                echo 'Ruba';
                                                break;
                                            case 3;
                                                echo 'Bakante';
                                                break;
                                            case 4;
                                                echo 'Gingamit';
                                                break;
                                            case 5;
                                                echo 'Ginaayad Pa!';
                                                break;
                                        } ?>
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-sm btn-success edit"
                                           data-id="<?php echo $fetch['id'] ?>"
                                           data-name="<?php echo $fetch['name'] ?>"
                                           data-platenumber="<?php echo $fetch['platenumber'] ?>"
                                           data-date="<?php echo $date ?>"
                                           data-type="<?php echo $fetch['status'] ?>"
                                           href="#">
                                            <i class="fa fa-edit"></i> edit
                                        </a>
                                        <a class="btn btn-sm btn-danger delete" data-id="<?php echo $fetch['id'] ?>" href="#" >
                                            <i class="fa fa-trash-alt"></i> delete
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
    </div>
</section>


<div id="modal-default" class="modal animated rubberBand delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="../asset/img/sent.png" alt="" width="50" height="46">
                <h3>Are you sure want to delete this Data?</h3>
                <div class="m-t-20"> <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-danger delete-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="modal-edit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: rgba(62,88,113);">
                <h4 class="modal-title"><i class="fa fa-globe-asia mr-2"></i>Edit MPV</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <input type="hidden" id="eid" name="">
                    <div class="form-group">
                        <label for="ename">MPV Name</label>
                        <input type="text" required="" id="ename" name="name" class="form-control" placeholder="Enter MPV name">
                    </div>
                    <div class="form-group">
                        <label for="eplatenumber">Platenumber</label>
                        <input type="text" required="" id="eplatenumber" name="platenumber" class="form-control" placeholder="Enter platenumber">
                    </div>
                    <div class="form-group">
                        <label for="edate">Date</label>
                        <input type="date" required="" id="edate" name="date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="etype">Status</label>
                        <select class="form-control" name="type" id="etype" required="">
                            <option value="1">Ayad/Nadara</option>
                            <option value="2">Ruba</option>
                            <option value="3">Bakante</option>
                            <option value="4">Gingamit</option>
                            <option value="5">Ginaayad Pa!</option>
                        </select>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="save_edit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php' ?>

<script type="text/javascript">
    $(function () {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })


    $('#save_edit').on('click', function(e) {

        e.preventDefault();

        $.ajax({
            type: 'post',
            url: 'save_mpv.php',
            data: {
                id: $('#eid').val(),
                name: $('#ename').val(),
                date: $('#edate').val(),
                type: $('#etype').val(),
                platenumber: $('#eplatenumber').val(),
            },
            success: function (data) {
                window.location = 'mpv.php'
            }

        });
    });

    $('.edit').on('click', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let name = $(this).data('name');
        let platenumber = $(this).data('platenumber');
        let date = $(this).data('date');
        let type = $(this).data('type');

        var datex = new Date(date).toISOString().split('T')[0];

        $('#eid').val(id)
        $('#ename').val(name)
        $('#eplatenumber').val(platenumber)
        $('#edate').val(datex)
        $('#etype option[value="' + type +'"]').prop("selected", true);
        $('#modal-edit').modal();
    })

    $('.delete').on('click', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        $('#modal-default').modal();

        $('.delete-btn').on('click', function() {

            $.ajax({
                type: 'post',
                url: 'delete_mpv.php',
                data: {
                    id: id
                },
                success: function (data) {
                    $('.id_' + id).hide('slow')
                    $('#modal-default').modal('hide');
                }

            });
        });

        console.log(id)
    })

    $('#mpvList').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

</script>