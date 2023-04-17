<?php $icon = 'fa-shuttle-van'; ?>
<?php $headTitle = 'Multipurpose Vehicle Information' ?>
<?php $mainTitle = "Multipurpose Vehicle Information" ?>
<?php $breadcrumbs = "Multipurpose Vehicle Information" ?>
<?php $menuSection = "Multipurpose Vehicle Information" ?>
<?php $menuParentSection = "mp" ?>
<?php $menuSection = "managempv" ?>

<?php
require_once 'connection.php';
$q1 = $conn->query("SELECT id, concat(name, ' - ', platenumber) as mpv FROM mpv");
$q3 = $conn->query("SELECT id, concat(name, ' - ', platenumber) as mpv FROM mpv");

if (isset($_POST['save'])) {

    $date = $_POST['date'];
    $time = $_POST['time'];
    $mpv_id = $_POST['mpv_id'];
    $evo_name = $_POST['evo_name'];
    $appointment = $_POST['appointment'];
    $route = $_POST['route'];
    $type = $_POST['type'];

    $sql = "INSERT INTO mpv_trans (id, date, time, mpv_id, evo_name, appointment, route, type) VALUE 
      ( null, '$date', '$time', '$mpv_id', '$evo_name', '$appointment', '$route', $type )";

    $conn->query($sql);

    $isSave = true;
}

$q2 = $conn->query("
      SELECT mt.*, m.name, m.platenumber
      FROM mpv_trans mt
      JOIN mpv m on mt.mpv_id = m.id
   ");
?>

<?php include 'main.php' ?>


<section class="content">

    <div class="container-fluid row">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                    <h5 class="card-title"><i class="fa fa-shuttle-van"></i> New Multipurpose Vehicle</h5>
                </div>
                <form method="POST" action="">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" required="" name="date" class="form-control" placeholder="Date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Time</label>
                                    <input type="time" required="" name="time" class="form-control" placeholder="Time">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>MPV Name / Platenumber</label>
                            <select name="mpv_id" class="form-control">
                                <?php while($fetch = $q1->fetch_array()) {  ?>
                                    <option value="<?php echo $fetch['id'] ?>"> <?php echo $fetch['mpv'] ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Emergency Vehicle Operator</label>
                            <input type="name" required="" name="evo_name" class="form-control" placeholder="EVO Name">
                        </div>
                        <div class="form-group">
                            <label>Appointment</label>
                            <input type="name" required="" name="appointment" class="form-control" placeholder="Appointment">
                        </div>
                        <div class="form-group">
                            <label>Route</label>
                            <input type="name" required="" name="route" class="form-control" placeholder="Route">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="type" required="">
                                <option value="1">In Use</option>
                                <option value="2">Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button class="btn btn-secondary">Cancel</button>
                        <button type="submit" name="save" class="btn btn-primary ml-2">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                    <h5 class="card-title"><i class="fa fa-shuttle-van"></i> New Multipurpose Vehicle</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="barangayList" class="table table-striped table-bordered table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>MPV Name</th>
                                <th>Platenumber</th>
                                <th>EVO Name</th>
                                <th>Appointment</th>
                                <th>Route</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while($f = $q2->fetch_assoc()) {
                                $date = date('m/d/Y', strtotime($f['date']));
                                ?>
                                <tr class="id_<?php echo $f['id'] ?>">
                                    <td><?php echo $date ?></td>
                                    <td><?php echo $f['time'] ?></td>
                                    <td><?php echo $f['name'] ?></td>
                                    <td><?php echo $f['platenumber'] ?></td>
                                    <td><?php echo $f['evo_name'] ?></td>
                                    <td><?php echo $f['appointment'] ?></td>
                                    <td><?php echo $f['route'] ?></td>
                                    <td><?php echo $f['type'] == 1 ? 'In Used' : 'Done' ?></td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-success edit"
                                           data-id="<?php echo $f['id'] ?>"
                                           data-date="<?php echo $f['date'] ?>"
                                           data-time="<?php echo $f['time'] ?>"
                                           data-mpv_id="<?php echo $f['mpv_id'] ?>"
                                           data-mpv_id="<?php echo $f['mpv_id'] ?>"
                                           data-evo_name="<?php echo $f['evo_name'] ?>"
                                           data-appointment="<?php echo $f['appointment'] ?>"
                                           data-route="<?php echo $f['route'] ?>"
                                           data-type="<?php echo $f['type'] ?>"
                                           href="#">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <!-- <a class="btn btn-sm btn-danger delete" data-id="<?php echo $f['id'] ?>" href="#">
                        <i class="fa fa-trash-alt"></i> Delete
                    </a> -->
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


<div id="modal-edit" class="modal animated rubberBand" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="fa fa-globe-asia">Edit MPV</span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="col-md-12">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="eid" name="">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" required="" id="edate" name="date" class="form-control" placeholder="Date">
                                </div>
                                <div class="form-group">
                                    <label>Time</label>
                                    <input type="time" required="" id="etime" name="time" class="form-control" placeholder="Time">
                                </div>
                                <div class="form-group">
                                    <label>MPV Name / Platenumber</label>
                                    <select id="empv_id" name="mpv_id" class="form-control">
                                        <?php while($fetch = $q3->fetch_array()) {  ?>
                                            <option value="<?php echo $fetch['id'] ?>"> <?php echo $fetch['mpv'] ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Emergency Vehicle Operator</label>
                                    <input type="name" required="" id="eevo_name" name="evo_name" class="form-control" placeholder="EVO Name">
                                </div>
                                <div class="form-group">
                                    <label>Appointment</label>
                                    <input type="name" required="" id="eappointment" name="appointment" class="form-control" placeholder="Apointment">
                                </div>
                                <div class="form-group">
                                    <label>Route</label>
                                    <input type="name" required="" id="eroute" name="route" class="form-control" placeholder="Route">
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" id="etype" name="type" required="">
                                        <option value="1">In Used</option>
                                        <option value="2">Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" id="save_edit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php' ?>


<script type="text/javascript">

    $('#save_edit').on('click', function(e) {

        let form = $('#save_edit').closest('.modal-footer').prev('.modal-body').find('.form')[0];

        var formData = new FormData(form);
        e.preventDefault();

        $.ajax({
            type: 'post',
            url: 'save_mpv_trans.php',
            data: {
                id: $('#eid').val(),
                date: $('#edate').val(),
                time: $('#etime').val(),
                mpv_id: $('#empv_id').val(),
                evo_name: $('#eevo_name').val(),
                appointment: $('#eappointment').val(),
                route: $('#eroute').val(),
                type: $('#etype').val()
            },
            success: function (data) {
                window.location = 'managempv.php'
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
                url: 'delete_mpv_trans.php',
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
        let date = $(this).data('date');
        let time = $(this).data('time');
        let mpv_id = $(this).data('mpv_id');
        let evo_name = $(this).data('evo_name');
        let appointment = $(this).data('appointment');
        let route = $(this).data('route');
        let type = $(this).data('type');

        var datex = new Date(date).toISOString().split('T')[0];

        $('#eid').val(id);
        $('#edate').val(datex);
        $('#etime').val(time);
        $('#empv_id option[value="' + mpv_id +'"]').prop("selected", true);

        $('#eevo_name').val(evo_name);
        $('#eappointment').val(appointment);
        $('#eroute').val(route);
        $('#etype option[value="' + type +'"]').prop("selected", true);

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
    }).buttons().container().appendTo('#barangayList_wrapper .col-md-6:eq(0)');

</script>

