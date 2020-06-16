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

// chargement de l'extension Twig_Extension_Debug
$twig->addExtension(new \Twig\Extension\DebugExtension());
session_start;
$formData = [
    'login' => '',
    'password' => '',
    $user = require __DIR__.'/user-data.php',
    $url = 'private-page.php',
];

if($_POST) {
    $errors = [];
    $messages = [];
    $user = require __DIR__.'/user-data.php';

    //Remplacement de valeurs
    if (isset($_POST['login'])){
        $formData['login'] = $_POST['login'];
    }
    if (isset($_POST['password'])){
        $formData['password'] = $_POST['password'];
    }

    //Validations
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
    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors['password'] = true;
        $messages['password'] = "Merci de renseigner votre mot de passe";
    }
    elseif (strlen($_POST['password']) < 4){
        $errors['password'] = true;
        $messages['password'] = "4 caractères minimum";
    }
    elseif (strlen($_POST['password']) > 100){
        $errors['password'] = true;
        $messages['password'] = "100 caractères max";
    }
   elseif (!password_verify($_POST['password'], $user['password_hash'])){
       $errors = true;
       $messages = "Identifiant ou mot de passe incorrect";
   }
   if ($errors) {
       dump("t'es trop con");
   }
   if (!$errors) {
       $_SESSION = $user['user_id'];
       $_SESSION = $user['login'];
       $_SESSION = $user['password'];
       $url = './private-page.php';
       header("Location: {$url}", true, 302);
       exit();
        dump("It's ok");
   }

}

// affichage du rendu d'un template
echo $twig->render('login.html.twig', [
    'errors' => $errors,
    'messages' => $messages,
    'formData' => $formData,
    'user' => $user, 
    'url' => $url,
]);