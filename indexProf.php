<?php
session_start();
if(!isset($_SESSION["user_prof"]))
{
    header("Location: loginProf.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Professor dashboard</title>
    </head>
    <body>
    

        <center><h1>Professor Dashboard </h1></center>
        <center> <h2> Welcome <?php echo  $_SESSION["firstname_prof"] . " " . $_SESSION["surname_prof"] . " !"; ?></h2></center>
        <center> <h3> Your Class ID:  <?php echo  $_SESSION["groupID_prof"]; ?></h3></center>

        <center><h3>View your student's grades</h3></center>

        <center>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th><?php echo $_SESSION["subject"] . " grade"; ?> </th>
                    <th>Email</th>
                </tr>
                <?php
                ini_set("display_errors", "1");
                require_once("database.php");
                $query1 = "SELECT * FROM Subjects JOIN Student_users ON Subjects.studentID = Student_users.id 
                WHERE Subjects.GroupID = '$_SESSION[groupID_prof]'";
                $result4 = mysqli_query($conn, $query1);
                while($final4 = mysqli_fetch_assoc($result4))
                {
                ?>
                <tr>
                    <td><?php echo $final4["studentID"]; ?></td>
                    <td><?php echo $final4["firstname"] . " " . $final4["surname"]; ?></td>
                    <td><?php echo $final4["$_SESSION[subject]"]; ?></td>
                    <td><?php echo $final4["email"]; ?></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </center>

        <center><h3>Modify students' grade</h3></center>

        <center>
            <form action="indexProf.php" method="post">
                <input type="text" name="student_id" placeholder="Student's ID">
                <input type="number" name="grade" placeholder="New grade">
                <input type="submit" name="modify" value="Modify grade">
            </form>
        </center>

        <?php
        ini_set("display_errors", "1");
        if(isset($_POST["modify"]))
        {
            $studentID = $_POST["student_id"];
            $newGrade = $_POST["grade"];
            $errors = array();

            if(empty($studentID) OR empty($newGrade))
            {
                array_push($errors, "No data inserted. ");
            }

            if($newGrade > 10 OR $newGrade < 1)
            {
                array_push($errors, "The grade should be between 1 and 10! ");
            }

            require_once("database.php");

            $sql1 = "SELECT * FROM Subjects WHERE studentID = '$studentID'";
            $res = mysqli_query($conn, $sql1);
            $rowCount = mysqli_num_rows($res);
            $res1 = mysqli_fetch_assoc(mysqli_query($conn, $sql1));
            
            if($rowCount == 0)
            {
                array_push($errors, "This student ID does not exist. ");
            }

            if(count($errors) > 0) 
            {
                foreach($errors as $error)
                    echo $error;
            } else if($_SESSION["groupID_prof"]==$res1["GroupID"])
            {
                require_once "database.php";
                $update = "UPDATE Subjects SET $_SESSION[subject] = '$newGrade' WHERE studentID = '$studentID'";
                $result = mysqli_query($conn, $update);

                if(!$result)
                {
                    die("Something went wrong");
                    echo ini_set("display error", "1");
                }
                echo "<meta http-equiv = 'refresh' content=0>";
            }
            else
            {
                echo "This student does not belong to this class! 
                Can't modify their grade. ";
            }
            
        }
        ?>

        <center><h3>View other professors</h3></center>

        <center>
            <table border="1">
                <tr>
                    <th>Name</th>
                    <th>Taught Subjects</th>
                    <th>Email</th>
                    <th>Class ID</th>
                </tr>
                <?php
                ini_set("display_errors", "1");
                $query1 = "SELECT * FROM Professor_users WHERE ID != '$_SESSION[id_prof]'";
                $result5 = mysqli_query($conn, $query1);
                while($final5 = mysqli_fetch_assoc($result5))
                {
                ?>
                <tr>
                    <td><?php echo $final5["firstname"] . " " . $final5["surname"];  ?></td>
                    <td><?php echo $final5["subject"]; ?></td>
                    <td><?php echo $final5["email"]; ?></td>
                    <td><?php echo $final5["GroupID"]; ?></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </center>


        <center> <a href = "logoutProf.php"> Logout </a> </center>

    </body>
    <script>
    if(window.history.replaceState) 
    {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</html>