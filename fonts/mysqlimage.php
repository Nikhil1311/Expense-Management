
<?php
   ini_set('mysql.connect_timeout',300);
   ini_set('default_socket_timeout',300);
?>

<html>
   <body>
       <form method="post" enctype="multipart/form-data"
       <br/>
           <input type="file" name="image" />
           <br/><br/>
           <input type="submit" name="submit" value="uplaod" />
       </form>
       <?php
           if(isset($_POST['submit']))
           {
               if(getimagesize($_FILES['image']['tmp_name'])== FALSE)
               {
                   echo "please select an image.";
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
           displayimage();
           function saveimage($name,$image)
           {
               $con=mysql_connect("localhost","root","", "profile");
               // mysqli_select_db("profile",$con);
               $qry="insert into images (name,image) values ('$name','$image')";
               $result=mysql_query($qry,$con);
               if($result)
               {
                   echo "<br/>image uploaded.";
               }
               else
               {
                  echo "<br/>image not uploaded."; 
               }
            }     
            function displayimage()
            {
               $con=mysql_connect("localhost","root","");
               mysql_select_db("profile",$con);
               $qry="select * from images";
               $result=mysql_query($qry,$con);
               while($row = mysql_fetch_array($result))
               {
                  echo '<img height="300" width="300" src="data:image;base64,'.$row[2].' "> ';
               }

            
            }


           
       ?>
    </body>
</html>

