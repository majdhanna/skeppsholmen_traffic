<!DOCTYPE html>
<html>
<head>
	<title>Time Tabel</title>
	<link rel="stylesheet" type="text/css" href="Style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
</head>

<body>


       
<br/>
<br/>
<br/>
<div class="right">
	<span id="bus" class="text"></span>
    <br/>
	<span id="demo1" class="time"></span>
</div>


<div class="left">
	<span id="boat" class="text"></span>
    <br/>
	<span id="demo2" class="time"></span>
</div>



<div class="imgRight">
    <img class="img_bus" src="bus.svg" alt="bus">
</div>

<div class="imgLeft">
    <img class="img_boat" src="boat.svg" alt="boat">
</div>





<script>  
    $(function () {
    var body = $('body');
    var text = $('.text');
    var time = $('.time');
    var backgrounds = [
      'url(skeppsholmen-01.svg)',
      'url(skeppsholmen-02.svg)', 
      'url(skeppsholmen-03.svg)'];
    var current;

    var txt_size , txt_style , txt_weight , txt_color;
    var tim_size , tim_style , tim_weight , tim_color;

    function setBackground() {
        var d = new Date();
        var h = d.getHours();
        var n=parseInt(h);
        console.log(n);
        
        if (5<=n && n<=12) {
            console.log('skeppsholmen-01');
        current=0;
        txt_color = "#838B8B";

        tim_color = "#838B8B";
        }
        else if (13<=n && n<=20){
            console.log('skeppsholmen-02');
        current=1;
        txt_color = "#838B8B"; 

        tim_color = "#838B8B";
        }
        else {
            console.log('skeppsholmen-03');
        current=2;
        txt_color = "#838B8B"; 
        tim_color = "#838B8B";
        }
       
        

    setTimeout(setBackground, 20000000);
    }
    setBackground();
     body.css({'background':backgrounds[current],
        'background-repeat':"no-repeat",
         'background-attachment': "fixed",
        'background-size':"cover"
    });

    text.css({'color':txt_color});

    time.css({'color':tim_color});

     });  
</script>

