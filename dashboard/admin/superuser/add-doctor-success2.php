<?php
session_start();
include('../../../connect.php');
include('../../../functions.php');

if(!isset($_SESSION['name'])){
    header('location:../../../login.php');
}

header('refresh: 2; url=edit-doctor.php');

?>

<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    <script src="sweetalert2.all.min.js"></script>
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
                <div class="company-name" style="font-size:x-large">
                    GSJ Animal Health & Production
                </div>
            </div>
            <div class="links-container">
                <div class="link">
                     <a href="dashboard.php"><span id='link'> Dashboard <img src="images/dashboard.png" alt="" height="20px"></span> </a>
                </div>
                <div class="link">
                    <a href="profiles.php"><span id="link"> Profiles <img src="images/user-small.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <a href="appointments.php"><span id='link'> Appointments <img src="images/appointments.png" alt="" height="20px"></span></a>
                </div>
                <div class="link">
                    <a href="settings.php"><span id='link'> Settings <img src="images/settings.png" alt="" height="20px"></span></a>
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
            <div class="greetings-container" style="padding-right: 20px">
               <a href="edit-doctor.php" style="text-decoration:underline"> <-- Previous Page </a>
            </div>
            <!-- the form that will allow the admin to add a doctor -->
            <div class="main-dashboard-container" id="main-dashboard-container">
                <div class="header">
                   <div class="pagetitle">  EDIT DOCTOR.</div>
                </div>
                <div class="form-container">
                    <div class="form">
                        <form action="edit-doctor3.php" method="post">
                            
                            <input type="text" name="fullname" id="input" value="<?php
                                $namevalue = "SELECT * from doctors where phone = '".$_SESSION['values']."'";
                                $namelink = mysqli_query($conn, $namevalue);
                                $fetchname = mysqli_fetch_assoc($namelink);
                                echo $fetchname['fullname']
                                ?>">

                            <input type="text" name="address" id="input"value="<?php
                                $addressvalue = "SELECT * from doctors where phone = '".$_SESSION['values']."'";
                                $addresslink = mysqli_query($conn, $addressvalue);
                                $fetchaddress = mysqli_fetch_assoc($addresslink);
                                echo $fetchaddress['address']
                                ?>">

                            <div class="col">
                                <div class="col1"><input type="text" name="phone" id="input2" value="<?php
                                $phonevalue = "SELECT * from doctors where phone = '".$_SESSION['values']."'";
                                $phonelink = mysqli_query($conn, $phonevalue);
                                $fetchphone = mysqli_fetch_assoc($phonelink);
                                echo $fetchphone['phone']
                                ?>"></div>
                                <div class="col2"> 
                                    <div class="docfield"> Doctors Field:</div>
                                        <div class="fieldbox">
                                        <select name="field" id="field" value="<?php
                                                $inputvalue = "SELECT * from doctors where phone = '".$_SESSION['values']."'";
                                                $inputlink = mysqli_query($conn, $inputvalue);
                                                $fetchinput = mysqli_fetch_assoc($inputlink);
                                                echo $fetchinput['field']
                                                ?>" required>                                            
                                            <option value="<?php
                                                $inputvalue = "SELECT * from doctors where phone = '".$_SESSION['values']."'";
                                                $inputlink = mysqli_query($conn, $inputvalue);
                                                $fetchinput = mysqli_fetch_assoc($inputlink);
                                                echo $fetchinput['field']
                                                ?>" hidden>Pet</option>
                                            <option value="pet">Pet</option>
                                            <option value="livestock">Livestock</option>
                                        </select> 
                                    </div>
                                </div>
                            </div>    

                            <input type="passoword" name="password" id="input" value="<?php
                                $passwordvalue = "SELECT * from doctors where phone = '".$_SESSION['values']."'";
                                $passwordlink = mysqli_query($conn, $passwordvalue);
                                $fetchpassword = mysqli_fetch_assoc($passwordlink);
                                echo  str_replace('*', '', $fetchpassword['password']);
                                ?>">
                            <input type="submit" value="Edit Doctor" name="edit" id="bttn" class="submit">
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
    <script>
        Swal.fire({
        title: 'Success!',
        text: 'Changes made successfully',
        icon: 'success',
        confirmButtonText: 'Okay'
})
    </script>
</body>
</html>