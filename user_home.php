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

	amounts={}
	data=[]
    $(function () {
      $('#modal_settleup').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var meta = button.data('meta'); // Extract info from data-* attributes
        
        meta = meta.split(',');
        amount = meta[2].trim();
        amount = parseInt(amount) * -1;

        var modal = $(this);
        modal.find('#amount_to_settle').val(amount);
        modal.find('#paying_to').html('Paying - ' + meta[1]);
        modal.find('#paidto_user_email').val(meta[0].trim());
      });
    });


    function recurringExp()
    {
        var categories = ['','DAY','WEEK','MONTH'];
        for(i = 1;i<4;i++){
            if(document.getElementById('rec_radio' + i).checked)
            {
                document.getElementById('rec_finalCategory').value = categories[i];
                document.getElementById('form3').submit();  
            }
        }

    }


    function runQueries()
    {
        xhr = new XMLHttpRequest();
        xhr.onreadystatechange = addToBody;
        xhr.open("GET", "get_dues.php", true);
        xhr.send();

        xhr_obj = new XMLHttpRequest();
        xhr_obj.onreadystatechange = addToTotals;
        xhr_obj.open("GET", "get_group_totals.php", true);
        xhr_obj.send();   

        xhr_activity = new XMLHttpRequest;
        xhr_activity.onreadystatechange = activity;
        xhr_activity.open("GET", "activity.php", true);
        xhr_activity.send();
    }

    function addToBody()
    {
        if(xhr.readyState == 4 &&  xhr.status == 200)
        {  
            to_give = 0;
            to_take = 0;
            break_line = document.createElement("br");
            data = JSON.parse(xhr.responseText);
            for(i = 0;i<data.length;i++)
            {
                var obj = data[i];
                console.log(obj);
                due_main = document.createElement("div");
                due_main.className = "col-lg-3 centered";
                due_main.style.cssText = 'padding-top:15px;';

                user_img = document.createElement("img");
                user_img.src = "img/user_image.png";
                user_img.height = "120";
                user_img.width = "120";

                name_heading = document.createElement("h4");
                bold_tag = document.createElement("b");
                bold_tag.innerHTML = obj[1];
                name_heading.appendChild(bold_tag);

                amount_holder = document.createElement("i");
                des_holder = document.createElement("p");
                des_holder.style.cssText = "padding-top:0;paddig-bottom:0";
                action_holder = document.createElement("p");
                action_holder.style.cssText = "padding-top:0;paddig-bottom:0";
                action_anchor = document.createElement("a");
                action_anchor.style.cssText = "text-decoration:none;color:grey;cursor:pointer";
                action_anchor.setAttribute("data-toggle", "modal");
                action_anchor.setAttribute("data-target", "#modal_settleup");
                action_anchor.setAttribute("data-meta", obj);

                if(obj[2] < 0)
                {
                    amt = obj[2] * -1;
                    des_holder.innerHTML = "You Owe";
                    amount_holder.style.cssText = "font-size:24px;color:red";
                    action_anchor.innerHTML = "Settle Up &rarr;"
                    action_anchor.setAttribute("data-target", "#modal_settleup");
                }

                else
                {   
                    des_holder.innerHTML = "Owes You";
                    amt = obj[2];
					var action_anchor=document.createElement("a");
                    action_anchor.innerHTML = "Ping Reminder &rarr;"
                    action_anchor.href = "email.php?name="+obj[1]+"&amt="+obj[2]+"&email_to="+obj[0];
                    amount_holder.style.cssText = "font-size:24px;color:green";
                    action_anchor.setAttribute("data-target", "#modal_ping");
                }

                amount_holder.innerHTML = amt;
                action_holder.appendChild(action_anchor);

                due_main.appendChild(user_img);
                due_main.appendChild(break_line);
                due_main.appendChild(name_heading);
                due_main.appendChild(des_holder);
                due_main.appendChild(amount_holder);
                due_main.appendChild(break_line);
                due_main.appendChild(action_holder);

                document.getElementById("duesholder").appendChild(due_main);
            }
        }
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
                    action_anchor.href = "group_graph.php?gid="+personal_group[i][0]+"&gname="+personal_group[i][1];
                    amount_holder1.innerHTML = "Your Expense: "+parseFloat(personal_group[i][2]).toFixed(2);
                    amount_holder2.innerHTML = "Group Expense: "+parseFloat(group_exp[i][2]).toFixed(2);
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

    function checker()
    {
        var categories = ['', 'food', 'utilities', 'rent', 'groceries', 'entertainment', 'others'];
        for(i = 1;i<7;i++){
            if(document.getElementById('radio' + i).checked)
            {
                document.getElementById('finalCategory').value = categories[i];
                document.getElementById('form1').submit();  
            }
        }
    }

    function createGroup()
    {
        document.getElementById('form2').submit();
    }

    function settleup()
    {
        document.getElementById('form_settle').submit();
    }


    function activity()
    {
        // data = ["You were added to group <i> Group2 <\/i> by <i> Pratul <\/i> on 2017-11-14 01:07:34","You were added to group <i> Group1 <\/i> by <i> Pratul <\/i> on 2017-11-14 01:00:17","You spent <i> 122 <\/i> for <i> cab <\/i> on 2017-11-08 01:48:33 ","You spent <i> 1111 <\/i> for <i> cab <\/i> on 2017-11-08 01:10:51 ","You spent <i> 1250 <\/i> for <i> Bread Butter <\/i> on 2017-10-09 21:03:42 ","You spent <i> 4468 <\/i> for <i> electricity <\/i> on 2017-10-09 20:56:50 "]

        // ans = JSON.parse(data);
        // l = ans.length;
        // for(var j = 0;j<l;j++)
        // {
        //     major = document.createElement('div');
        //     major.className = "col-lg-12";
        //     major.style.cssText = "border:solid 1px black; margin-bottom: 5px; paddig-bottom:10px;";
        //     para = document.createElement("p");
        //     para.innerHTML = ans[j];
        //     para.style.cssText = "margin-bottom : 25px";
        //     major.appendChild(para);
        //     var element=document.getElementById("activityholder");
        //     element.appendChild(major);
        // }


        if(xhr_activity.status == 200 && xhr_activity.readyState == 4)
        {
            console.log(xhr_activity.responseText);
            ans = JSON.parse(xhr_activity.responseText);
            l = ans.length;
            for(var j = 0;j<l;j++)
            {
                major = document.createElement('div');
                major.className = "col-lg-12";
                major.style.cssText = "border:solid 1px black; margin-bottom: 5px; paddig-bottom:10px;";
                para = document.createElement("p");
                para.innerHTML = ans[j];
                para.style.cssText = "margin-bottom : 25px";
                major.appendChild(para);
                var element=document.getElementById("activityholder");
                element.appendChild(major);
            }
        }
    }



    /*function checker_group()
    {
        var categories = ['', 'food', 'utilities', 'rent', 'groceries', 'entertainment', 'others'];
        for(i = 1;i<7;i++){
            //alert('radio' + i+i)
            if(document.getElementById('radio' + i+i).checked)
            {
                //alert("hi")
                document.getElementById('finalCategory1').value = categories[i];
                //document.getElementById('form_grp').submit();  
            }
        }
    }*/


    function update_amt()
    {
        
		xhr4 = new XMLHttpRequest();
        xhr4.onreadystatechange=updt;
        arr_desc=document.getElementsByName("description1")
        amounts["description"]=String(arr_desc[0].value)
        amounts["dates"]=String(document.getElementById("date1").value)
        amounts["category"]=String(document.getElementById("finalCategory1").value)
		//alert(data.id[0])
		amounts["groupid"]=String(data.id)
		paidclass=document.getElementsByClassName("paidby")
		//alert(paidclass[0].value)
		//amounts["emailpaid"]=paidclass[0].value
		var e = document.getElementById("select1");
		var strUser = e.options[e.selectedIndex].value;
		console.log(strUser)
		amounts["emailpaid"]=strUser
		
        console.log(amounts)
        new_amt=JSON.stringify(amounts)
        console.log(new_amt);
        xhr4.open("GET","update_amt.php?amt_array="+new_amt,true);
        xhr4.send();
    }

    function updt()
    {
        if(xhr4.readyState==4 && xhr4.status==200)
        {
            //document.getElementById("updt").innerHTML="Update Successful"
            alert("Update Successful")
			window.location.replace("user_home.php")
        }
    }

    function equal()
    {
        var arr=document.getElementsByName("eq");
        var amnt=document.getElementsByClassName("amts");
        console.log(arr.length);
        
        for(var j=0;j<arr.length;j++)
        {
            
            if(parseInt(amnt[0].value)==0 || amnt[0].value=="")
            {
                arr[j].value=0;
				amounts[data.mail[j]]=arr[j].value
				amounts["amnt"]=0;
            }
            else
            {
                arr[j].value=parseInt(amnt[0].value)/arr.length
                amounts[data.mail[j]]=arr[j].value
				
				
            }
        }  
			amounts["amnt"]=parseInt(amnt[0].value)		
    }

    function unequal()
    {
        var arr=document.getElementsByName("eq");
        //var amnt=document.getElementsByClassName("amts")
        console.log(arr.length)
        for(var j=0;j<arr.length;j++)
        {
                arr[j].value="";
        }  
    }

    function select_group()
    {
        xhr2=new XMLHttpRequest();
        xhr2.onreadystatechange=get_names;
		/*var data = "?q="+str+"&job_id="+job_id;
		name_grp=String(this.innerHTML)
		grp_name=name_grp.substr(0,name_grp.length-1)
		grp_id=name_grp.substr(name_grp.length-1,name_grp.length)
		console.log(grp_id,grp_name)*/
		document.getElementById("names").innerHTML=""
		//alert(this.id)
        xhr2.open("get","group_names.php?group_name="+this.innerHTML+"&group_id="+this.id,true);
        xhr2.send();
    }
        
    function get_names()
    {
        if(xhr2.readyState==4 && xhr2.status==200)
        {
             //break_line = document.createElement("br");
			 console.log(xhr2.responseText)
             data = JSON.parse(xhr2.responseText);
			 var names_div=document.getElementById("namesdiv")
			 paidclass=document.getElementById("select1")
             var names_div=document.getElementById("names")
             for(var i=0;i<data.mail.length;i++)
                {
                    para=document.createElement("p")
					//alert(data.mail[i])
					para.innerHTML=""
                    para.innerHTML=data.mail[i]+"    "
					sel=document.createElement("option")
					sel.text=data.mail[i]
					sel.value=data.mail[i]
					paidclass.add(sel)
                    //amounts[data.mail[i]]=0
                    var inp=document.createElement("input")
                    inp.type="text"
                    inp.id=data.mail[i]
					var amnt=document.getElementsByClassName("amts")
					/*if(parseInt(amnt[0].value)==0 || amnt[0].value=="")
					{
						inp.value="0"
					}
					else
					{	
						inp.value=parseInt(amnt[0].value)/data.mail.length
						amounts[data.mail[i]]=inp.value
					}
					amounts["amnt"]=parseInt(amnt[0].value)*/
                    inp.name="eq"
                    para.appendChild(inp)
                    names_div.appendChild(para)
                   // break_line = document.createElement("br");
                }
        }
    }
        
    function getGroup()
    {
		var categories = ['', 'food', 'utilities', 'rent', 'groceries', 'entertainment', 'others'];
        for(i = 1;i<7;i++){
            //alert('radio' + i+i)
            if(document.getElementById('radio' + i+i).checked)
            {
                //alert("hi")
                document.getElementById('finalCategory1').value = categories[i];
                //document.getElementById('form_grp').submit();  
            }
        }
		
		
	   xhr1=new XMLHttpRequest();
        xhr1.onreadystatechange=call;
        xhr1.open("get","group_exp.php",true);
        xhr1.send();
    }
        
        
    function call()
    {
        if(xhr1.readyState==4 && xhr1.status==200)
        {
                break_line = document.createElement("br");
				console.log(xhr1.responseText)
                data1 = JSON.parse(xhr1.responseText);
                //console.log(data1);
				console.log(JSON.parse((xhr1.responseText)))
                grp_name = document.getElementById("gh")
				del_div = document.getElementById("gh")
				var cnt=1;
				while (del_div.childNodes.length>2)
					{
						// console.log(del_div.childNodes[2])
						del_div.removeChild(del_div.childNodes[2]);
						
					}
                
				for(i=0;i<data1.id.length;i++)
					{
						console.log(data1.name[i])
						lab=document.createElement("label")
						lab.htmlFor="rad"
						lab.innerHTML=data1.name[i]
						lab.id=data1.id[i]
						lab.onclick=select_group;
						del_div.appendChild(lab)
					}
        }
    }

</script>

  </head>


  <body onload = "runQueries()">

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
                        <li><a data-toggle="modal" data-target="#myModal">Add Personal Expense</a></li>
                        <li><a data-toggle="modal" data-target="#groupModal">Add Group Expense</a></li>
                        <li><a data-toggle="modal" data-target="#myModal_recursive">Add Recurring Expense</a></li>
                        <li><a data-toggle="modal" data-target="#myModal1">Create Group</a></li>
                        <li><a href = "view_totals1.php">View Totals</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="settings.php" class="smoothScroll"><span class="icon icon-settings" style="font-size:15px;"> &nbsp</span>Settings</a></li>
                        <li><a href="logout.php" class="smoothScroll"><span class="icon icon-user-minus" style="font-size:15px;">&nbsp</span>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    

    <div style = "margin-top:60px" class = "full-width-tabs">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#duestab">Dues</a></li>
            <li> <a data-toggle="tab" href="#totals_tab">Your Totals</a></li>
            <li> <a data-toggle="tab" href="#activitytab">Activity</a></li>
        </ul>
    </div>

    <div class="tab-content">
        <div id="duestab" class="tab-pane fade in active">
            <div class="container" id="team" name="team">
            <br>
                <div class="row white centered" id = "duesholder">
                    <?php ?>
                        <h1 class="centered">Your Dues</h1>
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

        <div id="totals_tab" class="tab-pane fade">
            <div class="container" id="team" name="team">
            <br>
                <div class="row white" id = "totalsholder">
                    <?php ?>
                        <h1 class="centered">Your Totals</h1>
                        <hr style = "height:2px">
                        <br>
                        <br>
                        
                </div>
            </div>
        </div>
    </div>


    <div id="myModal" class="modal fade">
    <div class="modal-dialog modal-lg">

    <div class="modal-content">
    <div class="modal-header">
    <div class="loginColumns animated fadeInDown">
    <div class="row">

        <div class="col-md-12">
            <center><p class = "logintext2">Add Expense</p></center>
        </div>
        <html lang="en">

  
        <div class="col-md-12">
            <div class="wrappingbox">
                    <form method = "post" action = "add_to_db.php" id = "form1">
                     
                      <div class="input-container">
                        <input  type="#{type}" id="#{label}" name = "description" required="required" required/>
                        <label for="#{label}"> Description</label>
                        <div class="bar"></div>
                      </div>
                      <div class="input-container">
                        <input type="#{type}" id="#{label}" name = "amount" required="required"/>
                        <label for="#{label}">Amount</label>
                        <div class="bar"></div>
                      </div>
                       
                        <div class="datedivsib">
                        <div class="container">
                        <div class="row">
                            <div class='col-sm-4'>
                                <div class="form-group">
                                    <div class='input-group date' id='datetimepicker1'>
                                        <input type='text' placeholder="Pick a Date and Time" class="form-control" name = "date" id = "date" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#datetimepicker1').datetimepicker();
                                });
                            </script>
                        </div>
                        </div>
                        </div>
                        


                        <div class="radiodiv">
                        <label for="#{label}">Pick a Category</label>
                        </div>

                        <div class="col-md-8">
                            <div class="col-md-8">

                                <div class="funkyradio" name = "category">
                                    <div class="funkyradio-primary">
                                        <input type="radio" name="radio" id="radio1" />
                                        <label for="radio1">Food</label>
                                    </div>
                                    <div class="funkyradio-success">
                                        <input type="radio" name="radio" id="radio3" />
                                        <label for="radio3">Rent</label>
                                    </div>
                                    <div class="funkyradio-danger">
                                        <input type="radio" name="radio" id="radio4" />
                                        <label for="radio4">Groceries</label>
                                    </div>
                                    <div class="funkyradio-warning">
                                        <input type="radio" name="radio" id="radio5" />
                                        <label for="radio5">Entertainment</label>
                                    </div>
                                    <div class="funkyradio-info">
                                        <input type="radio" name="radio" id="radio2"/>
                                        <label for="radio2">Utilities</label>
                                    </div>
                                    <div class="funkyradio-default">
                                        <input type="radio" name="radio" id="radio6">
                                        <label for="radio6">Others</label>
                                    </div>
                                </div>
                                
                            <input type = "hidden" id = "finalCategory" name = "finalCategory">
                            
                            <div class="addbutton">
                            <button type="button" class="btn btn-success btn-lg" onclick = "checker()">Add</button>
                        </div>
                            </div>
                        </div>
                        
                    </form>
            </div>
        </div>
    </div>
    <br>
    </div>

    </div>
    </div></div></div>



