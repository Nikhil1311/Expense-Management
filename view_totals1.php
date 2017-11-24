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

    <link rel="stylesheet" href="css/filter_css.css">
    <link rel="stylesheet" href="css/filter_css_chart_table.css">
    <script src="js/modernizr.js"></script>

    <style>
        body
        {
            background-color: white;
        }
    </style>

    <script>
        number_of_groups = 0
        group_names_list = []
        function runQueries()
        {
            xhr1 = new XMLHttpRequest();
            xhr1.onreadystatechange = get_groups;
            xhr1.open("GET", "get_groups.php", true);
            xhr1.send();
        }

        function get_groups()
        {
            if(xhr1.readyState == 4 && xhr1.status == 200)
            {
                console.log(JSON.parse(xhr1.responseText));
                group_names = JSON.parse(xhr1.responseText);
                number_of_groups = group_names.length;
                group_names_list = JSON.parse(xhr1.responseText);
                for(i = 1;i < group_names.length + 1;)
                { 
                    i = i + 1
                    li = document.createElement("li");

                    inp = document.createElement("input");
                    inp.className = "filter";
                    inp.setAttribute("data-filter", ".check" + i);
                    inp.setAttribute("type", "checkbox");
                    inp.setAttribute("id", "check" + i);
                    inp.checked = true;
                    inp.onchange = update;

                    lb = document.createElement("label");
                    lb.className = "checkbox-label";
                    lb.setAttribute("for", inp.id);
                    lb.innerHTML = group_names[i - 2][1];

                    li.append(inp);
                    li.append(lb);

                    document.getElementById("list_groups").appendChild(li);
                }
                update();
            }
        }

        function update()
        {
            xhr = new XMLHttpRequest();
            xhr.onreadystatechange = change_graphs;
            xhr.open("POST", "totals_for_graph.php", true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            param = "";
            var categories = ['', 'food', 'utilities', 'rent', 'groceries', 'entertainment', 'others'];

            // filter on categories
            for(i = 1;i<=6;i++)
            {
                if(document.getElementById("checkbox" + i).checked)
                {
                    if(param != "")
                        param += "&" + categories[i] + "=1";

                    else
                        param += categories[i] + "=1"
                }
                else
                {
                    if(param != "")
                        param += "&" + categories[i] + "=0";

                    else
                        param += categories[i] + "=0";  
                }
            }

            // end of filter on categories


            // filter based on groups
            if(document.getElementById("check1").checked)
            {
                if(param != "")
                    param += "&personal=1";

                else
                    param += "personal=1";
            }

            else
            {
                if(param != "")
                    param += "&personal=0";

                else
                    param += "personal=0";   
            }
            
            selected_group_id_list = ""
            for(i = 0;i<number_of_groups;i++)
            {
                if(document.getElementById("check" + (i + 2)).checked)
                {
                    if(selected_group_id_list == "")
                        selected_group_id_list += group_names_list[i][0];

                    else
                        selected_group_id_list += "," + group_names_list[i][0];
                }
            }

            if(selected_group_id_list != "")
            {
                selected_group_id_list = "group_list=" + selected_group_id_list;
                if(param != "")
                {
                    param += "&" + selected_group_id_list;
                }
            }

            if(param == "")
            {
                param = selected_group_id_list;
            }
            // end of filter on groups

            console.log(param);
            xhr.send(param);
        }

        function change_graphs()
        {
            if(xhr.readyState == 4 && xhr.status == 200)
            {  
                var to_final_plot = {};
                if(xhr.responseText.length > 0)
                {  
                    response_for_graph = JSON.parse(xhr.responseText);
                    console.log(response_for_graph);
                    if(document.getElementById("total_by_category").checked == true)
                    {
                        var categories = ['food', 'utilities', 'rent', 'groceries', 'entertainment', 'others'];
                        var totals = {};

                        for(i = 0;i<categories.length;i++)
                        {
                            totals[categories[i]] = 0;
                        }

                        for(i = 0;i<response_for_graph.length;i++)
                        {
                            // a = totals[response_for_graph[i][1]];
                            // totals[response_for_graph[i][2]] += a;
                            totals[response_for_graph[i][2]] += parseFloat(response_for_graph[i][1]);
                        }
                        to_final_plot = totals;
                    }

                    else
                    {
                        var totals = {"personal" : 0};
                        var group_id_mapping = {0 : "personal"};
                        for(i = 0;i<number_of_groups;i++)
                        {
                            totals[group_names_list[i][1]] = 0;
                            group_id_mapping[group_names_list[i][0]] = group_names_list[i][1];
                        }

                        console.log(group_id_mapping);

                        for(i = 0;i<response_for_graph.length;i++)
                        {
                            x = group_id_mapping[response_for_graph[i][0]];
                            totals[x] += parseFloat(response_for_graph[i][1]);
                        }
                        to_final_plot = totals;
                    }
                    final_plot(to_final_plot);
                }
            }
        }

        function final_plot(data)
        {
            console.log(data);
            func(data);
        }



        function func(data)
        {
            // data = {"fruits" : 1000, "utilities" : 1234, "rent" : 0, "groceries" : 0, "entertainment" : 0, "others" : 3111};
        	var i=1;
			for(var key in data)
			{
				data[key] = data[key].toFixed(2);
				
			}

        	var tab = document.getElementById("chartData");
        	tab.innerHTML = "";

            // cent_1 = document.createElement("center");
            // cent_2 = document.createElement("center");

            head1 = document.createElement("tr");

            row_head_1 = document.createElement("th");
            row_head_1.innerHTML = "Category";
            // cent_1.appendChild(row_head_1);

            row_head_2 = document.createElement("th");
            row_head_2.innerHTML = "Amount";
            // cent_2.appendChild(row_head_2);

            head1.appendChild(row_head_1);
            head1.appendChild(row_head_2);

            tab.appendChild(head1);
            
            // var r = tab.insertRow(-1);
            // var head = tab.createTHead();
            // var c = r.insertCell(0);
            // c.innerHTML = "Category";
            // var c1 = r.insertCell(1);
            // c1.innerHTML = "Amount";
            // r.className = "col-xs-6 col-md-6 col-lg-6";
            // r.appendChild(head1);
            // r.appendChild(head2);

        	for(var key in data)
        	{
                row = document.createElement("tr");

                td_1 = document.createElement("td");
                td_2 = document.createElement("td");

                td_1.innerHTML = cap(key);
                td_2.innerHTML = data[key];

                row.style.color = getRandomColor();
                row.appendChild(td_1);
                row.appendChild(td_2);

                tab.appendChild(row);

            	// var row = tab.insertRow(i);
            	// var cell1 = row.insertCell(0);
            	// cell1.innerHTML = cap(key);
            	// var cell2 = row.insertCell(1);
            	// cell2.innerHTML = data[key];
            	// row.style.color = getRandomColor();
            	// row.className = "col-xs-6 col-md-6 col-lg-6";
            	// i++;
      		}
  			pieChart();
		}

		function getRandomColor()
		{
		  var letters = '0123456789ABCDEF';
		  var color = '#';
		  for (var i = 0; i < 6; i++)
		  {
		    color += letters[Math.floor(Math.random() * 16)];
		  }
		  return color;
		}

		function cap(string)
		{
		  return string.charAt(0).toUpperCase() + string.slice(1);
		}

		function pieChart()
		{

		  // Config settings
		  var chartSizePercent = 55;                        // The chart radius relative to the canvas width/height (in percent)
		  var sliceBorderWidth = 1;                         // Width (in pixels) of the border around each slice
		  var sliceBorderStyle = "#fff";                    // Colour of the border around each slice
		  var sliceGradientColour = "#ddd";                 // Colour to use for one end of the chart gradient
		  var maxPullOutDistance = 25;                      // How far, in pixels, to pull slices out when clicked
		  var pullOutFrameStep = 4;                         // How many pixels to move a slice with each animation frame
		  var pullOutFrameInterval = 40;                    // How long (in ms) between each animation frame
		  var pullOutLabelPadding = 65;                     // Padding between pulled-out slice and its label  
		  var pullOutLabelFont = "bold 16px 'Trebuchet MS', Verdana, sans-serif";  // Pull-out slice label font
		  var pullOutValueFont = "bold 12px 'Trebuchet MS', Verdana, sans-serif";  // Pull-out slice value font
		  var pullOutValuePrefix = "Rs.";                     // Pull-out slice value prefix
		  var pullOutShadowColour = "rgba( 0, 0, 0, .5 )";  // Colour to use for the pull-out slice shadow
		  var pullOutShadowOffsetX = 5;                     // X-offset (in pixels) of the pull-out slice shadow
		  var pullOutShadowOffsetY = 5;                     // Y-offset (in pixels) of the pull-out slice shadow
		  var pullOutShadowBlur = 5;                        // How much to blur the pull-out slice shadow
		  var pullOutBorderWidth = 2;                       // Width (in pixels) of the pull-out slice border
		  var pullOutBorderStyle = "#333";                  // Colour of the pull-out slice border
		  var chartStartAngle = -.5 * Math.PI;              // Start the chart at 12 o'clock instead of 3 o'clock

		  // Declare some variables for the chart
		  var canvas;                       // The canvas element in the page
		  var currentPullOutSlice = -1;     // The slice currently pulled out (-1 = no slice)
		  var currentPullOutDistance = 0;   // How many pixels the pulled-out slice is currently pulled out in the animation
		  var animationId = 0;              // Tracks the interval ID for the animation created by setInterval()
		  var chartData = [];               // Chart data (labels, values, and angles)
		  var chartColours = [];            // Chart colours (pulled from the HTML table)
		  var totalValue = 0;               // Total of all the values in the chart
		  var canvasWidth;                  // Width of the canvas, in pixels
		  var canvasHeight;                 // Height of the canvas, in pixels
		  var centreX;                      // X-coordinate of centre of the canvas/chart
		  var centreY;                      // Y-coordinate of centre of the canvas/chart
		  var chartRadius;                  // Radius of the pie chart, in pixels

		  // Set things up and draw the chart
		  init();


  /**
   * Set up the chart data and colours, as well as the chart and table click handlers,
   * and draw the initial pie chart
   */

		  function init()
		  {

		    // Get the canvas element in the page
		    canvas = document.getElementById('chart');

		    // Exit if the browser isn't canvas-capable
		    if ( typeof canvas.getContext === 'undefined' ) return;

		    // Initialise some properties of the canvas and chart
		    canvasWidth = canvas.width;
		    canvasHeight = canvas.height;
		    centreX = canvasWidth / 2;
		    centreY = canvasHeight / 2;
		    chartRadius = Math.min( canvasWidth, canvasHeight ) / 2 * ( chartSizePercent / 100 );

		    // Grab the data from the table,
		    // and assign click handlers to the table data cells
		    
		    var currentRow = -1;
		    var currentCell = 0;

		    $('#chartData td').each( function() {
		      currentCell++;
		      if ( currentCell % 2 != 0 ) {
		        currentRow++;
		        chartData[currentRow] = [];
		        chartData[currentRow]['label'] = $(this).text();
		      } else {
		       var value = parseFloat($(this).text());
		       totalValue += value;
		       value = value.toFixed(2);
		       chartData[currentRow]['value'] = value;
		      }

		      // Store the slice index in this cell, and attach a click handler to it
		      $(this).data( 'slice', currentRow );
		      $(this).click( handleTableClick );

		      // Extract and store the cell colour
		      if ( rgb = $(this).css('color').match( /rgb\((\d+), (\d+), (\d+)/) ) {
		        chartColours[currentRow] = [ rgb[1], rgb[2], rgb[3] ];
		      } else if ( hex = $(this).css('color').match(/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/) ) {
		        chartColours[currentRow] = [ parseInt(hex[1],16) ,parseInt(hex[2],16), parseInt(hex[3], 16) ];
		      } else {
		        alert( "Error: Colour could not be determined! Please specify table colours using the format '#xxxxxx'" );
		        return;
		      }

		    } );

		    // Now compute and store the start and end angles of each slice in the chart data

		    var currentPos = 0; // The current position of the slice in the pie (from 0 to 1)

		    for ( var slice in chartData ) {
		      chartData[slice]['startAngle'] = 2 * Math.PI * currentPos;
		      chartData[slice]['endAngle'] = 2 * Math.PI * ( currentPos + ( chartData[slice]['value'] / totalValue ) );
		      currentPos += chartData[slice]['value'] / totalValue;
		    }

		    // All ready! Now draw the pie chart, and add the click handler to it
		    drawChart();
		    $('#chart').click ( handleChartClick );
		  }


  /**
   * Process mouse clicks in the chart area.
   *
   * If a slice was clicked, toggle it in or out.
   * If the user clicked outside the pie, push any slices back in.
   *
   * @param Event The click event
   */

  function handleChartClick ( clickEvent ) {

    // Get the mouse cursor position at the time of the click, relative to the canvas
    var mouseX = clickEvent.pageX - this.offsetLeft;
    var mouseY = clickEvent.pageY - this.offsetTop;

    // Was the click inside the pie chart?
    var xFromCentre = mouseX - centreX;
    var yFromCentre = mouseY - centreY;
    var distanceFromCentre = Math.sqrt( Math.pow( Math.abs( xFromCentre ), 2 ) + Math.pow( Math.abs( yFromCentre ), 2 ) );

    if ( distanceFromCentre <= chartRadius ) {

      // Yes, the click was inside the chart.
      // Find the slice that was clicked by comparing angles relative to the chart centre.

      var clickAngle = Math.atan2( yFromCentre, xFromCentre ) - chartStartAngle;
      if ( clickAngle < 0 ) clickAngle = 2 * Math.PI + clickAngle;
                  
      for ( var slice in chartData ) {
        if ( clickAngle >= chartData[slice]['startAngle'] && clickAngle <= chartData[slice]['endAngle'] ) {

          // Slice found. Pull it out or push it in, as required.
          toggleSlice ( slice );
          return;
        }
      }
    }

    // User must have clicked outside the pie. Push any pulled-out slice back in.
    pushIn();
  }


  /**
   * Process mouse clicks in the table area.
   *
   * Retrieve the slice number from the jQuery data stored in the
   * clicked table cell, then toggle the slice
   *
   * @param Event The click event
   */

  function handleTableClick ( clickEvent ) {
    var slice = $(this).data('slice');
    toggleSlice ( slice );
  }


  /**
   * Push a slice in or out.
   *
   * If it's already pulled out, push it in. Otherwise, pull it out.
   *
   * @param Number The slice index (between 0 and the number of slices - 1)
   */

  function toggleSlice ( slice ) {
    if ( slice == currentPullOutSlice ) {
      pushIn();
    } else {
      startPullOut ( slice );
    }
  }

 
  /**
   * Start pulling a slice out from the pie.
   *
   * @param Number The slice index (between 0 and the number of slices - 1)
   */

  function startPullOut ( slice ) {

    // Exit if we're already pulling out this slice
    if ( currentPullOutSlice == slice ) return;

    // Record the slice that we're pulling out, clear any previous animation, then start the animation
    currentPullOutSlice = slice;
    currentPullOutDistance = 0;
    clearInterval( animationId );
    animationId = setInterval( function() { animatePullOut( slice ); }, pullOutFrameInterval );

    // Highlight the corresponding row in the key table
    $('#chartData td').removeClass('highlight');
    var labelCell = $('#chartData td:eq(' + (slice*2) + ')');
    var valueCell = $('#chartData td:eq(' + (slice*2+1) + ')');
    labelCell.addClass('highlight');
    valueCell.addClass('highlight');
  }

 
  /**
   * Draw a frame of the pull-out animation.
   *
   * @param Number The index of the slice being pulled out
   */

  function animatePullOut ( slice ) {

    // Pull the slice out some more
    currentPullOutDistance += pullOutFrameStep;

    // If we've pulled it right out, stop animating
    if ( currentPullOutDistance >= maxPullOutDistance ) {
      clearInterval( animationId );
      return;
    }

    // Draw the frame
    drawChart();
  }

 
  /**
   * Push any pulled-out slice back in.
   *
   * Resets the animation variables and redraws the chart.
   * Also un-highlights all rows in the table.
   */

  function pushIn() {
    currentPullOutSlice = -1;
    currentPullOutDistance = 0;
    clearInterval( animationId );
    drawChart();
    $('#chartData td').removeClass('highlight');
  }
 
 
  /**
   * Draw the chart.
   *
   * Loop through each slice of the pie, and draw it.
   */

  function drawChart() {

    // Get a drawing context
    var context = canvas.getContext('2d');
        
    // Clear the canvas, ready for the new frame
    context.clearRect ( 0, 0, canvasWidth, canvasHeight );

    // Draw each slice of the chart, skipping the pull-out slice (if any)
    for ( var slice in chartData ) {
      if ( slice != currentPullOutSlice ) drawSlice( context, slice );
    }

    // If there's a pull-out slice in effect, draw it.
    // (We draw the pull-out slice last so its drop shadow doesn't get painted over.)
    if ( currentPullOutSlice != -1 ) drawSlice( context, currentPullOutSlice );
  }


  /**
   * Draw an individual slice in the chart.
   *
   * @param Context A canvas context to draw on  
   * @param Number The index of the slice to draw
   */

  function drawSlice ( context, slice ) {

    // Compute the adjusted start and end angles for the slice
    var startAngle = chartData[slice]['startAngle']  + chartStartAngle;
    var endAngle = chartData[slice]['endAngle']  + chartStartAngle;
      
    if ( slice == currentPullOutSlice ) {

      // We're pulling (or have pulled) this slice out.
      // Offset it from the pie centre, draw the text label,
      // and add a drop shadow.

      var midAngle = (startAngle + endAngle) / 2;
      var actualPullOutDistance = currentPullOutDistance * easeOut( currentPullOutDistance/maxPullOutDistance, .8 );
      startX = centreX + Math.cos(midAngle) * actualPullOutDistance;
      startY = centreY + Math.sin(midAngle) * actualPullOutDistance;
      context.fillStyle = 'rgb(' + chartColours[slice].join(',') + ')';
      context.textAlign = "center";
      context.font = pullOutLabelFont;
      context.fillText( chartData[slice]['label'], centreX + Math.cos(midAngle) * ( chartRadius + maxPullOutDistance + pullOutLabelPadding ), centreY + Math.sin(midAngle) * ( chartRadius + maxPullOutDistance + pullOutLabelPadding ) );
      context.font = pullOutValueFont;
      context.fillText( pullOutValuePrefix + chartData[slice]['value'] + " (" + ( parseInt( chartData[slice]['value'] / totalValue * 100 + .5 ) ) +  "%)", centreX + Math.cos(midAngle) * ( chartRadius + maxPullOutDistance + pullOutLabelPadding ), centreY + Math.sin(midAngle) * ( chartRadius + maxPullOutDistance + pullOutLabelPadding ) + 20 );
      context.shadowOffsetX = pullOutShadowOffsetX;
      context.shadowOffsetY = pullOutShadowOffsetY;
      context.shadowBlur = pullOutShadowBlur;

    } else {

      // This slice isn't pulled out, so draw it from the pie centre
      startX = centreX;
      startY = centreY;
    }

    // Set up the gradient fill for the slice
    var sliceGradient = context.createLinearGradient( 0, 0, canvasWidth*.75, canvasHeight*.75 );
    sliceGradient.addColorStop( 0, sliceGradientColour );
    sliceGradient.addColorStop( 1, 'rgb(' + chartColours[slice].join(',') + ')' );

    // Draw the slice
    context.beginPath();
    context.moveTo( startX, startY );
    context.arc( startX, startY, chartRadius, startAngle, endAngle, false );
    context.lineTo( startX, startY );
    context.closePath();
    context.fillStyle = sliceGradient;
    context.shadowColor = ( slice == currentPullOutSlice ) ? pullOutShadowColour : "rgba( 0, 0, 0, 0 )";
    context.fill();
    context.shadowColor = "rgba( 0, 0, 0, 0 )";

    // Style the slice border appropriately
    if ( slice == currentPullOutSlice ) {
      context.lineWidth = pullOutBorderWidth;
      context.strokeStyle = pullOutBorderStyle;
    } else {
      context.lineWidth = sliceBorderWidth;
      context.strokeStyle = sliceBorderStyle;
    }

    // Draw the slice border
    context.stroke();
  }


  /**
   * Easing function.
   *
   * A bit hacky but it seems to work! (Note to self: Re-read my school maths books sometime)
   *
   * @param Number The ratio of the current distance travelled to the maximum distance
   * @param Number The power (higher numbers = more gradual easing)
   * @return Number The new ratio
   */

  function easeOut( ratio, power ) {
    return ( Math.pow ( 1 - ratio, power ) + 1 );
  }

};

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
                        <li><a href = "user_home.php">Home</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="login.html" class="smoothScroll"><span class="icon icon-settings" style="font-size:15px;"> &nbsp</span>Settings</a></li>
                        <li><a href="signup.html" class="smoothScroll"><span class="icon icon-user-minus" style="font-size:15px;">&nbsp</span>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


            <main>
                <div class="cd-filter">
                    <form style = "margin-top:60px">
                        <div class="cd-filter-block">
                            <h4>Category</h4>

                            <ul class="cd-filter-content cd-filters list">
                                <li>
                                    <input class="filter" data-filter=".check1" type="checkbox" id="checkbox1" onchange = "update()" checked>
                                    <label class="checkbox-label" for="checkbox1">Food</label>
                                </li>

                                <li>
                                    <input class="filter" data-filter=".check2" type="checkbox" id="checkbox2" onchange = "update()" checked>
                                    <label class="checkbox-label" for="checkbox2">Utilities</label>
                                </li>

                                <li>
                                    <input class="filter" data-filter=".check3" type="checkbox" id="checkbox3" onchange = "update()" checked>
                                    <label class="checkbox-label" for="checkbox3">Rent</label>
                                </li>

                                <li>
                                    <input class="filter" data-filter=".check4" type="checkbox" id="checkbox4" onchange = "update()" checked>
                                    <label class="checkbox-label" for="checkbox4">Groceries</label>
                                </li>

                                <li>
                                    <input class="filter" data-filter=".check5" type="checkbox" id="checkbox5" onchange = "update()" checked>
                                    <label class="checkbox-label" for="checkbox5">Entertainment</label>
                                </li>

                                <li>
                                    <input class="filter" data-filter=".check6" type="checkbox" id="checkbox6" onchange = "update()" checked>
                                    <label class="checkbox-label" for="checkbox6">Others</label>
                                </li>
                            </ul> 
                        </div> 

                        <div class="cd-filter-block">
                            <h4>Group Name</h4>
                            <ul class="cd-filter-content cd-filters list" id = "list_groups">
                                <li>
                                    <input class="filter" data-filter=".check1" type="checkbox" id="check1" onchange = "update()" checked>
                                    <label class="checkbox-label" for="check1">Personal</label>
                                </li>
                            </ul> 
                        </div> 

                        <div class="cd-filter-block">
                            <h4>Total By</h4>

                            <ul class="cd-filter-content cd-filters list">
                                <li>
                                    <input class="filter" data-filter="" type="radio" name="radioButton" id="total_by_category" checked onclick = "update()">
                                    <label class="radio-label" for="radio1">Category</label>
                                </li>

                                <li>
                                    <input class="filter" data-filter=".radio2" type="radio" name="radioButton" id="total_by_group" onclick = "update()">
                                    <label class="radio-label" for="radio2">Group & Personal</label>
                                </li>
                            </ul> <!-- cd-filter-content -->
                        </div> <!-- cd-filter-block -->


                    </form>

                    <a href="#0" class="cd-close" style = "margin-top:60px">Close</a>
                </div>

                <a href="#0" class="cd-filter-trigger" style = "margin-top:60px">Filters</a>
            </main>

            <script src="js/jquery-2.1.1.js"></script>
            <script src="js/jquery.mixitup.min.js"></script>
            <script src="js/main.js"></script>   



        <div class = "container" id = "chart_table_holder" style = "margin-top:100px">
        <div class = "row">
        <center>
          <div id="pie" class = "col-lg-6">
            <canvas id="chart" class = "chart_expense" width = "410 px" height = "400px"></canvas> <!-- width="600" height="500" -->
          </div>

          <div id="tab" class = "col-lg-6" style = "border:none;margin-top:100px">
              <table id="chartData" class ="table table-striped">
              </table>
          </div>
        </center>
        </div>
        </div>
</body>
</html>