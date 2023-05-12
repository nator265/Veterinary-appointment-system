<?php
session_start();
include('../connect.php');
include('../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
}
// to set global variable for the form to access
if(isset($_GET['edit'])){
    $_SESSION['idforedit'] = $_GET['edit'];
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style2.css">
    <title>Appointments</title>
</head>
<body>
   
    <!-- these are columns -->
    <div class="flex-container">
        
    <!-- this is a shadow that make the first column come out -->
    <div class="shadow"></div>

        <div class="column1">
            <div class="company-name">
                Veterinary
            </div>
            <div class="links-container">
                <div class="link">
                    <a href="index.php"> <span id='link'> Dashboard </span> </a>
                </div>
                <div class="link">
                    <span id='link'> Appointments </span>
                </div>
                <div class="link">
                    <a href="notifications.php"><span id='link'> Notifications </span> </a>
                </div>
                <div class="link">
                    <a href="logout.php" style="text-decoration: none; color: white">
                        <button class="logout" id="bttn">Logout</button>
                    </a>
                </div>
            </div>
        </div>

        <!-- thi is the modal for the appointments registration -->
        <div class="modal-container" id="modal-container">
            <div class="modal">
                <div class="close"><a href="appointments.php">&times;</a></div>
               
                <div class="form-container">
                    <div class="form-header">
                        <h1 style="text-align: center; color: white;">
                            Book An Appointment.
                        </h1>
                    </div>
                    <form action="appointments.php" method="POST" onsubmit="return validateForm(this);">
                        <div class="pushcontainer">
                            <div class="pushleft">
                                <input type="text" name="fullname" id="fullname" placeholder="Owner Name(Fullname)" value="<?php echo $_SESSION['fullname']?>" required>
                                <br>
                                <br>
                                <span style="color:white;"> Select Animal Type</span>
                                <br>
                                <div class="type">
                                    <select name="field" id="field" required value="<?php echo $_SESSION['field'] ?>">
                                        <option hidden><?php echo $_SESSION['field']?></option>
                                        <option value="pet">Pet</option>
                                        <option value="livestock">Livestock</option>
                                    </select>                        
                                    <input type="text" name="animal" id="animal" placeholder="Pet e.g. Dog | Livestock e.g. Cow" value="<?php echo $_SESSION['animal'] ?>" required>
                                </div>
                                <br>
                                <input type="date" name="ap_date" id="date" required value="<?php echo $_SESSION['date']?>" required>
                                <br>
                            </div>
                            <div class="pushright">
                                <div style="margin-bottom: 5px;" id="msg" style="color: red;" >Select Service:</div>
                                <?php
                                    $checked_arr = array();

                                    // Fetch checked values
                                    $fetchLang = mysqli_query($conn,"SELECT * FROM appointments where ap_id = '".$_SESSION['idforedit']."'");
                                    if(mysqli_num_rows($fetchLang) > 0){
                                          $result = mysqli_fetch_assoc($fetchLang);
                                          $checked_arr = explode(", ",$result['ap_type']);
                                    }
                           
                                    // Create checkboxes
                                    $service = "SELECT * FROM service_costs";
                                    $runservice = mysqli_query($conn, $service);
                                    checkSQL($conn, $runservice);
                                    $service_row = mysqli_num_rows($runservice);
                                    if (!$runservice){
                                        die("Invalid query: " .$conn->error);
                                    }                  
                                    $values = [];
                                    $price = [];
                                    // reading data contained in each row
                                    while($service_row = $runservice->fetch_assoc()){                                    
                                        $values[]=  $service_row["servicename"];   
                                        $price[] = $service_row["service_cost"];
                                    }

                                    $combined_arr = array_combine($values, $price);
                                    $languages_arr = $values;
                                    // $newarr = array_diff($values, $checked_arr);
                                    foreach($values as $value){
                                        $formated = number_format($combined_arr[$value]);
                                        if(in_array($value, $checked_arr)){
                                            echo "<input type='checkbox' id='checkbox' name= 'ap_type[]' value ='".$value."' checked .style='margin-top: 10px' > $value (K$formated) <br>";
                                        }
                                        else{
                                            echo "<input type='checkbox' id='checkbox' name='ap_type[]' value='". $value."' .style='margin-top: 10px'>   $value (K$formated) <br> ";
                                        }

                                        // echo '<input type="checkbox" id="checkbox" name="ap_type[]" "'. echo in_array($value, $newarr)? "checked" : "".'" value="$value" style="margin-top: 10px"><br>';
                                    }
                                    // foreach($checked_arr as $language){
                           
                                    //      $checked = "";
                                    //      if(in_array($language,$checked_arr)){
                                    //           $checked = "checked";
                                    //      }
                                    //      echo '<input type="checkbox" id="checkbox" name="ap_type[]" value="'.$language.'" '.$checked.' > '.$language.' <br/>';
                                         
                                    // }
                                    // getting the other arrayas that werent checked by the used
                                    // $newarr = array_diff( $values, $checked_arr);

                                    // print_r($newarr);
                                    // return;


                                    // // print_r($newarr);
                                    // foreach ($newarr as $value) {

                                    //     print_r($value);
                                    //     return;
                                        
                                    //     // echo  " <input type='checkbox' id='checkbox' name='ap_type[]' value='". $value."' style='margin-top: 10px'> $value<br> ";
                                    // }
                                ?>
                            </div>
                        </div>
                        <div class="bttn-container">
                            <input type="submit" value="Submit" name="re-submit"  id="btn" onClick="valthis()">    
                        </div>
                        <!-- some javascript to control the checkboxes -->

                    </form>
                </div>
            </div>
        </div>

        <div class="column2">
            <div class="greetings-container">
                <span class="greetings" id="greetings"></span>
                <?php 
                    // this is to call the name of the user with the session variable
                   
                    echo ucwords($_SESSION['name']) . '.';
                ?> 
            </div>
          
            <!-- 2.appointmets tab -->
            <div class="main-appointments-container" id="main-appointments-container">
                <div class="create">
                    <button class="create" id="bttn" onclick="document.getElementById('modal-container').style.display='flex'" style="border-radius: 5px; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; font-weight: 100;"> Create Appointment </button>
                </div>
                <div class="table-container"> 
                    <div class="table">
                        <table>
                            <tr class="first-row">
                                <th>
                                    Doctor
                                </th>
                                <th>
                                    Animal Type
                                </th>
                                <th>
                                    Appointment Type
                                </th>
                                <th>
                                    Date
                                </th>
                                <th colspan="2" style = "z-index: 1">
                                    Actions
                                </th>
                            </tr>
                            <?php 
                                $reg2 = "SELECT phone FROM users where phone = '".$_SESSION['phone']."'";
                                $rest2 = mysqli_query($conn, $reg2);                        
                                $fetch_rest2 = mysqli_fetch_assoc($rest2);
                                
                                // retrieve data for the user matching the phone number
                                // if($fetch_rest2['phone'] == )
                                // retrieving data from the database for the user to see
                                $retrieve = "SELECT doctors.fullname as name, appointments.animal, appointments.ap_type, appointments.ap_date, appointments.ap_id FROM doctors INNER JOIN appointments ON doctors.field = appointments.field where appointments.phone = '".$_SESSION['phone']."'";
                                $link = mysqli_query($conn, $retrieve);
                                checkSQL($conn, $link);
                                $row = mysqli_num_rows($link);
                                if (!$link){
                                    die("Invalid query: " .$conn->error);
                                }                  

                                // reading data contained in each row
                                while($row = $link->fetch_assoc()){
                                        $ap_date2 = date("d-m-Y", strtotime($row["ap_date"]));
                                        $ap_id = $row["ap_id"];
                                    ?>
                                    <tr>
                                    <td><?php echo $row["name"] ?></td>
                                    <td><?php echo $row["animal"] ?></td>
                                    <td><?php echo $row["ap_type"] ?></td>
                                    <td><?php echo $ap_date2 ?></td>
                                    <td style = "z-index: 2"><a href="appointments-edit.php?edit=<?php echo $ap_id; $_SESSION['id'] = $ap_id?>#" class="edit" onclick="document.getElementById('modal-container').style.display='flex'">Edit</td>
                                    <td style = "z-index: 2" onclick="fireSweetAlert()"><a href="appointments-edit.php?edit=<?php echo $ap_id; $_SESSION['id'] = $ap_id?>#" class="cancel">Cancel</a></td>
                                    
                                    </tr>
                                    <?php } ?>
            

                                    
                        </table>
                    </div>
                </div> 
            </div>
        </div>
    <script>
        // to create the load up animation for the table
        
        //  greeting the user on top of the dashboad page

        const greeting = document.getElementById('greetings');
        const hour = new Date().getHours();
        const welcomeTypes = ["Good Morning,", "Good Afternoon,", "Good Evening,", "Good Night,"];
        let welcomeText = "";

        if (hour < 12){
            welcomeText = welcomeTypes[0];
        }
        else if (hour < 17){
            welcomeText = welcomeTypes[1];    
        }
        else if (hour < 20){
            welcomeText = welcomeTypes[2];
        }
        else {
            welcomeText = welcomeTypes[3];
        }

        greeting.innerHTML = welcomeText;

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
        var yyyy = today.getFullYear();
        if(dd<10){
        dd='0'+dd
        } 
        if(mm<10){
        mm='0'+mm
        } 


        today = yyyy+'-'+mm+'-'+dd;
        document.getElementById("date").setAttribute("min", today);

        $(function(){
            $(".modal").css({"animation":"second-animation 1s forwards"});
        });

        // checkbox validation
        function validateForm(form) {

        var ap_type =  document.getElementsByName("ap_type[]");

        var checked_ap_type = 0;
        
        for (var i = 0; i < ap_type.length; i++) {
            if (ap_type[i].checked) {
                checked_ap_type++;
            }
        }

  
        if (checked_ap_type == 0) {
            document.getElementById("msg").innerHTML = "Service is required";
            document.getElementById("msg").style.color="red";
            return false;
        }
        return true;
    }
        
    </script>
</body>
</html>