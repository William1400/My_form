<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
        foreach ($errors as $key => $value) {

            if (!empty($value)) {

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
        <div class="container">

            <?php

            //popUp message indicate if the mail has been sent
            if ($_SERVER['REQUEST_METHOD'] == 'POST' and $formIsValid == true) {

                echo '<div class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                       Your email as been envoyed.
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>';
            }
            ?>

            <form action="contact.php" method="post" class="row">

                <h1>Contact</h1>

                <?php
                // first name: register validity bootsrap class 
                $validity = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                    if (empty($errors['gender'])) {
                        
                        $validity = 'is-valid';
                    } else {
    
                        $validity = 'is-invalid';
                    }
                    
                } 

                ?>

                <div class="col-12 d-flex flex-column" id="gender-buttons">
                    <div class="col-12 d-flex">
                    <div class="form-check form-check-inline ">
                        <label class="form-check-label" for="gender-male">Male</label>
                        <input 
                            class="form-check-input <?php echo $validity ?>"
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
                            class="form-check-input <?php echo $validity ?>" 
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
                            class="form-check-input <?php echo $validity ?>" 
                            type="radio" 
                            name="gender" 
                            id="gender-other"
                            <?php if ($gender == 'O'){echo 'checked';} ?> 
                            value="O"
                        >
                    </div>
                    </div>
                        <div class="invalid-feedback d-flex" id="gender-invalid-feedback">
                            <?php if (!empty($errors['gender'])) {echo $errors['gender'][0];} ?>
                        </div>
                </div>

                <?php
                $validity = '';
                // first name: register validity bootsrap class 
                if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                    if (empty($errors['first-name'])) {
                        
                        $validity = 'is-valid';
                    } else {
    
                        $validity = 'is-invalid';
                    }
            
                } 
                ?>
                <div class="col-6">
                    <label for="first-name" class="form-label">First name</label>
                    <input type="text" value="<?php echo $firstName ?>" class="form-control <?php echo $validity ?>" id="first-name" name="first-name">
                    <div class="invalid-feedback">
                        <?php if (!empty($errors['first-name'])) {echo $errors['first-name'][0];} ?>
                    </div>
                </div>

                <?php
                $validity = '';
                // last name: register validity bootsrap class 
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    if (empty($errors['last-name'])) {

                        $validity = 'is-valid';
                    } else {

                        $validity = 'is-invalid';
                    }
                }


                ?>
                <div class="col-6">
                    <label for="last-name" class="form-label">Last name</label>
                    <input type="text" value="<?php echo $lastName ?>" class="form-control <?php echo $validity; ?>" id="last-name" name="last-name">
                    <div class="invalid-feedback">
                        <?php if (!empty($errors['last-name'])) {echo $errors['last-name'][0];} ?>
                    </div>
                </div>

                <?php
                $validity = '';
                // company: register validity bootsrap class 
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    if (empty($errors['company'])) {

                        $validity = 'is-valid';
                    } else {

                        $validity = 'is-invalid';
                    }
                }

                ?>
                <div class="col-12">
                    <label for="company" class="form-label">Company</label>
                    <input type="text" value="<?php echo $company ?>" class="form-control <?php echo $validity; ?>" id="company" name="company">
                    <div class="invalid-feedback">
                        <?php if (!empty($errors['company'])) {echo $errors['company'][0];} ?>
                    </div>
                </div>

                <?php
                $validity = '';
                // eamil: register validity bootsrap class 
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    if (empty($errors['email'])) {

                        $validity = 'is-valid';
                    } else {

                        $validity = 'is-invalid';
                    }
                }
                ?>
                <div class="col-12">
                    <label for="email" class="form-label">Email address</label>
                    <input type="text" value= "<?php echo $email ?>" class="form-control <?php echo $validity; ?>" id="email" name="email">
                    <div class="invalid-feedback">
                        <?php if (!empty($errors['email'])) {echo $errors['email'][0];} ?>
                    </div>
                </div>

                <?php
                $validity = '';
                // subject: register validity bootsrap class 
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    if (empty($errors['subject'])) {

                        $validity = 'is-valid';
                    } else {

                        $validity = 'is-invalid';
                    }
                }
                ?>
                <div class="col-12">
                    <label for="subject" class="form-label">Subject</label>
                    <select class="form-select <?php echo $validity; ?> " id="subject" name="subject" aria-label="select">
                        <option <?php if ($subject == ''){echo 'selected';} ?> value="">Choose...</option>
                        <option <?php if ($subject == 'job'){echo 'selected';} ?> value="job">Job</option>
                        <option <?php if ($subject == 'internship'){echo 'selected';} ?> value="internship">Internship</option>
                        <option <?php if ($subject == 'other'){echo 'selected';} ?> value="other">Other</option>
                    </select>
                    <div class="invalid-feedback">
                        <?php if (!empty($errors['subject'])) {echo $errors['subject'][0];} ?>
                    </div>
                </div>

                <?php
                $validity = '';
                // message: register validity bootsrap class 
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    if (empty($errors['message'])) {

                        $validity = 'is-valid';
                    } else {

                        $validity = 'is-invalid';
                    }
                }
                ?>
                <div class="col-12">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control <?php echo $validity; ?>"  id="message" name="message" rows="3"><?php echo $message ?></textarea>
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
            <div class="row bg-light">
                <div class="col-12">
                    <p>Média-Sociaux</p>
                </div>
                <div class="col-12">
                    <a href="mailto:william.bady.1400@gmail.com"><i class="fas fa-at"></i></a>
                    <a href="https://github.com/William1400"><i class="fab fa-github"></i></a>
                    <a href="https://www.linkedin.com/in/william-bady-b3924221a/"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://kit.fontawesome.com/b404d5b12c.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>