<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid']==0)) {
  header('location:logout.php');
  } else{

    if(isset($_POST['create_event']))
    { 
        
        $targetDir = "uploads/"; 
        $allowTypes = array('jpg','png','jpeg','gif'); 
         
        $statusMsg = $errorMsg = $galary_images = $errorUpload = $errorUploadType = ''; 
        $main_image = array_filter($_FILES['main_image']['name']); 
        $event_name=$_POST['event_name'];
        $fileNames = array_filter($_FILES['galary']['name']);  

        // upload single image
        $cover_image = time().'_cover_'.basename($_FILES['main_image']['name']); 
        $targetFilePath_for_cover = $targetDir . $cover_image; 
        move_uploaded_file($_FILES["main_image"]["tmp_name"], $targetFilePath_for_cover);
         

        
       // upload multiple images for galary
        if(!empty($fileNames)){ 
            foreach($_FILES['galary']['name'] as $key=>$val){ 
                // File upload path 
                $fileName = time().uniqid().basename($_FILES['galary']['name'][$key]); 
                $targetFilePath = $targetDir . $fileName; 
                 
                // Check whether file type is valid 
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                if(in_array($fileType, $allowTypes)){ 
                    // Upload file to server 
                    if(move_uploaded_file($_FILES["galary"]["tmp_name"][$key], $targetFilePath)){ 
                        // Image db insert sql 
                        $galary_images .= $fileName. ','; 
                    }else{ 
                        $errorUpload .= $_FILES['galary']['name'][$key].' | '; 
                    } 
                }else{ 
                    $errorUploadType .= $_FILES['galary']['name'][$key].' | '; 
                } 
            } 
 
            // Error message 
            $errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
            $errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
            $errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
             
            if(!empty($galary_images)){ 
                $galary_images = trim($galary_images, ','); 
                // Insert image file name into database 
                $insert = mysqli_query($con, "INSERT INTO events (title, cover_image, images) VALUES ('".$event_name."', '".$cover_image."', '".$galary_images."')"); 
                
                if($insert){ 
                    $statusMsg = "<div class='alert alert-success'>Event created successfully.".$errorMsg."</div>"; 
                }else{ 
                    $statusMsg = "<div class='alert alert-success'>Sorry, there was an error uploading your file.</div>"; 
                } 
            }else{ 
                $statusMsg = "<div class='alert alert-success'>Upload failed! ".$errorMsg."</div>"; 
            } 
        }else{ 
            $statusMsg = "<div class='alert alert-success'>Please select a file to upload.<div>"; 
        } 
 
    }

    
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Admin | Dashboard </title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
   <?php include_once('includes/navbar.php');?>

   
        <div id="layoutSidenav">
          <?php include_once('includes/sidebar.php');?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <!-- Account page navigation--> 
                      <?php echo $statusMsg; ?>
 
                        <h1 class="mt-4">Add Event</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Event</li>
                        </ol>    
                        <div class="col-xl-12">
                            <!-- Add Event card-->
                            <div class="card mb-4">
                                <div class="card-header">Add Event</div>
                                <div class="card-body">
                                <form  method="post" enctype="multipart/form-data">

                                        <!-- Form Group (username)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputUsername">Name (Please write here event title)</label>
                                            <input class="form-control" id="inputUsername" type="text" name="event_name" placeholder="Event title" required>
                                        </div>
                                 
                                        <!-- Form Row        -->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (organization name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputMainImage">Event Image (Select main image for event)</label>
                                                <input class="form-control" id="inputMainImage" type="file" name="main_image" required>
                                            </div>
                                            <!-- Form Group (location)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputMainImageGalary">Event galary (Select multiple for event garary image)</label>
                                                <input class="form-control" id="inputMainImageGalary" type="file" name="galary[]" multiple>
                                            </div>
                                        </div>
                                       
                                       
                                        <!-- Save changes button-->
                                        <button class="btn btn-primary" type="submit" name="create_event">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </main>
             <?php include_once('../includes/footer.php'); ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
    </body>
</html>
<?php } ?>
