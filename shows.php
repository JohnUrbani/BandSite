<?php
include ("top.php");

// Open shows CSV file
$filename = 'data/shows.csv';

$file = fopen($filename, "r");
?>
<main>
    <?php
    if ($file) {
        // read all the data
        while (!feof($file)) {
            $shows[] = fgetcsv($file);
        }

        print '<table>';
        foreach ($shows as $show) {
            if ($show[0] != "") {
                print '<tr>';
                print '<td><p>' . $show[0] . '</p></td>';
                print '<td><p>' . $show[1] . '</p></td>';
                print '<td><p>' . $show[2] . '</p></td>';
                print '<td><p>' . $show[3] . '</p></td>';
                print '</tr>';
            }
        }
        print '</table>';
    } // ends if file was opened 
    fclose($file);
    ?>
    <!-- This page will read from a csv file including some info for their shows, make it easy to add new shows to the file -->
</main>
<?php
include ("footer.php");
?>
</html>