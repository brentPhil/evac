<?php $icon = 'fa-info-circle'; ?>
<?php $headTitle = 'Barangay Information' ?>
<?php $mainTitle = "Barangay Information" ?>
<?php $breadcrumbs = "Barangay Information" ?>
<?php $menuParentSection = "barangay" ?>
<?php $menuSection = "brgy" ?>

<?php

require_once 'connection.php';

$brgyQuery = $conn->query("SELECT * FROM barangay WHERE status = 1");

?>

<?php include 'main.php' ?>

<section class="content">

    <div class="container-fluid">

        <?php
        if (isset($_SESSION['isSave'])) {
            echo '<div class="alert alert-success"> <i class="fas fa-check"></i> Successfully Saved </div>';
            unset($_SESSION['isSave']);
        }
        ?>
        <!-- form start -->
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                        Add Barangay
                    </div>
                    <div class="card-body">
                        <form method="POST" action="./add_brgy_db.php">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Barangay Name</label>
                                        <input type="text" required="" name="barangay" class="form-control" placeholder="Barangay name">
                                    </div>
                                    <div class="form-group">
                                        <label>Barangay Captain</label>
                                        <input type="text" required="" name="captain" class="form-control" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Barangay Secretary</label>
                                        <input type="text" required="" name="secretary" class="form-control" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact #</label>
                                        <input type="text" required="" name="contact_number" class="form-control" placeholder="">
                                    </div>
                                    <div class="form-group hide">
                                        <label>Lattitude</label>
                                        <input type="text"  name="lat" class="form-control" placeholder="Lattitude">
                                    </div>
                                    <div class="form-group hide">
                                        <label>Longitude</label>
                                        <input type="text"  name="long" class="form-control" placeholder="Longitude">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-danger text-sm px-3">Cancel</button>
                                <button type="submit" name="brgy" class="btn btn-primary text-sm ml-2 px-3">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                        Manage Barangay</div>
                    <div class="card-body">
                        <table id="barangayList" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Barangay Name</th>
                                <th>Barangay Captain</th>
                                <th>Barangay Secretary</th>
                                <th>Contact #</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while($fetch = $brgyQuery->fetch_array()) { ?>
                                <tr class="id_<?php echo $fetch['id'] ?>">
                                    <td><?php echo $fetch['name'] ?></td>
                                    <td><?php echo $fetch['captain'] ?></td>
                                    <td><?php echo $fetch['secretary'] ?></td>
                                    <td><?php echo $fetch['contact_no'] ?></td>
                                    <td class="text-right">
                                        <a class="btn btn-sm btn-success edit"
                                           data-id="<?php echo $fetch['id'] ?>"
                                           data-name="<?php echo $fetch['name'] ?>"
                                           data-captain="<?php echo $fetch['captain'] ?>"
                                           data-secretary="<?php echo $fetch['secretary'] ?>"
                                           data-contact_number="<?php echo $fetch['contact_no'] ?>"
                                           data-lat="<?php echo $fetch['lat'] ?>"
                                           data-long="<?php echo $fetch['lng'] ?>"
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
                <h3>Are you sure want to delete this Barangay?</h3>
                <div class="m-t-20"> <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-danger delete-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="modal-edit" class="modal animated rubberBand" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="fa fa-globe-asia"></span>Edit Barangay</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" id="eid" name="">
                    <div class="form-group">
                        <label for="ebarangay">Barangay Name:</label>
                        <input type="text" required="" id="ebarangay" name="barangay" class="form-control" placeholder="Enter barangay name">
                    </div>
                    <div class="form-group">
                        <label for="captain">Barangay Captain:</label>
                        <input type="text" required="" id="captain" name="captain" class="form-control" placeholder="Enter barangay captain's name">
                    </div>
                    <div class="form-group">
                        <label for="secretary">Secretary:</label>
                        <input type="text" required="" id="secretary" name="secretary" class="form-control" placeholder="Enter barangay secretary's name">
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact #:</label>
                        <input type="text" required="" id="contact_number" name="contact_number" class="form-control" placeholder="Enter contact number">
                    </div>
                    <input type="hidden" required="" id="elattitude" name="lat">
                    <input type="hidden" required="" id="elongitude" name="long">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" id="save_edit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php' ?>

<script type="text/javascript">

    $('#save_edit').on('click', function(e) {

        e.preventDefault();

        $.ajax({
            type: 'post',
            url: 'save_barangay.php',
            data: {
                id: $('#eid').val(),
                name: $('#ebarangay').val(),
                captain: $('#captain').val(),
                secretary: $('#secretary').val(),
                contact_number: $('#contact_number').val(),
                lat: $('#elattitude').val(),
                long: $('#elongitude').val(),
            },
            success: function (data) {
                window.location = 'barangay.php'
            }

        });
    });

    $('.delete').on('click', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        $('#modal-default').modal();

        $('.delete-btn').on('click', function() {

            $.ajax({
                type: 'post',
                url: 'delete_barangay.php',
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

    $('.edit').on('click', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let name = $(this).data('name');
        let captain = $(this).data('captain');
        let secretary = $(this).data('secretary');
        let contact_number = $(this).data('contact_number');
        let lat = $(this).data('lat');
        let long = $(this).data('long');

        $('#eid').val(id)
        $('#ebarangay').val(name)
        $('#captain').val(captain)
        $('#secretary').val(secretary)
        $('#contact_number').val(contact_number)
        $('#elattitude').val(lat)
        $('#elongitude').val(long)

        $('#modal-edit').modal();
    })


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