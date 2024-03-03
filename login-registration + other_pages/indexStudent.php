<?php
session_start();
if(!isset($_SESSION["user"]))
{
    header("Location: loginStudent.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Student dashboard</title>
    </head>
    <body>
    

        <center><h1>Student Dashboard</h1> </center>
        <center><h2> Welcome <?php echo  $_SESSION["firstname"] . " " . $_SESSION["surname"] . " !"; ?></h2> </center>
        <center><h3> Your Class ID:  <?php echo  $_SESSION["groupID"]; ?></h3> </center>


        <center><h3>Your grades</h3></center>

        <center> 
            <table border="1"> 
                <tr>
                    <th>Student ID </th>
                    <th> Programming </th> 
                    <th> Math </th>
                    <th> Physics </th>
                    <th> Grade Average </th>   
                <tr>    

                <?php 
                ini_set("display_errors", "1");
                require_once "database.php";
                $sql2 ="SELECT * FROM Subjects WHERE studentID = '$_SESSION[id]' ";
                $result1 = mysqli_query($conn, $sql2);
            
                while($final = mysqli_fetch_assoc($result1)) 
                {
                ?>
                <tr>
                    <td><?php echo  $final["studentID"]; ?> </td>
                    <td><?php echo  $final["Programming"]; ?></td>
                    <td><?php echo  $final["Math"]; ?> </td>
                    <td><?php echo  $final["Physics"]; ?> </td>
                    <td>
                <?php
                if($final["Programming"] !== "To be graded" && $final["Math"] !== "To be graded" && $final["Physics"] !== "To be graded")
                { 
                    $avg = (intval($final["Programming"], 10) + intval($final["Math"], 10) + intval($final["Physics"], 10))/3;
                    echo  number_format($avg, 2,','); 
                } else 
                    echo "To be calculated";
                ?>  
                    </td>
                </tr>
                <?php
                }
                ?>

            </table>
        </center> 

    

        <center><h3>Contact info for your class's professors</h3></center>

        <center> 
            <table border="1"> 
                <tr>
                    <th> Name </th>
                    <th> Taught subject </th> 
                    <th> Email address </th>
                <tr>    

                <?php   
                ini_set("display_erros", "1");
                $query = "SELECT * FROM Professor_users WHERE GroupID = '$_SESSION[groupID]'";
                $result2 = mysqli_query($conn, $query);
                while($final2 = mysqli_fetch_assoc($result2))
                {
                ?>
                <tr>
                    <td><?php echo $final2["firstname"] . " " . $final2["surname"]; ?></td>
                    <td><?php echo $final2["subject"]; ?></td>
                    <td><?php echo $final2["email"]; ?></td>    
                </tr>
                <?php
                }
                ?>
            </table>
        </center> 

        <center><h3>Get in touch with your class colleagues</h3></center>

        <center>
            <table border="1">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>

                <?php
                ini_set("display_erros", "1");
                $query = "SELECT * FROM Student_users WHERE GroupID = '$_SESSION[groupID]' AND id != '$_SESSION[id]'";
                $result3 = mysqli_query($conn, $query);
                while($final3 = mysqli_fetch_assoc($result3))
                {
                ?>
                <tr>
                    <td><?php echo $final3["firstname"] . " " . $final3["surname"]; ?></td>
                    <td><?php echo $final3["email"]; ?></td>    
                </tr>
                <?php
                }
                ?>
            </table>
        </center>

        <center> <a href = "logoutStudent.php"> Logout </a> </center>

    </body>
</html>