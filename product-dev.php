<?php
include("header.php");

?>
<!--header end here-->
<?php
	
	//Load composer's autoloader
	require 'vendor/autoload.php';
	$message = "";

    if (isset($_REQUEST['submitBtn'])) {
        $name = $_REQUEST['name'];
		$number = $_REQUEST['number'];
        $email = $_REQUEST['email'];
		$require1 = $_REQUEST['require'];
		$plan = $_REQUEST['plan'];
		$pay_type = $_REQUEST['pay_type'];
		$dev_team = $_REQUEST['dev_team'];
        $recruit = $_REQUEST['recruit'];
        
        $upload_dir = '/data/iiic/uploads/';
        // $upload_dir = 'uploads/';
        $file_name = $_FILES["requirements"]['name'];

        if (empty($_FILES) && empty($_POST)) {
            $message = 'The uploaded zip was too large. You must upload a file smaller than ' . ini_get("upload_max_filesize");
        } else if ($name != "" && $email != "" && $number != "" && $require1 != "" && $plan != "" && $pay_type != "" && $dev_team != "" && $recruit != "" ) {
            $connection = new mysqli("127.0.0.1", "iiicdba", "iiicdb@2018", "iiicdb");
            // $connection = new mysqli("127.0.0.1", "root", "root", "iiic");

            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }

            $statement = $connection->prepare("INSERT INTO ProductDev (name, phone_number, email, requirements, plan, pay_type, dev_team, recruit, requirements_file, file_hash ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $file_hash = hash_file("sha256", $_FILES["requirements"]['tmp_name']);
            $hashed_filename = $file_hash;
            $upload_file = $upload_dir . basename($hashed_filename);
            // move_uploaded_file($_FILES["business-plan"]['tmp_name'], $upload_file);
            if (move_uploaded_file($_FILES["requirements"]['tmp_name'], $upload_file)) {
                $statement->bind_param("sssssss", $name, $number, $email, $require1, $plan, $pay_type, $dev_team,
                                    $recruit, $file_name ,$hashed_filename);

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
                    $mail->Subject = 'Product Development email';
                    $mail->setFrom("iiic@iiita.ac.in", $name);
			        $mail->addAddress("iiic@iiita.ac.in");  
			        // $mail->AddReplyTo($email, $name);   // Add a recipient    // Add a recipient
                    //$mail->addAddress('ellen@example.com');               // Name is optional
                    //$mail->addReplyTo('info@example.com', 'Information');
                    //$mail->addCC('cc@example.com');
                    //$mail->addBCC('bcc@example.com');

                    //Attachments
                    $files_to_attach = $_FILES["requirements"]['tmp_name']; 
                    // $mail->AddAttachment($files_to_attach, $file_name); 
                    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                       // Optional name

                    //Content
                    $mail->isHTML(false);                                  // Set email format to HTML
                    
                    $mes = 'Name: '.$name.' Number: '.$number.'  Email: '.$email.'  Requirements: '.$require1.'  Summary of company status: '.$plan.'  Payment Type: '.$pay_type.'   Development Team: '.$dev_team.' Development Team Recruitment: '.$recruit;
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
	@media (max-width: 565px)
	div.h3 {
		font-size: 20px;
	}
	.parallax-mirror {
    opacity: 1.5;
    filter: blur(0px);
}
</style>
<!--page title section-->
<section class="inner_cover parallax-mirror" data-parallax="scroll" data-image-src="assets/img/123.jpeg">
    <div class="overlay_dark"></div>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-12">
                <div class="inner_cover_content">
                    <h3 style="color:#fff" class="pdc">
                        Product Development Cell
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
                    <h2 class="col-md-12 col-12" style="text-align: center" size="20px">
                        Product Development Cell
                    </h2>
                    <p class="col-md-12 col-12">
                        Product Development Cell is a body which provides aid in development of a product required by any start-up, company or an individual for that instance. If you are currently running a business or making your mind to setup one, and have any specific requirements, our team of highly talented faculty and students can develop customized modules, software or the complete product for a reasonable price or equity.

The product development is done with great expertise under the supervision of knowledgeable professors of our prestigious institute : IIIT-Allahabad.
                    </p>

                </div>
            </div>
            <div class="col-md-12 col-12">
                <div class="contact_form">
                <form id="project-contact-form" action="product-develop.php" method="post" enctype="multipart/form-data"/>
                    <div class="form-group col-md-12" style="padding-top: 1.5em">
                        <input name="name" type="text" class="form-control" placeholder="Name" required>
                    </div>
                    <div class="form-group col-md-12">
                        <input name="email" type="email" class="form-control" placeholder="E-mail" required>
                    </div>
                    <div class="form-group col-md-12">
                        <input name="number" type="text" class="form-control" placeholder="Phone Number" required>
                    </div>
                    <div class="form-group col-md-12">
                        <textarea name="require" class="form-control" cols="4" rows="2" placeholder="Summary of Requirements"></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <textarea name="plan" class="form-control" cols="4" rows="2" placeholder="A Brief of the current nature of the company"></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <input name="pay_type" type="text" class="form-control" placeholder="Whether you prefer to pay by equity (preferred), by cash, or by mix?">
                    </div>
                    <div class="form-group col-md-12">
                        <input name="dev_team" type="text" class="form-control" placeholder="Will your provide your own development team with our mentorship, or you would like us to do the development?">
                    </div>
                    <div class="form-group col-md-12">
                        <input name="recruit" type="text" class="form-control" placeholder="In case you would like our team to mentor, will you recruit the students/employees yourself, or would you like us to do so?">
                    </div>
                    <div class="form-group col-md-6">
                            <input name="file" type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                            Upload your detailed requirements in PDF format: <input type="file" name="reqirements" placeholder="Upload your reqirements" class="form-control" id="reqirements">
                        </div>
                    <div class="form-group text-right">
                        <button class="btn btn-rounded btn-primary" name="submitBtn" type="submit">Submit</button>
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