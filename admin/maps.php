<?php $icon = 'fa-map-marked-alt'; ?>
<?php $headTitle = 'Maps' ?>
<?php $mainTitle = "Maps" ?>
<?php $breadcrumbs = " Maps" ?>
<?php $menuSection = "maps" ?>

<?php
require_once 'logincheck.php';
require_once 'connection.php';

$calamity_id = '';
$calamitySql = '';
$type = $_GET['evacuation_type'] ?? '';
$evacuationSql = $type !== '' ? "AND c.type = '{$type}'" : '';

if (isset($_GET['c']) && $_GET['calamity_id'] != "") {
  $calamity_id = $_GET['calamity_id'];
  $querySql = " and calamity_id = $calamity_id";
}

$sqlCalamity = $conn->query("SELECT id, name FROM calamity");


$center = $conn->query("
  select count(evacuation_center_id) as cnt, c.id, c.center as center, c.lat, c.lng , c.type, c.capacity,c.image_path
  from evacuation_center c 
  LEFT JOIN evacuees e ON c.id = e.evacuation_center_id and e.status = 1 
  WHERE c.status = 1 {$evacuationSql} group by c.id");


$mapBrgy = $conn->query("
  SELECT ec.id as evacId,ec.center, ec.lat, ec.lng, b.name, b.lat as blat, b.lng as blong
  from evacuees e
  LEFT JOIN evacuation_center ec on e.evacuation_center_id = ec.id
  LEFT JOIN barangay_info bi on e.person_id = bi.id 
  LEFT JOIN barangay b on bi.barangay_id = b.id
  WHERE ec.status = 1 group by b.id;
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
    'type' => $each[5],
    'capacity' => $each[6],
    'imagePath' => $each[7],
    'totalEvacuees' => $evacueeData['cnt'],
    'totalFamilies' => $headOfFamilyData['cnt'],
  ];
}

// echo "<pre>";
$lists = json_encode(array_values($centerList));
// var_dump($lists);

// exit;


?>

<?php include 'main.php' ?>

<style type="text/css">
  #map {
    height: 80vh;
  }
</style>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Select Calamity</label>
          <select id="select_calamity" class="form-control">
            <option value="">Select Calamity</option>
            <?php while ($fetch = $sqlCalamity->fetch_assoc()) { ?>
              <option <?php echo $calamity_id === $fetch['id'] ? 'selected' : '' ?> value="<?php echo $fetch['id'] ?>"><?php echo $fetch['name'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label>Select Evacuation</label>
          <select id="select_type" class="form-control">
            <option value="">Select Evacuation Type</option>
            <option <?php echo $type == 1 ? 'selected' : '' ?> value="1">Primary</option>
            <option <?php echo $type == 2 ? 'selected' : '' ?> value="2">Secondary</option>
            <option <?php echo $type == 3 ? 'selected' : '' ?> value="3">Alternative</option>
          </select>
        </div>
      </div>
    </div>

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



<?php include 'footer.php' ?>

<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script type="text/javascript">
  var map = L.map('map').setView([11.06925469364305, 124.9707054913475], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 17,
    attribution: '© OpenStreetMap'
  }).addTo(map);



  var primaryIcon = new L.Icon({
    iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  var secondaryIcon = new L.Icon({
    iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  var alternativeIcon = new L.Icon({
    iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.3.4/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  let iconMapping = {
    "1": primaryIcon,
    "2": secondaryIcon,
    "3": alternativeIcon
  }


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

    var randomColor = Math.floor(Math.random() * 16777215).toString(16);

    // var circle = L.circle([mapx[5], mapx[6]], {
    //         color: '#'+randomColor,
    //         fillColor: '#'+randomColor,
    //         fillOpacity: 0.5,
    //         radius: 100
    //     }).addTo(map);

    //     circle.bindPopup(mapx[4]);


    //  var control  = L.Routing.control({
    //   waypoints: [
    //     L.latLng(mapx[5], mapx[6]),
    //     L.latLng(mapx[2], mapx[3]),

    //   ],
    //   addWaypoints: false,
    //   routeWhileDragging: false,
    //   show: false,
    //   createMarker: function(i, wp, nWps) {

    //     // return L.marker(wp.latLng, {
    //     //     icon: greenIcon // here pass the custom marker icon instance
    //     //   }).bindPopup(mapx[4]);
    //   }
    // }).addTo(map);

    // control._container.style.display = "None";
  }


  for (let x of data) {






    var marker = L.marker([x['lat'], x['long']], {
      icon: iconMapping[x.type]
    }).addTo(map);

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
  //for ()
  // 11.10371, 125.00042
  // 11.10656, 125.00576
  // 11.10896, 125.00348



  //marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();
</script>

<script type="text/javascript">

  let calamity_id = JSON.parse('<?php echo json_encode($calamity_id) ?>') || '';
  let type = JSON.parse('<?php echo json_encode($type) ?>') || '';

  const filter = (params) => {
    let query = 'maps.php?';
    let calamityParams = calamity_id !== '' ? `c=true&calamity_id=${calamity_id}&` : ''
    let typeParams = type !== '' ? `evacuation_type=${type}` : ''
    window.location = query + calamityParams + typeParams;
  }
  $('#select_calamity').on('change', function(e) {
    calamity_id = $(this).val()
    filter();

  })

  $('#select_type').on('change', function(e) {
    type = $(this).val()
    filter();
  })
</script>