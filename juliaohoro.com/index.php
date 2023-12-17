<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

global $pdo;

include "config/connection_database.php";

if (!$pdo) {
    echo "Error: Unable to establish a database connection.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Main page</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/alert.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/header.css">
</head>

<body>
<main class="table" id="customers_table">
    <?php include("_header.php"); ?>
    <section class="table__header">
        <h1>Customer's Orders</h1>
        <div class="input-group">
            <input type="search" placeholder="Search Data...">
<!--            <img src="./images/search.png" alt="">-->
        </div>
    </section>
    <section class="table__body">
        <table>
            <thead>
            <tr>
                <th> Id <span class="icon-arrow">&UpArrow;</span></th>
                <th> Image <span class="icon-arrow">&UpArrow;</span></th>
                <th> Name <span class="icon-arrow">&UpArrow;</span></th>
                <th><span class="icon-arrow">&UpArrow;</span></th>
                <th><span class="icon-arrow">&UpArrow;</span></th>
                <th><span class="icon-arrow">&UpArrow;</span></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT id, name, image, description FROM categories";
            $stmt = $pdo->query($sql);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                ?>
                <tr>
                    <td> <?php echo $row["id"]; ?> </td>
                    <td><img src="/img/<?php echo $row["image"]; ?>" height="75" alt=""></td>
                    <td> <?php echo $row["name"]; ?> </td>
                    <td>
                        <a href="update.php?id=<?php echo $row["id"]; ?>" class="status edit">
                            <span>Edit</span>
                        </a>
                    </td>
                    <td>
                        <a class="status delete" data-toggle="modal" data-target="#myModal_<?php echo $row["id"]; ?>">
                            <span>Delete</span>
                        </a>
                    </td>
                    <td>
                        <a href="#" class="status view">
                            <span>View</span>
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </section>
</main>

<?php foreach ($results as $row) { ?>
<div id="myModal_<?php echo $row["id"]; ?>" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header flex-column">
                <div class="icon-box">
                    <i class="material-icons">&#xE5CD;</i>
                </div>
                <h4 class="modal-title w-100">Are you sure?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <p>Do you really want to delete these records? This process cannot be undone.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteRecord(<?php echo $row["id"]; ?>)">Delete</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script src="js/index.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
    function deleteRecord(recordId) {
        var confirmed = confirm("Are you sure you want to delete this record?");
        if (confirmed) {
            $.ajax({
                type: "POST",
                url: "delete_record.php",
                data: { id: recordId },
                success: function(response) {
                    location.reload();
                },
                error: function(error) {
                    console.error("Error deleting record: ", error);
                }
            });
        }
    }
</script>

</body>

</html>
