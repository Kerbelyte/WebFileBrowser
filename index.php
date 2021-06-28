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
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="browser_table">
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

        // directory creation logic
        if (isset($_POST['submit'])) {
            $directoryName = $_POST["directoryName"];
            if (is_dir($rootDir . (isset($_GET['path']) ? $_GET['path'] : '') . '/' . $directoryName)) {
                $message = 'Directory ' . $directoryName . ' already exists!';
            } else if ($directoryName == "") {
                $message = 'Enter name for new directory!';
            } else {
                mkdir($currentPath . '/' . $directoryName);
                $message = 'Directory ' . $directoryName . ' was succesfuly created!';
            }
            echo $message;
        }

        echo "<h1>Directory contents: $diff</h1>";

        $files = array_slice(scandir($currentPath), 2);
        echo '<table>
                <tr class="column_names">
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
            } else {
                $type = 'File';
                $name;
            }
            echo '<tr>
                        <td>' . $type . '</td>
                        <td>' . $name . '</td>
                        <td>' . '#' . '</td>
                    </tr>';
        }
        echo '</table>';
        if ($rootDir != $currentPath) {
            $urlBack = '?path=' . dirname($diff);
            if (dirname($diff) == '/') {
                $urlBack = '';
            }
            echo '<a class="back_button" href="index.php' . $urlBack . '">Back</a>';
        }
        ?>
    </div>
    <form method="post">
        <div>
            <label>Create a new directory</label>
            <input type="text" name="directoryName" placeholder="Enter directory name" />
            <button type="submit" name="submit">Submit</button>
        </div>
    </form>
    <div>
        Click here to <a href="auth.php?action=logout"> logout.
    </div>
</body>

</html>