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
    header('location:appointments.php');
}

if(isset($_GET['delete'])){
    $_SESSION['forthisid'] = $_GET['delete'];
    
}
?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-fordelete.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Appointments</title>
</head>
<body>
   
    <div class="body">
        <div class="body-container">
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
                        <a href="index.php"><span class="link1"> Dashboard <img src="images/dashboard.png" alt="" height="20px"> </a>
                    </div>
                    <div class="link">
                        <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"></span></a>
                    </div>
                    <div class="link">
                        <a href="notifications.php"><span id='link'> Notifications <img src="images/notifications.png" alt="" height="20px"></span> </a>
                    </div>
                    <div class="link">
                        <span id='link'> Settings <img src="images/settings.png" alt="" height="20px"></span>
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
                    <span class="greetings" id="greetings"></span>
                    <?php 
                        // this is to call the name of the user with the session variable
                       
                        echo ucwords($_SESSION['name']) . '.';
                    ?> 
                </div>
    
                <!-- 1.Dashboard -->
                <div class="main-dashboard-container" id="main-dashboard-container">
                    <div class="dashboard" id="dashboard"> 
                        <a href="edit-profile.php" class="appointments-container" id="link2">
                            <div class="appointments">
                                <div class="count-container">
                                    <div class="count-info">
                                       Edit Profile
                                    </div>
                                    <div class="count">
                                       <div class="recimage">
                                            <img src="images/admin.png" alt="appointments" height="150px" style="padding-top:10px;" id="image1">
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="settingsdelete.php" class="appointments-container" id="link2">
                            <div class="appointments">
                                <div class="count-container">
                                    <div class="count-info" style="background-color:red">
                                       Delete Account
                                    </div>
                                    <div class="count">
                                       <div class="recimage">
                                            <img src="images/delete.png" alt="appointments" height="150px" style="padding-top:10px;" id="image12">
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>                                
                <div class="alert-container" id="target">
                    <div class="alert" id="alert">
                        <div class="warning-container">
                            <div class="warning-header">
                                DELETE ACCOUNTANT.
                            </div>
                            <div class="subtext">
                                Are you sure you want to permanently delete your account?
                            </div>
                            <form action="appointments.php" method="post">
                                    <div class="buttonsection">
                                    <a href="settings.php?yes=<?php echo $_SESSION['forthisid'] ?>">
                                        <input type="button" class="edit2" value="Yes" name="yes">
                                    </a>
                                    <a href="appointments.php">
                                        <input type="button" class="cancel2" value="No" name="no" id="noclearance">
                                    </a>
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
            $(".alert").css({"animation":"opacity-foralert 1s forwards"});
        });
        // the alert for the delete function
    

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
        function fireSweetAlert() {
            Swal.fire({
                title: 'CANCEL APPOINTMENT!',
                text: 'Are you sure?',
                icon: 'warning',
                confirmButtonText: 'Yes',
                showDenyButton: 'false',
                denyButtonText: 'No',
            }).then((willdelete) => {
                if (willdelete) {
                    $.ajax({
                        url: "/student/confirm-delete/"+id,
                        success: function(response){
                            swal({
                                title: response.status,
                                text: response.status_text,
                                icon: response.status_icon,
                                button: "Ok",
                            }).then({
                                
                            })
                        }
                    })
                }
    if (result.isConfirmed) {
        Swal.fire('Deleted!', '', 'success')
    } else if (result.isDenied) {
        Swal.fire('No changes done to the appointment', '', 'info')
    }
    })
        }
    
        
    </script>
</body>
</html>