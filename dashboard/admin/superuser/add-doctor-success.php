<?php
session_start();
include('../../../connect.php');
include('../../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../../login.php');
}

header('refresh: 2; url=doctors.php');

if(isset($_POST['submit'])){
    
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $field = $_POST['field'];
    $password = $_POST['password'];
    
    $s = "select * from doctors where phone = '$phone'";
    $result = mysqli_query($conn, $s);
    $num = mysqli_num_rows($result);

    if (empty($fullname) || empty($address) || empty($field)|| empty($phone) || empty($password)) {
        header('location: add-doctor-blank.php');
        exit;
    }else{
        if($num == 1){
        
            header('location: add-doctor-error.php');
            exit;
       
         }
         else{
             $reg = "INSERT into accountant(fullname, address, phone, password) values ('$fullname', '$address', '$phone', '$password')";
             mysqli_query($conn, $reg);
             header("location:add-accountant-success.php");
             exit;
         }
    }
}

?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="add-doctor-success.css">
    <title>Dashboard</title>
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
                     <a href="dashboard.php"><span id='link'> Dashboard </span> </a>
                </div>
                <div class="link">
                    <a href="doctors.php"><span id="link"> Doctors </span></a>
                </div>
                <div class="link">
                    <a href="appointments.php"><span id='link'> Appointments </span></a>
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
            <!-- the form that will allow the admin to add a doctor -->
            <div class="main-dashboard-container" id="main-dashboard-container">
                <div class="header">
                   <div class="pagetitle">  ADD A DOCTOR.</div>
                </div>
                <div class="form-container">
                    <div class="form">
                        <form action="" method="post">
                            <input type="text" name="fullname" id="input" placeholder="Doctors Fullname">
                            <input type="text" name="address" id="input" placeholder="Doctors Address">
                            <div class="col">
                                <div class="col1"><input type="text" name="phone" id="input2" placeholder="Doctors Phone number"></div>
                                <div class="col2"> 
                                   <div class="docfield"> Doctors Field:</div>
                                    <div class="fieldbox">
                                    <select name="field" id="field" required>
                                        <option value="pet">Pet</option>
                                        <option value="livestock">Livestock</option>
                                    </select> 
                                   </div>
                            </div>
                            </div>    
                            <input type="password" name="password" id="input" placeholder="Password">
                            <input type="submit" value="Add Doctor" name="submit" id="bttn" class="submit">
                        </form>
                    </div>
                </div>
            </div>
            <div class="alert-container" id="target">
                    <div class="alert" id="alert">
                        <div class="warning-container">

                            <div class="warning-header">
                                Doctor added successfully!
                            </div>
                            
                            <form action="appointments.php" method="post">
                                    <div class="buttonsection">
                                        <a href="add-doctor.php">
                                            <input type="button" class="edit2" value="Okay">
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

        // this is to open the modal
        $(function(){
        $(".alert-container").css({"animation":"opacity-animation2 1s forwards"});
        $(".alert").css({"animation":" opacity-foralert 1s forwards"});
    });
        
    </script>
</body>
</html>