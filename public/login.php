<?php

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new \Twig\Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
]);
$user = require __DIR__.'/user-data.php';
// chargement de l'extension Twig_Extension_Debug
$twig->addExtension(new \Twig\Extension\DebugExtension());
$formData = [
    'login' => '',
    'password' => '',
];
    $errors = [];
    $messages = [];
if($_POST) {
    
    //Remplacement de valeurs
    if (isset($_POST['login'])){
        $formData['login'] = $_POST['login'];
    }
    if (isset($_POST['password'])){
        $formData['password'] = $_POST['password'];
    }

    //Validations login
    if (!isset($_POST['login']) || empty($_POST['login'])) {
        $errors['login'] = true;
        $messages['login'] = "Merci de renseigner votre login";
    }
    elseif (strlen($_POST['login']) < 4){
        $errors['login'] = true;
        $messages['login'] = "4 caractères minimum";
    }
    elseif (strlen($_POST['login']) > 100){
        $errors['login'] = true;
        $messages['login'] = "100 caractères maximum";
    }
    elseif ($_POST['login'] != $user['login']){
        $errors['login'] = true;
        $messages['login'] = "Identifiant ou mot de passe incorrect";
    }
    ///Validations password
    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors['password'] = true;
        $messages['password'] = 'identifiant ou mot de passe incorrect';
    } elseif (strlen($_POST['password']) < 4 || strlen($_POST['password']) > 100) {
        $errors['password'] = true;
        $messages['password'] = 'identifiant ou mot de passe incorrect';
    } elseif (!password_verify($_POST['password'], $user['password_hash'])) {
        $errors['password'] = true;
        $messages['password'] = 'identifiant ou mot de passe incorrect';
    }
   if (!$errors) {
       session_start();
       $_SESSION['user_id'] = $user['user_id'];
       $_SESSION['login'] = $user['login'];

       $url = 'private-page.php';
       header("Location: {$url}", true, 302);
       exit();
   }

}

// affichage du rendu d'un template
echo $twig->render('login.html.twig', [
    'errors' => $errors,
    'messages' => $messages,
    'formData' => $formData,
]);