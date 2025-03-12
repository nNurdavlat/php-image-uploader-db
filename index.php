<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Save images</title>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">   <!--For the browser to be able to send a file.-->
        <input type="file" name="image">  <!--The user can choose an image from their computer.-->
        <button type="submit">Submit</button>
    </form>
    <?php
        require "DB.php";
        $db = new DB();
        // var_dump($_FILES);  // See what is coming to us.
        if (isset($_FILES['image'])) {  // Check if the image is received.
            $file = $_FILES['image'];
            $file_name = $file['name'];
            $file_size = $file['size'];
            $file_tmp = $file['tmp_name'];
            $file_type = $file['type'];
            $file_error = $file['error'];

            $file_ext = explode('.', $file_name); // Extract the dot from '.jpeg', '.png', '.jpg' and etc.
            $file_ext = strtolower(end($file_ext));

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed)) {  // It is necessary to check by type to prevent hacking.
                if ($file_error === 0) {
                    if ($file_size <= 2097152) {
                        $file_name_new = uniqid() . '.' . $file_ext;
                        $file_path = 'uploads/' . $file_name_new;

                        if (move_uploaded_file($file_tmp, $file_path)) { // From where to where.
                            if ($db->saveImageAddress($file_path)) {
                                $file_path = $db->getLastImage();
                                foreach ($file_path as $value) {
                                    $image_url = $_ENV['APP_URL'] . $value['path'];
                                    echo "<img src='$image_url' alt='Yuklangan rasm' style='max-width:300px; display:block; margin-top:10px;'>";
                                }
                            }
                        }
                    }
                }
            }
        }
    ?>
</body>
</html>
