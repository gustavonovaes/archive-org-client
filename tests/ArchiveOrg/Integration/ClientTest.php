<?php

namespace GNovaes\Test\ArchiveOrg\Integration;

use PHPUnit\Framework\TestCase;

use GNovaes\ArchiveOrg\Client;
use GNovaes\ArchiveOrg\Exceptions\ConnectionException;
use GNovaes\ArchiveOrg\Exceptions\IdentifierNotFoundException;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;

class ClientTest extends TestCase
{
  private Client $client;

  public function setUp(): void
  {
    $config = [ 'timeout' => 5 ];
    $this->client = new Client(new GuzzleHttpClient($config));
  }

  public function testMetadataFetchs()
  {
    $identifier = 'popeye_taxi-turvey';
    $metadata = $this->client->fetchMetadata($identifier);

    $this->assertSame($identifier, $metadata->identifier());
    $this->assertEquals(new \DateTimeImmutable('2005-03-10 16:36:11'), $metadata->publicDate());
    $this->assertSame(['classic_cartoons', 'animationandcartoons'], $metadata->collections());
  }

  public function testIfMetadataFetchsThrowsExceptionWhenIdentifierNotFound()
  {
    $identifier = 'invalid-identifier';

    $this->expectException(IdentifierNotFoundException::class);

    $this->client->fetchMetadata($identifier);
  }

  public function testIfMetadataFetchsThrowsExceptionWhenAPIFailsToRespond()
  {
    $this->expectException(ConnectionException::class);
    $this->expectExceptionMessage('Fail to get a response from API');

    $httpClient = $this->givenHttpClientWithAPICrashed();
    $client = new Client($httpClient);
    $client->fetchMetadata('foo');
  }

  public function testItThrowsExceptionWhenHasNoInternetConnection()
  {
    $this->expectException(ConnectionException::class);
    $this->expectExceptionMessage('Fail to communicate with API');

    $httpClient = $this->givenHttpClientWithNoInternetConnection();
    $client = new Client($httpClient);
    $client->fetchMetadata('foo');
  }

  /**
   * @return ClientInterface
   */
  private function givenHttpClientWithAPICrashed()
  {
    $responseMock = $this->createMock(Response::class);
    $responseMock->method('getStatusCode')->willReturn(500);

    $httpClientMock =  $this->createMock(GuzzleHttpClient::class);
    $httpClientMock->method('request')->willReturn($responseMock);

    return $httpClientMock;
  }

  /**
   * @return ClientInterface
   */
  private function givenHttpClientWithNoInternetConnection()
  {
    $httpClientMock =  $this->createMock(GuzzleHttpClient::class);
    $httpClientMock->method('request')->willThrowException(new \Exception('There is no internet connection 🦖'));

    return $httpClientMock;
  }
}
