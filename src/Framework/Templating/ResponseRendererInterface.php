<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 08/01/2016
 * Time: 10:16
 */

namespace Framework\Templating;


use Framework\Http\ResponseInterface;

interface ResponseRendererInterface extends RendererInterface
{
    /**
     * Evaluate a template view file and returns a Response instance.
     * @param string $view The template filename
     * @param array $vars The view variables
     * @param int $statusCode The response status code.
     * @return Response
     */
    public function renderResponse($view, array $vars = [], $statusCode = ResponseInterface::HTTP_OK);
}