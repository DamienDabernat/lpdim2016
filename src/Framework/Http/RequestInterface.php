<?php

namespace Framework\Http;


interface RequestInterface extends MessageInterface
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const OPTION = 'OPTION';
    const TRACE = 'TRACE';
    const HEAD = 'HEAD';
    const DELETE = 'DELETE';
    const CONNECT = 'CONNECT';

    public function getMethod();
    public function getPath();
}