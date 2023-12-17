<?php
global $pdo;
if($_SERVER["REQUEST_METHOD"] == "POST") {
    include "config/connection_database.php";
        $name = $_POST["name"];
    $image_name="";
    if(isset($_FILES["image"])) {
        $directory = "img";
        $image_name = uniqid().".".pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $save = $_SERVER["DOCUMENT_ROOT"]."/".$directory."/".$image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $save);
    }
        $description = $_POST["description"];
//        echo "$name $image $description\n";

        $sql = "INSERT INTO categories (name, image, description) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([$name, $image_name, $description]);
        header("Location: /");
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add new category</title>
<!--    <link rel="stylesheet" href="./css/bootstrap.min.css">-->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/header.css">

    <style>
        input::-webkit-file-upload-button {
            background-color: #6c00bd;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .image-preview-container {
            display: flex;
            align-items: center;
        }

        #previewImage {
            width: 50%;
            height: auto;
            max-width: 150px;
            margin-right: 10px;
        }

        .add-photo-btn {
            cursor: pointer;
            padding: 5px;
            background-color: #6c00bd;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .add-photo-btn:hover {
            background-color: #4d0073;
        }

    </style>
</head>

<body>
<main class="table" id="customers_table">
    <?php include("_header.php") ?>
    <section class="table__header">
        <h1>Add new category</h1>
    </section>
    <section class="table__body">
        <div class="container">
            <form class="col-md-8 offset-md-2" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Select Image</label>
                    <div class="image-preview-container">
                        <img id="previewImage" src="https://bgkllen.org.au/wp-content/uploads/2019/04/noimage-700x700.jpg" alt="Selected image" class="img-thumbnail">
                        <label for="image" class="add-photo-btn">Add Photo</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewFile()" style="display: none;">
                    </div>
                </div>


                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="description" required></textarea>
                </div>


                <button type="submit" class="">Add</button>
            </form>
        </div>
    </section>
</main>
<script src="./js/create.js"></script>
<script>
    function previewFile() {
        var preview = document.getElementById('previewImage');
        var fileInput = document.getElementById('image');
        var file = fileInput.files[0];

        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "https://bgkllen.org.au/wp-content/uploads/2019/04/noimage-700x700.jpg";
        }
    }
    function openFileInput() {
        document.getElementById('image').click();
    }
</script>
</body>

</html>
