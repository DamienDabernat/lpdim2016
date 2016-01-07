<?php

namespace Framework\Kernel;

use Framework\Http\RequestInterface;
use Framework\Http\ResponseInterface;

interface KernelInterface
{
    /**
     * Convert request object into a Response object.
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request);
}