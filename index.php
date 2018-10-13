<?php

if (!empty($_FILES['files']['name'] [0])) {

    $files = $_FILES['files'];
    $uploaded = [];
    $failed = [];
    $allowed = ['png', 'jpg', 'jpeg', 'gif'];

    foreach ($files['name'] as $position =>$file_name) {
        $file_tmp = $files['tmp_name'][$position];
        $file_size = $files['size'][$position];
        $file_error = $files['error'][$position];

        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        if (in_array($file_ext, $allowed)) {

            if($file_error === 0) {
                if ($file_size < 1048576) {

                    $file_name_new = uniqid('image') . '.' . $file_ext;
                    $file_destination = 'upload/' . $file_name_new;

                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        $uploaded[$position] = $file_destination;
                    } else {
                        $failed[$position] = "Le fichier n'a pas été envoyé";
                    }

                } else {
                    $failed[$position] = "Le fichier dépasse la limite de taille";
                }
            } else {
                $failed[$position] = "Le fichier n'a pas été chargé.";
            }

        } else {
            $failed[$position] = "Seuls les fichiers jpg, jpeg, png et gif sont autorisés.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Laisse ton file</title>
</head>
<body>

<div class="container">
    <div class="row">
        <?php
        if (isset($failed)){
            foreach ($failed as $value) {?>
                <div class="row">
                    <div class="col">
                        <p><?=$value;?></p>
                    </div>
                </div>
            <?php }
        }
        ?>
    </div>
    <form enctype="multipart/form-data" method="post">
        <div class="form-group">
            <label for='upload'>Ajouter:</label>
            <input id='upload' class="form-control-file" name="files[]" type="file" multiple="multiple">
            <input type="submit" name="submit" value="Submit">
        </div>
    </form>


    <div class="row">
        <?php
        $liste = [];
        if ($dossier = opendir('upload/')) {
            while (($item = readdir($dossier)) !== false) {
                if ($item[0] == '.') {
                    continue;
                }
                $liste[] = $item;
            }
            closedir($dossier);
            rsort($liste);
            foreach ($liste as $val) { ?>
                <div class="col-md-4">
                    <img src= "upload/<?=$val ?>" alt="image" class="img-fluid">
                    <h5><?= $val ?></h5>
                    <a href="index.php?delete=<?= $val ?>" class="btn btn-primary">Delete</a>
                </div>
            <?php }
        }
        if (!empty($_GET['delete'])) {
            if (file_exists('upload/' . $_GET['delete'])) {
                unlink('upload/' . $_GET['delete']);
                header('Location: index.php');
            }
        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>


