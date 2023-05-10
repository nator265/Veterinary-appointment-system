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
// if(isset($_GET['approve'])){
//     $id = $_GET['approve'];
//     $approved = 'yes';
//     // inserting approval status into the database
//     $entry = "UPDATE appointments SET approved = '$approved' WHERE appointments.ap_id = '$id'";
//     $link = mysqli_query($conn, $entry);
//     // header('location: appointments.php');
// }


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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="appointments.css">
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
                    <a href="doctors.php"><span id='link'> Doctors </span></a>
                </div>
                <div class="link">
                    <span id='link'> Appointments </span>
                </div>
                <div class="link">
                    <a href="settings.php"><span id='link'> Settings </span> </a>
                </div>
                <div class="link">
                    <a href="../../logout.php" style="text-decoration: none; color: white">
                        <button class="logout" id="bttn">Logout</button>
                    </a>
                </div>
            </div>
        </div>

        <!-- thi is the modal for the appointments registration -->
        <div class="modal-container" id="modal-container">
            <div class="modal">
                <a href="appointments.php">
                    <div class="close" onclick="document.getElementById('modal-container').style.display='none'">&times;</div>
                </a>
                <div class="form-container">
                    <div class="form-header">
                        <h1 style="text-align: center; color: white;">
                            Book An Appointment.
                        </h1>
                    </div>
                    <form action="appointments.php" method="POST" onsubmit="return validateForm(this);">
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
                        <div style="margin-bottom: 5px;" id="msg">Select Service:</div>
                        <input type="checkbox" class="checkboxes" name="ap_type[]" id="checkbox" value="Vaccination"> Vaccination <br>
                        <input type="checkbox" class="checkboxes" name="ap_type[]" id="checkbox" value="Check up"> Check up <br>
                        <input type="checkbox" class="checkboxes" name="ap_type[]" id="checkbox" value="Diet"> Diet<br>
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
                <div class="search-container">
                <input type="text" id="search" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
                <img src="images/search.webp" height="30px" width="30px" alt=" search">
                </div>
                <div class="table-container"> 
                    <div class="table" >
                        <table id="myTable">
                            <tr class="first-row">
                                <th>
                                    Owner
                                </th>
                                <th>
                                    Animal Type
                                </th>
                                <th>
                                    Doctor
                                </th>
                                <th>
                                    Appointment Type
                                </th> 
                                <th>
                                    Date
                                </th>
                                <th>
                                   Approval Status
                                </th>
                                <th>
                                    Session Status
                                </th>
                            </tr>
                            <div class="approved-tab" style="display:none">
                                <?php
                                    // retrieve data for the user matching the phone number
                                    // if($fetch_rest2['phone'] == )
                                    // retrieving data from the database for the user to see
                                    $approved = "yes";

                                    $retrieve = "SELECT appointments.fullname, appointments.animal, appointments.ap_type, appointments.ap_date, appointments.approved, appointments.session_expiry, doctors.fullname as 'name'
                                        from appointments INNER JOIN doctors on appointments.field = doctors.field ORDER BY appointments.ap_date asc";

                                    $link = mysqli_query($conn, $retrieve);
                                    checkSQL($conn, $link);
                                    $row = mysqli_num_rows($link);
                                    if (!$link){
                                        die("Invalid query: " .$conn->error);
                                    }
                                    // reading data contained in each row
                                    while($row = $link->fetch_assoc()){
                                            $ap_date2 = date("d-m-Y", strtotime($row["ap_date"]));
                                            // $ap_id = $row["ap_id"];
                                        ?>
                                        <tr>
                                        <td><?php echo $row["fullname"] ?></td>
                                        <td><?php echo $row["animal"] ?></td>
                                        <td><?php echo $row["name"] ?></td>
                                        <td><?php echo $row["ap_type"] ?></td>
                                        <td><?php echo $ap_date2 ?></td>
                                        <td><?php echo $row["approved"] ?></td>
                                        <td><?php echo $row["session_expiry"] ?></td>
                                        <!-- <td><button class="action-buttons" id="approve-button" value="<?php echo $ap_id ?>" onclick="approve(this.value)">Approve</a></button>
                                        <td><button class="action-buttons" id="reject-button" name="reject">Reject</a></button> -->
                                        </tr>
                                        
                                    <?php } ?>
                            </div>        
                        </table>
                    </div>
                </div> 
            </div>
        </div>
        
        <script src="jquery.js"></script>
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
    function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
    // changing the dates form-container
    $(function(){
                $(".table").css({"animation":"fourth-animation 1s forwards"});
            })
        
    </script>
</body>
</html>