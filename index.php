<?php

use Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$job = $_GET['job'] ?? 'web_developer';
$dataPath = __DIR__ . "{$_ENV['DATA_PATH']}/{$job}.json";
if (!file_exists($dataPath)) {
    http_response_code(404);
    echo "No data found for the item: $job";
    exit;
}

$data = json_decode(file_get_contents($dataPath), true);

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);
usort($data['courses']['items'], function ($a, $b) {
    $dateA = DateTime::createFromFormat('d.m.Y', $a['date']);
    $dateB = DateTime::createFromFormat('d.m.Y', $b['date']);
    return $dateB <=> $dateA;
});
try {
    echo $twig->render(
        // 'resume.html.twig',
        'resume.html.twig',
        [
            'cv'  => $data,
            'env' => $_ENV,
        ]);
} catch (Throwable $e) {
    echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
}