<div id="myModal1" class="modal fade">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
<div class="loginColumns animated fadeInDown">
    <div class="row">

        <div class="col-md-12">
            <center><p class = "logintext2">Create Group</p></center>
        </div>
        <div class="col-md-12">

                <form method = "post" action = "create_group.php" id = "form2">

                  <h2>Enter Your Group Details</h2>
                  <h4 id = "summary_balance"></h4>
                  <br>
                  <br>
                    <div class="input-container">
                      <input type="#{type}"  id="gname" name="group_name" required="required">
                        <label for="#{gname}">Group Name</label>
                        <div class="bar"></div>
                    </div>
                    <div class="input-container">
                  
                    <textarea class="form-control" name = "email_list" rows="5" id="email" placeholder="Email ID's Of Group Members You Wish To Add, Separate Email ID's by a single comma" ></textarea>
                    
                    <div class="addbutton">
                    <button type="button" class="btn btn-success btn-lg" onclick = "createGroup()">Create Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
</div>
</div>
</div></div></div>




<div id="modal_settleup" class="modal fade">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
<div class="loginColumns animated fadeInDown">
    <div class="row">

        <div class="col-md-12">
            <center><p class = "logintext2">Settle Up</p></center>
        </div>

        <div class="col-md-12">

                <form method = "GET" action = "settle_up.php" id = "form_settle">

                    <h2 id = "paying_to"></h2>
                    <br>
                    <br>
                    <div class="input-container">
                        <input type="#{type}"  id="amount_to_settle" name="amount" required="required">
                        <label for="#{gname}">Amount</label>
                        <div class="bar"></div>
                    </div>
                    
                    <input type = "hidden" id = "paidto_user_email" name = "paidto_user_email">
                    
                    <div class="addbutton">
                    <button type="button" class="btn btn-success btn-lg" onclick = "settleup()">Save</button>
                    </div>
                
                </form>
        </div>
        </div>
        </div>
        <br>
        </div>
        </div>
        </div>
    </div>
