<?php $icon = 'fa-address-book'; ?>
<?php $headTitle = 'Manage Users' ?>
<?php $mainTitle = "Manage Users" ?>
<?php $breadcrumbs = " Manage Users" ?>
<?php $menuParentSection = "user" ?>
<?php $menuSection = "userlist" ?>

<?php

require_once 'connection.php';


$isSave = false;
if (isset($_POST['save'])) {

   $barangay = $_POST['barangay'];
   $conn->query("INSERT INTO `barangay` (id, name, status) VALUES(null,'$barangay', 1)");

   $isSave = true;
}


$q1 = $conn->query("SELECT * FROM users ORDER BY fullname ASC");


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
                              <th>Users info</th>
                              <th>Account Category</th>
                              <th>Account Status</th>
                              <th>Assigned Evacuation Center</th>
                              <th class="">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php while($fetch = $q1->fetch_array()) {
                              $role_id = $fetch['role_id'];
                              $evac = $fetch['evacuation_center_id'];
                              $q2 = $conn->query("SELECT * FROM role WHERE id = $role_id");
                              $myRole = $q2->fetch_assoc();

                              $q4 = $conn->query("SELECT * FROM evacuation_center WHERE id = $evac");
                              $evacuationCenter = $q4->fetch_assoc();
                            ?>
                              <tr class="id_<?php echo $fetch['id'] ?>">
                                 <td><?php echo $fetch['id'] ?></td>
                                 <td>
                                    <p class="info">Name: <b><?php echo $fetch['fullname'] ?></b></p>
                                    <p class="info"><small>Designation: <b><?php echo $fetch['designation'] ?></b></small></p>
                                    <p class="info"><small>Contact: <b><?php echo $fetch['contactinfo'] ?></b></small></p>
                                    <p class="info"><small>Email: <b><?php echo $fetch['email'] ?></b></small></p>
                                 </td>
                                 <td>
                                   <?php echo $myRole['role'] ?>
                                 </td>
                                 <td>
                                  <?php if ($fetch['status'] == 1) { ?>
                                    <span class="badge badge-success">Active</span>
                                  <?php } else { ?>
                                    <span class="badge badge-danger">In Active</span>
                                  <?php } ?>
                                </td>
                                <td><?php echo $evacuationCenter['center'] ?></td>
                                 <td class="text-right">
                                    <a class="btn btn-sm btn-success edit" 
                                       data-id="<?php echo $fetch['id'] ?>"
                                       data-name="<?php echo $fetch['name'] ?>"
                                       href="#">
                                       <i class="fa fa-edit"></i> edit
                                    </a>
                                    <a class="btn btn-sm btn-danger delete" data-id="<?php echo $fetch['id'] ?>" href="#" >
                                       <i class="fa fa-trash-alt"></i> De activate
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
            <h3>De activate this User?</h3>
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
        url: 'save_user.php',
        data: formData,
        contentType: false,
        dataType: "json",
        processData: false,
        success: function (data) {
          window.location = 'manage-user.php'
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
           url: 'delete_user.php',
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
         url: 'edit_user.php',
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
    });
</script>