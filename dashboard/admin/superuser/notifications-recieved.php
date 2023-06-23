<?php
session_start();
include('../../../connect.php');
include('../../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../../login.php');
}

if(isset($_POST['submit'])){
    $fullname = $_POST['fullname'];
    $field = $_POST['field'];
    $date = $_POST['ap_date'];
    $animal = $_POST['animal'];
    $ap_type = $_POST['ap_type'];
    $_SESSION['field2'] = $field;
    // converting the ap_type to string
    $allaptype = implode(", ", $ap_type);
    // inserting data into the appointments table in the database
    $reg = "INSERT INTO appointments(fullname, field, animal, ap_date, ap_type, phone) VALUES ('$fullname', '$field', '$animal', '$date', '$allaptype', '".$_SESSION['phone']."')";
                            
    $rest = mysqli_query($conn, $reg);
    
    checkSQL($conn, $rest);
}
if(isset($_POST['re-submit'])){
    $fullname = $_POST['fullname'];
    $field = $_POST['field'];
    $date = $_POST['ap_date'];
    $animal = $_POST['animal'];
    $ap_type = $_POST['ap_type'];
    $_SESSION['field2'] = $field;

    // converting the ap_type to string
    $allaptype = implode(", ", $ap_type);
    
    // inserting data into the appointments table in the database
    $update = "UPDATE appointments SET fullname = '$fullname', field = '$field', ap_date = '$date', animal = '$animal', ap_type = '$allaptype' where ap_id = '".$_SESSION['id']."' ";
    mysqli_query($conn, $update);
    // header('location:appointments.php');
    
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="notifications.css">
    <title>Notifications</title>
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
                       <a href="dashboard.php"> <span id='link'> Dashboard </span> </a>
                    </div>
                    <div class="link">
                    <a href="doctors.php"><span id='link'> Doctors </span></a>
                </div>
                    <div class="link">
                        <a href="appointments.php"><span id='link'> Appointments </span></a>
                    </div>
                    <div class="link">
                        <span id='link'> Notifications </span>
                    </div>
                    <div class="link">
                    <a href="settings.php"><span id='link'> Settings </span></a>
                    </div>
                    <div class="link">
                        <a href="../../logout.php" style="text-decoration: none; color: white">
                            <button class="logout" id="bttn">Logout</button>
                        </a>
                    </div>
                </div>
            </div>
    
            <!-- this is the second column -->
            <div class="column2">
                <div class="greetings-container">
                    <span class="greetings" id="greetings"></span>
                    <?php 
                        // this is to call the name of the user with the session variable
                       
                        echo ucwords($_SESSION['name']) . '.';
                    ?> 
                </div>
    
                <div class="main-appointments-container" id="main-appointments-container">
                <div class="table-container"> 
                    <div class="table">
                        <table>
                            <tr class="first-row">
                                <th>
                                    from
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Message
                                </th>
                                <th>
                                    To
                                </th>
                                <th>
                                    Time
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                            <?php
                                
                                // retrieve data for the user matching the phone number
                                // retrieving data from the database for the user to see
                                $retrieve = "SELECT notifications.sender, notifications.title, notifications.phone, notifications.time
                                FROM ((notifications
                                INNER JOIN users ON notifications.CustomerID = users.CustomerID)
                                INNER JOIN doctors ON notifications.ShipperID = doctors.ShipperID)";
                                $link = mysqli_query($conn, $retrieve);
                                checkSQL($conn, $link);
                                $row = mysqli_num_rows($link);
                                if (!$link){
                                    die("Invalid query: " .$conn->error);
                                }                  

                                // reading data contained in each row
                                while($row = $link->fetch_assoc()){
                                    ?>
                                    <tr>
                                    <td><?php echo $row["name"] ?></td>
                                    <td><?php echo $row["title"] ?></td>
                                    <td><?php echo $row["message"] ?></td>
                                    <td><?php echo $row["time"] ?></td>
                                    <td><a href="notifications.php?delete=<?php echo $row['id'] ?>" class="cancel">Delete</a></td>
                                    </tr>
                                    <?php } ?>
            

                                    
                        </table>
                    </div>
                </div> 
            </div>
        <script>
    
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
    
            // this is to close the modal
            
        </script>
    </body>
    </html>
</body>
</html>