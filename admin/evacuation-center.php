<?php $icon = 'fa-hotel'; ?>
<?php $headTitle = 'Evacuation Center' ?>
<?php $mainTitle = "Evacuation Center" ?>
<?php $breadcrumbs = "Evacuation Center" ?>
<?php $menuSection = "evacuation" ?>

<?php

require_once 'connection.php';

$q1 = $conn->query("SELECT * FROM barangay WHERE status = 1");
$brgys = $q1->fetch_all();

//$evacuationsQuery = $conn->query("SELECT * FROM evacuation_center WHERE status = 1 ORDER BY center ASC");
//$evacuations = $evacuationsQuery->fetch_all();

?>

<?php include 'main.php' ?>

<section class="content">

    <div class="container-fluid">

        <?php
        if (isset($_SESSION['isSave'])) {
            echo '<div class="alert alert-success"> <i class="fas fa-check"></i>Evacuation Center Successfully added</div>';
            unset($_SESSION['isSave']);
        }
        ?>
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                        <h5 class="mb-0"><i class="fas fa-hotel"></i> Evacuation Center Info</h5>
                    </div>
                    <form method="POST" action="./add_center.php" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Center Name</label>
                                <input type="text" required name="centername" class="form-control" placeholder="Enter center name">
                            </div>
                            <div class="form-group">
                                <label>Camp Manager</label>
                                <input type="text" required name="manager" class="form-control" placeholder="Enter camp manager">
                            </div>
                            <div class="form-group">
                                <label>Guard</label>
                                <input type="text" required name="guard" class="form-control" placeholder="Enter guard">
                            </div>
                            <div class="form-group">
                                <label>Contact Info</label>
                                <input type="text" required name="contactinfo" class="form-control" placeholder="Enter contact info">
                            </div>
                            <div class="form-group">
                                <label>Barangays</label>
                                <select name="brgy[]" class="select2bs4 form-control" multiple data-placeholder="Select barangays">
                                    <?php foreach ($brgys as $brg) { ?>
                                        <option value="<?php echo $brg[0] ?>"><?php echo $brg[1] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Latitude</label>
                                    <input type="text" name="lat" class="form-control" placeholder="Enter latitude">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Longitude</label>
                                    <input type="text" name="long" class="form-control" placeholder="Enter longitude">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Capacity</label>
                                <input type="number" required name="capacity" class="form-control" placeholder="Enter capacity">
                            </div>
                            <div class="form-group">
                                <label>Evacuation Center Photo</label>
                                <div class="custom-file">
                                    <input type="file" required name="image_path" class="custom-file-input" accept="image/png, image/jpeg">
                                    <label class="custom-file-label">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Evacuation Type</label>
                                <select class="form-control" name="type" required>
                                    <option value="1">Primary</option>
                                    <option value="2">Secondary</option>
                                    <option value="3">Alternative</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" name="saveCenter" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                        <h5 class="mb-0"><i class="fas fa-hotel"></i> Manage Evacuation Center's</h5>
                    </div>
                    <div class="card-body">
                        <table id="barangayList" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Center</th>
                                <th class="">Brgy Included</th>
                                <th class="hide">Address</th>
                                <th>Manager</th>
                                <th>Guard</th>
                                <th>Contact Info</th>
                                <th>Capacity</th>
                                <th>Type</th>
                                <th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $evacuationsQuery = $conn->query("
                            SELECT ec.*, 
                            (SELECT GROUP_CONCAT(b.name SEPARATOR ', ') FROM evacuation_barangay eb INNER JOIN barangay b ON eb.barangay_id = b.id WHERE eb.evac_id = ec.id) AS barangays,
                            COUNT(DISTINCT ev.person_id) AS num_citizens,
                            COUNT(DISTINCT CASE WHEN bi.age <= 18 THEN ev.person_id ELSE NULL END) AS num_children,
                            COUNT(DISTINCT ev.head_person_id) AS num_family_heads
                            FROM evacuation_center ec
                            LEFT JOIN evacuees ev ON ec.id = ev.evacuation_center_id AND ev.status = 1 AND ev.is_present = 1
                            LEFT JOIN barangay_info bi ON ev.person_id = bi.id
                            WHERE ec.status = 1
                            GROUP BY ec.id
                            ORDER BY ec.center ASC
                            ");

                            while ($fetch = $evacuationsQuery->fetch_assoc()) {
                                ?>
                                <tr class="id_<?php echo $fetch['id'] ?>">
                                    <td><?php echo $fetch['center'] ?></td>
                                    <td class=""><?php echo $fetch['barangays'] ?></td>
                                    <td class="hide"><?php echo $fetch['address'] ?></td>
                                    <td><?php echo $fetch['camp_manager'] ?></td>
                                    <td><?php echo $fetch['guard'] ?></td>
                                    <td><?php echo $fetch['contact'] ?></td>
                                    <td><?php echo $fetch['capacity'] ?></td>
                                    <td><?php echo $fetch['type'] == 1 ? 'PRIMARY' : ($fetch['type'] == 2 ? 'SECONDARY' : 'ALTERNATIVE') ?></td>
                                    <td class="text-right">
                                        <a class="btn btn-sm btn-success edit" data-id="<?php echo $fetch['id'] ?>" data-manager="<?php echo $fetch['camp_manager'] ?>" data-guard="<?php echo $fetch['guard'] ?>" data-address="<?php echo $fetch['address'] ?>" data-contact="<?php echo $fetch['contact'] ?>" data-lat="<?php echo $fetch['lat'] ?>" data-lng="<?php echo $fetch['lng'] ?>" data-capacity="<?php echo $fetch['capacity'] ?>" data-type="<?php echo $fetch['type'] ?>" href="#">
                                            <i class="fa fa-edit"></i> edit
                                        </a>
                                        <a class="btn btn-sm btn-danger delete" data-id="<?php echo $fetch['id'] ?>" data-toggle="modal" data-target="#modal-default">
                                            <i class="fa fa-trash-alt"></i> Delete
                                        </a>
                                        <a class="btn btn-sm btn-info view" data-id="<?php echo $fetch['id'] ?>" data-name="<?= $fetch['center'] ?>" href="#">
                                            <i class="fa fa-eye"></i> view
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


<div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="../asset/img/sent.png" alt="" width="50" height="46">
                <h3>Are you sure you want to delete this Evacuation Center?</h3>
                <div class="m-t-20">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                <h4 class="modal-title"><span class="fa fa-globe-asia">Edit Info</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 editform">

                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" id="save_edit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-view" class="modal animated rubberBand" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="fa fa-globe-asia" id="view_center_name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 modal-content-view">

                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php' ?>

<script type="text/javascript">
    // $(function() {

    // });
    $(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })


    })

    $('.view').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        let name = $(this).data('name');

        $('#view_center_name').html(`${name} EVACUEES`)
        $.ajax({
            type: 'get',
            url: 'view_evacuees.php',
            data: {
                id: id
            },
            success: function(data) {
                $('#modal-view').modal();
                $('.modal-content-view').html(data)
            }

        });
    });

    $('#save_edit').on('click', function(e) {

        let form = $('#save_edit').closest('.modal-footer').prev('.modal-body').find('.form')[0];


        console.log(form)
        var formData = new FormData(form);
        console.log(formData);


        e.preventDefault();

        $.ajax({
            type: 'post',
            url: 'save_evacuation.php',
            data: formData,
            contentType: false,
            dataType: "json",
            processData: false,
            success: function(data) {
                window.location = 'evacuation-center.php'
            }

        });
    });

    $('.delete-btn').on('click', function() {
        let id = $(this).data('id');

        $.ajax({
            type: 'post',
            url: 'delete_evacuation.php',
            data: { id: id },
            success: function(data) {
                $('.id_' + id).hide('slow');
                $('#modal-default').modal('hide');
            }
        });
    });

    $('.delete').on('click', function(e) {
        e.preventDefault();

        let id = $(this).data('id');

        // Add the "show" class to display the modal
        $('#modal-default').modal('show');

        console.log(id);
    });

    $('.edit').on('click', function(e) {
        e.preventDefault();
        console.log('run')
        let id = $(this).data('id');

        $.ajax({
            type: 'post',
            url: 'edit_evacuation.php',
            data: {
                id: id
            },
            success: function(data) {
                console.log(data)
                $('.editform').html(data)
            }

        });
        //

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