<?php

namespace App\Shared\Model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Turbo\TurboBundle;

trait TurboResponseTraits
{
    public function renderTurboStream(Request $request, string $view, array $parameters = [], Response $response = null): Response
    {
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->renderForm($view, $parameters, $response);
    }

    abstract protected function renderForm(string $view, array $parameters = [], Response $response = null): Response;
}
