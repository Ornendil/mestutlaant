<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


echo '<pre>';
require __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Node\Expression\TestExpression;
use Twig\TwigTest;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/config.yaml');

$loader = new FilesystemLoader(__DIR__ . '/templates');

$twig = new Environment($loader, [
    'debug' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

$twig->addFilter(new \Twig\TwigFilter('preg_replace', function($subject, $pattern, $replacement){
    return preg_replace($pattern, $replacement, $subject);
}));

if (isset( $_GET['aar'])) {
    $aar = preg_replace('/\D/', '', htmlspecialchars($_GET['aar']));
} else {
    $aar = date("Y") - 1;
}

include('list.php');

foreach ($config['lister'] as $key => $liste){
    if (isset($liste['ccl'])) {
        $config['lister'][$key]['data'] = skaffListe($aar, $liste ['ccl']);
    } else {
        $config['lister'][$key]['data'] = skaffListe($aar);
    }
}

echo '</pre>';

echo $twig->render('index.twig', [
    'aar' => $aar,
    'lister' => $config['lister'],
    'bibliotek' => $config['bibliotek'],
]);

?>