<?php
include ("top.php");

// Open shows CSV file
$filename = 'data/bios.csv';

$file = fopen($filename, "r");
?>
<main>
    <h2 style="text-align:center;">Learn About the Band</h2>
    <?php
    if ($file) {
        // read all the data
        while (!feof($file)) {
            $bios[] = fgetcsv($file);
        }
        
        $alternate = true;
        
        foreach ($bios as $bio) {
            if ($bio[0] != "") {
                if ($alternate) {
                    print '<article>';
                    print '<header><h2>' . $bio[1] . '</h2></header>';
                    print '<p><img = src="images\\' . $bio[0] . '", alt=' . $bio[1] . ', style="float:left;width:200px;height:200px;"><p>' . $bio[2] . '</p>';
                    print '</article>';
                    $alternate = false;
                } else {
                    print '<article>';
                    print '<header><h2>' . $bio[1] . '</h2></header>';
                    print '<p><img = src="images\\' . $bio[0] . '", alt=' . $bio[1] . ', style="float:right;width:200px;height:200px;"><p>' . $bio[2] . '</p>';
                    print '</article>';
                    $alternate = true;
                }
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