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

    /**
     * @param array $jobs
     */
    public function __construct(private array $jobs = [])
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->jobs[] = '';
        $this->twig = new Environment($loader);
    }

    /**
     * @param string $path
     * @param array|null $arguments
     * @throws NotFoundException
     */
    public function render(string $path, ?array $arguments = []): void
    {
        if (!in_array($path, $this->jobs)) {
            throw new NotFoundException($path);
        }

        if($this->isIndex($path)) {
            $this->renderIndex();
            return;
        }
        $this->renderResume($path, $arguments);
    }

    /**
     * @param string $path
     * @param array|null $arguments
     * @return array|null
     */
    public function renderResume(string $path, ?array $arguments): ?array
    {
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
        return $data;
    }

    /**
     * @return void
     */
    private function renderIndex(): void
    {
        try {
            echo $this->twig->render(
                'index.html.twig',
                [
                    'jobs'  => $this->jobs,
                    'env' => $_ENV,
                ]);
        } catch (Throwable $e) {
            echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
        }
    }

    /**
     * @param string $job
     * @return array
     */
    private function getJob(string $job): array
    {
        $dataPath = __DIR__ . "/../..{$_ENV['DATA_PATH']}/$job.json";
        return json_decode(file_get_contents($dataPath), true);
    }

    /**
     * @param array $data
     * @return void
     */
    private function sortCourses(array &$data): void
    {
        usort($data['courses']['items'], function ($a, $b) {
            $dateA = DateTime::createFromFormat('d.m.Y', $a['date']);
            $dateB = DateTime::createFromFormat('d.m.Y', $b['date']);
            return $dateB <=> $dateA;
        });
    }

    /**
     * @param string $path
     * @return bool
     */
    private function isIndex(string $path): bool
    {
        return $path === '';
    }


}