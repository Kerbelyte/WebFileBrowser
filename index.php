<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
    header('Location: auth.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FileBrowser</title>
    <link rel="stylesheet" href="./css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <?php
        $rootDir = __DIR__;
        $diff = '';
        if (!empty($_GET['path'])) {
            $currentPath = $rootDir . $_GET['path'];
            $diff = str_replace($rootDir, '', $currentPath);
        } else {
            $currentPath = $rootDir;
            $diff = '/';
        }

        include 'functions.php';
        if (isset($_POST['submit'])) {
            echo createDir($currentPath, $rootDir);
        }

        if (isset($_POST['delete'])) {
            echo deleteFile($currentPath);
        }

        if (isset($_FILES['image'])) {
            echo uploadFile($diff);
        }

        if (isset($_POST['download'])) {
            downloadFile($currentPath . '/' . $_POST['download']);
            exit;
        }
        
        // HTML
        echo "<h1>Directory contents: $diff</h1>";
        $files = array_slice(scandir($currentPath), 2);
        echo '<table class="table table-striped">
                <tr class="column-names">
                    <td>Type</td>
                    <td>Name</td>
                    <td>Actions</td>
                </tr>';
        for ($i = 0; $i < count($files); $i++) {
            $name = $files[$i];
            if (is_dir($rootDir . $diff . '/' . $name)) {
                if ($diff === '/') {
                    $noWhiteSpaceURL = urlencode($diff . $name);
                } else {
                    $noWhiteSpaceURL = urlencode($diff . '/' . $name);
                }
                $type = 'Directory';
                $name = "<a href=\"index.php?path=$noWhiteSpaceURL\">$name</a>";
                $actions = '';
            } else {
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $type = 'File';
                $actions = '';
                $protectExtensions = array('php', 'css', 'md');
               if (in_array($extension, $protectExtensions) === false) {
                    $actions .= '<form class="action-form" method="POST">
                                    <button type="submit" class="btn_delete" name="delete" value="' . $name . '">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>';
               }

                $actions .= '
                            <form class="action-form" action="" method="POST">
                                <button type="submit" class="btn_download" name="download" value="' . $name . '">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                </button>
                            </form>';
            }
            echo '<tr>
                    <td>' . $type . '</td>
                    <td class="middle-column">' . $name . '</td>
                    <td class="action-buttons">' . $actions . '</td>
                  </tr>';
        }
        echo '</table>';
        if ($rootDir != $currentPath) {
            $urlBack = '?path=' . dirname($diff);
            if (dirname($diff) == '/') {
                $urlBack = '';
            }
            echo '<button><a class="back_button" href="index.php' . $urlBack . '">Back</a></button>';
        }
        ?>
    </div>

    <!-- new directory -->
    <div class="form">
        <form method="POST">
            <label>Create a new directory</label>
            <input type="text" name="directoryName" placeholder="Enter directory name" />
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <!-- upload files -->
    <div class="form">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="image" id="img" style="display:none;" />
            <button style="display: block; width: 100%" type="button">
                <label for="img" style="display: block; cursor: pointer; width: 100%">Choose file</label>
            </button>
            <button <input style="display: block; width: 100%" type="submit" />Upload file</button>
        </form>
    </div>
    <div class="logout">
        Click here to <a href="auth.php?action=logout"> logout
    </div>
</body>

</html>