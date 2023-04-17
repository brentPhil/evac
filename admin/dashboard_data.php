<?php $icon = 'fa fa-laptop'; ?>
<?php $headTitle = 'Dashboard' ?>
<?php $mainTitle = "Dashboard" ?>
<?php $breadcrumbs = "Dashboard" ?>
<?php $menuSection = "dashboard" ?>

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

if ($isAdmin) {
    $adminList = $conn->query("SELECT id, center FROM evacuation_center");

} else {

    $gender = $conn->query("SELECT count(*) as cnt, bi.gender 
    FROM evacuees 
    LEFT JOIN barangay_info bi on evacuees.person_id = bi.id 
    WHERE evacuation_center_id = $adminEvac and evacuees.status = 1 $calamitySql group by bi.gender");

    $evacueesCnt = $conn->query("SELECT count(*) as cnt FROM evacuees WHERE evacuation_center_id = $adminEvac and status = 1 $calamitySql");

    $brgy = $conn->query("SELECT COUNT(DISTINCT bi.barangay_id) as cnt 
    FROM evacuees 
    LEFT JOIN barangay_info bi on evacuees.person_id = bi.id 
    WHERE evacuation_center_id = $adminEvac and evacuees.status = 1 $calamitySql");

    $headOfFamily = $conn->query("SELECT COUNT(evacuees.head_person_id) as cnt 
    FROM evacuees 
    LEFT JOIN barangay_info bi on evacuees.head_person_id = bi.id
    WHERE evacuation_center_id = $adminEvac and evacuees.status = 1 $calamitySql");

    $evacuationcenter = $conn->query("SELECT count(*) as cnt FROM `evacuation_center` WHERE status = 1 ");

    $evacuationcenterData = $evacuationcenter->fetch_assoc();
    $headOfFamilyData = $headOfFamily->fetch_assoc();
    $brgyData = $brgy->fetch_assoc();
    $evacueeData = $evacueesCnt->fetch_assoc();
    $genderData = $gender->fetch_all();

}


?>

<?php include 'main.php' ?>
<style type="text/css">
    #map { height: 80vh; }
</style>


<section class="content">

    <div class="container-fluid">

        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Dashboard 1</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Dashboard 2</a>
            </li>
        </ul>

        <div class="card card-info">
            <!-- form start -->

            <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                        <div class="row">
                            <div class="col-md-12">
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
                        </div>

                        <?php if ($isAdmin) {  ?>
                            <?php while($fetch = $adminList->fetch_array()) {
                                $id = $fetch['id'];

                                $sql = " SELECT COUNT(bi.head_person_id) as cnt 
                            FROM evacuees 
                            LEFT JOIN barangay_info bi on evacuees.head_person_id = bi.id
                            WHERE evacuation_center_id = $id and evacuees.status = 1 $calamitySql";

                                $gender = $conn->query("SELECT count(*) as cnt, bi.gender 
                            FROM evacuees 
                            LEFT JOIN barangay_info bi on evacuees.person_id = bi.id 
                            WHERE evacuation_center_id = $id and evacuees.status = 1 $calamitySql group by bi.gender");

                                $evacueesCnt = $conn->query("SELECT count(*) as cnt FROM evacuees WHERE evacuation_center_id = $id and status = 1 $calamitySql");

                                $brgy = $conn->query("SELECT COUNT(DISTINCT bi.barangay_id) as cnt 
                            FROM evacuees 
                            LEFT JOIN barangay_info bi on evacuees.person_id = bi.id 
                            WHERE evacuation_center_id = $id and evacuees.status = 1 $calamitySql ");

                                $headOfFamily = $conn->query("SELECT COUNT(evacuees.head_person_id) as cnt 
                            FROM evacuees 
                            LEFT JOIN barangay_info bi on evacuees.head_person_id = bi.id
                            WHERE evacuation_center_id = $id and evacuees.status = 1 $calamitySql ");


                                $evacuationcenter = $conn->query("SELECT count(*) as cnt FROM `evacuation_center` WHERE status = 1  ");
                                $noOfBrgy = $conn->query("SELECT COUNT(*) as cnt
                            from evacuation_barangay
                            where evac_id = $id");

                                $evacuationcenterData = $evacuationcenter->fetch_assoc();
                                $headOfFamilyData = $headOfFamily->fetch_assoc();
                                $brgyData = $brgy->fetch_assoc();
                                $evacueeData = $evacueesCnt->fetch_assoc();
                                $noOfBrgyData = $noOfBrgy->fetch_assoc();
                                $genderData = $gender->fetch_all();
                                ?>

                                <h1 class="m-4"> <?php echo $fetch['center'] ?> </h1>

                                <div class="row">
                                    <div class="col-lg-3 col-6">
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3><?php echo $headOfFamilyData['cnt'] ?></h3>

                                                <p>Number of Family</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3><?php echo $evacueeData['cnt'] ?></h3>

                                                <p>Number of Evacuees</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h3><?php echo $genderData[1][0] ?? '0' ?></h3>

                                                <p>Number of Female</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-venus"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-6">
                                        <div class="small-box bg-secondary">
                                            <div class="inner">
                                                <h3><?php echo $genderData[0][0] ?? '0' ?></h3>

                                                <p>Number of Male</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-mars"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-dark">
                                            <div class="inner">
                                                <h3><?php echo $noOfBrgyData['cnt'] ?></h3>

                                                <p>Number of Barangay</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-university"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                  <div class="inner">
                                    <h3><?php // echo $evacuationcenterData['cnt'] ?></h3>

                                    <p>Number of Evacuation Center</p>
                                  </div>
                                  <div class="icon">
                                    <i class="fas fa-hotel"></i>
                                  </div>
                                </div>
                              </div> -->

                                </div>



                            <?php } ?>

                        <?php } else { ?>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Number of Family</span>
                                            <span class="info-box-number">
                                    <?php echo $headOfFamilyData['cnt'] ?>
                                  </span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Number of Evacuees</span>
                                            <span class="info-box-number">
                                    <?php echo $evacueeData['cnt'] ?>
                                  </span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <!-- /.col -->
                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-venus"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Number of Female</span>
                                            <span class="info-box-number"><?php echo $genderData[1][0] ?? '0' ?></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <!-- /.col -->

                                <!-- fix for small devices only -->
                                <div class="clearfix hidden-md-up"></div>

                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-mars"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Number of Male</span>
                                            <span class="info-box-number"><?php echo $genderData[0][0] ?? '0' ?></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <!-- fix for small devices only -->
                                <div class="clearfix hidden-md-up"></div>

                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Number of Barangay</span>
                                            <span class="info-box-number"><?php echo $brgyData['cnt'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix hidden-md-up"></div>

                                <div class="col-12 col-sm-6 col-md-6">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-indigo elevation-1"><i class="fas fa-hotel"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Number of Evacuation Center</span>
                                            <span class="info-box-number"><?php echo $evacuationcenterData['cnt'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="tab-pane fade active show" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                        <h3>TANAUAN EVACUATION CENTER</h3>
                        <div class="row">

                            <?php
                            $brgy = $conn->query("SELECT COUNT(id) as cnt FROM barangay where status = 1");
                            $evac = $conn->query("SELECT COUNT(id) as cnt FROM evacuation_center where status = 1");
                            $evacuees = $conn->query("SELECT COUNT(id) as cnt FROM evacuees where status = 1");
                            $calamity = $conn->query("SELECT COUNT(id) as cnt FROM calamity");
                            $brgyCNT = $brgy->fetch_assoc();
                            $evacCNT = $evac->fetch_assoc();
                            $evacueesCNT = $evacuees->fetch_assoc();
                            $calamityCNT = $calamity->fetch_assoc();
                            ?>
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?php echo $brgyCNT['cnt'] ?></h3>
                                        <p>BARANGAY</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-bars"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3><?php echo $evacCNT['cnt'] ?></h3>
                                        <p>EVACUATIONS</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3><?php echo $evacueesCNT['cnt'] ?></h3>
                                        <p>EVACUEES</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3><?php echo $calamityCNT['cnt'] ?></h3>
                                        <p>DISASTER</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <?php
                        $center = $conn->query("
                        select count(evacuation_center_id) as cnt, c.id, c.center as center, c.lat, c.lng, c.capacity, c.type 
                        from evacuation_center c 
                        LEFT JOIN evacuees e ON c.id = e.evacuation_center_id and e.status = 1 
                        WHERE c.status = 1 group by c.id");


                        $mapBrgy = $conn->query("
                        SELECT ec.id as evacId,ec.center, ec.lat, ec.lng, b.name, b.lat as blat, b.lng as blong
                        from evacuees e
                        LEFT JOIN evacuation_center ec on e.evacuation_center_id = ec.id
                        LEFT JOIN barangay_info bi on e.person_id = bi.id 
                        LEFT JOIN barangay b on bi.barangay_id = b.id
                        WHERE ec.status = 1
                        group by b.id;
                      ");

                        $mapBrgyData = $mapBrgy->fetch_all();

                        $mapBrgyDataJson = json_encode(array_values($mapBrgyData));
                        $centerData = $center->fetch_all();

                        $centerList = [];
                        foreach ($centerData as $each) {
                            $evacId = $each[1];
                            $evacueesCnt = $conn->query("SELECT count(*) as cnt FROM evacuees WHERE evacuation_center_id = $evacId and status = 1 $calamitySql");
                            $evacueeData = $evacueesCnt->fetch_assoc();

                            $headOfFamily = $conn->query("SELECT COUNT(DISTINCT head_person_id) as cnt
                        FROM evacuees 
                        LEFT JOIN barangay_info bi on evacuees.head_person_id = bi.id
                        WHERE evacuation_center_id = $evacId and evacuees.status = 1 $calamitySql");
                            $headOfFamilyData = $headOfFamily->fetch_assoc();


                            $centerList[$each[2]] = [
                                'count' => $each[0],
                                'center' => $each[2],
                                'lat' => $each[3],
                                'long' => $each[4],
                                'capacity' => $each[5],
                                'type' => $each[6] == 1 ? 'PRIMARY' : ($each[6] == 2 ? 'SECONDARY' : 'ALTERNATIVE'),
                                'totalEvacuees' => $evacueeData['cnt'],
                                'totalFamilies' => $headOfFamilyData['cnt'],
                            ] ;
                        }
                        $lists = json_encode(array_values($centerList));
                        ?>

                        <div class="card card-primary card-outline" style="position: relative; width: 100%">
                            <div id="map"></div>
                            <div class="card card-primary card-outline p-2" style="position: absolute; z-index: 9999;width: 250px;  top: 5%;right: 5%;transform: translateX(10%);">
                                <h6 class="m-2">Legends</h6>
                                <table>
                                    <tr>
                                        <td class="text-center"><img src="https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png" style="width:25px" /></td>
                                        <td class="text-left">
                                            <h6>Primary</h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"> <img src="https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png" style="width:25px" /></td>
                                        <td class="text-left">
                                            <h6>Secondary</h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"> <img src="https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png" style="width:25px" /></td>
                                        <td class="text-left">
                                            <h6>Alternative</h6>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include 'footer.php' ?>

<script type="text/javascript">


    $('#select_calamity').on('change', function(e) {
        let id = $(this).val()
        window.location = 'dashboard_data.php?c=true&calamity_id=' +id
    })
</script>

<script type="text/javascript">
    var map = L.map('map').setView([11.06925469364305, 124.9707054913475], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 17,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var greenIcon = new L.Icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });



    var polygon = L.polygon([
        [11.138704630512903, 125.02173007176077],
        [11.093562515061173, 124.94448245717831],
        [11.075912558595848, 124.95208661807597],
        [11.06188781234035, 124.9619023791179],
        [11.046445648315592, 124.9682537549282],
        [11.02873574501786, 124.97301728602206],
        [11.038653422418784, 124.99134966326213],
        [11.043045429720753, 125.0089602933668],
        [11.059762791915484, 125.01776561064464],
        [11.075487575853996, 125.03234490278045],
        [11.088991107614628, 125.0263174244731],
        [11.096655773016234, 125.0213392448667],
        [11.096824224942981, 125.02176839828104],
        [11.10617315453883, 125.02245504374399],
        [11.114090032469987, 125.02202589041376],
        [11.126133380597027, 125.02520162567993],
        [11.138007803053979, 125.02185422902556],
    ]).addTo(map);


    let data = JSON.parse('<?php echo $lists ?>');

    let mapBrgyDataJson = JSON.parse('<?php echo $mapBrgyDataJson ?>');
    for (let mapx of mapBrgyDataJson) {

        var randomColor = Math.floor(Math.random()*16777215).toString(16);


    }

    var greenIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var redIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    console.log(data);
    for (let x of data) {

        var icon = x['type'] == 'MAIN' ? greenIcon : redIcon;

        var marker = L.marker([x['lat'], x['long']], {icon: icon}).addTo(map);

        marker.bindPopup(
            `
     <h5>${x['center']}</h5>
        <table>
          <tr>
            <td>Evacuees:</td> <td> <span class="badge badge-success"> ${x['totalEvacuees']} </span> </td>
          </tr>
          <tr>
            <td>Total Families:</td> <td> <span class="badge badge-info"> ${x['totalFamilies']} </span> </td>
          </tr>
          <tr>
            <td>Capacity:</td> <td> <span class="badge badge-info"> ${x['capacity']} </span> </td>
          </tr>
        </table>
        <img src="../${x.imagePath}" style="width:100%; height:150px"/>
        `
        );
    }

    //marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();



</script>

