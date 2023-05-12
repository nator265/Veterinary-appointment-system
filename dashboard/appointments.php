<?php
session_start();
include('../connect.php');
include('../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
}

if(isset($_POST['submit'])){
    $fullname = $_POST['fullname'];
    $field = $_POST['field'];
    $date = $_POST['ap_date'];
    $animal = $_POST['animal'];
    $selectedservices = $_POST['ap_type']; 
    $_SESSION['field2'] = $field;
    // converting the ap_type to string
    $allaptype = implode(", ", $selectedservices);

     // inserting data into the appointments table in the database
     $reg = "INSERT INTO appointments(fullname, field, animal, ap_date, ap_type, phone) VALUES ('$fullname', '$field', '$animal', '$date', '$allaptype', '".$_SESSION['phone']."')";
                            
     $rest = mysqli_query($conn, $reg);
     
     checkSQL($conn, $rest);

     $ap_id = mysqli_insert_id($conn);
 
    // Get the total cost by comparing selected services with `service_costs` table
    $total = 0;
    foreach ($selectedservices as $service) {
        $service = mysqli_real_escape_string($conn, $service); // Prevent SQL injection
        $query = "SELECT service_cost FROM service_costs WHERE servicename = '$service'";
        $result = mysqli_query($conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            $serviceCost = intval($row['service_cost']); // Convert to integer
            $total += $serviceCost;
        }
    }

    $query2 = "UPDATE appointments SET total = '$total' WHERE ap_id = '$ap_id'";
    mysqli_query($conn, $query2);
    
    // Close the database connection
    mysqli_close($conn);
   
    header('location: appointments-success.php');   
}
if(isset($_POST['re-submit'])){
    $fullname = $_POST['fullname'];
    $field = $_POST['field'];
    $date = $_POST['ap_date'];
    $animal = $_POST['animal'];
    $selectedservices = $_POST['ap_type'];
    $_SESSION['field2'] = $field;
    
    // converting the ap_type to string
    $allaptype = implode(", ", $selectedservices);
    
    // inserting data into the appointments table in the database
    $update = "UPDATE appointments SET fullname = '$fullname', field = '$field', ap_date = '$date', animal = '$animal', ap_type = '$allaptype' where ap_id = '".$_SESSION['idforedit']."' ";
    mysqli_query($conn, $update);
    header('location:appointments.php');

    $total = 0;
    foreach ($selectedservices as $service) {
        $service = mysqli_real_escape_string($conn, $service); // Prevent SQL injection
        $query = "SELECT service_cost FROM service_costs WHERE servicename = '$service'";
        $result = mysqli_query($conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            $serviceCost = intval($row['service_cost']); // Convert to integer
            $total += $serviceCost;
        }
    }

    $query2 = "UPDATE appointments SET total = '$total' WHERE ap_id = '".$_SESSION['idforedit']."'";
    mysqli_query($conn, $query2);
    
    // Close the database connection
    mysqli_close($conn);
    
}


