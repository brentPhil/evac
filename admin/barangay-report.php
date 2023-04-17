<?php $icon = 'fa-clipboard'; ?>
<?php $headTitle = 'Reports by Barangay' ?>
<?php $mainTitle = "Reports by Barangay" ?>
<?php $breadcrumbs = " Reports by Barangay" ?>
<?php $menuParentSection = "evacuee-report" ?>
<?php $menuSection = "report-brgy" ?>

<?php
require_once 'logincheck.php';
require_once 'connection.php';

$calamity_id = '';
$evac_center = 5;
$calamitySql = '';

if (isset($_GET['c']) && $_GET['calamity_id'] != "" && $_GET['center'] != "") {
    $calamity_id = $_GET['calamity_id'];
    $evac_center = $_GET['center'];
    $calamitySql = " and calamity_id = $calamity_id  and calamity_id = $calamity_id";
}

$sqlCalamity = $conn->query("SELECT id, name FROM calamity");


if ($isAdmin) {
    $adminList = $conn->query("SELECT id, center FROM evacuation_center WHERE id = $evac_center");
} else {
    $brgy = $conn->query("SELECT count(bi.barangay_id) as cnt, bi.barangay_id, b.name as brgy 
    FROM evacuees e 
    LEFT JOIN barangay_info bi on e.person_id = bi.id
    LEFT JOIN barangay b on bi.barangay_id = b.id
    WHERE evacuation_center_id = $adminEvac and e.status = 1 $calamitySql
    group by barangay_id
  ");

    $brgyData = $brgy->fetch_all();

    $brgyList = [];
    foreach ($brgyData as $each) {
        $brgyList[$each[2]] = $each[0];
    }

    $b = json_encode(array_keys($brgyList));
    $v = json_encode(array_values($brgyList));

}
$evacCenter = $conn->query("SELECT * FROM evacuation_center WHERE status = 1");
?>

<?php include 'main.php' ?>
<script src="../asset/js/chart.js"></script>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Disaster</label>
                    <select id="select_calamity" class="form-control">
                        <option value="">Select Disaster</option>
                        <?php while($fetch = $sqlCalamity->fetch_assoc()) { ?>
                            <option <?php echo $calamity_id === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Evacuation Center</label>
                    <select name="brgy" id="select_evac" class="form-control selectcntr" data-placeholder="Select a Barangays">
                        <?php while($fetch = $evacCenter->fetch_assoc()) { ?>
                            <option <?php echo $evac_center === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['center'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <?php if ($isAdmin) {  ?>

            <?php $fetch = $adminList->fetch_array();
            $id = $fetch['id'];

            $brgy = $conn->query("SELECT count(bi.barangay_id) as cnt, bi.barangay_id, b.name as brgy 
        FROM evacuees e 
        LEFT JOIN barangay_info bi on e.person_id = bi.id 
        LEFT JOIN barangay b on bi.barangay_id = b.id
          WHERE evacuation_center_id = $id and e.status = 1 $calamitySql
          group by barangay_id
        ");

            $brgyData = $brgy->fetch_all();

            $brgyList = [];
            foreach ($brgyData as $each) {
                $brgyList[$each[2]] = $each[0];
            }

            $b = json_encode(array_keys($brgyList));
            $v = json_encode(array_values($brgyList));

            ?>
        <hr>
            <div class="row">
                <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h3> <?php echo $fetch['center'] ?> </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered mytable">
                                <thead>
                                <tr>
                                    <th>Barangay</th>
                                    <th>Number</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($brgyData as $each) { ?>
                                    <tr>
                                        <td><?php echo $each[2] ?></td>
                                        <td><?php echo $each[0] ?></td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="bargraph_<?php echo $id ?>"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
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

                    var ctx = document.getElementById('bargraph_<?php echo $id ?>').getContext('2d');
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
            </script>
        <?php } else { ?>

            <div class="row">
                <div class="col-12 col-md-4 col-lg-4 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered mytable">
                                <thead>
                                <tr>
                                    <th>Barangay</th>
                                    <th>Number</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($brgyData as $each) { ?>
                                    <tr>
                                        <td><?php echo $each[2] ?></td>
                                        <td><?php echo $each[0] ?></td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="bargraph"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>


    <?php include 'footer.php' ?>

    <script src="../asset/js/chart.js"></script>

    <?php if (!$isAdmin) { ?>

        <script>
            document.addEventListener("DOMContentLoaded", function () {

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
        </script>

    <?php } ?>

    <script type="text/javascript">
        $('#select_calamity').on('change', function(e) {
            let calamity_id = $(this).val();
            let center_id = getParameterByName('center'); // get the value of the center ID from the URL
            window.location = `barangay-report.php?c=true&calamity_id=` +calamity_id + (center_id !== null ? `&center=` +center_id : '&center=5');
        });

        $('#select_evac').on('change', function(e) {
            let center_id = $(this).val();
            let calamity_id = getParameterByName('calamity_id'); // get the value of the calamity ID from the URL
            window.location = `barangay-report.php?c=true&center=` +center_id + (calamity_id !== null ? `&calamity_id=` +calamity_id : '&calamity_id=1');
        });

        // helper function to get a query parameter from the URL
        function getParameterByName(name) {
            let url = window.location.href;
            name = name.replace(/[\[\]]/g, '\\$&');
            let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
            let results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }
    </script>
