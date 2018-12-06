<?php
include 'top.php';

$firstName = "";
$lastName = "";
$member = "Paul";
$comeTogether = false;
$helloGoodbye = false;
$pennyLane = false;
$dontLetMeDown = false;
$help = false;
$ticketToRide = false;
$page = "Home";
$comments = "";
$email = "";

$firstNameERROR = false;
$lastNameERROR = false;
$memberERROR = false;
$checkERROR = false;
$totalChecked = 0;
$totalERROR = false;
$commentsERROR = false;
$emailERROR = false;
$pageERROR = false;
$errorMsg = array();

$mailed = false;

if (isset($_POST["btnSubmit"])) {

    $thisURL = $domain . $phpSelf;

    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page.</p>';
        $msg .= '<p>Security breach detected and reported.</p>';
        die($msg);
    }

    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $member = htmlentities($_POST["radMember"], ENT_QUOTES, "UTF-8");
    
    if (isset($_POST["chkComeTogether"])) {
        $comeTogether = true;
        $totalChecked++;
    } else {
    $comeTogether = false;
    }
    if (isset($_POST["chkHelloGoodbye"])) {
        $helloGoodbye = true;
        $totalChecked++;
    } else {
    $helloGoodbye = false;
    }
    if (isset($_POST["PennyLane"])) {
        $pennyLane = true;
        $totalChecked++;
    } else {
    $pennyLane = false;
    }
    if (isset($_POST["chkDontLetMeDown"])) {
        $dontLetMeDown = true;
        $totalChecked++;
    } else {
    $dontLetMeDown = false;
    }
    if (isset($_POST["chkHelp"])) {
        $help = true;
        $totalChecked++;
    } else {
    $help = false;
    }
    if (isset($_POST["chkTicketToRide"])) {
        $ticketToRide = true;
        $totalChecked++;
    } else {
    $ticketToRide = false;
    }
    
    
    $page = htmlentities($_POST["favPage"], ENT_QUOTES, "UTF-8");
    $comments = htmlentities($_POST["txtComments"], ENT_QUOTES, "UTF-8");
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);

    if ($firstName == "") {
        $errorMsg[] = 'Please enter your first name';
        $firstNameERROR = true;
    } elseif (!verifyAlphaNum($firstName)) {
        $errorMsg[] = 'Your first name appears to have extra characters.';
        $firstNameERROR = true;
    }
    
    if ($lastName == "") {
        $errorMsg[] = 'Please enter your last name';
        $lastNameERROR = true;
    } elseif (!verifyAlphaNum($lastName)) {
        $errorMsg[] = 'Your last name appears to have extra characters.';
        $lastNameERROR = true;
    }
    
    if ($member != "Paul" AND $member != "John" AND $member != "George" AND $member != "Ringo") {
        $errorMsg[] = "Please choose a Beatle";
        $memberERROR = true;
    }

    if ($totalChecked < 1) {
        $errorMsg[] = "Please check atleast one box";
        $checkERROR = true;
    }

    if ($page == "") {
        $errorMsg[] = "Please select your favorite page";
        $pageERROR = true;
    }

    if ($comments != "") {
        if (!verifyAlphaNum($comments)) {
            $errorMsg[] = "Your comments appear to have extra characters that are not allowed.";
            $commentsERROR = true;
        }
    }
    
    if ($email == "") {
        $errorMsg[] = 'Please enter your email address';
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;
    }

    if (!$errorMsg) {
        if ($debug)
            print '<p>Form is valid</p>';

        $dataRecord = array();

        $dataRecord[] = $firstName;
        $dataRecord[] = $lastName;
        $dataRecord[] = $member;
        $dataRecord[] = $comeTogether;
        $dataRecord[] = $helloGoodbye;
        $dataRecord[] = $pennyLane;
        $dataRecord[] = $dontLetMeDown;
        $dataRecord[] = $help;
        $dataRecord[] = $ticketToRide;
        $dataRecord[] = $page;
        $dataRecord[] = $comments;
        $dataRecord[] = $email;

        $myFolder = 'data/';
        $myFileName = 'contact';
        $fileExt = '.csv';
        $filename = $myFolder . $myFileName . $fileExt;

        $file = fopen($filename, 'a');

        fputcsv($file, $dataRecord);

        fclose($file);

        $message = '<h2>Your information</h2>';

        foreach ($_POST as $htmlName => $value) {
            if ($value != "Register") {
                $message .= '<p>';

                $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 3));

                foreach ($camelCase as $oneWord) {
                    $message .= $oneWord . ' ';
                }

                $message .= ' = ' . htmlentities($value, ENT_QUOTES, "UTF-8") . '</p>';
            }
        }

        $to = $email;
        $cc = '';
        $bcc = '';

        $from = 'Automatic Contact Response <jurbani@uvm.edu>';

        $subject = 'We got your message!';

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);
    }
}
?>
<main>
    <article>
        <?php
        if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) {
            print '<h2>Thank you for contacting us.</h2>';

            print '<p>For your records a copy of this data has ';

            if (!$mailed) {
                print "not ";
            }

            print 'been sent:</p>';
            print '<p>To: ' . $email . '</p>';
            print $message;
        } else {

            print '<h1 style="font-size:2em;text-align:center;">Contact us and Survey Questions</h1>';
            print '<hr>';
            print `<p class = "form-heading">Ask questions and we'll get back to you!</p>`;

            if ($errorMsg) {
                print '<div id="errors">' . PHP_EOL;
                print '<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
                print '<ol>' . PHP_EOL;

                foreach ($errorMsg as $err) {
                    print '<li>' . $err . '</li>' . PHP_EOL;
                }
                print '</ol>' . PHP_EOL;
                print '</div>' . PHP_EOL;
            }
            ?>   
            <form action = "<?php print $phpSelf; ?>" 
                  id = "frmRegister"
                  method = "post">

                <fieldset class = "contact">
                    <legend>Contact Information</legend>


                    <p>
                        <label class = "required" for = "txtFirstName">First Name</label>
                        <input 
                        <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                            autofocus
                            id = "txtFirstName"
                            maxlength = "45"
                            name = "txtFirstName"
                            onfocus = "this.select()"
                            placeholder = "Enter your first name"
                            tabindex = "100"
                            type = "text"
                            value = "<?php print $firstName; ?>"
                            >            
                    </p>
                    
                    <p>
                        <label class = "required" for = "txtLastName">Last Name</label>
                        <input 
                        <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                            id = "txtLastName"
                            maxlength = "45"
                            name = "txtLastName"
                            onfocus = "this.select()"
                            placeholder = "Enter your last name"
                            tabindex = "200"
                            type = "text"
                            value = "<?php print $lastName; ?>"
                            >            
                    </p>
                    
                    <fieldset class="radio <?php if ($memberERROR) print ' mistake'; ?>">
                        <legend>Who is your favorite Beatle?</legend>
                        <p>
                            <label class="radio-field"><input type="radio" id="radMemberPaul" name="radMember" value="Paul" tabindex="300" 
                            <?php if ($member == "Paul") echo ' checked="checked" '; ?>>
                                Paul</label>
                        </p>
                        <p>    
                            <label class="radio-field"><input type="radio" id="radMemberJohn" name="radMember" value="John" tabindex="310" 
                            <?php if ($member == "John") echo ' checked="checked" '; ?>>
                                John</label>
                        </p>
                        <p>
                            <label class="radio-field"><input type="radio" id="radMemberGeorge" name="radMember" value="George" tabindex="320" 
                            <?php if ($member == "George") echo ' checked="checked" '; ?>>
                                George</label>
                        </p>
                        <p>
                            <label class="radio-field"><input type="radio" id="radMemberRingo" name="radMember" value="Ringo" tabindex="330" 
                            <?php if ($member == "Ringo") echo ' checked="checked" '; ?>>
                                Ringo</label>
                        </p>
                    </fieldset>
                    
                    <fieldset class="checkbox <?php if ($checkERROR) print ' mistake'; ?>">
                        <legend>Please check all of your favorite songs</legend>

                        <p>
                            <label class="check-field">
                                <input <?php if ($comeTogether) print " checked "; ?>
                                    id="chkComeTogether"
                                    name="chkComeTogether"
                                    tabindex="400"
                                    type="checkbox"
                                    value="Come Together">Come Together</label>
                        </p>

                        <p>
                            <label class="check-field">
                                <input <?php if ($helloGoodbye) print " checked "; ?>
                                    id="chkHelloGoodbye"
                                    name="chkHelloGoodbye"
                                    tabindex="410"
                                    type="checkbox"
                                    value="Hello Goodbye">Hello, Goodbye</label>
                        </p>
                        
                        <p>
                            <label class="check-field">
                                <input <?php if ($pennyLane) print " checked "; ?>
                                    id="chkPennyLane"
                                    name="chkPennyLane"
                                    tabindex="420"
                                    type="checkbox"
                                    value="Penny Lane">Penny Lane</label>
                        </p>
                        
                        <p>
                            <label class="check-field">
                                <input <?php if ($dontLetMeDown) print " checked "; ?>
                                    id="chkDontLetMeDown"
                                    name="chkDontLetMeDown"
                                    tabindex="430"
                                    type="checkbox"
                                    value="Don't Let Me Down">Don't Let Me Down</label>
                        </p>

                        <p>
                            <label class="check-field">
                                <input <?php if ($help) print " checked "; ?>
                                    id="chkHelp"
                                    name="chkHelp"
                                    tabindex="440"
                                    type="checkbox"
                                    value="Help!">Help!</label>
                        </p>
                        
                        <p>
                            <label class="check-field">
                                <input <?php if ($ticketToRide) print " checked "; ?>
                                    id="chkTicketToRide"
                                    name="chkTicketToRide"
                                    tabindex="450"
                                    type="checkbox"
                                    value="Ticket to Ride">Ticket to Ride</label>
                        </p>
                    </fieldset>
                    
                    <fieldset  class="listbox <?php if ($pageERROR) print ' mistake'; ?>">
                        <legend>Favorite Page</legend>
                        <p>
                            <select id="favPage" 
                                    name="favPage" 
                                    tabindex="500" >
                                <option <?php if ($page == "Home") print " selected "; ?>
                                    value="Home">Home</option>
                                
                                <option <?php if ($page == "About the Band") print " selected "; ?>
                                    value="About the Band">About the Band</option>

                                <option <?php if ($page == "Shows") print " selected "; ?>
                                    value="Shows">Shows</option>

                                <option <?php if ($page == "Photos") print " selected "; ?>
                                    value="Photos">Photos</option>
                                
                                <option <?php if ($page == "Videos") print " selected "; ?>
                                    value="Videos">Videos</option>
                                
                                <option <?php if ($page == "Contact") print " selected "; ?>
                                    value="Contact">Contact</option>
                            </select>
                        </p>
                    </fieldset>
                    
                    <p>
                        <label class="required" for="txtComments">Comments and Questions</label>
                        <textarea <?php if ($commentsERROR) print 'class="mistake"'; ?>
                            id="txtComments" 
                            name="txtComments" 
                            onfocus="this.select()" 
                            tabindex="600"><?php print $comments; ?></textarea>
                    </p>

                    <p>
                        <label class = "required" for = "txtEmail">Email</label>
                        <input 
                        <?php if ($emailERROR) print 'class="mistake"'; ?>
                            id = "txtEmail"
                            maxlength = "45"
                            name = "txtEmail"
                            onfocus = "this.select()"
                            placeholder = "Enter your email"
                            tabindex = "700"
                            type = "text"
                            value = "<?php print $email; ?>"
                            >            
                    </p>
                </fieldset><!-- ends contact -->

                <fieldset class = "buttons">
                    <legend></legend>
                    <input class = "button" id = "btnSubmit" name = "btnSubmit" tabindex = "900" type = "submit" value = "Register">
                </fieldset><!-- ends buttons -->
            </form>
            <?php
        }
        ?>
    </article>
</main>

<?php include 'footer.php'; ?>

</html>