if(isset($_GET['yes'])){
    $id = $_GET['yes'];
    $delete = "DELETE FROM appointments where ap_id = $id ";
    mysqli_query($conn, $delete);
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Appointments</title>
</head>
<body>
   
    <!-- these are columns -->
    <div class="flex-container">
        
    <!-- this is a shadow that make the first column come out -->
    <div class="shadow"></div>

        <div class="column1">
        <div class="company-name-container">
                    <div class="company-name">
                        Veterinary
                    </div>
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
                <div class="button-position">
                    <a href="logout.php" style="text-decoration: none; color: white">
                        <button class="logout" id="bttn">Logout</button>
                    </a>
                </div>
            </div>
        </div>

        <!-- thi is the modal for the appointments registration -->
        <div class="modal-container" id="modal-container">
            <div class="modal">
                <div class="close" onClick="document.getElementById('modal-container').style.display='none'">&times;</div>
                <div class="form-container">
                    <div class="form-header">
                        <h1 style="text-align: center; color: white;">
                            Book An Appointment.
                        </h1>
                    </div>
                    <form action="appointments.php" method="POST">
                        <div class="pushcontainer">
                            <div class="pushleft">
                                <input type="text" name="fullname" id="fullname" placeholder="Owner Name(Fullname)" required>
                                <br>
                                <br>
                                <span style="color:white;"> Select Animal Type</span>
                                <br>
                                <div class="type">
                                    <select name="field" id="field" required>
                                        <option value="pet">Pet</option>
                                        <option value="livestock">Livestock</option>
                                    </select>                        
                                    <input type="text" name="animal" id="animal" placeholder="Pet e.g. Dog | Livestock e.g. Cow" required>
                                </div>
                                <br>
                                <input type="date" name="ap_date" id="date" required>
                                <br>
                            </div>
                            <div class="pushright">
                                <div style="margin-bottom: 5px;" id="msg">Select Service:</div>
                                <?php
                                    // this is to bring out the services and costs from the service table
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
                                        $values[] =  $service_row["servicename"];
                                        $price[] = $service_row["service_cost"];
                                    }
                                    
                                    $combined_arr = array_combine($values, $price);
                                    
                                    foreach ($values as $value) {
                                        $formated = number_format($combined_arr[$value]);
                                        echo "<input type='checkbox' id='checkbox' name='ap_type[]' value='". $value."' .style='margin-top: 10px'>   $value (K$formated) <br> ";
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="bttn-container">
                            <input type="submit" value="Submit" name="submit"  id="btn">    
                        </div>
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
                                <th>
                                    Total
                                </th>
                                <th colspan="2" style = "z-index: 2">
                                    Actions
                                </th>
                            </tr>
                            <?php 
                                $reg2 = "SELECT phone FROM users where phone = '".$_SESSION['phone']."'";
                                $rest2 = mysqli_query($conn, $reg2);                        
                                $fetch_rest2 = mysqli_fetch_assoc($rest2);
                                
                                // retrieve data for the user matching the phone number
                                $retrieve = "SELECT doctors.fullname as name, appointments.animal, appointments.ap_type, appointments.ap_date, appointments.ap_id, appointments.total FROM doctors INNER JOIN appointments ON doctors.field = appointments.field where appointments.phone = '".$_SESSION['phone']."' ORDER BY appointments.ap_date asc";
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
                                    <td><?php echo "K".number_format($row["total"]) ?></td>
                                    <td style = "z-index: 1"><a href="appointments-edit.php?edit=<?php echo $ap_id ?>" class="edit">Edit</td>
                                    <td style = "z-index: 1"><a href="appointments-delete.php?delete=<?php echo $ap_id ?>" class="cancel" id="clearance">Cancel</a></td>
                                    </tr>
                                <?php } 
                            ?>                                    
                        </table>
                    </div>
                </div> 
                <div class="alert-container" id="target">
                                        <div class="alert" id="alert">
                                            <div class="warning-container">
                                                <div class="warning-header">
                                                    CANCEL APPOINTMENT.
                                                </div>
                                                <div class="subtext">
                                                    Are you sure you want to cancel the appointment?
                                                </div>
                                                <form action="appointments.php" method="post">
                                                     <div class="buttonsection">
                                                        <a href="appointments.php?yes=<?php echo $ap_id ?>">
                                                            <input type="button" class="edit2" value="Yes" name="yes">
                                                        </a>
                                                        <input type="button" class="cancel2" value="No" name="no" id="noclearance">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
            </div>
        </div>
    <script>
        // animations for the table and the create button.
        $(function(){
            $(".create").css({"animation":"second-animation 1s forwards"});
            $(".table").css({"animation":"third-animation 1s forwards"});
        })
        // the alert for the delete function
        $(document).ready(function(){
            // to make the modal appear and animate it
            $(".modal").fadeOut(0);
            $(".create").click(function(){
                $(".modal-container").css('display', 'flex');
                $('.modal').fadeIn(1000);
            })
            $(".close").click(function(){
                $(".modal").fadeOut(500, function(){
                    $(".modal-container").css('display', 'none');
                });
                
            })

            // to make the alert box appear
            $("#clearance").click(function(){
                $('#target').fadeIn(100).css('display', 'flex');
                $('#alert').slideDown(500).css('display', 'flex');
            });
            $("#noclearance").click(function(){
                $('#alert').slideUp(400).css('display', 'none');
                $("#target").fadeOut(600);

            });
            });

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

            // checkbox validation
        function validateForm(form) {

        var ap_type = document.getElementsByName("ap_type[]");

        var checked_ap_type = 0;
        
        for (var i = 0; i < ap_type.length; i++) {
            if (ap_type[i].checked) {
                checked_ap_type++;
            }
        }


        if (checked_ap_type == 0) {
            document.getElementById("msg").innerHTML = "Service is required";
            document.getElementById('msg').style.color="red";
            return false;
        }
        return true;
    }

        // changing the dates form-container
    
        
    </script>
</body>
</html>