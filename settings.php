<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>POCKET EXPENSES</title>
    <script src="js/jquery.min.js"></script>
    <link href="css/bootstrap2.css" rel="stylesheet">
    <script type="text/javascript" src="js/bootstrap.min2.js"></script>  
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css"> -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>     
    <link rel="stylesheet" href="css/icomoon.css">
    <link href="css/styleAdd.css" rel="stylesheet">
    <link href="css/wow.css" rel="stylesheet">
    <script type="text/javascript" src="js/smoothscroll.js"></script> 
    <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>   
    
    <script type="text/javascript" src="data.json"></script>
    <style>
        
    </style>

  </head>


    <body>

        <div id="navbar-main">
            <div class="navbar navbar-inverse navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <img src = "img/logo.png" class = "logo1">
                      </button>
                      
                      <a class="navbar-brand hidden-xs hidden-sm" href="user_home.php"><img src = "img/logo.png" class = "logo"></a>
                    </div>

                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                        <li><a href = "user_home.php">Home</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="settings.php" class="smoothScroll"><span class="icon icon-settings" style="font-size:15px;">&nbsp</span>Settings</a></li>
                            <li><a href="logout.php" class="smoothScroll"><span class="icon icon-user-minus" style="font-size:15px;">&nbsp</span>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <div class="loginColumns animated fadeInDown" style = "margin-top:100px">
        <div class="row">
            <div class="col-md-12">
                <p class = "logintext2" style = "color:white;font-size:40px">Settings</p>
            </div>
            <div class="col-md-12">
                <div class="wrappingbox">
                        <div class = "form-group">
                            <button data-toggle = "modal" data-target = "#change_password" type="submit" class="btn btn-primary btn-block">Change Password</button>
                        </div>
                        
                        <div class = "form-group">
                            <button data-toggle="modal" data-target = "#picture_modal" type="submit" class="btn btn-primary btn-block">Upload Profile Picture</button>
                        </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <p><a href = "user_home.php" class = "back" style = "text-decoration:none;color:white;cursor:pointer">&larr; Home </a></p>
            </div>
        </div>
    </div>


    <div id="picture_modal" class="modal fade">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
        <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-12">
                <center><p class = "logintext2">Set a Profile Picture</p></center>
            </div>

            <div class="col-md-12">
                    <form method="post" enctype="multipart/form-data" action = "uploading_image.php">
                    <h2>Upload a file</h2>
                    <br>
                    <br>
                    <input type="file" name="image" />
                    <br/><br/>
                        <input type="submit" name="submit" value="uplaod" />
                    </form>
            </div>
        </div>
        </div>
        <br>
        </div>
        </div>
        </div>
    </div>

    <div id="change_password" class="modal fade">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
        <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-12">
                <center><p class = "logintext2">Change Password</p></center>
            </div>

            <div class="col-md-12">
                <div class="wrappingbox">
                    <form action = "change_password.php" method = "post">
                        <div class="form-group">
                            <input type="password" name = "password_old" class="form-control" placeholder="Old Password" required>
                        </div>

                        <div class="form-group">
                            <input type="password" name = "password_new" class="form-control" placeholder="New Password" required>
                        </div>

                        <div class="form-group">
                            <input type="password" name = "password_new_confirm" class="form-control" placeholder="Confirm New Password" required>
                        </div>
                        
                        <div class = "form-group">
                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        <br>
        </div>
        </div>
        </div>
    </div>


    </body>