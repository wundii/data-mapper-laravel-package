<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage\Tests;

use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wundii\DataMapper\DataConfig;
use Wundii\DataMapper\Enum\ApproachEnum;
use Wundii\DataMapper\LaravelPackage\DataMapper;
use Wundii\DataMapper\LaravelPackage\Enum\MapStatusEnum;
use Wundii\DataMapper\LaravelPackage\Tests\MockClasses\TypeString;

class DataMapperTest extends TestCase
{
    private DataMapper $dataMapper;

    protected function setUp(): void
    {
        $dataConfig = new DataConfig(ApproachEnum::CONSTRUCTOR);
        $this->dataMapper = new DataMapper($dataConfig);
    }

    /**
     * @throws Exception
     */
    public function getRequest(
        string $contentType,
        string $content
    ): Request|MockObject {
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getContent')
            ->willReturn($content);
        $request->expects($this->once())
            ->method('header')
            ->with('Content-Type')
            ->willReturn($contentType);

        return $request;

    }

    public function expectedObject(): TypeString
    {
        return new TypeString('test');
    }

    /**
     * @throws Exception
     */
    public function testRequestWithInvalidContentTypes(): void
    {
        $content = '{"name": "test"}';
        $object = TypeString::class;

        $request = $this->getRequest('fail', $content);
        $result = $this->dataMapper->tryRequest($request, $object);

        $this->assertNull($result);
        $this->assertEquals(MapStatusEnum::ERROR, $this->dataMapper->getMapStatusEnum());
        $this->assertSame('Unsupported content type', $this->dataMapper->getErrorMessage());
    }

    /**
     * @throws Exception
     */
    public function testRequestWithValidContentTypeJson(): void
    {
        $content = '{"string": "test"}';
        $object = TypeString::class;

        $request = $this->getRequest('application/json', $content);
        $result = $this->dataMapper->tryRequest($request, $object);

        $this->assertNull($this->dataMapper->getErrorMessage());
        $this->assertEquals(MapStatusEnum::SUCCESS, $this->dataMapper->getMapStatusEnum());
        $this->assertEquals($this->expectedObject(), $result);
    }

    /**
     * @throws Exception
     */
    public function testRequestWithEmptyContentTypeJson(): void
    {
        $content = '';
        $object = TypeString::class;

        $request = $this->getRequest('application/json', $content);
        $result = $this->dataMapper->tryRequest($request, $object);

        $this->assertNull($result);
        $this->assertEquals(MapStatusEnum::ERROR, $this->dataMapper->getMapStatusEnum());
        $this->assertSame('No content provided in request', $this->dataMapper->getErrorMessage());
    }

    /**
     * @throws Exception
     */
    public function testRequestWithEmptyContentTypeJsonAndForceInstance(): void
    {
        $content = '';
        $object = TypeString::class;

        $request = $this->getRequest('application/json', $content);
        $result = $this->dataMapper->tryRequest($request, $object, [], true);

        $this->assertNull($result);
        $this->assertEquals(MapStatusEnum::ERROR, $this->dataMapper->getMapStatusEnum());
        $this->assertSame('Invalid Json string', $this->dataMapper->getErrorMessage());
    }

    /**
     * @throws Exception
     */
    public function testRequestWithValidContentTypeNeon(): void
    {
        $content = 'string: "test"';
        $object = TypeString::class;

        $request = $this->getRequest('application/neon', $content);
        $result = $this->dataMapper->tryRequest($request, $object);

        $this->assertNull($this->dataMapper->getErrorMessage());
        $this->assertEquals(MapStatusEnum::SUCCESS, $this->dataMapper->getMapStatusEnum());
        $this->assertEquals($this->expectedObject(), $result);
    }

    /**
     * @throws Exception
     */
    public function testRequestWithEmptyContentTypeNeon(): void
    {
        $content = '';
        $object = TypeString::class;

        $request = $this->getRequest('application/neon', $content);
        $result = $this->dataMapper->tryRequest($request, $object);

        $this->assertNull($result);
        $this->assertEquals(MapStatusEnum::ERROR, $this->dataMapper->getMapStatusEnum());
        $this->assertSame('No content provided in request', $this->dataMapper->getErrorMessage());
    }

