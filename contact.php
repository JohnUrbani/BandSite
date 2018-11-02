<?php
include 'top.php';

$email = "";

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

    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);

    if ($email == "") {
        $errorMsg[] = 'Please enter your email address';
        $emailError = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;
    }

    if (!$errorMsg) {
        if ($debug)
            print '<p>Form is valid</p>';

        $dataRecord = array();

        $dataRecord[] = $email;

        $myFolder = 'data/';
        $myFileName = 'registration';
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
                        <label class = "required" for = "txtEmail">Email</label>
                        <input 
                        <?php if ($emailERROR) print 'class="mistake"'; ?>
                            id = "txtEmail"
                            maxlength = "45"
                            name = "txtEmail"
                            onfocus = "this.select()"
                            placeholder = "Enter your email"
                            tabindex = "120"
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