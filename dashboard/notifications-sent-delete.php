<?php
session_start();
include('../connect.php');
include('../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
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
    header('location:appointments.php');
    
}
if(isset($_POST['submit'])){
    $message = $_POST['message'];
    $reg = "UPDATE notifications 
            SET message1 = '$message'
            WHERE notifications_id = '".$_SESSION['message-to-reply']."'";
                            
    $rest = mysqli_query($conn, $reg);
    
    checkSQL($conn, $rest);
    header('location: notifications-sent-reply.php');
}
if(isset($_GET['yes'])){
    $id = $_GET['yes'];
    $delete = "DELETE FROM notifications where notifications_id = $id ";
    mysqli_query($conn, $delete);
    header('location:notifications-sent.php');
}

if(isset($_GET['delete'])){
    $_SESSION['this-id'] = $_GET['delete'];
};
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style-fordelete.css">
    <title>Notifications</title>
</head>
<body>
     <!-- these are columns -->
     <div class="flex-container">
        
        <!-- this is a shadow that make the first column come out -->
        <div class="shadow"></div>
    
            <div class="column1">
                <div class="company-name-container">
                    <div class="company-name" style="font-size: x-large; font-weight:100">
                    GSJ Animal Health & Production
                    </div>
                </div>
                <div class="links-container">
                    <div class="link">
                        <a href="index.php"><span class="link1"> Dashboard <img src="images/dashboard.png" alt="" height="20px"></a>
                    </div>
                    <div class="link">
                        <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"></span></a>
                    </div>
                    <div class="link">
                        <a href="notifications.php"><span id='link'> Notifications <img src="images/notifications.png" alt="" height="20px"></span> </a>
                    </div>
                    <div class="link">
                        <a href="settings.php"><span id='link'> Settings <img src="images/settings.png" alt="" height="20px"></span> </a>
                    </div>
                    <div class="logout">
                        <a href="logout.php" style="text-decoration: none; color: white">
                            <button id="bttn">Logout</button>
                        </a>
                    </div>
                </div>
            </div>
    
            <!-- this is the second column -->
            <div class="column2">
            <div class="greetings-container">
                    <span class="greetings" id="greetings"> </span>
                    <?php 
                        // this is to call the name of the user with the session variable
                       
                        echo ucwords($_SESSION['name']) . '.';
                    ?> 
                </div>
                <div class="create">
                    <a href="notifications-recieved.php">
                        <button class="create" id="bttn">
                            View Recieved Messages 
                        </button>
                    </a>
                </div>
    
                <div class="main-appointments-container" id="main-appointments-container">
                <div class="table-container"> 
                    <div class="table">
                        <table>
                            <tr class="first-row">
                                <th style="padding-left: 10px">
                                    To
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Time
                                </th>
                                <th colspan="2">
                                    Actions
                                </th>
                            </tr>
                            <?php
                                
                                // retrieve data for the user matching the phone number
                                // retrieving data from the database for the user to see
                                $retrieve = "SELECT * FROM notifications Where phone = '" .$_SESSION['phone']."'";
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
                                    <td style="padding-left:10px"><?php echo $row["sender"] ?></td>
                                    <td><?php echo $row["title"] ?></td>
                                    <td><?php echo $row["time"] ?></td>
                                    <td><a href="notifications-sent-reply.php?reply=<?php echo $row['notifications_id'] ?>" class="edit">View</a></td>
                                    <td><a href="notifications-sent-delete?delete=<?php echo $row['notifications_id']?>#" class="cancel" id="clearance">Delete</a></td>
                                    </tr>
                                    <?php } ?>
                        </table>
                    </div>
                </div> 
                <div class="alert-container" id="target">
                    <div class="alert" id="alert">
                        <div class="warning-container">
                            <div class="warning-header">
                                DELETE
                            </div>
                            <div class="subtext">
                                Are you sure you want to delete the notification?
                            </div>
                            <form action="appointments.php" method="post">
                                    <div class="buttonsection">
                                        <?php
                                        $retrieve = "SELECT * FROM notifications Where phone = '" .$_SESSION['phone']."'";
                                        $link = mysqli_query($conn, $retrieve);
                                        checkSQL($conn, $link);
                                        ?>
                                        <a href="notifications-sent.php?yes=<?php echo $_SESSION['this-id'] ?>">
                                            <input type="button" class="edit2" value="Yes" name="yes">
                                        </a>
                                        <a href="notifications-sent.php">
                                            <input type="button" class="cancel2" value="No" name="no" id="noclearance">
                                        </a>
                                </div>
                            </form>
                        </div>
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

             // the alert for the delete function

            $(function(){
                $(".alert").css({"animation":"second-animation 1s forwards"});
            });
            
        </script>
    </body>
    </html>
</body>
</html>