<?php
    require_once('var/protect.php');
    require_once('var/constants.php');
    require_once('services/request_service.php');

    $statistics = getAllRequests();
    if (gettype($statistics) != 'array') {
        echo $statistics;
        $statistics = array();
    }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <?php require_once('html/head.html'); ?>
    <title>Project 4 - Task Statistics</title>
</head>
<body>
<?php require_once('html/header.php'); ?>
<main>
    <div class="container-fluid">
        <h3 class="pt-3">Task Statistics for all users</h3>

        <hr class="border-2 border-top border-p4-accent mb-3">

        <table class="table table-striped">
            <tbody>
            <tr>
                <th scope="col">User</th>
                <th scope="col">Create</th>
                <th scope="col">Read</th>
                <th scope="col">Read All</th>
                <th scope="col">Update</th>
                <th scope="col">Delete</th>
            </tr>

            <?php

            foreach ($statistics as $record) {
                echo '<tr><td>'. $record->username.'</td>';
                echo '<td>'. $record->create_request.'</td>';
                echo '<td>'. $record->read_request.'</td>';
                echo '<td>'. $record->readAll_request.'</td>';
                echo '<td>'. $record->update_request.'</td>';
                echo '<td>'. $record->delete_request.'</td></tr>';
            }
            ?>
            </tbody>
        </table>
</main>
<?php require_once('html/footer.html'); ?>
</body>
</html>