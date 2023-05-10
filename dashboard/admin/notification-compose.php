<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}

if(isset($_POST['submit'])){
    $fullname = $_POST['Category'];
    $title = $_POST['title'];
    $message = $_POST['message'];
   
    $query = "INSERT INTO notifications (sender, title, message1, phone) VALUES ('".$_SESSION['name']."', '$title', '$message', '".$_SESSION['phone']."'";
                            
    $rest = mysqli_query($conn, $query);
    header('location: notification-compose.php');
    
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
    header('location:appointments.php');
    
}


if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete = "DELETE FROM appointments where ap_id = $id ";
    mysqli_query($conn, $delete);
    header('location:appointments.php');
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="notification-compose.css">
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
                        <a href="appointments.php"><span id='link'> Appointments </span></a>
                    </div>
                    <div class="link">
                        <span id='link'> Notifications </span>
                    </div>
                    <div class="link">
                        <a href="../logout.php" style="text-decoration: none; color: white">
                            <button class="logout" id="bttn">Logout</button>
                        </a>
                    </div>
                </div>
            </div>
    
            <!-- this is the second column -->
            <div class="column2">    
                <div class="main-dashboard-container" id="main-dashboard-container">
                    <div class="header-container">
                        <div class="head">
                            Compose a Message
                        </div>
                    </div>
                    <div class="mesage-form">
                        <div class="form-container">
                            <form action="notification-compose.php" method="POST">
                                <?php
                                    $sql="SELECT fullname, phone from doctors union SELECT fullname, phone from users ";
                                    $link = mysqli_query($conn,$sql);
                                ?>

                                <label>Send Message to:</label>
                                <select name="Category">
                                    <?php
                                        // use a while loop to fetch data
                                        // from the $all_categories variable
                                        // and individually display as an option
                                        while ($category = mysqli_fetch_array(
                                                $link,MYSQLI_ASSOC)):;
                                    ?>
                                        <option value="<?php echo $category["phone"], $_SESSION['phone2'] = $category["phone"];
                                            // The value we usually set is the primary key
                                        ?>">
                                            <?php echo $category["fullname"];
                                                // To show the category name to the user
                                            ?>
                                         </option>
                                         <option hidden value="<?php echo $category["phone"];?>"></option>
                                    <?php
                                        endwhile;
                                        // While loop must be terminated
                                    ?>
                                </select>
                                <br>
                                <br>
                                <label for="title">Title:</label>
                                <input type="text" name="title" id="text" placeholder="Title">
                                <br>
                                <br>
                                <textarea required style="padding:15px;" name="w3review" rows="8" cols="100" placeholder="Write your message here..."></textarea>
                                <br>
                                <br>
                                <input type="submit" value="Send" class="send" name="submit">
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
            
        </script>
    </body>
    </html>
</body>
</html>