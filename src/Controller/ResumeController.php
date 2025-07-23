<?php

namespace Resumenter\Controller;

use DateTime;
use Resumenter\Exception\NotFoundException;

use Throwable;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
final class ResumeController implements ControllerInterface
{
       private Environment $twig;

    public function __construct(private array $jobs = [])
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);
    }

    public function render(string $path, ?array $arguments = []): void
    {
        if (!in_array($path, $this->jobs)) {
            throw new NotFoundException($path);
        }

        $data = count($arguments) > 0 ? $arguments : $this->getJob($path);
        $this->sortCourses($data);
        try {
            echo $this->twig->render(
                'resume.html.twig',
                [
                    'cv'  => $data,
                    'env' => $_ENV,
                ]);
        } catch (Throwable $e) {
            echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
        }
    }

    private function getJob(string $job): array
    {
        $dataPath = __DIR__ . "/../..{$_ENV['DATA_PATH']}/{$job}.json";
        return json_decode(file_get_contents($dataPath), true);
    }

    private function sortCourses(array &$data): void
    {
        usort($data['courses']['items'], function ($a, $b) {
            $dateA = DateTime::createFromFormat('d.m.Y', $a['date']);
            $dateB = DateTime::createFromFormat('d.m.Y', $b['date']);
            return $dateB <=> $dateA;
        });
    }
}