    /**
     * @throws Exception
     */
    public function testRequestWithEmptyContentTypeNeonAndForceInstance(): void
    {
        $content = '';
        $object = TypeString::class;

        $request = $this->getRequest('application/neon', $content);
        $result = $this->dataMapper->tryRequest($request, $object, [], true);

        $this->assertNull($result);
        $this->assertEquals(MapStatusEnum::ERROR, $this->dataMapper->getMapStatusEnum());
        $this->assertSame('Invalid Neon decode return', $this->dataMapper->getErrorMessage());
    }

    /**
     * @throws Exception
     */
    public function testRequestWithValidContentTypeXml(): void
    {
        $content = '<?xml version="1.0" encoding="UTF-8" ?>' .
            '<root><string>test</string></root>';
        $object = TypeString::class;

        $request = $this->getRequest('application/xml', $content);
        $result = $this->dataMapper->tryRequest($request, $object);

        $this->assertNull($this->dataMapper->getErrorMessage());
        $this->assertEquals(MapStatusEnum::SUCCESS, $this->dataMapper->getMapStatusEnum());
        $this->assertEquals($this->expectedObject(), $result);
    }

    /**
     * @throws Exception
     */
    public function testRequestWithEmptyContentTypeXml(): void
    {
        $content = '';
        $object = TypeString::class;

        $request = $this->getRequest('application/xml', $content);
        $result = $this->dataMapper->tryRequest($request, $object);

        $this->assertNull($result);
        $this->assertEquals(MapStatusEnum::ERROR, $this->dataMapper->getMapStatusEnum());
        $this->assertSame('No content provided in request', $this->dataMapper->getErrorMessage());
    }

    /**
     * @throws Exception
     */
    public function testRequestWithEmptyContentTypeXmlAndForceInstance(): void
    {
        $content = '';
        $object = TypeString::class;

        $request = $this->getRequest('application/xml', $content);
        $result = $this->dataMapper->tryRequest($request, $object, [], true);

        $this->assertNull($result);
        $this->assertEquals(MapStatusEnum::ERROR, $this->dataMapper->getMapStatusEnum());
        $this->assertSame('Invalid XML: String could not be parsed as XML', $this->dataMapper->getErrorMessage());
    }

    /**
     * @throws Exception
     */
    public function testRequestWithValidContentTypeYaml(): void
    {
        $content = 'string: "test"';
        $object = TypeString::class;

        $request = $this->getRequest('application/yaml', $content);
        $result = $this->dataMapper->tryRequest($request, $object);

        $this->assertNull($this->dataMapper->getErrorMessage());
        $this->assertEquals(MapStatusEnum::SUCCESS, $this->dataMapper->getMapStatusEnum());
        $this->assertEquals($this->expectedObject(), $result);
    }

    /**
     * @throws Exception
     */
    public function testRequestWithEmptyContentTypeYaml(): void
    {
        $content = '';
        $object = TypeString::class;

        $request = $this->getRequest('application/yaml', $content);
        $result = $this->dataMapper->tryRequest($request, $object);

        $this->assertNull($result);
        $this->assertEquals(MapStatusEnum::ERROR, $this->dataMapper->getMapStatusEnum());
        $this->assertSame('No content provided in request', $this->dataMapper->getErrorMessage());
    }

    /**
     * @throws Exception
     */
    public function testRequestWithEmptyContentTypeYamlAndForceInstance(): void
    {
        $content = '';
        $object = TypeString::class;

        $request = $this->getRequest('application/yaml', $content);
        $result = $this->dataMapper->tryRequest($request, $object, [], true);

        $this->assertNull($result);
        $this->assertEquals(MapStatusEnum::ERROR, $this->dataMapper->getMapStatusEnum());
        $this->assertSame('Invalid Yaml decode return', $this->dataMapper->getErrorMessage());
    }
}
