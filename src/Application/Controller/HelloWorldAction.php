<?php

namespace Application\Controller;


use Framework\Http\RequestInterface;
use Framework\Http\ResponseInterface;
use Framework\Http\Response;
use Framework\Templating\ResponseRendererInterface;

class HelloWorldAction
{
    /**
     * The template engine.
     * @var ResponseRendererInterface
     */
    private $renderer;

    public function setRenderer(ResponseRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    final public function __invoke(RequestInterface $request)
    {
        return $this->renderer->renderResponse('hello.tpl', ['name' => 'Damien']);
    }
}