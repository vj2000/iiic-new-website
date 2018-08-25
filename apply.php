<?php
include("header.php");

?>

<?php
    require 'vendor/autoload.php';
    $message = "";

    if (isset($_REQUEST['recruiterBtn'])) {
        $name = $_REQUEST['contact-name'];
        $phone_no = $_REQUEST['contact-number'];
        $email = $_REQUEST['contact-email'];
        $summary = $_REQUEST['summary'];
        $current_position = $_REQUEST['current-position'];
        $current_nature = $_REQUEST['current-nature'];
        $proposal_for = $_REQUEST['proposal-for'];
        $tide_scheme = $_REQUEST['tide-scheme'];
        
        $upload_dir = '/data/iiic/uploads/';
        // $upload_dir = 'uploads/';
        $file_name = $_FILES["business-plan"]['name'];

        if (empty($_FILES) && empty($_POST)) {
            $message = 'The uploaded zip was too large. You must upload a file smaller than ' . ini_get("upload_max_filesize");
        } else if ($name != "" && $phone_no != "" && $email != "" && $summary != "" && $current_position != "" && $current_nature != "" && $file_name != "") {
            $connection = new mysqli("127.0.0.1", "iiicdba", "iiicdb@2018", "iiicdb");
            // $connection = new mysqli("127.0.0.1", "root", "root", "iiic");

            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }

            $statement = $connection->prepare("INSERT INTO Recruiter (name, email, phone_no, summary, 
                                    current_position, current_nature, proposal_for, tide_scheme, idea_file, file_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $file_hash = hash_file("sha256", $_FILES["business-plan"]['tmp_name']);
            $hashed_filename = $file_hash;
            $upload_file = $upload_dir . basename($hashed_filename);
            // move_uploaded_file($_FILES["business-plan"]['tmp_name'], $upload_file);
            if (move_uploaded_file($_FILES["business-plan"]['tmp_name'], $upload_file)) {
                $statement->bind_param("ssssssssss", $name, $email, $phone_no, $summary, $current_position, $current_nature, $proposal_for, $tide_scheme,
                                    $file_name, $hashed_filename);

                if ($statement->execute()) {
                    $message = "Submitted your current_nature Successfully";
                } else {
                    $message = "There was some error";
                }

                $mail = new PHPMailer(true);                             
                try {
                    //Server settings
                    
                    $mail->isSMTP();                                    // Set mailer to use SMTP
                    $mail->Host = "smtp.gmail.com";  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'iiic@iiita.ac.in';                 // SMTP username
                    $mail->Password = 'ecell@iiic18';                           // SMTP password
                    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 465;                                    // TCP port to connect to

                    //Recipients
                    $mail->Subject = 'Contact Us query email';
                    $mail->setFrom("iiic@iiita.ac.in", $name);
			        $mail->addAddress("iiic@iiita.ac.in");  
			        // $mail->AddReplyTo($email, $name);   // Add a recipient    // Add a recipient
                    //$mail->addAddress('ellen@example.com');               // Name is optional
                    //$mail->addReplyTo('info@example.com', 'Information');
                    //$mail->addCC('cc@example.com');
                    //$mail->addBCC('bcc@example.com');

                    //Attachments
                    $files_to_attach = $_FILES["business-plan"]['tmp_name']; 
                    // $mail->AddAttachment($files_to_attach, $file_name); 
                    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                       // Optional name

                    //Content
                    $mail->isHTML(false);                                  // Set email format to HTML
                    $mail->Subject = 'Application for Incubation';
                    $mes = 'Name: '.$name.'  Email: '.$email.'  Phone: '.$phone_no.'  Executive Summary: '.$summary.'  Brief of current position: '.$current_position.'  Brief of current nature of the company: '.$current_nature.'  Proposal for: '.$proposal_for.'  Applying for tide scheme: '.$tide_scheme;
                    $mail->Body = $mes;
                    
                    
                    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                    $mail->send();
                    //echo 'Message has been sent';
                    $message = "Application Submitted Successfully";
                }
                catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }
            } else {
                $message = "There was some error. Please try again";
            }
                       
            $statement->close();
            $connection->close();
        }
    }
