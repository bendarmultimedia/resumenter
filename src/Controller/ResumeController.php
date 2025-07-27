<?php

namespace Resumenter\Controller;

use DateTime;
use Resumenter\Exception\NotFoundException;
use Throwable;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ResumeController implements ControllerInterface
{

    /**
     * @param Environment $twig
     * @param string $template
     * @param array $jobs
     */
    public function __construct(readonly private Environment $twig, readonly string $template = 'resume.html.twig', private array $jobs = [])
    {
        $this->jobs[] = '';
    }

    /**
     * @param string $path
     * @param array|null $arguments
     * @return string
     * @throws NotFoundException
     */
    public function render(string $path, ?array $arguments = []): string
    {
        if (!in_array($path, $this->jobs)) {
            throw new NotFoundException($path);
        }

        if ($this->isIndex($path)) {
            return $this->renderIndex();
        }
        return $this->renderResume($path, $arguments);
    }

    /**
     * @param string $path
     * @param array|null $arguments
     * @return string
     */
    public function renderResume(string $path, ?array $arguments): string
    {
        $data = count($arguments) > 0 ? $arguments : $this->getJob($path);
        $this->sortCourses($data);
        try {
            return $this->twig->render(
                $this->template,
                [
                    'cv'  => $data,
                    'env' => $_ENV,
                ]);
        } catch (Throwable $e) {
            echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
        }
        return '';
    }

    /**
     * @param array $data
     * @return void
     */
    public function sortCourses(array &$data): void
    {
        if (is_array($data['courses']) && count($data['courses']) > 0) {

            usort($data['courses']['items'], function ($a, $b) {
                $dateA = DateTime::createFromFormat('d.m.Y', $a['date']);
                $dateB = DateTime::createFromFormat('d.m.Y', $b['date']);
                return $dateB <=> $dateA;
            });
        }
    }

    /**
     * @return string
     */
    private function renderIndex(): string
    {
        try {
            return $this->twig->render(
                'index.html.twig',
                [
                    'jobs' => $this->jobs,
                    'env'  => $_ENV,
                ]);
        } catch (Throwable $e) {
            echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
        }
        return '';
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
     * @param string $path
     * @return bool
     */
    private function isIndex(string $path): bool
    {
        return $path === '';
    }


}