<?php $icon = 'fa-book-reader'; ?>
<?php $headTitle = 'Manage Evacuees' ?>
<?php $mainTitle = "Manage Evacuees" ?>
<?php $breadcrumbs = " Manage Evacuees" ?>
<?php $menuParentSection = "evacuee" ?>
<?php $menuSection = "evacuess-list" ?>

<?php

require_once 'logincheck.php';
require_once 'connection.php';

$isSave = false;

$calamity_id = '';
$calamitySql = '';

if (isset($_GET['c']) && $_GET['calamity_id'] != "") {
   $calamity_id = $_GET['calamity_id'];
   $calamitySql = " and calamity_id = $calamity_id";
}


if (isset($_POST['save'])) {

   $barangay = $_POST['barangay'];
   $conn->query("INSERT INTO `barangay` (id, name, status) VALUES(null,'$barangay', 1)");

   $isSave = true;
}

$sql = "";
if (!$isAdmin) {
   $sql = "and evacuation_center_id = $evacuation_center_id ";
}


$q1 = $conn->query("
 SELECT evacuees.*, c.name as calamity, 
 CONCAT(bi.lastname, ', ', bi.firstname) as fullname,
 bi.address as evacAddress,
 bi.age as evacAge,
 bi.gender as evacGender,
 bi.contact_number as evacContact,
 bi.gender as evacGender,
 b.name as evacBrgy,
 CONCAT(bi2.lastname, ', ', bi2.firstname) as head
    FROM evacuees 
   LEFT JOIN calamity c on evacuees.calamity_id = c.id 
   LEFT JOIN barangay_info bi on evacuees.person_id = bi.id 
   LEFT JOIN barangay b on bi.barangay_id = b.id
   LEFT JOIN barangay_info bi2 on evacuees.head_person_id = bi2.id  

  WHERE evacuees.status = 1 $sql $calamitySql and evacuees.is_present = 1
  ORDER BY lastname ASC");

$sqlCalamity = $conn->query("SELECT id, name FROM calamity");
?>

<?php include 'main.php' ?>
<style>
    .del:hover {
        background-color: rgb(255, 180, 186) !important;
    }
    .edt:hover {
        background-color: rgb(176, 213, 255) !important;
    }

</style>
<section class="content">

   <div class="container-fluid">

      <?php if ($isSave) { ?>
         <div class="alert alert-success"> <i class="fas fa-check"> </i> Successfully Saved </div>
      <?php } ?>

      <div class="card">
          <div class="card-body">

              <div class="form-group">
                  <select id="select_calamity" class="form-control">
                      <option value="">Select Calamity</option>
                      <?php while ($fetch = $sqlCalamity->fetch_assoc()) { ?>
                          <option <?php echo $calamity_id === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['name'] ?></option>
                      <?php } ?>
                  </select>
              </div>

              <table id="barangayList" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                      <th scope="col">#</th>
                      <th scope="col">Evacuees info</th>
                      <th scope="col">Barangay</th>
                      <th scope="col">Address</th>
                      <th scope="col">Head of Family</th>
                      <th scope="col">Evacuation Center</th>
                      <th scope="col">Disaster</th>
                      <th scope="col">Status</th>
                      <th scope="col">Days</th>
                      <th scope="col">Remarks</th>
                      <th scope="col">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php while ($fetch = $q1->fetch_array()) {
                      //$brgy_id = $fetch['barangay_id'];
                      $evac = $fetch['evacuation_center_id'];
                      $calamity_id = $fetch['calamity_id'];

                      // $q2 = $conn->query("SELECT * FROM barangay WHERE id = $brgy_id");
                      // $brgy = $q2->fetch_assoc();

                      $q4 = $conn->query("SELECT * FROM evacuation_center WHERE id = $evac");
                      $evacuationCenter = $q4->fetch_assoc();

                      $q5 = $conn->query("SELECT * FROM calamity WHERE id = $calamity_id");
                      $calamityR = $q5->fetch_assoc();

                      $now = time();
                      $from = strtotime($fetch['date_added']);
                      $days = ceil((($now - $from) / 86400) - 1);
                      ?>
                      <tr class="id_<?php echo $fetch['id'] ?>" id="evacuee_<?php echo $fetch['id'] ?>">
                          <td><?php echo $fetch['id'] ?></td>
                          <td>
                              <p class="info"><strong>Name:</strong> <?php echo $fetch['fullname'] ?></p>
                              <p class="info"><strong>Age:</strong> <?php echo $fetch['evacAge'] ?></p>
                              <p class="info"><strong>Gender:</strong> <?php echo $fetch['evacGender'] == '1' ? 'Male' : 'Female' ?></p>
                              <p class="info"><strong>Contact:</strong> <?php echo $fetch['evacContact'] ?></p>
                          </td>
                          <td><?php echo $fetch['evacBrgy'] ?></td>
                          <td><?php echo $fetch['evacAddress'] ?></td>
                          <td><?php echo $fetch['head'] ?></td>
                          <td><?php echo $evacuationCenter['center'] ?></td>
                          <td><?php echo $calamityR['name'] ?></td>
                          <td><?php echo $fetch['is_present'] == 1 ? 'Present' : 'Went Home' ?></td>
                          <td><?= $days === 0 ? 'Today' : ($days === 1 ?  $days . ' day' : $days . ' days') ?></td>
                          <td>
                              <div class="form-check form-switch">
                                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" data-id="<?php echo $fetch['id'] ?>" onchange="removeEvacuee('<?php echo $fetch['id'] ?>')">
                                  <label class="form-check-label" for="flexSwitchCheckDefault">Remove Evacuee</label>
                              </div>
                          </td>
                          <td width="40">
                              <div class="btn-group-vertical d-grid justify-content-center w-100">
                                  <button class="btn del btn-danger border-0 text-danger" data-id="<?php echo $fetch['id'] ?>" onclick="removeEvacuee('<?php echo $fetch['id'] ?>')" style="background-color: rgb(255,219,224); transition: background-color 0.3s ease;">
                                      <i class="fas fa-trash"></i>
                                  </button>
                                  <button class="btn edt btn-primary border-0 text-primary" data-id="<?php echo $fetch['id'] ?>" href="#" onclick="editEvacuee('<?php echo $fetch['id'] ?>')" style="background-color: rgb(215,235,255); transition: background-color 0.3s ease;">
                                      <i class="fas fa-edit"></i>
                                  </button>
                              </div>
                          </td>
                      </tr>
                  <?php } ?>
                  </tbody>
              </table>
          </div>
      </div>
   </div>
</section>


<div id="modal-default" class="modal animated rubberBand delete-modal" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body text-center">
            <img src="../asset/img/sent.png" alt="" width="50" height="46">
            <h3>Are you sure you want to delete this Evacuee?</h3>
            <div class="m-t-20"> <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
               <button type="submit" class="btn btn-danger delete-btn">Ok</button>
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


<?php include 'footer.php' ?>

<script type="text/javascript">
   $('#save_edit').on('click', function(e) {

      let form = $('#save_edit').closest('.modal-footer').prev('.modal-body').find('.form')[0];

      var formData = new FormData(form);

      e.preventDefault();

      $.ajax({
         type: 'post',
         url: 'save_evacuees.php',
         data: formData,
         contentType: false,
         dataType: "json",
         processData: false,
         success: function(data) {
            window.location = 'manage-evacuees.php'
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
            url: 'delete_evacuess.php',
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

   const removeEvacuee = (id) => {
      console.log(id)

      $.ajax({
         type: 'post',
         url: 'remove_evacuee.php',
         data: {
            id: id
         },
         success: function(data) {

            if (data.success) $(`#evacuee_${id}`).remove();

         }

      });


   }

   $('.edit').on('click', function(e) {
      e.preventDefault();

      let id = $(this).data('id');

      $.ajax({
         type: 'post',
         url: 'edit_evacuees.php',
         data: {
            id: id
         },
         success: function(data) {
            console.log(data)
            $('.editform').html(data)
         }

      });

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
      "buttons": [
         "excel",
         "print",
         {
            extend: 'pdfHtml5',
            title: 'LIST OF EVACUEES',
         }
      ]
   }).buttons().container().appendTo('#barangayList_wrapper .col-md-6:eq(0)');
</script>

<script type="text/javascript">
   $('#select_calamity').on('change', function(e) {
      let id = $(this).val()
      window.location = 'manage-evacuees.php?c=true&calamity_id=' + id
   })
</script>