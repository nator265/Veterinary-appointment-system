<?php
session_start();
include('../connect.php');
include('../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../login.php');
}

if(isset($_GET['reply'])){
    $id = $_GET['reply'];
    $_SESSION['notifications_id'] = $id;

    // selecting the name of the reciever from the database
    $query = "SELECT * from notifications where notifications_id = '$id'";
    $link = mysqli_query($conn, $query);
    checkSQL($conn, $link);
    $fetchphone = mysqli_fetch_assoc($link);
    $phone = $fetchphone['reciever'];
    $query2 = "SELECT * from doctors where phone = $phone";
    $link2 = mysqli_query($conn, $query2);
    $fetchname = mysqli_fetch_assoc($link2);
    $name = $fetchname['fullname'];
    $_SESSION['recieversname'] = $name;

    // getting the message for the reciever from the database
    $query3 = "SELECT * from notifications where notifications_id = $id";
    $link3 = mysqli_query($conn, $query3);
    $fetchmessage = mysqli_fetch_assoc($link3);
    $message = $fetchmessage['message1'];
    $_SESSION['message-for-reciever'] = $message;

}
if(isset($_POST['submit'])){
    $message = $_POST['message'];
   
    $reg = "UPDATE notifications 
            SET message1 = '$message'
            WHERE notifications_id = '".$_SESSION['notifications_id']."'";
    $rest = mysqli_query($conn, $reg);

    $query3 = "SELECT * from notifications where notifications_id = '".$_SESSION['notifications_id']."'";
    $link3 = mysqli_query($conn, $query3);
    $fetchmessage = mysqli_fetch_assoc($link3);
    $message2 = $fetchmessage['message1'];
    $_SESSION['message-for-reciever'] = $message2;
    
    checkSQL($conn, $rest);
    // header('location: notifications-sent-reply?.php');
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
                       <a href="index.php"> <span id='link'> Dashboard </span> </a>
                    </div>
                    <div class="link">
                        <a href="appointments.php"><span id='link'> Appointments </span></a>
                    </div>
                    <div class="link">
                       <a href="notifications.php"> <span id='link'> Notifications </span> </a> 
                    </div>
                    <div class="link">
                        <a href="logout.php" style="text-decoration: none; color: white">
                            <button class="logout" id="bttn">Logout</button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- this is the modal for the reply console -->
            <div class="modal-container" id="modal-container">
                <div class="modal">
                    <a href="notifications-sent.php">
                        <div class="close" name = "close">&times;</div>
                    </a>
                    <div class="form-container">
                        <div class="form-header">
                            <h1 style="text-align: center; color: white; font-weight:100; font-size:xx-large">
                                <?php
                                    echo 'Sent to', ' ', $_SESSION['recieversname']
                                ?>
                            </h1>
                            <div class="messagecontainer">
                                <div class="messageheader">
                                    Notification sent:
                                </div>
                                <div class="messagebody">
                                    <?php    
                                      echo $_SESSION['message-for-reciever'];
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div id="thisform">
                        <form action="notifications-sent-reply.php" method="POST">
                            
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
                            <button  id="tog" class="edit"> Edit </button>
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
                    <button class="create" id="bttn"> Compose a Message </button>
                    <a href="notifications-sent.php"><button class="create2" id="bttn">  </button></a>
                </div>
    
                <div class="main-appointments-container" id="main-appointments-container">

                <div class="table-container"> 
                    <div class="table">
                        <table>
                            <tr class="first-row">
                                <th style="padding-left:10px">
                                    From
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
                                    $phone = $row['phone'];
                                    // $_SESSION['reply-to'] = $row['sender'];
                                    // $_SESSION['message-to-reply'] = $row['message1'];
                                    $_SESSION['message-title'] = $row['title'];
                                    $_SESSION['phone2'] = $row['phone']
                                    ?>
                                    <tr>
                                    <td style="padding-left: 10px"><?php echo $row["sender"] ?></td>
                                    <td><?php echo $row["title"] ?></td>
                                    <td><?php echo $row["time"] ?></td>
                                    <td><a href="notifications-recieved.php?reply=<?php echo $row['message1'] ?>" class="edit" onclick="document.getElementById('modal-container').style.display='flex'">Reply</a></td>
                                    <td><a href="notifications-recieved.php?delete=<?php echo $row['notifications_id'] ?>" class="cancel">Delete</a></td>
                                    </tr>
                                    <?php } ?>
            

                                    
                        </table>
                    </div>
                </div> 
            </div>
        <script>
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
    
            // this is to open the input text area
            
            
        </script>
    </body>
    </html>
</body>
</html>