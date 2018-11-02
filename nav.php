<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ul>
        <?php
        // Should we make the index/home page also the news page?
        print '<li class="';
        if ($path_parts['filename'] == "index") {
            print ' activePage ';
        }
        print '">';
        print '<a href="index.php">Home</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "news") {
            print ' activePage ';
        }
        print '">';
        print '<a href="news.php">News</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "about") {
            print ' activePage ';
        }
        print '">';
        print '<a href="about.php">About</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "shows") {
            print ' activePage ';
        }
        print '">';
        print '<a href="shows.php">Shows</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "photos") {
            print ' activePage ';
        }
        print '">';
        print '<a href="photos.php">Photos</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "videos") {
            print ' activePage ';
        }
        print '">';
        print '<a href="videos.php">Videos</a>';
        print '</li>';
        
        print '<li class="';
        if ($path_parts['filename'] == "contact") {
            print ' activePage ';
        }
        print '">';
        print '<a href="contact.php">Contact</a>';
        print '</li>';

        ?>
    </ul>
</nav>
