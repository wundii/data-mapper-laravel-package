<?php

declare(strict_types=1);

namespace Wundii\DataMapper\LaravelPackage\Tests;

use Illuminate\Http\Request;
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

    public function getRequest(
        string $contentType,
        string $content
    ): Request {
        return Request::create(
            '/dummy-uri',
            'POST',
            [], // parameters
            [], // cookies
            [], // files
            [
                'CONTENT_TYPE' => $contentType,
            ],
            $content
        );
    }

    public function expectedObject(): TypeString
    {
        return new TypeString('test');
    }

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
