<?php
include ("top.php");

// Open shows CSV file
$filename = 'data/shows.csv';

$file = fopen($filename, "r");
?>
<main>
    <h2>Find Live Shows and Tickets</h2>
    <hr>
    <?php
    if ($file) {
        // read all the data
        while (!feof($file)) {
            $shows[] = fgetcsv($file);
        }
        
        foreach ($shows as $show) {
            if ($show[0] != "") {
                print '<article>';
                print '<header><h2>' . $show[0] . '</h2></header>';
                print '<hr>';
                print '<h1>' . $show[1] . '</h1>';
                print '<p>Show on ' . $show[2] . ', <a href="' . $show[3] . '">get tickets here.</a></p>';
                print '</article>';
            }
        }
    } // ends if file was opened 
    fclose($file);
    ?>
    <!-- This page will read from a csv file including some info for their shows, make it easy to add new shows to the file -->
</main>
<?php
include ("footer.php");
?>
</html>