</div>


<!-- Prajwal DIVS -->

<div  id="nameModal" class="modal fade">
    <div class="modal-dialog modal-lg">

    <div class="modal-content">
    <div class="modal-header">
    <div class="loginColumns animated fadeInDown">
    <div class="row">

        <div class="col-md-12">
        
            <center><p class = "logintext2">Provide Details</p></center>

        </div>
        
                <div class="col-md-8">
                    <div class="addbutton">
                        <div class="form-group">
						  <label for="sel1">Amount Paid By:</label>
						  <select class="form-control" id="select1" class="paidby">
							
						  </select>
						</div>   

                        <button type="button" onclick="equal()" class="btn btn-success btn-lg">Equally</button>  
                        <button type="button" onclick="unequal()" class="btn btn-success btn-lg">Unequally</button>
                        
                        <br><br>
                    </div>

                    <div class="col-md-8" id ="names">
					<div id="namesdiv"></div>
                        <input type = "hidden" id = "finalgroup" name = "finalgroup">
                    </div>

                    <br>
                    <br>

                    <div class="col-md-8" style = "margin-top:10px">
                        <button type="button" onclick="update_amt()" class="btn btn-success btn-lg">Update</button>
                        <p id="updt"></p>
                    </div>
                </div>    
    </div>
    </div>
    </div>
    <br>
    </div>

    </div>
    </div>
    
    
    
    
