<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FileBrowser</title>
</head>

<body>

    <div class="BrowserTable">
        <h1>Directory contents:</h1>
        <?php

        $rootDir = '/Applications/XAMPP/xamppfiles/htdocs';
        $diff = '';
        if (!empty($_GET['path'])) {
            $currentPath = $rootDir . '/' . $_GET['path'];
            $diff = str_replace($rootDir, '', $currentPath);
        } else {
            $currentPath = $rootDir;
        }
        $files = array_slice(scandir($currentPath), 2);

        echo '<table>
            <tr>
            <td>Type</td>
            <td>Name</td>
            <td>Actions</td>
            </tr>';
        for ($i = 0; $i < count($files); $i++) {
            $name = $files[$i];
            if (is_dir($rootDir . $diff . '/' . $name)) {
                $type = 'Directory';
                $name = "<a href=\"index.php?path=$diff/$files[$i]\">$name</a>";
            } else {
                $type = 'File';
                $name;
            }
            echo '<tr>
                        <td>' . $type . '</td>
                        <td>' . $name . '</td>
                        <td>'. '#' . '</td>
                    </tr>';
        }

        echo '</table>';

        if (!isset($_COOKIE['counter'])) {
            $cookie = 1;
            setcookie('counter', $cookie);
        } else {
            $cookie = ++$_COOKIE['counter'];
            setcookie('counter', $cookie);
        }
        echo 'You have viewed this page ' . $cookie . ' times' . "<br />";

        ?>
    </div>
</body>

</html>