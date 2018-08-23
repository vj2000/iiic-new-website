<?php
include("header.php");

?><!--header end here-->

<?php
	require 'vendor/autoload.php';
    $message = "";

    if (isset($_REQUEST['submitBtn'])) {
        $name = $_REQUEST['name'];
        $idea = $_REQUEST['idea'];
        $email = $_REQUEST['email'];
        $current_status = $_REQUEST['current-status'];
        $skills = $_REQUEST['skills'];
        $requirements = $_REQUEST['requirements'];
        
        $upload_dir = '/data/iiic/uploads/';
        // $upload_dir = 'uploads/';
        $file_name = $_FILES["document"]['name'];

        if (empty($_FILES) && empty($_POST)) {
            $message = 'The uploaded zip was too large. You must upload a file smaller than ' . ini_get("upload_max_filesize");
        } else if ($name != "" && $idea != "" && $email != "" && $current_status != "" && $skills != "" && $requirements != "") {
            $connection = new mysqli("127.0.0.1", "iiicdba", "iiicdb@2018", "iiicdb");
            // $connection = new mysqli("127.0.0.1", "root", "root", "iiic");

            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }

            $statement = $connection->prepare("INSERT INTO Help (name, email, idea, current_status, skills, requirements, document) VALUES (?, ?, ?, ?, ?, ?, ?)");

            $file_hash = hash_file("sha256", $_FILES["document"]['tmp_name']);
            $hashed_filename = $file_hash;
            $upload_file = $upload_dir . basename($hashed_filename);
            // move_uploaded_file($_FILES["business-plan"]['tmp_name'], $upload_file);
            if (move_uploaded_file($_FILES["document"]['tmp_name'], $upload_file)) {
                $statement->bind_param("sssssss", $name, $email, $idea, $current_status, $skills, $requirements,
                                    $hashed_filename);

                if ($statement->execute()) {
                    $message = "Submitted Successfully";
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
                    $mail->Subject = 'Got problem email';
                    $mail->setFrom("iiic@iiita.ac.in", $name);
			        $mail->addAddress("iiic@iiita.ac.in");  
			        // $mail->AddReplyTo($email, $name);   // Add a recipient    // Add a recipient
                    //$mail->addAddress('ellen@example.com');               // Name is optional
                    //$mail->addReplyTo('info@example.com', 'Information');
                    //$mail->addCC('cc@example.com');
                    //$mail->addBCC('bcc@example.com');

                    //Attachments
                    $files_to_attach = $_FILES["document"]['tmp_name']; 
                    // $mail->AddAttachment($files_to_attach, $file_name); 
                    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                       // Optional name

                    //Content
                    $mail->isHTML(false);                                  // Set email format to HTML
                    
                    $mes = 'Name: '.$name.'  Email: '.$email.'  Idea: '.$idea.'  Current Status: '.$current_status.'  Skills: '.$skills.'   Requirements: '.$requirements;
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

<!--page title section-->
<section class="inner_cover parallax-mirror" data-parallax="scroll" data-image-src="assets/img/OK8NB00.jpeg">
    <div class="overlay_dark"></div>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-12">
                <div class="inner_cover_content">
                    <h3>
                        Got A Problem?
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
                        Got A Problem
                    </h2>
                    <p class="col-md-12 col-12" style="text-align: center">
                            Have a fantastic idea but do not know the next steps? Have the zeal to change the world but cannot sharpen up the idea? Have wonderful skills but lack teammates?

                           <p class="col-md-12 col-12" style="text-align: center"> While applying for incubation is a combursome process, IIIC helps young minds to frame an idea, improve upon the idea, network with other young minds to create cross-skill teams, and to finally develop the business plan.
                            </p>
                            <p class="col-md-12 col-12" style="text-align: center">If you have the slightest of inclination, we are happy to help. Please give us as much details as possible, and we will get back to you for help.
                                </p>
                    </p>

                </div>
            </div>
            <div class="col-md-12 col-12">
                <div class="contact_form">
                <form id="project-contact-form" action="got-problem.php" method="post" enctype="multipart/form-data"/>
				
                    <div class="form-group col-md-12" style="padding-top: 1.5em">
                        <input type="text" class="form-control" placeholder="Name" name="name" required>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="email" class="form-control" placeholder="E-mail" name="email" required>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="text" class="form-control" placeholder="Idea" name="idea" required>
                    </div>
                    <div class="form-group col-md-12">
                        <input style="height: 10em" class="form-control" type="text" name="current-status" placeholder="Current Status"/>
                    </div>
                    <div class="form-group col-md-12">
                        <input class="form-control" type="text" name="skills" placeholder="Skills"/>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="text" class="form-control" name="requirements" placeholder="What are your requirements?">
                    </div>
                    <div class="form-group col-md-6">
                            <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                            Upload your detailed requirements in PDF format: <input type="file" name="document" placeholder="Upload your reqirements" class="form-control" id="reqirements">
                        </div>
                    <div class="form-group text-right">
                        <button class="btn btn-rounded btn-primary" type="submit" name="submitBtn">Submit</button>
                    </div>
                </form>
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