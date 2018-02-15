
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <script src="map_grid.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="main.css">

    
  	</head>
  	<body>


     	<span id="chelu"> </span>

      	<script type="text/javascript">
        	var list = [];

        	//points array stores the selected coordinates
        	var points = new Array(10);
        	for(var i = 0;i < 10; i++) 
        	{
        		points[i] = new Array(2);
        		points[i][0] = 0; points[i][1] = 0;
        	}
        	//Point_counter keeps count of total number of points
        	var point_counter = 0;

        	var lastClicked;
        	var grid = clickableGrid(10,10,function(el,row,col,i)
        	{
            	console.log("You clicked on element:",el);
	            console.log("You clicked on row:",row);
	            console.log("You clicked on col:",col);
	            console.log("You clicked on item #:",i);
	            //console.log(el.textContent);
	            //console.log(el.style.color);
	            if(el.style.color == "blue")
	            {
	            	el.style.backgroundColor = "red";
	            	el.style.color = "white";
	            }
            	else 
            	{
              		el.style.color = "blue";
              		el.style.backgroundColor = "white";
            	}
            	//comsole.log(i);


            	//Limiting Condition for exiting
	            if(i == 100)
	            {
		            console.log("okayy, Element List Finalized");
	            	simulate(list, points, point_counter);
	            }
	            else
	            {

	            	if(list.includes(i)) 
	            	{
	                	var index = list.indexOf(i);
	                	if (index > -1) 
	                	{
	                		list.splice(index, 1);
	                	}
	              	}
	             	else
	              	{
	                	list.push(i);

	                	points[point_counter][0] = row;
	                	points[point_counter][1] = col;
	                	point_counter++;

	              	}

	              	console.log(list);
	              
	              	el.className='clicked';
	              	if (lastClicked) lastClicked.className='';
	              	lastClicked = el;
	            }



            
        });
        console.log(list);
       	document.body.appendChild(grid);
             
        //Funtion to generate a clickable grid
        function clickableGrid( rows, cols, callback ){
            var i=0;
            var grid = document.createElement('table');
            grid.className = 'grid';
            for (var r=0;r<rows;++r){
                var tr = grid.appendChild(document.createElement('tr'));
                for (var c=0;c<cols;++c){
                    var cell = tr.appendChild(document.createElement('td'));
                    cell.innerHTML = ++i;
                    cell.style.color = "blue";
                    cell.addEventListener('click',(function(el,r,c,i){
                        return function(){
                            callback(el,r,c,i);
                        }
                    })(cell,r,c,i),false);
                }
            }
            return grid;
        }


        function simulate(list, points, point_counter)
        {
        	console.log(list);
          	document.getElementById("chelu").innerHTML = "#"+list;
          	console.log(point_counter);
          	console.log(points);
        }
      </script>


  </body>
</html>
