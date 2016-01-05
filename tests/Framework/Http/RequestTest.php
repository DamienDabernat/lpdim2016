<?php
namespace Tests\Framework\Http;
use Framework\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{

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
        $request = new Request($method, '/', Request::HTTP, '1.1');
        $this->assertSame($method, $request->getMethod());
        $this->assertSame($path, $request->getPath());
        $this->assertSame(Request::HTTP, $request->getScheme());
        $this->assertSame('1.1', $request->getSchemeVersion());
        $this->assertEmpty($request->getHeaders());
        $this->assertEmpty($request->getBody());
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