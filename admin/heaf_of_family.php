<?php $icon = 'fa-users'; ?>
<?php $headTitle = 'Family Head' ?>
<?php $mainTitle = "Family Head" ?>
<?php $breadcrumbs = " Family Head" ?>
<?php $menuParentSection = "evacuee" ?>
<?php $menuSection = "family_head" ?>

<?php

require_once 'logincheck.php';
require_once 'connection.php';

$calamity_id = '';
$center_id = '';
$calamitySql = '';

if (isset($_GET['c']) && $_GET['calamity_id'] != "") {
  $calamity_id = $_GET['calamity_id'];
  $calamitySql = " and calamity_id = $calamity_id";
}

if (isset($_GET['evacuation_id']) && $_GET['evacuation_id'] != "") {
  $center_id = $_GET['evacuation_id'];
  $calamitySql = " and evacuation_center_id = $center_id";
}

$sqlCalamity = $conn->query("SELECT id, name FROM calamity");
$sqlCenter = $conn->query("SELECT id,center FROM evacuation_center");


$sql = "";
if (!$isAdmin) {
  $sql = "and evacuation_center_id = $evacuation_center_id ";
}


$q1 = $conn->query("
  SELECT evacuees.id, head_person_id, COUNT(head_person_id) as cnt, calamity_id ,evacuation_center_id, CONCAT(bi.lastname, ', ', bi.firstname) as head_of_family
  FROM evacuees 
  LEFT JOIN barangay_info bi on evacuees.head_person_id = bi.id
  WHERE evacuees.status = 1 $sql $calamitySql GROUP BY head_person_id 
  ORDER BY head_of_family ASC");


?>

<?php include 'main.php' ?>

<section class="content">

  <div class="container-fluid">
    <div class="card card-info">
      <!-- form start -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <select id="select_calamity" class="form-control">
                    <option value="">Select Calamity</option>
                    <?php while ($fetch = $sqlCalamity->fetch_assoc()) { ?>
                      <option <?php echo $calamity_id === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['name'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <select id="select_center" class="form-control">
                    <option value="">Select Evacuation Center</option>
                    <?php while ($fetch = $sqlCenter->fetch_assoc()) { ?>
                      <option <?php echo $center_id === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['center'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <table id="barangayList" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Head of Family</th>
                  <th>Total Members</th>
                  <th>Calamity</th>
                  <th>Evacuation Center</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($fetch = $q1->fetch_array()) {
                  $calamity_id = $fetch['calamity_id'];
                  $center_id = $fetch['evacuation_center_id'];

                  $q5 = $conn->query("SELECT * FROM calamity WHERE id = $calamity_id");
                  $center = $conn->query("SELECT * FROM evacuation_center WHERE id = $center_id");
                  $calamityR = $q5->fetch_assoc();
                  $centerR = $center->fetch_assoc();
                ?>
                  <tr class="id_<?php echo $fetch['id'] ?>">
                    <td><?php echo $fetch['head_of_family'] ?></td>
                    <td> <?php echo $fetch['cnt'] ?> </td>
                    <td><?php echo $calamityR['name'] ?></td>
                    <td><?php echo $centerR['center'] ?></td>
                    <td class="text-right">
                      <a class="btn btn-sm btn-success view" data-id="<?php echo $fetch['head_person_id'] ?>" href="#">
                        <i class="fa fa-eye"></i>
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


<div id="modal-default" class="modal animated rubberBand" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span class="fa fa-globe-asia"> View Members</span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="viewMembers"></div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" data-dismiss="modal" class="btn btn-primary">Ok</button>
      </div>
    </div>
  </div>
</div>



<?php include 'footer.php' ?>

<script type="text/javascript">
  $('.view').on('click', function(e) {
    e.preventDefault();

    let id = $(this).data('id');
    $('#modal-default').modal();

    $.ajax({
      type: 'post',
      url: 'view_head_members.php',
      data: {
        family_head: id
      },
      success: function(data) {
        $('.viewMembers').html(data)
      }

    });

    console.log(id)
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

<script type="text/javascript">
  // $('#select_calamity').on('change', function(e) {
  //   let id = $(this).val()
  //   window.location = 'heaf_of_family.php?c=true&calamity_id=' + id
  // })


  let calamity_id = JSON.parse('<?php echo json_encode($calamity_id) ?>') || '';
  let center_id = JSON.parse('<?php echo json_encode($center_id) ?>') || '';

  const filter = (params) => {
    let query = 'heaf_of_family.php?';
    let calamityParams = calamity_id !== '' ? `c=true&calamity_id=${calamity_id}&` : ''
    let centerParams = center_id !== '' ? `evacuation_id=${center_id}` : ''
    window.location = query + calamityParams + centerParams;
  }
  $('#select_calamity').on('change', function(e) {
    calamity_id = $(this).val()
    filter();

  })

  $('#select_center').on('change', function(e) {
    center_id = $(this).val()
    filter();
  })
</script>