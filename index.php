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
        $rootDir = '/Applications/XAMPP/xamppfiles/htdocs';
        $diff = '';
        if (!empty($_GET['path'])) {
            $currentPath = $rootDir . '/' . $_GET['path'];
            $diff = str_replace($rootDir, '', $currentPath);
            echo '<h1>Directory contents: ' . "$diff" . '</h1>';
        } else {
            $currentPath = $rootDir;
            echo '<h1>Directory contents: ' . "$currentPath" . '</h1>';
        }
        $files = array_slice(scandir($currentPath), 2);

        echo '<table>
            <tr class="column_names">
            <td>Type</td>
            <td>Name</td>
            <td>Actions</td>
            </tr>';
        for ($i = 1; $i < count($files); $i++) {
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
                        <td>' . '#' . '</td>
                    </tr>';
        }

        echo '</table>';



        ?>
        <button class="back_button" href="#" onclick="history.go(-1);">Back</button>
    </div>
</body>

</html>