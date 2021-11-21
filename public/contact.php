<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <title>Contact</title>
</head>

<body class="d-flex flex-column h-100">

    <?php

    // Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    //Load Composer's autoloader
    require '../vendor/autoload.php';

    //frame work php to secure important informations out public access
    $dotenv = Dotenv\Dotenv::createImmutable('..');
    $dotenv->load();

    $gender = "";
    $firstName = "";
    $lastName = "";
    $company = "";
    $email = "";
    $subject = "";
    $message = "";

    // keys of the error messages  
    $errors = [
        'gender' => [],
        'first-name' => [],
        'last-name' => [],
        'company' => [],
        'email' => [],
        'subject' => [],
        'message' => []
    ];

    $validity = [
       
        'gender' => "",
        'first-name' => "",
        'last-name' => "",
        'company' => "",
        'email' => "",
        'subject' => "",
        'message' => ""
    ];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (!isset($_POST['gender'])) {

            $errors['gender'][] = 'Please select a gender';
        }

        if ($_POST['first-name'] == "") {

            $errors['first-name'][] = 'Please enter your first name';
        }

        if ($_POST['last-name'] == "") {

            $errors['last-name'][] = 'Please enter your last name';
        }

        if ($_POST['company'] == "") {

            $errors['company'][] = 'Please enter the name of your company';
        }

        if ($_POST['email'] == "") {

            $errors['email'][] = 'Please enter your email address';
        }

        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ==  false) {

            $errors['email'][] = 'The entered email address is not valid';
        }

        if ($_POST['subject'] == '') {

            $errors['subject'][] = 'Please select a subject';
        }

        if ($_POST['message'] == "") {

            $errors['message'][] =  'Please enter a message';
        }

    
        $gender = isset($_POST['gender']) and $gender = $_POST['gender'];
        $firstName = filter_var($_POST['first-name'], FILTER_SANITIZE_STRING);
        $lastName = filter_var($_POST['last-name'], FILTER_SANITIZE_STRING);
        $company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $subject = $_POST['subject'];
        $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

        $formIsValid = true;
        foreach (array_keys($validity) as $key){

            if (empty($errors[$key])) {
                        
                $validity[$key] =  'is-valid';
            } else {

                $validity[$key] = 'is-invalid';
                $formIsValid = false;   
            }
            
        }

        if ($formIsValid == true) {

            //Create an instance; passing `true` enables exceptions
            $phpMailer = new PHPMailer();

            $phpMailer->SMTPOptions = array(

                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // $mail->SMTPDebug = 1;// log info to transfer mail to mail by google server SMTP
            $phpMailer->isSMTP();
            $phpMailer->SMTPSecure = 'tls';

            $phpMailer->Host = 'smtp.gmail.com';
            $phpMailer->Port = 587;

            $phpMailer->SMTPAuth = true;
            $phpMailer->Username = $_ENV['GMAIL_USERNAME'];
            $phpMailer->Password = $_ENV['GMAIL_PASSWORD'];

            $phpMailer->setFrom($_ENV['GMAIL_USERNAME']);
            $phpMailer->addAddress($email);
            $phpMailer->Subject = 'Sent from my portfolio' . " " . $subject;
            $phpMailer->Body = $gender . ' ' . $firstName . ' ' . $lastName . ' ' . $company . ' ' . $message;

            //send the message
            $phpMailer->send();

      
        }
        
    }
    ?>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Portfolio</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="contact.php">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="container" id="form-container">
            
            <div class="modal fade" id="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        <div class="modal-body">
                           Thank you.
                           Your message has been sent!
                        </div>
                    </div>
                </div>
            </div>
          
            <?php

            if (($_SERVER['REQUEST_METHOD'] == 'POST') and ($formIsValid == true)) {

                echo "  
                    <script>
                        let modal = new bootstrap.Modal(document.querySelector('#modal'), {});
                        modal.show();
                    </script>
                ";

            }
        
            ?>

            <form action="contact.php" method="post" class="row" id="contact-form">

                <h1>Contact</h1>

                <div class="col-12 d-flex flex-column" id="gender-buttons">
                    <div class="col-12 d-flex">
                        <div class="form-check form-check-inline ">
                            <label class="form-check-label" for="gender-male">Male</label>
                            <input 
                                class="form-check-input <?php echo $validity["gender"] ?>"
                                type="radio" 
                                name="gender" 
                                id="gender-male" 
                                <?php if ($gender == 'M'){echo 'checked';} ?> 
                                value="M"
                            >
                        </div>

                        <div class="form-check form-check-inline ">
                            <label class="form-check-label" for="gender-female">Female</label>
                            <input 
                                class="form-check-input <?php echo $validity["gender"] ?>" 
                                type="radio" 
                                name="gender" 
                                id="gender-female"
                                <?php if ($gender == 'F'){echo 'checked';} ?> 
                                value="F"
                            >
                        </div>

                        <div class="form-check form-check-inline ">
                            <label class="form-check-label" for="gender-other">Other</label>
                            <input 
                                class="form-check-input <?php echo $validity["gender"] ?>" 
                                type="radio" 
                                name="gender" 
                                id="gender-other"
                                <?php if ($gender == 'O'){echo 'checked';} ?> 
                                value="O"
                            >
                        </div>
                    </div>
                    <?php 
                    if (!empty($errors['gender'])) { 
                        
                        echo "
                            <div class='invalid-feedback d-flex' id='gender-invalid-feedback'>
                                {$errors['gender'][0]}
                            </div>
                        ";
                    } 
                
                    ?>
                </div>

                <div class="col-12" id="names">
                    <div class="name">
                        <label for="first-name" class="form-label">First name</label>
                        <input 
                            type="text" 
                            value="<?php echo $firstName ?>"
                            class="form-control <?php echo $validity["first-name"] ?>" 
                            id="first-name" 
                            name="first-name"
                        >
                        <div class="invalid-feedback">
                            <?php if (!empty($errors['first-name'])) {echo $errors['first-name'][0];} ?>
                        </div>
                    </div>

                    <div class="name">
                        <label for="last-name" class="form-label">Last name</label>
                        <input 
                            type="text" 
                            value="<?php echo $lastName ?>" 
                            class="form-control <?php echo $validity["last-name"]; ?>" 
                            id="last-name" 
                            name="last-name"
                        >
                        <div class="invalid-feedback">
                            <?php if (!empty($errors['last-name'])) {echo $errors['last-name'][0];} ?>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label for="company" class="form-label">Company</label>
                    <input 
                        type="text"
                        value="<?php echo $company ?>" 
                        class="form-control <?php echo $validity["company"]; ?>" 
                        id="company" 
                        name="company"
                    >
                    <div class="invalid-feedback">
                        <?php if (!empty($errors['company'])) {echo $errors['company'][0];} ?>
                    </div>
                </div>

                <div class="col-12">
                    <label for="email" class="form-label">Email address</label>
                    <input 
                        type="text" 
                        value= "<?php echo $email ?>" 
                        class="form-control <?php echo $validity["email"]; ?>" 
                        id="email" 
                        name="email"
                    >
                    <div class="invalid-feedback">
                        <?php if (!empty($errors['email'])) {echo $errors['email'][0];} ?>
                    </div>
                </div>

                <div class="col-12">
                    <label for="subject" class="form-label">Subject</label>
                    <select 
                        class="form-select <?php echo $validity["subject"]; ?> " 
                        id="subject" 
                        name="subject" 
                    >
                        <option <?php if ($subject == ''){echo 'selected';} ?> value="">Choose...</option>
                        <option <?php if ($subject == 'job'){echo 'selected';} ?> value="job">Job</option>
                        <option <?php if ($subject == 'internship'){echo 'selected';} ?> value="internship">Internship</option>
                        <option <?php if ($subject == 'other'){echo 'selected';} ?> value="other">Other</option>
                    </select>
                    <div class="invalid-feedback">
                        <?php if (!empty($errors['subject'])) {echo $errors['subject'][0];} ?>
                    </div>
                </div>

                <div class="col-12">
                    <label for="message" class="form-label">Message</label>
                    <textarea 
                        class="form-control <?php echo $validity["message"]; ?>" 
                        id="message" 
                        name="message" 
                        rows="3"
                    ><?php echo $message ?></textarea>
                    <div class="invalid-feedback">
                        <?php if (!empty($errors['message'])) {echo $errors['message'][0];} ?>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit" name="submit" id="submit">Submit form</button>
                </div>
            </form>
        </div>
    </main>

    <footer class="footer mt-auto">
        <div class="container-fluid">
            <div class="bg-light" id="footer-bar">
                <div>
                    <p>Média-Sociaux</p>
                </div>
                <div id="icons">
                    <a href="mailto:william.bady.1400@gmail.com"><i class="fas fa-at fa-2x"></i></a>
                    <a href="https://github.com/William1400"><i class="fab fa-github fa-2x"></i></a>
                    <a href="https://www.linkedin.com/in/william-bady-b3924221a/"><i class="fab fa-linkedin fa-2x"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://kit.fontawesome.com/b404d5b12c.js" crossorigin="anonymous"></script>
   
</body>

</html>