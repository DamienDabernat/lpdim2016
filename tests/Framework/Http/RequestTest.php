<?php
namespace Tests\Framework\Http;
use Framework\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateFromMessage()
    {
        $message = <<<MESSAGE
GET /fr/article/42 HTTP/1.1
host: http://wikipedia.com
user-agent: Mozilla/Firefox
accept: text/plain, text/html

{"foo": "bar"}
MESSAGE;

        $request = Request::createFromMessage($message);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame($message, $request->getMessage());
        $this->assertSame($message, (string) $request);
    }


    public function testGetMessage()
    {
        $message = <<<MESSAGE
GET /fr/article/42 HTTP/1.1
host: http://wikipedia.com
user-agent: Mozilla/Firefox
accept: text/plain, text/html

{"foo": "bar"}
MESSAGE;

        $body = '{"foo": "bar"}';
        $request = new Request('GET', '/fr/article/42', Request::HTTP, Request::VERSION_1_1, [
            'Host' => 'http://wikipedia.com',
            'User-Agent' => 'Mozilla/Firefox',
            'Accept' => 'text/plain, text/html',
        ], $body);

        $this->assertSame($message, $request->getMessage());
        $this->assertSame($message, (string) $request);
    }

    /**
     * @expectedException  \RuntimeException
     */
    public function testAddSameHttpHeaderTwice()
    {
        $headers = [
            'Content-Type' => 'text/xml',
            'CONTENT-TYPE' => 'application/json',
        ];

        new Request('GET', '/', 'HTTP', '1.1', $headers);
    }

    /**
     * @param $version
     * @expectedException \InvalidArgumentException
     * @dataProvider provideInvalidSchemeVersion
     */
    public function testUnsupportedSchemeVersion($version)
    {
        new Request('PUT', '/', 'HTTP', $version);
    }

    public function provideInvalidSchemeVersion()
    {
        return [
            ['0.0'],
            ['0.1'],
            ['1.2'],
            ['1.5'],
            ['2.1'],
        ];
    }

    /**
     * @param $version
     * @dataProvider provideValidSchemeVersion
     */
    public function testSupportedSchemeVersion($version)
    {
        new Request('PUT', '/', 'HTTP', $version);
    }

    public function provideValidSchemeVersion()
    {
        return [
            [Request::VERSION_1_0],
            [Request::VERSION_1_1],
            [Request::VERSION_2_0],
        ];
    }

    /**
     * @param $scheme
     * @expectedException \InvalidArgumentException
     * @dataProvider provideInvalidScheme
     */
    public function testUnsupportedScheme($scheme)
    {
        new Request('PUT', '/', $scheme, '1.1');
    }

    public function provideInvalidScheme()
    {
        return [
            ['SFTP'],
            ['FTP'],
            ['SSH'],
        ];
    }

    /**
     * @param $scheme
     * @dataProvider provideValidScheme
     */
    public function testSupportedScheme($scheme)
    {
        new Request('PUT', '/', $scheme, '1.1');
    }

    public function provideValidScheme()
    {
        return [
            ['HTTP'],
            ['HTTPS'],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideInvalidHttpMethod
     */
    public function testUnsupporttedHttpMethod($method)
    {
        new Request($method, '/', 'HTTP', '1.1');
    }

    public function provideInvalidHttpMethod()
    {
        return [
            ['FOO'],
            ['BAR'],
            ['BAZ'],
            ['PURGE'],
            ['TOTO'],
        ];
    }

    /**
     * @dataProvider provideRequestParameters
     */
    public function testCreateRequestInstance($method, $path)
    {
        $request = new Request($method, $path, Request::HTTP, Request::VERSION_1_1, [
            'Host' => 'http://wikipedia.com',
            'User-Agent' => 'Mozilla/Firefox'
        ]);
        $this->assertSame($method, $request->getMethod());
        $this->assertSame($path, $request->getPath());
        $this->assertSame(Request::HTTP, $request->getScheme());
        $this->assertSame(Request::VERSION_1_1, $request->getSchemeVersion());
        $this->assertCount(2, $request->getHeaders());
        $this->assertEmpty($request->getBody());

        $this->assertSame(['host' => 'http://wikipedia.com', 'user-agent' => 'Mozilla/Firefox'],
            $request->getHeaders()
        );

        $this->assertSame('http://wikipedia.com', $request->getHeader('Host'));
        $this->assertSame('Mozilla/Firefox', $request->getHeader('User-Agent'));
    }

    public function provideRequestParameters()
    {
        return [
                [Request::GET, '/'                  ],
                [Request::POST, '/home'             ],
                [Request::PUT, '/foo'               ],
                [Request::PATCH, '/bar'             ],
                [Request::OPTION, '/options'        ],
                [Request::TRACE, '/fr/article/42'   ],
                [Request::HEAD, '/contact'          ],
                [Request::DELETE, '/lol'            ],
                [Request::CONNECT, '/cgv'           ],
            ];
    }
}