<?php

namespace Resumenter\Controller;

Interface ControllerInterface
{
       public function render(string $path, ?array $arguments);
}