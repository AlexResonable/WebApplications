<!DOCTYPE html>
<html lang="en">
    <head>        
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css" type="text/css" />
        <link rel="icon" type="image/png" href="images/logo.png"/>
        <title>LOTOJA</title>
    </head>
    <body>
        <?php
            const MIN_PER_HOUR = 60;
            const TOTAL_DIST = 207;

            // interval distances per checkpoint (miles)
            $intDis = array(1=>44, 43, 41, 37, 42);

            function computeSpeed($distance, $time)
            {
                $speed = ($distance / $time) * MIN_PER_HOUR;
                return $speed;
            }
            $overallSpeed = 0;
            $speed = array();
            $intTimes = array();

            $nameErr="";
            $cp1Err="";
            $cp2Err="";
            $cp3Err="";
            $cp4Err="";
            $cp5Err="";
            $validValue = 0;

            if(!empty($_POST)){
                $error = false;
                if(empty($_POST['name'])){
                    $nameErr="Must enter a name";
                    $error = true;
                }
                foreach($_POST["checkPoint"] as $key => $value){
                    if(!is_numeric($value)){
                        $errorName="cp{$key}Err";
                        $$errorName= "Must enter a number";
                        $error = true;
                        echo $errorName;
                    }
                    else if($validValue >= $value){
                        $errorName="cp{$key}Err";
                        if($errorName == "cp1Err"){
                             $$errorName= "Time must be greater than 0";
                        }
                        else{
                            $$errorName= "Time must be greater than the previous checkpoint";
                        }
                        $error = true;
                    }
                    $validValue = $value;
                }

                if(!$error){
                    $intTimes[1] = $_POST['checkPoint'][1];
                    $intTimes[2] = $_POST['checkPoint'][2] - $_POST['checkPoint'][1];
                    $intTimes[3] = $_POST['checkPoint'][3] - $_POST['checkPoint'][2];
                    $intTimes[4] = $_POST['checkPoint'][4] - $_POST['checkPoint'][3];
                    $intTimes[5] = $_POST['checkPoint'][5] - $_POST['checkPoint'][4];
                    $overallSpeed = computeSpeed(TOTAL_DIST, $_POST['checkPoint'][5]);
                    $speed[1] = computeSpeed($intDis[1], $intTimes[1]);
                    $speed[2] = computeSpeed($intDis[2], $intTimes[2]);
                    $speed[3] = computeSpeed($intDis[3], $intTimes[3]);
                    $speed[4] = computeSpeed($intDis[4], $intTimes[4]);
                    $speed[5] = computeSpeed($intDis[5], $intTimes[5]);
                }
            }
        ?>
        <div class="wrapper">
            <div class="headerWrapper">
                <p><img id="lotoja" src="images/lotoja.jpg" alt="LOTOJA Banner"/></p>
                <h3>Logan, UT to Jackson Hole, WY</h3>
            </div>

            <div class="formWrapper">
                <form action="<?php echo htmlentities($_SERVER['SCRIPT_NAME']) ?>" method="post" enctype="application/x-www-form-urlencoded" name="">
                    <div class="infoWrapper">
                        <p class="input">
                        <label class="field" for="name" >Name: </label>
                        <input class="nameTextbox" type="text" name="name" id="name" value="<?php if(isset($_POST['name'])){ print htmlentities($_POST['name']);}?>" />
                            <span><?php echo $nameErr ?></span></p><br/>

                        <fieldset>
                            <h1><?php echo htmlentities($_SERVER['SCRIPT_NAME']) ?></h1>
                            <legend>Time Completed (min)</legend>

                            <p class="input">
                            <label class="field" for="checkPoint1">Check Point #1</label>
                            <input class="textbox" type="text" name="checkPoint[1]" id="checkPoint1" value="<?php if(isset($_POST['checkPoint'][1])){ print htmlentities($_POST['checkPoint'][1]);}?>" />
                                <span><?php echo $cp1Err ?></span></p><br/>

                            <p class="input">
                            <label class="field" for="checkPoint2">Check Point #2</label>
                            <input class="textbox" type="text" name="checkPoint[2]" id="checkPoint2" value="<?php if(isset($_POST['checkPoint'][2])){ print htmlentities($_POST['checkPoint'][2]);}?>" />
                                <span><?php echo $cp2Err ?></span></p><br/>

                            <p class="input">
                            <label class="field" for="checkPoint3">Check Point #3</label>
                            <input class="textbox" type="text" name="checkPoint[3]" id="checkPoint3" value="<?php if(isset($_POST['checkPoint'][3])){ print htmlentities($_POST['checkPoint'][3]);}?>" />
                                <span><?php echo $cp3Err ?></span></p><br/>

                            <p class="input">
                            <label class="field" for="checkPoint4">Check Point #4</label>
                            <input class="textbox" type="text" name="checkPoint[4]" id="checkPoint4" value="<?php if(isset($_POST['checkPoint'][4])){ print htmlentities($_POST['checkPoint'][4]);}?>" />
                                <span><?php echo $cp4Err ?></span></p><br/>

                            <p class="input">
                            <label class="field" for="checkPoint5">Check Point #5</label>
                            <input class="textbox" type="text" name="checkPoint[5]" id="checkPoint5" value="<?php if(isset($_POST['checkPoint'][5])){ print htmlentities($_POST['checkPoint'][5]);}?>" />
                                <span><?php echo $cp5Err ?></span></p><br/>

                        </fieldset>                   
                        <input class="submit" type="submit" value="submit" />
                    </div>
                </form>
            </div>

            <div class="tableWrapper">
                
                <p class="oSpeed">Overall Speed: <?php if(!empty($_POST)){printf("%.2f %s", $overallSpeed, " mph");} ?></p>
                <table>
                    <caption>
                        Racer: <?php if(!empty($_POST['name'])){$name = htmlentities($_POST['name']); echo $name;} ?>
                    </caption>

                    <tr><th>Check Point</th>    <th>Distance traveled (mi)</th>                                     <th>Interval Times (min)</th>                                           <th>Interval Speeds (mph)</th></tr>
                    <tr><td>1</td>              <td><?php if(!empty($_POST)){echo $intDis[1]." miles";} ?></td>     <td><?php if(isset($intTimes[1])){echo $intTimes[1]." min";} ?></td>    <td><?php if(isset($speed[1])){printf("%.2f %s", $speed[1], " mph");} ?></td></tr>
                    <tr><td>2</td>              <td><?php if(!empty($_POST)){echo $intDis[2]." miles";} ?></td>     <td><?php if(isset($intTimes[2])){echo $intTimes[2]." min";} ?></td>    <td><?php if(isset($speed[2])){printf("%.2f %s", $speed[2], " mph");} ?></td></tr>
                    <tr><td>3</td>              <td><?php if(!empty($_POST)){echo $intDis[3]." miles";} ?></td>     <td><?php if(isset($intTimes[3])){echo $intTimes[3]." min";} ?></td>    <td><?php if(isset($speed[3])){printf("%.2f %s", $speed[3], " mph");} ?></td></tr>
                    <tr><td>4</td>              <td><?php if(!empty($_POST)){echo $intDis[4]." miles";} ?></td>     <td><?php if(isset($intTimes[4])){echo $intTimes[4]." min";} ?></td>    <td><?php if(isset($speed[4])){printf("%.2f %s", $speed[4], " mph");} ?></td></tr>
                    <tr><td>5</td>              <td><?php if(!empty($_POST)){echo $intDis[5]." miles";} ?></td>     <td><?php if(isset($intTimes[5])){echo $intTimes[5]." min";} ?></td>    <td><?php if(isset($speed[5])){printf("%.2f %s", $speed[5], " mph");} ?></td></tr>
                </table>
            </div>
        </div>
    </body>
</html>