<div  id="grpModalexp" class="modal fade">
    <div class="modal-dialog modal-lg">

    <div class="modal-content">
    <div class="modal-header">
    <div class="loginColumns animated fadeInDown">
    <div class="row">

        <div class="col-md-12">
        
            <center><p class = "logintext2">Choose The Group</p></center>
        </div>
        
        
        <html lang="en">

                <div class="radiodiv">
                        
                        </div>

                        <div class="col-md-8">
                            <div class="col-md-8" >

                                <a data-toggle="modal" data-target="#nameModal" data-dismiss="modal"><div class="funkyradio"  id="grpdiv "name ="grp">
                                    <div class="funkyradio-success" id="gh">
                                        <input type="radio" name="radio" id="rad" />
                                        
                                    </div>
                                </div></a>
                            <input type = "hidden" id = "finalgroup" name = "finalgroup">
                            <div class="addbutton">
                            
                            <a data-toggle="modal" data-target="#groupModal" data-dismiss="modal"><button type="button" class="btn btn-success btn-lg">Back</button></a>
                            </div>
                        </div>
                        </div>
                    
            </div>
        </div>
    </div>
    <br>
    </div>

    </div>
    </div></div></div>

    
    
    <div id="groupModal" class="modal fade">
	<div class="modal-dialog modal-lg">

    <div class="modal-content">
    <div class="modal-header">
    <div class="loginColumns animated fadeInDown">
    <div class="row">

        <div class="col-md-12">
        
            <center><p class = "logintext2">Add Expense</p></center>

        </div>
        
        
        <html lang="en">
                <div class="col-md-8">
                <div class="addbutton">
                <div class="input-container">
                        <input type="#{type}" id="#{label}" class="paidby" name = "description1" required="required"/>
                        <label for="#{label}">Description</label>
                        <div class="bar"></div>
                      </div>    
    
                      <div class="input-container">
                        <input type="#{type}" id="#group_amount" name = "amount" class="amts" required="required"/>
                        <label for="#group_amount">Amount</label>
                        <div class="bar"></div>
                      </div>
                       
                        <div class="datedivsib">
                        <div class="container">
                        <div class="row">
                            <div class='col-sm-4'>
                                <div class="form-group">
                                    <div class='input-group date' id='datetimepicker2'>
                                        <input type='text' placeholder="Pick a Date and Time" class="form-control" name = "date" id = "date1" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#datetimepicker2').datetimepicker();
                                });
                            </script>
                        </div>
                        </div>
                        </div>
                        


                        <div class="radiodiv">
                        <label for="#{label}">Pick a Category</label>
                        </div>

                        <div class="col-md-8">
                            <div class="col-md-8">

                                <div class="funkyradio" name = "category">
                                    <div class="funkyradio-primary">
                                        <input type="radio" name="radio" id="radio11" />
                                        <label for="radio11">Food</label>
                                    </div>
                                    <div class="funkyradio-success">
                                        <input type="radio" name="radio" id="radio33" />
                                        <label for="radio33">Rent</label>
                                    </div>
                                    <div class="funkyradio-danger">
                                        <input type="radio" name="radio" id="radio44" />
                                        <label for="radio44">Groceries</label>
                                    </div>
                                    <div class="funkyradio-warning">
                                        <input type="radio" name="radio" id="radio55" />
                                        <label for="radio55">Entertainment</label>
                                    </div>
                                    <div class="funkyradio-info">
                                        <input type="radio" name="radio" id="radio22"/>
                                        <label for="radio22">Utilities</label>
                                    </div>
                                    <div class="funkyradio-default">
                                        <input type="radio" name="radio" id="radio66">
                                        <label for="radio66">Others</label>
                                    </div>
                                </div>
                                
                            <input type = "hidden" id = "finalCategory1" name = "finalCategory">
                            
                            <div class="addbutton">
                            
                            <a data-toggle="modal" data-target="#grpModalexp" data-dismiss="modal"><button type="button" class="btn btn-success btn-lg" onclick="getGroup()">Next</button></a>
                        </div>
                            </div>
                        </div>
                        
                    </form>
            </div>
        </div>
    </div>
    <br>
    </div>

    </div>
    </div></div></div>