?>

<style>
	.parallax-mirror {
    opacity: 1;
    filter: blur(0px);
}
.inner_cover .inner_cover_content h3 {
    text-transform: capitalize;
    color: #000;
    font-size: 84px;
    font-weight: 500;
    text-align: center;
}
</style>
<!--page title section-->
<section class="inner_cover parallax-mirror" data-parallax="scroll" data-image-src="assets/img/34207-NZINCW.jpeg">
    <div class="overlay_dark"></div>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-12">
                <div class="inner_cover_content">
                    <h3 style="color:#30FF90;">
                        Have an Idea?
                        Apply to get Incubated
                    </h3>
                </div>
            </div>
        </div>
    </div>
</section>
<!--page title section end-->


<!--contact section -->
<section class="pt100 pb100">
    <div class="container">
        <div class="row justify-content-center mt100">
            <div class="col-md-12 col-12">
                <div class="contact_info">
                    <h2 class="col-md-12 col-12" style="text-align: center">
                            Get Incubated
                    </h2>
                    <p class="col-md-12 col-12" style="text-align: center">
                            IIIC is happy to announce that the current round of recruitments are open.<br>
        The application deadline for applying against the current round of recruitments is 12th March, 2018.<br>
        Screening of applications will take place on 13th March, 2018 and final selections at IIIT Allahabad will be done on 16-18th March, 2018.
        
                    </p>

                </div>
            </div>
            <div class="col-md-12 col-12">
                <div class="contact_form">
                <form id="project-contact-form" action="apply.php" method="post" enctype="multipart/form-data"/>
                    <div class="form-group col-md-12" style="padding-top: 1.5em">
                        <input type="text" class="form-control" placeholder="Name" name="contact-name" required>
                    </div>
                    <div class="form-group col-md-12" style="padding-top: 1.5em">
                        <input type="text" class="form-control" placeholder="Phone Number" name="contact-number" required>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="email" class="form-control" placeholder="E-mail" name="contact-email" required>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="text" class="form-control" placeholder="Executive Summary" name="summary" required>
                    </div>
                    <div class="form-group col-md-12">
                        <input style="height: 10em" class="form-control" type="text" name="current-position" placeholder="A brief of your current position"/>
                    </div>
                    <div class="form-group col-md-12">
                        <input class="form-control" type="text" name="current-nature" placeholder="A brief of the current nature of the company seeking incubation"/>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="text" class="form-control"  name="proposal-for" placeholder="Is the proposal for B-plan Writing competition, seeking incubation at IIIC or both?">
                    </div>
                        <div class="form-group col-md-12">
                                <input type="text" class="form-control" name="tide-scheme" placeholder="Whether you would like to apply for seed funding under the TIDE scheme?">
                            </div>
                    <div class="form-group col-md-6">
                            <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                            Upload your business plan in pdf format: <input type="file" name="business-plan" placeholder="Upload your business plan in pdf format" class="form-control" id="reqirements">
                        </div>
                    <div class="form-group text-right">
                        <button class="btn btn-rounded btn-primary" name="recruiterBtn" type="submit">Submit</button>
                    </div>
                    </form>
                    <center>
                    <p style="margin-bottom: 2%;margin-top: 10%; font-size: large">If you need information about the incubation centre, its offerings or if you have any additional requirements, please contact:</p>
                    <p>Dr. Rahul Kala<br>Coordinator, IIIC<br>Email: rkala@iiita.ac.in<br>Ph: +91-7521050744</p>
                </center>
                </div>
            </div>
        </div>

    </div>
</section>
<!--contact section end -->


<!--footer start -->
<?php
include("footer.php");

?>