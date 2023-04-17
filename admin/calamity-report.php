<?php $icon = 'fa-bell'; ?>
<?php $headTitle = 'Reports by Disaster' ?>
<?php $mainTitle = "Reports by Disaster" ?>
<?php $breadcrumbs = " Reports by Disaster" ?>
<?php $menuParentSection = "evacuee-report" ?>
<?php $menuSection = "report-calamity" ?>

<?php
require_once 'logincheck.php';
require_once 'connection.php';

$calamity_id = 1;
$calamitySql = '';

if (isset($_GET['c']) && $_GET['calamity_id'] != "") {
    $calamity_id = $_GET['calamity_id'];
    $calamitySql = " and calamity_id = $calamity_id";
}

$sqlCalamity = $conn->query("SELECT id, name FROM calamity");

$calamities =  $conn->query("SELECT * FROM calamity WHERE id = $calamity_id");
$calamitiesArray = [];

?>

<?php include 'main.php' ?>

<section class="content">

    <div class="container-fluid">

        <?php $fetch = $calamities->fetch_array();
        $calamity_id = $fetch['id'];
        array_push($calamitiesArray, $fetch['name']);

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
        
        WHERE evacuees.status = 1 and evacuees.is_present = 2 and c.id=$calamity_id
        ORDER BY lastname ASC");

        ?>
        <div class="row">
            <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="m-0"> <?php echo ucfirst($fetch['name'])  ?></h3>
                    </div>
                    <div class="card-body">
                        <table id="<?php echo $fetch['name'] ?>" class="table table-bordered table-hover <?php echo $fetch['name'] ?>">
                            <thead>
                            <tr>

                                <th>Name</th>
                                <th>Address</th>
                                <th>Age</th>
                                <th>Brgy</th>
                                <th>Family Head</th>
                                <th>Date went home</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $evacuee_info =  $conn->query("SELECT * FROM evacuees where calamity_id=$calamity_id");

                            while ($ev = $q1->fetch_array()) {

                                ?>
                                <tr>

                                    <td><?= $ev['fullname'] ?></td>
                                    <td><?= $ev['evacAddress'] ?></td>
                                    <td><?= $ev['evacAge'] ?></td>
                                    <td><?= $ev['evacBrgy'] ?></td>
                                    <td><?= $ev['head'] ?></td>
                                    <td><?= date_format(date_create($ev['date_left']), "Y/m/d h:i a") ?></td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <div class="form-group">
                            <label>Select Disaster</label>
                            <select id="select_calamity" class="form-control">
                                <option value="">Select Disaster</option>
                                <?php while ($fetch = $sqlCalamity->fetch_assoc()) { ?>
                                    <option <?php echo $calamity_id === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered mytable">
                            <thead>
                            <tr>
                                <th>Disaster</th>
                                <th>Number</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $natural = $conn->query("SELECT * 
                                FROM calamity WHERE type = 1")->fetch_all();
                            $manmade = $conn->query("SELECT * 
                                FROM calamity WHERE type = 2")->fetch_all();
                            ?>
                            <tr>
                                <td>Natural</td>
                                <td><?= count($natural) ?></td>
                            </tr>

                            <tr>
                                <td>Man Made</td>
                                <td><?= count($manmade) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php' ?>

<script src="../asset/js/chart.js"></script>
<script>
    $(function() {
        calamities = JSON.parse('<?php echo json_encode($calamitiesArray) ?>')
        console.log(calamities)
        calamities.map((calamity) => {

            $(`#${calamity}`).DataTable({
                destroy: true,
                "paging": true,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "buttons": ["pdf"]
            }).buttons().container().appendTo(`#${calamity}_length`);

        })
    });
</script>
<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {

        // Bar Chart
        console.log(JSON.parse('<?php echo $v ?>'));

        var barChartData = {
            labels: JSON.parse('<?php echo $b ?>'),
            datasets: [{
                label: 'Evacuees',
                backgroundColor: 'rgb(79,129,189)',
                borderColor: 'rgba(0, 158, 251, 1)',
                borderWidth: 1,
                data: JSON.parse('<?php echo $v ?>')
            }]
        };

        var ctx = document.getElementById('bargraph').getContext('2d');
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                responsive: true,
                legend: {
                    display: false,
                }
            }
        });

    });
</script> -->

<script type="text/javascript">
    $('#select_calamity').on('change', function(e) {
        let id = $(this).val()
        window.location = 'calamity-report.php?c=true&calamity_id=' + id
    })
</script>