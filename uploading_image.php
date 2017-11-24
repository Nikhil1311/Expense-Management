<?php
         ini_set('mysqli.connect_timeout',300);
         ini_set('default_socket_timeout',300);

         if(isset($_POST['submit']))
         {
             if(getimagesize($_FILES['image']['tmp_name'])== FALSE)
             {
                 echo "<script>alert('Please select an image');
                  window.location.href='settings.php';
                  </script>";
             }

             else
             {
                 $image= addslashes($_FILES['image']['tmp_name']);
                 $name= addslashes($_FILES['image']['name']);
                 $image= file_get_contents($image);
                 $image= base64_encode($image);
                 saveimage($name,$image);
             }
         }

         function saveimage($name,$image)
         {
             include('init.php');
             $result = upload_image($name, $image);
             if($result)
             {
                  echo "<script>alert('Profile Picture Uploaded');
                  window.location.href='settings.php';
                  </script>";
             }
             else
             {
                echo "<script>alert('Failed to Upload Picture, Please Try Again');
                  window.location.href='settings.php';
                  </script>";
             }
          }

          
          // function displayimage()
          // {
          //    $con=mysqli_connect("localhost","root","", "profile");
          //    $qry="SELECT * from `images`";
          //    $result=mysqli_query($con, $qry);
          //    while($row = mysqli_fetch_array($result))
          //    {
          //       echo '<img height="300" width="300" src="data:image;base64,'.$row[2].' "> ';
          //    }            
          // }
?>