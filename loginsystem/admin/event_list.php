<?php session_start();
include_once('../includes/config.php');
if (strlen($_SESSION['adminid']==0)) {
  header('location:logout.php');
  } else{
// for deleting user
if(isset($_GET['id']) && isset($_GET['delete']) && $_GET['delete'] === 'true')
{
    $event_id=$_GET['id']; 
    $query=mysqli_query($con,"select * from events where id='$event_id'");
    $result=mysqli_fetch_assoc($query);

    unlink(ADMIN_URL.'uploads/'.$result['cover_image']);

    $images = $result['images'];

    foreach($images as $image){
        unlink(ADMIN_URL.'uploads/'.$image);
    }

    $msg=mysqli_query($con,"delete from events where id='$event_id'");
    if($msg)
    {
        echo "<script>alert('Event deleted successfully.');</script>";
        echo "<script type='text/javascript'> document.location = 'event_list.php'; </script>";
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
        <title>Admin | Events</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />

        <link href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.3/af-2.5.2/b-2.3.5/b-colvis-2.3.5/b-html5-2.3.5/b-print-2.3.5/r-2.4.0/datatables.min.css"/>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

    </head>
    <body class="sb-nav-fixed">
      <?php include_once('includes/navbar.php');?>
        <div id="layoutSidenav">
         <?php include_once('includes/sidebar.php');?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Manage Events</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Events</li>
                        </ol>
            
                        <div class="card mb-12">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Events Details 
                                <div class="card-tools" style="text-align: right;"> 
                                    <button class="btn btn-primary">
                                        <a style="color: #fff;text-decoration: none;" href="add_event.php">Add Event</a> 
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="event_list">
                                    <thead>
                                        <tr>
                                            <th>Sno.</th>
                                            <th>Title</th>
                                            <th> Image</th>
                                            <th> Created At</th> 
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sno.</th>
                                            <th>Title</th>
                                            <th> Image</th>
                                            <th> Created At</th> 
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php 
                                        $ret=mysqli_query($con,"select * from events");
                                        $cnt=1;
                                        while($row=mysqli_fetch_array($ret))
                                        {?>
                                            <tr>
                                                <td><?php echo $cnt;?></td>
                                                <td><?php echo $row['title'];?></td>
                                                <td><img style="width: 100px;" src="<?php echo ADMIN_URL."uploads/". $row['cover_image'];?>"/> </td>
                                                <td><?php echo $row['create_at'];?></td>
                                                <td> 
                                                    <a href="edit-event.php?id=<?php echo $row['id'];?>"> 
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="event_list.php?delete=true&id=<?php echo $row['id'];?>" onClick="return confirm('Do you really want to delete');"><i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php $cnt=$cnt+1; }?>                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
  <?php include('../includes/footer.php');?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
       
       
 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.3/af-2.5.2/b-2.3.5/b-colvis-2.3.5/b-html5-2.3.5/b-print-2.3.5/r-2.4.0/datatables.min.js"></script>
         
        <script>
            $(document).ready(function() {
                $('#event_list').DataTable( {
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                });
            });
            </script>
    </body>
</html>
<?php } ?>