<div id="myModal_recursive" class="modal fade">
<div class="modal-dialog modal-lg">

    <div class="modal-content">
      <div class="modal-header">
<div class="loginColumns animated fadeInDown">
    <div class="row">

        <div class="col-md-12">
            <center><p class = "logintext2">Setup Recurring Expense</p></center>
        </div>
        <html lang="en">

  
        <div class="col-md-12">
            <div class="wrappingbox">
                    <form method = "post" action = "recurring.php" id = "form3">
                     
                      <div class="input-container">
                        <input type="#{type}" id="rec_name" name = "description" required="required"/>
                        <label for="#{rec_name}"> Description</label>
                        <div class="bar"></div>
                      </div>
                              

                      <div class="input-container">
                        <input type="#{type}" id="rec_amount" name = "amount" required="required"/>
                        <label for="#{rec_amount}">Amount</label>
                        <div class="bar"></div>
                      </div>
                       
                        <div class="datedivsib">
                        <div class="container">
                        <div class="row">
                            <div class='col-sm-4'>
                                <div class="form-group">
                                    <div class='input-group date' id='rec_datetimepicker1'>
                                        <input type='text' placeholder="Pick a Date and Time" class="form-control" name = "date" id = "date" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#rec_datetimepicker1').datetimepicker();
                                });
                            </script>
                        </div>
                        </div>
                        </div>
                        


                        <div class="radiodiv">
                        <label for="#{label}">Frequency</label>
                        </div>

                        <div class="col-md-8">
                            <div class="col-md-8">

                                <div class="funkyradio" name = "category">
                                    <div class="funkyradio-primary">
                                        <input type="radio" name="radio" id="rec_radio1" />
                                        <label for="rec_radio1">Daily</label>
                                    </div>
                                    <div class="funkyradio-success">
                                        <input type="radio" name="radio" id="rec_radio2" />
                                        <label for="rec_radio2">Weekly</label>
                                    </div>
                                    <div class="funkyradio-danger">
                                        <input type="radio" name="radio" id="rec_radio3" />
                                        <label for="rec_radio3">Monthly</label>
                                    </div>
                                </div>
                                
                            <input type = "hidden" id = "rec_finalCategory" name = "finalCategory">
                            
                            <div class="addbutton">
                            <button type="button" class="btn btn-success btn-lg" onclick="recurringExp()">Add</button>
                            </div>
                            </div>
                        </div>
                        
                    </form>
            </div>
        </div>
    </div>
    <br>
</div>

</div>
</div></div></div>
</body>
</html>