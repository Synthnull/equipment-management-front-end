<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Advanced Software Engineering</title>
<link href="assets/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/css/owl.carousel.css">
<link rel="stylesheet" href="assets/css/owl.theme.default.min.css">

<!-- MAIN CSS -->
<link rel="stylesheet" href="assets/css/templatemo-style.css">
</head>
<body>
<body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
     <!-- MENU -->
     <section class="navbar custom-navbar navbar-fixed-top" role="navigation">
          <div class="container">
               <div class="navbar-header">
                    <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                    </button>

                    <!-- lOGO TEXT HERE -->
                    <a href="#" class="navbar-brand">View Equipment</a>
               </div>
               <!-- MENU LINKS -->
               <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-nav-first">
                         <li><a href="index.php" class="smoothScroll">Home</a></li>
                         <li><a href="search.php" class="smoothScroll">Search Equipment</a></li>
                         <li><a href="add.php" class="smoothScroll">Add Equipment</a></li>
                         <li><a href="add-device-type.php" class="smoothScroll">Add Device Type</a></li>
                         <li><a href="add-manufacturer.php" class="smoothScroll">Add Manufacturer</a></li>
                         <li><a href="modify-device-type.php" class="smoothScroll">Modify Device Type</a></li>
                         <li><a href="modify-manufacturer.php" class="smoothScroll">Modify Manufacturer</a></li>
                    </ul>
               </div>
          </div>
     </section>
 <!-- HOME -->
     <section id="home">
          </div>
     </section>
     <!-- FEATURE -->
      <section id="feature">
         <div class="container">
               <div class="row">
                  <?php
                  

                  if(!isset($_GET['edit_mode']) || $_GET['edit_mode'] == 'false')
                  {
                     include_once("functions.php");
                     include_once("api_base_url.php");
                     global $API_BASE_URL;
                     
                     $res = callApi($API_BASE_URL . "/get_equipment_by_id/" . $_GET['item_id'], [], 'GET');
                     $data = $res['data'][0];
                        echo '<h4>Device Type:</h4>
                              <p>' . $data['device_type_name'] . '</p>
                              <h4>Manufacturer:</h4>
                              <p>' . $data['manufacturer_name'] . '</p>
                              <h4>Status:</h4>
                              <p>' . $data['status_name'] . '</p>
                              <h4>Status:</h4>
                              <p>' . $data['serial_number_prefix'] . '-' . $data['serial_number_body'] . '</p>';
                        echo '<div class = col>
                           <form method="post" action="">
                              <button type="submit" class="btn btn-warning" name="back" value="Search">Back</button>
                              <button type="submit" class="btn btn-primary" name="modify" value="Search">Modify</button>
                           </form>
                        </div>';

                  }else if (isset($_GET['edit_mode']) && $_GET['edit_mode'] == 'true') {
                     include_once("functions.php");
                     include_once("api_base_url.php");
                     global $API_BASE_URL;
                     
                     $res = callApi($API_BASE_URL . "/get_equipment_by_id/" . $_GET['item_id'], [], 'GET');
                     $itemData = $res['data'][0];

                     $deviceTypes=array();
                     $manufacturers=array();
                     $statuses=array();
                     $deviceTypes[0]='any';
                     $manufacturers[0]='any';
                     $statuses[0]='any';
                     
                     $res = callApi($API_BASE_URL . "/get_device_types", [], 'GET');
                        foreach($res['data'] as $data) {
                           $deviceTypes[$data['device_type_id']]=$data['device_type_name'];
                        }

                     $res = callApi($API_BASE_URL . "/get_manufacturers", [], 'GET');
                     foreach ($res['data'] as $data) {
                        $manufacturers[$data['manufacturer_id']]=$data['manufacturer_name'];
                     }

                     $res = callApi($API_BASE_URL . "/get_statuses", [], 'GET');
                     foreach ($res['data'] as $data) {
                        $statuses[$data['status_id']]=$data['status_name'];
                     }

                  
                  ?>
                  <form method="post" action="">
                    <div class="form-group">
                        <label for="exampleDevice">Device:</label>
                        <select class="form-control" name="deviceType">
                            <?php
                              foreach($deviceTypes as $key=>$value) {
                                 $selected="";
                                 if($itemData['device_type_name'] == $key) {
                                    $selected="selected";
                                 } 
                                 echo '<option ' . $selected . ' value="'.$key.'">'.$value.'</option>';
                              }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleManufacturer">Manufacturer:</label>
                        <select class="form-control" name="manufacturer">
                            <?php
                              foreach($manufacturers as $key=>$value) {
                                 $selected="";
                                 if($itemData['manufacturer_name'] == $key) {
                                     $selected="selected";
                                 } 
                                 echo '<option ' . $selected . ' value="'.$key.'">'.$value.'</option>';
                              }
                           ?>
                       </select>
                   </div>
                   <div class="form-group">
                        <label for="exampleStatus">Status:</label>
                        <select class="form-control" name="status">
                        <?php
                        foreach($statuses as $key=>$value) {
                           $selected="";
                           if($itemData['status_id'] == $key) {
                              $selected="selected";
                           } 
                           echo '<option ' . $selected . ' value="'.$key.'">'.$value.'</option>';
                        }
                        ?>
                       </select>
                   </div>
                   <div class="form-group">
                        <label for="exampleSerial">Serial Number:</label>
                        <?php echo '<input type="text" class="form-control" value="' . $data['serial_number_prefix'] . '-' . $data['serial_number_body'] . '" id="serialInput" name="serialnumber">'; ?>
                   </div>
                        <button type="submit" class="btn btn-success" name="save" value="Search">Save</button>
                        <button type="submit" class="btn btn-primary" name="view" value="Search">View</button>
               </form>
               <?php  }?>
            </div>
          </div>
      </section>
</body>
</html>
<?php
    if (isset($_POST['modify']))
    {
        redirect("view.php?item_id=" . $_GET['item_id'] . "&edit_mode=true");
    }
    if (isset($_POST['view']))
    {
        redirect("view.php?item_id=" . $_GET['item_id'] . "&edit_mode=false");
    }
    if(isset($_POST['back'])) 
    {
      redirect("search.php");
    }
    if(isset($_POST['save']))
    {
        include_once("functions.php");
        include_once("api_base_url.php");
        global $API_BASE_URL;

        $device=$_POST['deviceType'];
        $manufacturer=$_POST['manufacturer'];
        $serialNumber=trim($_POST['serialnumber']);
        $status = $_POST['status'];

        validateSerialNumber($prefix, $body, $serialNumber);
        $overwrite = [
            "device_type_id" => $device,
            "manufacturer_id" => $manufacturer,
            "serial_number" => $serialNumber,
            "status_id" => $status
        ];

        $res = callApi($API_BASE_URL . "/modify_equipment_by_id", $overwrite, 'PUT');

        if ($res['status'] === "Success")
        {
            redirect("view.php?item_id=" . $_GET['item_id'] . "&edit_mode=true&msg=success");
        }
        else
            redirect("view.php?item_id=" . $_GET['item_id'] . "&edit_mode=true&msg=duplicate");
    }
?>
