<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Contact</title>
</head>

<body class="d-flex flex-column h-100">

    <?php

    // error_reporting(E_ALL ^ E_WARNING);
    $my_email = "william.bady.1400@gamil.com";
    $firstName = "";
    $lastName = "";
    $company = "";
    $email = "";
    $message = "";
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

        if (isset($_POST['submit'])){

            $to = $my_email; // this is your Email address
            $from = $_POST['email']; // this is the sender's Email address
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $subject = "Form submission";
            $subject2 = "Copy of your form submission";
            $message = $first_name . " " . $last_name . " wrote the following:" . "\n\n" . $_POST['message'];
            $message2 = "Here is a copy of your message " . $first_name . "\n\n" . $_POST['message'];
        
            $headers = "From:" . $from;
            $headers2 = "From:" . $to;
            mail($to,$subject,$message,$headers);
            mail($from,$subject2,$message2,$headers2); // sends a copy of the message to the sender
            echo "Mail Sent. Thank you " . $first_name . ", I will contact you shortly.";
            // You can also use header('Location: thank_you.php'); to redirect to another page.
            // You cannot use header and echo together. It's one or the other.
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
                if (empty($errors['gender'])) {

                    $validity = 'is-valid';
                } else {

                    $validity = 'is-invalid';
                }
                ?>
                <div class="col-12">
                    <div class="form-check form-check-inline ">
                        <label class="form-check-label" for="gender-male">Male</label>
                        <input class="form-check-input <?php echo $validity ?>" type="radio" name="gender" id="gender-male" value="M">
                    </div>

                    <div class="form-check form-check-inline ">
                        <label class="form-check-label" for="gender-female">Female</label>
                        <input class="form-check-input <?php echo $validity ?>" type="radio" name="gender" id="gender-female" value="F">

                    </div>

                    <div class="form-check form-check-inline ">
                        <label class="form-check-label" for="gender-other">Other</label>
                        <input class="form-check-input <?php echo $validity ?>" type="radio" name="gender" id="gender-other" value="O">
                        <div class="invalid-feedback">
                            <?php echo $errors['gender'][0] ?>
                        </div>
                    </div>



                </div>

                <?php
                // first name: register validity bootsrap class 
                if (empty($errors['first-name'])) {

                    $validity = 'is-valid';
                } else {

                    $validity = 'is-invalid';
                }
                ?>
                <div class="col-6">
                    <label for="first-name" class="form-label">First name</label>
                    <input type="text" class="form-control <?php echo $validity ?>" id="first-name" name="first-name">
                    <div class="invalid-feedback">
                        <?php echo $errors['first-name'][0] ?>
                    </div>
                </div>

                <?php
                // last name: register validity bootsrap class 
                if (empty($errors['last-name'])) {

                    $validity = 'is-valid';
                } else {

                    $validity = 'is-invalid';
                }
                ?>
                <div class="col-6">
                    <label for="last-name" class="form-label">Last name</label>
                    <input type="text" class="form-control <?php echo $validity; ?>" id="last-name" name="last-name">
                    <div class="invalid-feedback">
                        <?php echo $errors['last-name'][0] ?>
                    </div>
                </div>

                <?php
                // company: register validity bootsrap class 
                if (empty($errors['company'])) {

                    $validity = 'is-valid';
                } else {

                    $validity = 'is-invalid';
                }
                ?>
                <div class="col-12">
                    <label for="company" class="form-label">Company</label>
                    <input type="text" class="form-control <?php echo $validity; ?>" id="company" name="company">
                    <div class="invalid-feedback">
                        <?php echo $errors['company'][0] ?>
                    </div>
                </div>

                <?php
                // eamil: register validity bootsrap class 
                if (empty($errors['email'])) {

                    $validity = 'is-valid';
                } else {

                    $validity = 'is-invalid';
                }
                ?>
                <div class="col-12">
                    <label for="email" class="form-label">Email address</label>
                    <input type="text" class="form-control <?php echo $validity; ?>" id="email" name="email">
                    <div class="invalid-feedback">
                        <?php echo $errors['email'][0] ?>
                    </div>
                </div>
                <?php
                // subject: register validity bootsrap class 
                if (empty($errors['subject'])) {

                    $validity = 'is-valid';
                } else {

                    $validity = 'is-invalid';
                }
                ?>
                <div class="col-12">
                    <label for="subject" class="form-label">Subject</label>
                    <select class="form-select <?php echo $validity; ?> " id="subject" name="subject" aria-label="select">
                        <option selected value="">Choose...</option>
                        <option value="job">Job</option>
                        <option value="internship">Internship</option>
                        <option value="other">Other</option>
                    </select>
                    <div class="invalid-feedback">
                        <?php echo $errors['subject'][0] ?>
                    </div>
                </div>
                <?php
                // message: register validity bootsrap class 
                if (empty($errors['message'])) {

                    $validity = 'is-valid';
                } else {

                    $validity = 'is-invalid';
                }
                ?>
                <div class="col-12">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control <?php echo $validity; ?>" id="message" name="message" rows="3"></textarea>
                    <div class="invalid-feedback">
                        <?php echo $errors['message'][0] ?>
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