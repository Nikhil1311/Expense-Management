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
        body{
            background-color: white;
        }

        .full-width-tabs > ul.nav.nav-tabs {
        display: table;
        width: 100%;
        table-layout: fixed;
        }
    </style>


    <script>
        function runQueries()
        {
            xhr_obj = new XMLHttpRequest();
            xhr_obj.onreadystatechange = addToTotals;
            xhr_obj.open("GET", "get_group_totals.php", true);
            xhr_obj.send();
        }
		
        function addToTotals()
        {
			if(xhr_obj.readyState == 4 && xhr_obj.status == 200)
			{
				data = JSON.parse(xhr_obj.responseText);
				console.log(data);

			 	var break_line = document.createElement("br");

				var personal_total = data[0];
				due_main = document.createElement("div");
				due_main.className = "col-lg-3 centered";
				due_main.style.cssText = 'padding-top:15px;';

				user_img = document.createElement("img");
				user_img.src = "img/user_image.png";
				user_img.height = "120";
				user_img.width = "120";

				name_heading = document.createElement("h4");
				bold_tag = document.createElement("b");
				bold_tag.innerHTML = "Personal Expense";
				name_heading.appendChild(bold_tag);

				amount_holder = document.createElement("i");
				amount_holder.style.color = 'Red';
				des_holder = document.createElement("p");
				des_holder.style.cssText = "padding-top:0;paddig-bottom:0";
				action_holder = document.createElement("p");
				action_holder.style.cssText = "padding-top:0;paddig-bottom:0";
				action_anchor = document.createElement("a");
				action_anchor.style.cssText = "text-decoration:none;color:grey;cursor:pointer";
				action_anchor.setAttribute("data-toggle", "modal");
				action_anchor.setAttribute("data-target", "#modal_settleup");
				action_anchor.setAttribute("data-meta", personal_total);
				
				amount_holder.innerHTML = personal_total;
				action_holder.appendChild(action_anchor);

				due_main.appendChild(user_img);
				due_main.appendChild(break_line);
				due_main.appendChild(name_heading);
				due_main.appendChild(des_holder);
				due_main.appendChild(amount_holder);
				due_main.appendChild(break_line);
				due_main.appendChild(action_holder);

				document.getElementById("totalsholder").appendChild(due_main);
				
				var personal_group = data[1];
				var group_exp = data[2];
				for(i=0;i<personal_group.length;i++)
				{
					due_main = document.createElement("div");
					due_main.className = "col-lg-3 centered";
					due_main.style.cssText = 'padding-top:15px;';
					due_main.style.height = "211.8px";
					name_heading = document.createElement("h4");
					bold_tag1 = document.createElement("b");
					bold_tag1.innerHTML = personal_group[i][1];
					bold_tag2 = document.createElement("b");
					name_heading.appendChild(bold_tag1);
					amount_holder1 = document.createElement("i");
					amount_holder2 = document.createElement("i");
					des_holder = document.createElement("p");
					des_holder.style.cssText = "padding-top:0;paddig-bottom:0";
					action_holder = document.createElement("p");
					action_holder.style.cssText = "padding-top:0;paddig-bottom:0";
					action_anchor = document.createElement("a");
					action_anchor.style.cssText = "text-decoration:none;color:green;cursor:pointer";
					action_anchor.innerHTML = "Compare &rarr;";
					action_anchor.href = "barchart1.php?gid="+personal_group[i][0]+"&gname="+personal_group[i][1];
					amount_holder1.innerHTML = "Your Expense: "+personal_group[i][2];
					amount_holder2.innerHTML = "Group Expense: "+group_exp[i][2];
					action_holder.appendChild(action_anchor);
					due_main.appendChild(document.createElement("br"));
					due_main.appendChild(name_heading);
					due_main.appendChild(document.createElement("br"));
					due_main.appendChild(amount_holder1);
					due_main.appendChild(document.createElement("br"));
					due_main.appendChild(amount_holder2);
					due_main.appendChild(document.createElement("br"));	
					due_main.appendChild(document.createElement("br"));
					due_main.appendChild(action_holder);
					document.getElementById("totalsholder").appendChild(due_main);
				}
			}
		}
    </script>
	
  <body onload = "runQueries()">

    <div id="navbar-main">
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <img src = "img/logo.png" class = "logo1">
                  </button>
                  
                  <a class="navbar-brand hidden-xs hidden-sm" href="index.html"><img src = "img/logo.png" class = "logo"></a>
                </div>

                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a data-toggle="modal" data-target="#myModal">Add Personal Expense</a></li>
                        <li><a data-toggle="modal" data-target="#">Add Group Expense</a></li>
                        <li><a data-toggle="modal" data-target="#myModal1">Create Group</a></li>
                        <li><a data-toggle="modal" data-target="view_totals.php">View Totals</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="login.html" class="smoothScroll"><span class="icon icon-settings" style="font-size:15px;"> &nbsp</span>Settings</a></li>
                        <li><a href="signup.html" class="smoothScroll"><span class="icon icon-user-minus" style="font-size:15px;">&nbsp</span>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
	
	    <div class="tab-content">
        <div id="duestab" class="tab-pane fade in active">
            <div class="container" id="team" name="team">
            <br>
                <div class="row white centered" id = "totalsholder">
                    <?php ?>
                        <h1 class="centered">Your Totals</h1>
                        <hr style = "height:2px">
                        <br>
                        <br>
                        
                </div>
            </div>
        </div>

        <div id="activitytab" class="tab-pane fade">
            <div class="container" id="team" name="team">
            <br>
                <div class="row white" id = "activityholder">
                    <?php ?>
                        <h1 class="centered">Your Activity</h1>
                        <hr style = "height:2px">
                        <br>
                        <br>
                        
                </div>
            </div>
        </div>
    </div>

</body>