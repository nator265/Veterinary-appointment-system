<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
}

if(isset($_POST['submit'])){
    $message = $_POST['message'];
    // getting message title from the notifications
    $query = "SELECT * from notifications where notifications_id = '".$_SESSION['notifications_id']."'";
    $link = mysqli_query($conn, $query);
    $fetchtitle = mysqli_fetch_assoc($link);
    $messagetitle = $fetchtitle['title'];
    // fetching the phone of the user
    $query2 = "SELECT * from notifications where notifications_id = '".$_SESSION['notifications_id']."'";
    $link2 = mysqli_query($conn, $query2);
    $fetchphone2 = mysqli_fetch_assoc($link2);
    $phone2 = $fetchphone2['phone'];
    $reg = "INSERT INTO notifications(sender, title, message1, phone, reciever) VALUES ('".$_SESSION['name']."', '$messagetitle', '$message', '".$_SESSION['phone']."', '$phone2')";
    checkSQL($conn, $reg);
                            
    $rest = mysqli_query($conn, $reg);
    
    checkSQL($conn, $rest);
    header('location: notifications-recieved.php');
}

if(isset($_GET['reply'])){
    $_SESSION['notifications_id'] = $_GET['reply'];
    $query = "SELECT * from notifications where notifications_id = '".$_SESSION['notifications_id']."'";
    $linkquery = mysqli_query($conn, $query);
    checkSQL($conn, $linkquery);

    // getting the phone from the notifications for the receiver
    $fetchphone = mysqli_fetch_assoc($linkquery);
    $phone = $fetchphone['phone'];

    // getting the message from the database
    $_SESSION['notifications_id'] = $_GET['reply'];
    $query = "SELECT * from notifications where notifications_id = '".$_SESSION['notifications_id']."'";
    $linkquery = mysqli_query($conn, $query);
    checkSQL($conn, $linkquery);

    // gettong the message from the database
    $fetchmessage = mysqli_fetch_assoc($linkquery);
    $_SESSION['message-to-reply'] = $fetchmessage['message1'];
    

    // getting the name of the reciever from the database
    $query2 = "SELECT * from users where phone = $phone";
    $linkquery2 = mysqli_query($conn, $query2);
    checkSQL($conn, $linkquery2);
    $fetchname = mysqli_fetch_assoc($linkquery2);
    $num = mysqli_num_rows($linkquery);
    $name = $fetchname['fullname'];
    $_SESSION['reply-to'] = $name;
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete = "DELETE FROM appointments where ap_id = $id ";
    mysqli_query($conn, $delete);
    header('location:notifications-recieved.php');
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="notification-style-reply.css">
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
                        <a href="dashboard.php"><span id='link'> Dashboard <img src="images/dashboard.png" alt="" height="20px"></span></a>
                    </div>
                    <div class="link">
                    <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"> </a>
                    </div>
                    <div class="link">
                    <a href="check-appointments.php"><span id='link'> Checkoff Appointments <img src="images/total.png" alt="" height="20px"></span> </a>
                    </div>
                    <div class="link">
                       <a href="notifications.php"><span id='link'> Notifications <img src="images/notifications.png" alt="" height="20px"></span> </a>
                    </div>
                    <div class="link">
                        <a href="../logout.php" style="text-decoration: none; color: white">
                            <button class="logout" id="bttn">Logout</button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- this is the modal for the reply console -->
            <div class="modal-container" id="modal-container">
                <div class="modal">
                   <a href="notifications-recieved.php">
                        <div class="close">&times;</div>
                   </a> 
                    <div class="form-container">
                        <div class="form-header">
                            <h1 style="text-align: center; color: white; font-weight:100; font-size:xx-large">
                                <?php
                                    echo "Message from"," ", $_SESSION['reply-to'];
                                ?>
                            </h1>
                            <div class="messagecontainer">
                            <div class="messageheader">
                                    Notification Recieved.
                            </div>
                            <div class="messagebody">
                                <?php
                                    echo $_SESSION['message-to-reply']
                                ?>
                            </div>
                            </div>
                        </div>
                        <div id="thisform">
                        <form action="notifications-recieved-reply.php" method="POST">
                            
                                <textarea type="text" name="message" id="fullname" placeholder="Type message here..." cols="67" rows="4" style="padding:10px" required></textarea>
                                <br>
                                <br>
                                <div class="bttn-container">
                                    <input type="submit" value="Send" name="submit"  class="edit" style="font-size:large; margin-right:5px;">
                                    <input type="button" value="Cancel" id="togg" class="cancel" style="font-size:large;">
                                </div>
                            
                        </form>
                        </div>
                        <div id="editbutton" style="display:flex; align-content:center; justify-content:center;">
                            <button  id="tog" class="edit"> Reply </button>
                        </div>
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
                <div class="create">
                    <a href="notifications-sent.php">
                        <button class="create" id="bttn"> View Sent Messages </button>
                    </a>
                </div>
    
                <div class="main-appointments-container" id="main-appointments-container">

                <div class="table-container"> 
                    <div class="table">
                        <table>
                            <tr class="first-row">
                                <th>
                                    From
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Time
                                </th>
                                <th style="z-index:1">
                                    Actions
                                </th>
                            </tr>
                            <?php
                                
                                // retrieve data for the user matching the phone number
                                // retrieving data from the database for the user to see
                                $retrieve = "SELECT * FROM notifications Where reciever = '" .$_SESSION['phone']."' ORDER BY time desc";
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
                                    <td><?php echo $row["sender"] ?></td>
                                    <td><?php echo $row["title"] ?></td>
                                    <td><?php echo $row["time"] ?></td>
                                    <td style="z-index:2"><a href="notifications-recieved-reply.php?reply=<?php echo $row['notifications_id'] ?>" class="cancel" onclick="document.getElementById('modal-container').style.display='flex'">View</a></td>
                                    <!-- <td><a href="notifications.php?delete=<?php echo $row['id'] ?>" class="cancel">Delete</a></td> -->
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
    
            // this is to open the modal
            $(function(){
            $(".modal").css({"animation":"opacity-animation 1s forwards"});
            });
            $("#thisform").hide();
                $("#tog").click(function(){
                    $("#thisform").show(1000);
                    $("#tog").hide(700);
                    $(".messagecontainer").hide(500);
                });
                $("#togg").click(function(){
                    $("#thisform").hide(1000);
                    $("#tog").show(1000);
                    $(".messagecontainer").show(800);
                });
        </script>
    </body>
    </html>
</body>
</html>