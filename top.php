<?php
$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

$path_parts = pathinfo($phpSelf);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        print '<title>';
        if ($path_parts['filename'] == "about") {
            print 'About the Band';
        }

        if ($path_parts['filename'] == "contact") {
            print 'Contact Us';
        }

        if ($path_parts['filename'] == "index") {
            print 'Home';
        }

        /*if ($path_parts['filename'] == "news") {
            print '';
        }*/

        if ($path_parts['filename'] == "photos") {
            print 'Photos';
        }

        if ($path_parts['filename'] == "shows") {
            print 'Shows';
        }

        if ($path_parts['filename'] == "videos") {
            print 'Videos';
        }
        print('</title>')
        ?>

        <meta charset="utf-8">
        <meta name="author" content="John Urbani & Alex Silence">

        <?php
        print '<meta name="description" content="';
        if ($path_parts['filename'] == "about") {
            print 'Learn about the Beatles band members';
        }

        if ($path_parts['filename'] == "contact") {
            print 'Contact the owners of the website';
        }

        if ($path_parts['filename'] == "index") {
            print 'Home page with recent Beatles news';
        }

        /*if ($path_parts['filename'] == "news") {
            print '';
        }*/

        if ($path_parts['filename'] == "photos") {
            print 'Photos of the Beatles';
        }

        if ($path_parts['filename'] == "shows") {
            print 'Show times for Paul, Ringo, and tribute bands';
        }

        if ($path_parts['filename'] == "videos") {
            print 'Beatles music videos and information about them';
        }
        print('">')
        ?>
        
        <?php
        $domain = '//';
        
        $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');
        
        $domain .= $server;
        
        if ($debug) {
            print '<p>php Self: ' . $phpSelf;
        }
        
        require_once 'lib/security.php';
        
        include_once 'lib/validation-functions.php';
        
        include_once 'lib/mail-message.php';

        ?>


        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/custom.css" type="text/css" media="screen">

    </head>

    <?php

    print '<body id="' . $path_parts['filename'] . '">';
    ?>
   
    <?php
    //include ("header.php");
    include ("nav.php");
    ?>