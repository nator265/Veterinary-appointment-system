<?php
session_start();
include('../../connect.php');
include('../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../login.php');
}

if(isset($_GET['reject'])){
    $_SESSION['ap_id'] = $_GET['reject'];
    $ap_id = $_SESSION['ap_id'];
    // to remove the appoitnment from the pending tab to the rejected tab.
    // $removeqry = "UPDATE appointments 
    //     SET approved = 'rejected' 
    //     WHERE ap_id = $rejectedPhone";
    // $removelink = mysqli_query($conn, $removeqry);
    // checkSQL($conn, $removelink);
    $query = "SELECT * from appointments where ap_id = $ap_id ";
    $linkquery = mysqli_query($conn, $query);
    checkSQL($conn, $linkquery);
    $phoneassoc = mysqli_fetch_assoc($linkquery);
    checkSQL($conn, $phoneassoc);
    $phone = $phoneassoc['phone'];

    if(isset($_POST['submit'])){
        $message = $_POST['message'];       
        $reg = "INSERT into notifications(sender, title, message1, phone, reciever) VALUES ('".$_SESSION['name']."', 'Appointment has been rejected', '$message', '".$_SESSION['phone']."', '$phone')";
        checkSQL($conn, $reg);
        $rest = mysqli_query($conn, $reg);
        checkSQL($conn, $rest);
        header('location:appointments-approved.php');
    }
    
}


// if(isset($_POST['submit'])){
//     $message = $_POST['message'];
   
//     $reg = "INSERT INTO notifications(sender, title, message1, phone, reciever) VALUES ('".$_SESSION['name']."', 'Appointment has been rejected', '$message', '".$_SESSION['phone']."', '".$_SESSION['phone2']."')";
                            
//     $rest = mysqli_query($conn, $reg);
    
//     checkSQL($conn, $rest);
// }

// if(isset($_POST['re-submit'])){
//     $fullname = $_POST['fullname'];
//     $field = $_POST['field'];
//     $date = $_POST['ap_date'];
//     $animal = $_POST['animal'];
//     $ap_type = $_POST['ap_type'];
//     $_SESSION['field2'] = $field;

//     // converting the ap_type to string
//     $allaptype = implode(", ", $ap_type);
    
//     // inserting data into the appointments table in the database
//     $update = "UPDATE appointments SET fullname = '$fullname', field = '$field', ap_date = '$date', animal = '$animal', ap_type = '$allaptype' where ap_id = '".$_SESSION['id']."' ";
//     mysqli_query($conn, $update);
//     // header('location:appointments.php');
    
// }
// if(isset($_GET['approve'])){
//     $id = $_GET['approve'];
//     $approved = 'yes';
//     // inserting approval status into the database
//     $entry = "UPDATE appointments SET approved = '$approved' WHERE appointments.ap_id = '$id'";
//     $link = mysqli_query($conn, $entry);
//     // header('location: appointments.php');
// }

?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="appointments-reject.css">
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
                    <a href="dashboard.php"> <span id='link'> Dashboard </span> </a>
                </div>
                <div class="link">
                    <span id='link'> Appointments </span>
                </div>
                <div class="link">
                    <a href="notifications.php"><span id='link'> Notifications </span> </a>
                </div>
                <div class="link">
                    <a href="../logout.php" style="text-decoration: none; color: white">
                        <button class="logout" id="bttn">Logout</button>
                    </a>
                </div>
            </div>
        </div>

        <!-- thi is the modal for the appointments registration -->
        <div class="modal-container" id="modal-container">
            <div class="modal">
                <div class="form-container">
                    <div class="form-header">
                        <h1 style="text-align: center; color: white;">
                            Why Am I Being Rejected?
                        </h1>
                    </div>
                    <form action="appointments-reject.php" method="POST">
                        <textarea type="text" name="message" id="fullname" placeholder="Due to..." cols="67" rows="4" style="padding:10px" required></textarea>
                        <br>
                        <br>
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
                <div class="table-container"> 
                    <div class="sort-container">
                        <div class="sort">
                            <div class="recents-container">
                                <button class="sort-buttons" id="recents" name="recents">
                                    Recents
                                </button>
                            </div>
                            <div class="approved">
                                <a href="appointments-approved.php">
                                    <button class="sort-buttons" id="approved" name="approved">
                                        Approved
                                    </button>
                                </a>
                            </div>
                            <div class="rejected">
                                <a href="appointments-rejected.php">
                                    <button class="sort-buttons" id="rejected" name="rejected">
                                        Rejected
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table" id="recents2">
                        <table>
                            <tr class="first-row">
                                <th>
                                    Owner
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
                                <!-- <th>
                                    Approved
                                </th>-->
                                <th colspan="2">
                                    Actions
                                </th>
                            </tr>
                            <div class="recents-tab">
                                <?php
                                    // retrieve data for the user matching the phone number
                                    // if($fetch_rest2['phone'] == )
                                    // retrieving data from the database for the user to see
                                    $query = "SELECT * from appointments where field = '".$_SESSION['field3']."' and approved='approved'";
                                    $approved_filter = mysqli_query($conn, $query);
                                    checkSQL($conn, $approved_filter);
                                    $row = mysqli_num_rows($approved_filter);
                                    if (!$approved_filter){
                                        die("Invalid query: " .$conn->error);
                                    }
                                    while($row = $approved_filter->fetch_assoc()){
                                        $ap_date2 = date("d-m-Y", strtotime($row["ap_date"]));
                                        $ap_id = $row["ap_id"];
                                    ?>
                                    
                                    <tr>
                                    <td><?php echo $row["fullname"] ?></td>
                                    <td><?php echo $row["animal"] ?></td>
                                    <td><?php echo $row["ap_type"] ?></td>
                                    <td><?php echo $ap_date2 ?></td>
                                    <td><a href="appointments-reject.php?reject=<?php echo $row['ap_id'] ?>."><button class="action-buttons" id="reject-button" name="reject">Reject</button></a>
                                    </tr>
                                        
                                    <?php }
                                ?>
                            </div>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
        
        <script src="jquery.js"></script>
        <script>
            function approve(id){
                Swal.fire({
                    title: 'Approve?',
                    text: 'Do you want to continue?',
                    icon: 'question',
                    confirmButtonText: 'Approve',
                    showCancelButton: true
                }).then((value) => {
                    if(value){
                        //Your Ajax here fada
                        $.ajax({
                            url: approve.php,
                            method: "POST",
                            data: {
                                ap_id: id
                            },
                            success: function(response){
                                if(response === "success"){
                                    Swal.fire('Approved', 'The Appointment Has Been Approved', 'success').then((res)=>{
                                        location.href = "appointments.php";
                                    })
                                }
                                else{
                                    Swal.fire(response,'Unable To Approve, Please Try Again','error');
                                }
                            }
                        })
                    }
                });
            }
        </script>
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
    
        
    </script>
</body>
</html>