<?php
include 'top.php';

$firstName = "";
$lastName = "";
$gender = "Male";
$cool = false;
$satisfied = true;
$willing = true;
$time = "Eastern Standard";
$comments = "";
$email = "";

$firstNameERROR = false;
$lastNameERROR = false;
$genderERROR = false;
$checkERROR = false;
$totalChecked = 0;
$totalERROR = false;
$commentsERROR = false;
$emailERROR = false;

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
    $gender = htmlentities($_POST["radGender"], ENT_QUOTES, "UTF-8");
    
    if (isset($_POST["chkCool"])) {
        $cool = true;
        $totalChecked++;
    } else {
    $cool = false;
    }
    if (isset($_POST["chkSatisfied"])) {
        $satisfied = true;
        $totalChecked++;
    } else {
    $satisfied = false;
    }
    if (isset($_POST["chkWilling"])) {
        $willing = true;
        $totalChecked++;
    } else {
    $willing = false;
    }
    
    $time = htmlentities($_POST["zneTime"], ENT_QUOTES, "UTF-8");
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
    
    if ($gender != "Female" AND $gender != "Male" AND $gender != "Prefer") {
        $errorMsg[] = "Please choose a gender";
        $genderERROR = true;
    }

    if ($totalChecked < 2) {
        $errorMsg[] = "Please check atleast two boxes";
        $checkERROR = true;
    }
    
    if (!$satisfied) {
        $errorMsg[] = "Please check the satisfied box so my boss thinks my website is good";
        $checkERROR = true;
    }

    if ($time == "") {
        $errorMsg[] = "Please select your time zone";
        $timeERROR = true;
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
        $dataRecord[] = $gender;
        $dataRecord[] = $cool;
        $dataRecord[] = $satisfied;
        $dataRecord[] = $willing;
        $dataRecord[] = $time;
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

            print '<h2>Contact Us</h2>';
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
                    
                    <fieldset class="radio <?php if ($genderERROR) print ' mistake'; ?>">
                        <legend>What is your gender?</legend>
                        <p>
                            <label class="radio-field"><input type="radio" id="radGenderMale" name="radGender" value="Male" tabindex="300" 
                            <?php if ($gender == "Male") echo ' checked="checked" '; ?>>
                                Male</label>
                        </p>
                        <p>    
                            <label class="radio-field"><input type="radio" id="radGenderFemale" name="radGender" value="Female" tabindex="310" 
                            <?php if ($gender == "Female") echo ' checked="checked" '; ?>>
                                Female</label>
                        </p>
                        <p>
                            <label class="radio-field"><input type="radio" id="radGenderPrefer" name="radGender" value="Prefer" tabindex="320" 
                            <?php if ($gender == "Prefer") echo ' checked="checked" '; ?>>
                                Prefer not to say</label>
                        </p>
                    </fieldset>
                    
                    <fieldset class="checkbox <?php if ($checkERROR) print ' mistake'; ?>">
                        <legend>Please check all that apply (at least two, including that you're satisfied please):</legend>

                        <p>
                            <label class="check-field">
                                <input <?php if ($cool) print " checked "; ?>
                                    id="chkCool"
                                    name="chkCool"
                                    tabindex="400"
                                    type="checkbox"
                                    value="Cool">I am cool</label>
                        </p>

                        <p>
                            <label class="check-field">
                                <input <?php if ($satisfied) print " checked "; ?>
                                    id="chkSatisfied"
                                    name="chkSatisfied"
                                    tabindex="410"
                                    type="checkbox"
                                    value="Satisfied">I am satisfied with this web site</label>
                        </p>
                        
                        <p>
                            <label class="check-field">
                                <input <?php if ($willing) print " checked "; ?>
                                    id="chkWilling"
                                    name="chkWilling"
                                    tabindex="420"
                                    type="checkbox"
                                    value="Willing">I am willing to share this site with others</label>
                        </p>
                    </fieldset>
                    
                    <fieldset  class="listbox <?php if ($timeERROR) print ' mistake'; ?>">
                        <legend>Time Zone</legend>
                        <p>
                            <select id="zneTime" 
                                    name="zneTime" 
                                    tabindex="500" >
                                <option <?php if ($time == "Eastern Standard") print " selected "; ?>
                                    value="Eastern Standard">Eastern Standard</option>
                                
                                <option <?php if ($time == "Central Standard") print " selected "; ?>
                                    value="Central Standard">Central Standard</option>

                                <option <?php if ($time == "Mountain Standard") print " selected "; ?>
                                    value="Mountain Standard">Mountain Standard</option>

                                <option <?php if ($time == "Pacific Standard") print " selected "; ?>
                                    value="Pacific Standard">Pacific Standard</option>
                                
                                <option <?php if ($time == "Alaska Standard") print " selected "; ?>
                                    value="Alaska Standard">Alaska Standard</option>
                                
                                <option <?php if ($time == "Hawaii-Aleutian Standard") print " selected "; ?>
                                    value="Hawaii-Aleutian Standard">Hawaii-Aleutian Standard</option>
                            </select>
                        </p>
                    </fieldset>
                    
                    <p>
                        <label class="required" for="txtComments">Comments</label>
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