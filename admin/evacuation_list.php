<?php $icon = 'fa-user-lock'; ?>
<?php $headTitle = 'List of Evacuees' ?>
<?php $mainTitle = "List of Evacuees" ?>
<?php $breadcrumbs = " List of Evacuees" ?>
<?php $menuParentSection = "" ?>
<?php $menuSection = "evac_list" ?>

<?php

require_once 'logincheck.php';
require_once 'connection.php';


$isSave = false;
if (isset($_POST['save'])) {

   $barangay = $_POST['barangay'];
   $conn->query("INSERT INTO `barangay` (id, name, status) VALUES(null,'$barangay', 1)");

   $isSave = true;
}

  $q1 = $conn->query("SELECT evacuees.*, c.name as calamity, 
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
  
  WHERE evacuees.status = 1");


?>

<?php include 'main.php' ?>

<section class="content">

   <div class="container-fluid">

      <?php if ($isSave) { ?>
         <div class="alert alert-success"> <i class="fas fa-check"> </i> Successfully Saved </div>
      <?php } ?>

      <div class="card card-info">
         <!-- form start -->
            <div class="card-body">
               <div class="row">
                  <div class="col-md-12">
                     <table id="barangayList" class="table table-bordered table-hover">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Evacuees Name</th>
                              <th>Age</th>
                              <th>Contact #</th>
                              <th>Gender</th>
                              <th>Barangay</th>
                              <th>Address</th>
                              <th>Evacuation Center</th>
                              <th>Calamity</th>
                              <th>Date Evacuated</th>
                              <!-- <th class="text-right">Action</th> -->
                           </tr>
                        </thead>
                        <tbody>
                           <?php while($fetch = $q1->fetch_array()) {
                              //$brgy_id = $fetch['barangay_id'];
                              $evac = $fetch['evacuation_center_id'];
                              $calamity_id = $fetch['calamity_id'];
                              
                              // $q2 = $conn->query("SELECT * FROM barangay WHERE id = $brgy_id");
                              // $brgy = $q2->fetch_assoc();

                              $q4 = $conn->query("SELECT * FROM evacuation_center WHERE id = $evac");
                              $evacuationCenter = $q4->fetch_assoc();

                              $q5 = $conn->query("SELECT * FROM calamity WHERE id = $calamity_id");
                              $calamityR = $q5->fetch_assoc();
                            ?>
                              <tr class="id_<?php echo $fetch['id'] ?>">
                                 <td><?php echo $fetch['id'] ?></td>
                                 <td>
                                  <?php echo $fetch['fullname'] ?>
                                 </td>
                                 <td><?php echo $fetch['evacAge'] ?></td>
                                 <td><?php echo $fetch['evacContact'] ?></td>
                                 <td><?php echo $fetch['evacGender'] ?></td>
                                 <td>
                                   <?php echo $fetch['evacBrgy'] ?>
                                 </td>
                                 <td>
                                    <?php echo $fetch['evacAddress'] ?>
                                 </td>
                                <td><?php echo $evacuationCenter['center'] ?></td>
                                <td><?php echo $calamityR['name'] ?></td>
                                <td><?php echo date('m/d/Y', strtotime($fetch['date_added'])) ?></td>
                                <!--  <td class="text-right">
                                    <a class="btn btn-sm btn-success edit" 
                                       data-id="<?php echo $fetch['id'] ?>"
                                       href="#">
                                       <i class="fa fa-edit"></i> edit
                                    </a>
                                    <a class="btn btn-sm btn-danger delete" data-id="<?php echo $fetch['id'] ?>" href="#" >
                                       <i class="fa fa-trash-alt"></i> Delete
                                    </a>
                                 </td> -->
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
        success: function (data) {
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
      
      $.ajax({
         type: 'post',
         url: 'edit_evacuees.php',
         data: {
           id: id
         },
         success: function (data) {
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
      // "buttons": [
      //   "excel", 
      //   "print",
      //     {
      //   extend: 'pdfHtml5',
      //   title: 'LIST OF EVACUEES',
      // }  
      // ]
    }).buttons().container().appendTo('#barangayList_wrapper .col-md-6:eq(0)');
</script>