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
                    <a href="#" class="navbar-brand">Modify Manufacturer</a>
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
                        include_once("functions.php");
                        include_once("api_base_url.php");
                        global $API_BASE_URL;

                        $manufacturers=array();
                        $statuses=array();

                        $res = callApi($API_BASE_URL . "/get_manufacturers", [], 'GET');
                        foreach ($res['data'] as $data) {
                           $manufacturers[$data['manufacturer_id']]=$data['manufacturer_name'];
                        }

                        $res = callApi($API_BASE_URL . "/get_statuses", [], 'GET');

                        foreach ($res['data'] as $data) {
                        $statuses[$data['status_id']]=$data['status_name'];
                        }

                        if (isset($_REQUEST['msg']) && $_REQUEST['msg']=="ManufacturerNameInvalid")
                        {
                            echo '<div class="alert alert-danger" role="alert">Manufacturer name is invalid.</div>';
                        }

                        if (isset($_REQUEST['msg']) && $_REQUEST['msg']=="ManufacturerDuplicate")
                        {
                            echo '<div class="alert alert-danger" role="alert">Manufacturer already exists in database!</div>';
                        }
                                            ?>
                  <form method="post" action="">
                    <div class="form-group">
                        <label for="exampleDevice">Manufacturers:</label>
                        <select class="form-control" name="manufacturer">
                            <?php
                                foreach($manufacturers as $key=>$value)
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                            ?>
                        </select>
                    </div>
                        <div class="form-group">
                           <label for="exampleSerial">New Manufacturer Name:</label>
                           <input type="text" class="form-control" id="serialInput" name="new_manufacturer">
                       </div>

                        <div class="form-group">
                           <label for="exampleStatus">Set Status:</label>
                           <select class="form-control" name="status">
                            <?php
                                   foreach($statuses as $key=>$value)
                                       echo '<option value="'.$key.'">'.$value.'</option>';
                               ?>
                           </select>
                        </div>

                           <button type="submit" class="btn btn-success" name="save" value="submit">Save</button>
                    </form>
               </div>
          </div>
      </section>
</body>
</html>
<?php
    if (isset($_POST['save']))
    {
       include_once("functions.php");
       include_once("api_base_url.php");
       global $API_BASE_URL;

       $newManufacturerName=$_POST['new_manufacturer'];
       $manufacturer = $_POST['manufacturer'];
       $oldManufacturerName = $manufacturers[$_POST['manufacturer']];
       $status = $_POST['status'];
       if($newManufacturerName) 
       {
          if(!preg_match('/^[A-Z][a-z\s]+$/', $newManufacturerName)) 
          {
             redirect("modify-manufacturer.php?msg=ManufacturerNameInvalid");
          }
          
          $payload = [
               "manufacturer_name" => $newManufacturerName,
               "status_id" => $status 
          ];
          }
       }else {
         $payload = [
               "manufacturer_name" => $oldManufacturerName,
               "status_id" => $status 
         ];
       }

          $res = callApi($API_BASE_URL . "/modify_manufacturer_by_id/" . $manufacturer, $payload, 'PUT'); 
          if ($res['status'] === "Success")
          {
            redirect("index.php?msg=ManufacturerEdited");
          }
          else {
            redirect("modify-manufacturer.php?msg=ManufacturerDuplicate");
    }
?>