<script>
    var global_sl_bus_time;
    var global_sl_boat_time;

    var sl_bus_h;
    var sl_bus_m;
    var sl_bus_s; 

    var sl_boat_h;
    var sl_boat_m;
    var sl_boat_s;

    var sl_bus_cancelled;
    var sl_boat_cancelled;

    var sl_bus_error;
    var sl_boat_error;

    getLocation();
    myTimer(global_sl_bus_time,global_sl_boat_time);

    setInterval(function(){ myTimer(global_sl_bus_time,global_sl_boat_time); }, 500); 
    setInterval(function(){ getLocation(); }, 10000);
    
    function getLocation() {
        console.log('getLocation');
        if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success);            
        } else { 
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
        }

    function success(pos){

    console.log('pos');
    var from_bus= "<?php echo "NEXT BUS FROM CENTRAL STATION LEAVES IN:"; ?>";
    var from_boat= "<?php echo "NEXT BOAT FROM SLUSSEN LEAVES IN:"; ?>";
    var to_bus= "<?php echo "NEXT BUS TO CENTRAL STATION LEAVES IN:"; ?>";
    var to_boat="<?php echo "NEXT BOAT TO SLUSSEN LEAVES IN:"; ?>";

    var crd = pos.coords;
        console.log(crd.latitude);
        console.log(crd.longitude);

    if ((59.327>crd.latitude && crd.latitude >59.323)&&
        (18.09>crd.longitude &&crd.longitude>18.07)){

        console.log('take json go');
        document.getElementById("bus").innerHTML= to_bus;
        document.getElementById("boat").innerHTML= to_boat ;

        $.getJSON("sl_time_table_go.php", function(data) {
            console.log(data);
        sl_bus_h = data.bus_hour;
        sl_bus_m = data.bus_minute;
        sl_bus_s = data.bus_second;

        sl_boat_h = data.boat_hour;
        sl_boat_m = data.boat_minute;
        sl_boat_s = data.boat_second; 

        sl_bus_cancelled=data.cancelled1;
        sl_bus_error=data.error1;

        sl_boat_cancelled = data.cancelled2;
        sl_boat_error = data.error2;

       }); 

        update_sltime();
        
        }
        else{
        console.log('take json come');
        document.getElementById("bus").innerHTML= from_bus ;
        document.getElementById("boat").innerHTML= from_boat;
                            
        $.getJSON('sl_time_table_come.php', function(data) {
        console.log(data);
        sl_bus_h = data.bus_hour;
        sl_bus_m = data.bus_minute;
        sl_bus_s = data.bus_second;

        sl_boat_h = data.boat_hour;
        sl_boat_m = data.boat_minute;
        sl_boat_s = data.boat_second;

        sl_bus_cancelled=data.cancelled1;
        sl_bus_error=data.error1;

        sl_boat_cancelled = data.cancelled2;
        sl_boat_error = data.error2;     
        });
      update_sltime();
    }

    
    }
    
    function update_sltime() {

    // calculate sl bus time in milliseconds
        

        if(sl_bus_cancelled){
            document.getElementById("demo1").innerHTML = sl_bus_cancelled;
        }

        else if(sl_bus_error){
            document.getElementById("demo1").innerHTML = sl_bus_error;
        }
        else{
            sl_bus_s = sl_bus_s * 1000;
            sl_bus_m = sl_bus_m * 60000;
            sl_bus_h = sl_bus_h * 3600000;
            var sl_bus_time = sl_bus_h + sl_bus_m + sl_bus_s;
            global_sl_bus_time = sl_bus_time;
        }
        

        // calculate sl boat time in millisecounds
        if(sl_boat_cancelled){
        document.getElementById("demo2").innerHTML = sl_boat_cancelled;
         console.log("test2");
            }
        
        else if(sl_boat_error){
            document.getElementById("demo2").innerHTML = sl_boat_error;
        }

        else{
            sl_boat_s = sl_boat_s * 1000;
            sl_boat_m = sl_boat_m * 60000;
            sl_boat_h = sl_boat_h * 3600000;
            var sl_boat_time = sl_boat_h + sl_boat_m + sl_boat_s;
            global_sl_boat_time = sl_boat_time;
        }

    }

    function myTimer(sl_bus_time,sl_boat_time) {

        // calculate real time in milliscounds
        var date= new Date();
        var cur_h= date.getHours();
        var cur_m= date.getMinutes();
        var cur_s= date.getSeconds();

        cur_s = cur_s * 1000;
        cur_m = cur_m * 60000;
        cur_h = cur_h * 3600000;
        var cur_time = cur_h + cur_m + cur_s;
 
        // subtract time for bus and print it
        var ms_bus = sl_bus_time - cur_time;
        var seconds_bus = ms_bus / 1000; 
        var hours_bus = parseInt( seconds_bus / 3600 );     
        seconds_bus = seconds_bus % 3600;     
        var minutes_bus = parseInt( seconds_bus / 60 );
        seconds_bus = seconds_bus % 60;

        seconds_bus = checkTime(seconds_bus);
        hours_bus = checkTime(hours_bus);
        minutes_bus = checkTime(minutes_bus);
        if((ms_bus<=0) && (ms_bus => -50000)){
            document.getElementById("demo1").innerHTML = "Nu";       
                
          }

        if(ms_bus>0){
            document.getElementById("demo1").innerHTML = hours_bus+":"+minutes_bus+":"+seconds_bus;
        }
        if (ms_bus<-50000){
                document.getElementById("demo2").innerHTML = "Error..";
            }

        // subtract time for boat and print it

        var ms_boat = sl_boat_time - cur_time;

        var seconds_boat = ms_boat / 1000;
        var hours_boat = parseInt( seconds_boat / 3600 );     
        seconds_boat = seconds_boat % 3600; 
        var minutes_boat = parseInt( seconds_boat / 60 );
        seconds_boat = seconds_boat % 60;

        seconds_boat = checkTime(seconds_boat);
        minutes_boat = checkTime(minutes_boat);
        hours_boat = checkTime(hours_boat);

        console.log(ms_boat);


           if(ms_boat>0){
            console.log(minutes_boat);
            document.getElementById("demo2").innerHTML = hours_boat+":"+minutes_boat+":"+seconds_boat;
            }
            if((ms_boat<=0) && (ms_boat => -1000000)){
                document.getElementById("demo2").innerHTML = "Nu";
            }

            if (ms_boat<-1000000){
                document.getElementById("demo2").innerHTML = "Error..";
            }
    }

    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
        }
</script>


</body>
</html>