<?php $icon = 'fa-chart-bar'; ?>
<?php $headTitle = 'List of Evacuees' ?>
<?php $mainTitle = "List of Evacuees" ?>
<?php $breadcrumbs = " List of Evacuees" ?>
<?php $menuParentSection = "evacuee-report" ?>
<?php $menuSection = "evacuess-list-report" ?>

<?php

require_once 'logincheck.php';
require_once 'connection.php';

$calamity_id = '';
$calamitySql = '';

if (isset($_GET['c']) && $_GET['calamity_id'] != "") {
  $calamity_id = $_GET['calamity_id'];
  $calamitySql = " and calamity_id = $calamity_id";
}

  $sqlCalamity = $conn->query("SELECT id, name FROM calamity");


$sql = " ";
if (!$isAdmin) {
 $sql = "AND evacuation_center_id = $evacuation_center_id";
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
   WHERE evacuees.status = 1 ". $sql . $calamitySql ."
   ORDER BY lastname ASC");
?>

<?php include 'main.php' ?>

<section class="content">

   <div class="container-fluid">

      <div class="card card-info">
         <!-- form start -->
            <div class="card-body">
               <div class="row">
                  <div class="col-md-12">
                     
                     <div class="row" style="">
                      <div class="col-md-4">
                        <div class="form-group">
                          <select id="select_calamity" class="form-control">
                            <option value="">Select Calamity</option>
                            <?php while($fetch = $sqlCalamity->fetch_assoc()) { ?>
                              <option <?php echo $calamity_id === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['name'] ?></option>
                            <?php } ?>
                          </select>                                       
                        </div>
                      </div>
                     </div>

                     <table id="barangayList" class="table table-bordered table-hover">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>FullName</th>
                              <th>Contact</th>
                              <th>Barangay</th>
                              <th>Age</th>
                              <th>Gender</th>
                              <th>Address</th>
                              <th>Evacuation C</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php while($fetch = $q1->fetch_array()) {
                              $evac = $fetch['evacuation_center_id'];
                              
                              // $q2 = $conn->query("SELECT * FROM barangay WHERE id = $brgy_id");
                              // $brgy = $q2->fetch_assoc();

                              $q4 = $conn->query("SELECT * FROM evacuation_center WHERE id = $evac");
                              $evacuationCenter = $q4->fetch_assoc();
                            ?>
                              <tr class="id_<?php echo $fetch['id'] ?>">
                                 <td><?php echo $fetch['id'] ?></td>
                                 <td>
                                 	<?php echo $fetch['fullname'] ?>
                                 </td>
                                 <td><?php echo $fetch['evacContact'] ?></td>
                                 <td>
                                   <?php echo $fetch['evacBrgy'] ?>
                                 </td>
                                 <td><?php echo $fetch['evacAge'] ?></td>
                                 <td><?php echo $fetch['evacGender'] == '1' ? 'Male' : 'Female' ?></td>
                                 <td>
                                    <?php echo $fetch['evacAddress'] ?>
                                 </td>
                                 <td> <?php echo $evacuationCenter['center'] ?> </td>
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


<?php include 'footer.php' ?>         

<script type="text/javascript">
   
  

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
			  customize: function(doc) {
			  	var tbl = $('#barangayList');

			  	var colCount = new Array();
	            $(tbl).find('tbody tr:first-child td').each(function(){
	                if($(this).attr('colspan')){
	                    for(var i=1;i<=$(this).attr('colspan');$i++){
	                        colCount.push('*');
	                    }
	                }else{ colCount.push('*'); }
	            });
	            doc.content[1].table.widths = colCount;

			    // doc.styles.title = {
			    //   // color: 'red',
			    //   // fontSize: '40',
			    //   // background: 'blue',
			    //   alignment: 'left'
			    // }   
			  }
		  }  
      ]
    }).buttons().container().appendTo('#barangayList_wrapper .col-md-6:eq(0)');
</script>

<script type="text/javascript">
  $('#select_calamity').on('change', function(e) {
    let id = $(this).val()
    window.location = 'evacuees-report.php?c=true&calamity_id=' +id
  })
</script>
