<?php $icon = 'fa-address-book'; ?>
<?php $headTitle = 'Barangay Database' ?>
<?php $mainTitle = "Barangay Database" ?>
<?php $breadcrumbs = "Barangay Database" ?>
<?php $menuParentSection = "barangay" ?>
<?php $menuSection = "brgy_db" ?>

<?php

require_once 'connection.php';

$q1 = $conn->query("SELECT * FROM barangay WHERE status = 1");
$brgys = $q1->fetch_all();

$brgyName = $_GET['name'] ?? '';
$brgyParams = $brgyName !== '' ? "AND b.name='{$_GET['name']}'" : '';

$brgyQuery = $conn->query("
SELECT bi.*, b.name as brgy FROM barangay_info bi
LEFT JOIN barangay b on bi.barangay_id = b.id
WHERE bi.status = 1 {$brgyParams}");

$q2 = $conn->query("SELECT * FROM barangay ");
?>

<?php include 'main.php' ?>
<style>
    label {
        font-size: 16px;
        color: #333;
        font-weight: 600;
    }
</style>
<section class="content">

    <div class="container-fluid">

        <?php
        if (isset($_SESSION['isSave'])) {
            echo '<div class="alert alert-success"> <i class="fas fa-check"></i> Successfully Saved </div>';
            unset($_SESSION['isSave']);
        }
        ?>

        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                        Manage Personnel</div>
                    <div class="card-body">
                        <label class="mr-2">Barangay </label>
                        <select class="form-control mb-3" aria-label="Default select example" id="filter_barangay">
                            <option selected value=''>All</option>
                            <?php while ($fetch = $q2->fetch_array()) : ?>
                                <option <?php echo $brgyName == $fetch['name'] ? 'selected' : '' ?> value="<?= $fetch['name'] ?>"><?= $fetch['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <table id="barangayList" class="table table-bordered table-hover ">

                            <thead>
                            <tr>
                                <th>Date Added</th>
                                <th>Barangay</th>
                                <th>Fullname</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Contact #</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($fetch = $brgyQuery->fetch_array()) { ?>
                                <tr class="id_<?php echo $fetch['id'] ?>">
                                    <td><?php echo date('m/d/Y', strtotime($fetch['date_added'])) ?></td>
                                    <td><?php echo $fetch['brgy'] ?></td>
                                    <td><?php echo $fetch['lastname'] ?>, <?php echo $fetch['firstname'] ?></td>
                                    <td><?php echo $fetch['age'] ?></td>
                                    <td><?php echo $fetch['gender'] == 1 ? 'Male' : 'Female' ?></td>
                                    <td><?php echo $fetch['address'] ?></td>
                                    <td><?php echo $fetch['contact_number'] ?></td>
                                    <td class="text-right">
                                        <a class="btn btn-sm btn-success edit"
                                           data-id="<?php echo $fetch['id'] ?>"
                                           data-lastname="<?php echo $fetch['lastname'] ?>"
                                           data-firstname="<?php echo $fetch['firstname'] ?>"
                                           data-age="<?php echo $fetch['age'] ?>"
                                           data-gender="<?php echo $fetch['gender'] ?>"
                                           data-address="<?php echo $fetch['address'] ?>"
                                           data-contact_number="<?php echo $fetch['contact_number'] ?>"
                                           href="#">
                                            <i class="fa fa-edit"></i> edit
                                        </a>
                                        <a class="btn btn-sm btn-danger delete" data-id="<?php echo $fetch['id'] ?>" href="#">
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

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header text-white pt-3" style="background-color: rgba(62,88,113);">
                        Add Personnel
                    </div>
                    <form method="POST" action="./add_brgy_db.php">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="brgy">Barangay's</label>
                                <select name="brgy" class="form-control" id="brgy" data-placeholder="Select a Barangays">
                                    <?php foreach ($brgys as $brg) { ?>
                                        <option value="<?php echo $brg[0] ?>"><?php echo $brg[1] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Lastname</label>
                                <input type="text" required="" name="lastname" class="form-control" id="lastname" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="firstname">Firstname</label>
                                <input type="text" required="" name="firstname" class="form-control" id="firstname" placeholder="">
                            </div>
                            <div class="row">
                                <div class="col-6">
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" required="" name="age" class="form-control" id="age" placeholder="">
                            </div>
                            </div>
                                <div class="col-6">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" class="form-control" id="gender">
                                    <option value="1">Male</option>
                                    <option value="2">FeMale</option>
                                </select>
                            </div>
                            </div>

                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" required="" name="address" class="form-control" id="address" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Contact #</label>
                                <input type="text" required="" name="contact_number" class="form-control" id="contact_number" placeholder="">
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <a href="./barangay_db.php" class="btn btn-secondary text-sm px-3">Cancel</a>
                            <button type="submit" name="save" class="btn btn-primary text-sm ml-2 px-3">Save</button>
                        </div>
                    </form>
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

<div id="modal-edit" class="modal animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-globe-asia"></i> Edit User Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="form-row">
                        <input type="hidden" id="eid">
                        <div class="form-group col-md-6">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" required placeholder="Last Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control" required placeholder="First Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" class="form-control" required placeholder="Age">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" class="form-control" required placeholder="Address">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="form-control" required placeholder="Contact Number">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="submit" id="save_edit" class="btn btn-primary">Save Changes</button>
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
            url: 'save_barangay_db.php',
            data: {
                id: $('#eid').val(),
                lastname: $('#lastname').val(),
                firstname: $('#firstname').val(),
                age: $('#age').val(),
                gender: $('#gender').val(),
                contact_number: $('#contact_number').val(),
                address: $('#address').val(),
            },
            success: function(data) {
                window.location = 'barangay_db.php'
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
                url: 'delete_barangay_db.php',
                data: {
                    id: id
                },
                success: function(data) {
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
        let lastname = $(this).data('lastname');
        let firstname = $(this).data('firstname');
        let age = $(this).data('age');
        let gender = $(this).data('gender');
        let contact_number = $(this).data('contact_number');
        let address = $(this).data('address');

        console.log(gender);
        $('#eid').val(id)
        $('#lastname').val(lastname)
        $('#firstname').val(firstname)
        $('#age').val(age)
        $('#gender').val(gender)
        $('#contact_number').val(contact_number)
        $('#address').val(address)

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

    $(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })

    $('#filter_barangay').on('change', function(e) {
        let name = $(this).val()

        window.location = `?name=${name}`;
    })
</script>