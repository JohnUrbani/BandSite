<?php
include ("top.php");

$directory = "images/gallery";
$images = glob($directory . "/*.jpg");
?>
<main>
<?php
foreach($images as $image)
{
    echo '<article>';
    echo    '<a target="_blank" href="' . $image . '">';
    echo    '<img src="' . $image . '" alt="" width="300" height="200">';
    echo    '</a>';
    echo '</article>';
}
?>
    <p style='padding:50em;'></p>
</main>
<?php
include ("footer.php");
?>
</html>