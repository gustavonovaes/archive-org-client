<?php

namespace GNovaes\ArchiveOrg;

use GNovaes\ArchiveOrg\Exceptions\ConnectionException;
use GNovaes\ArchiveOrg\Exceptions\IdentifierNotFoundException;
use GNovaes\ArchiveOrg\Item\Metadata;
use GuzzleHttp\ClientInterface;

/**
 * A client that pick data from https://archive.org
 */
class Client
{
  private string $apiUrl = 'https://archive.org';

  private ClientInterface $httpClient;

  /**
   * Create a instance of Client.
   * @param \GuzzleHttp\ClientInterface $httpClient
   */
  public function __construct(ClientInterface $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  /**
   * Fetch metadata from api.
   * @param string $identifier
   * 
   * @throws ConnectionException When it fails to communicate with API.
   * @throws IdentifierNotFoundException When identifier was not found.
   *
   * @return Metadata
   */
  public function fetchMetadata(string $identifier): Metadata
  {
    try {
      $response = $this->httpClient->request('GET', $this->apiUrl . "/metadata/$identifier");
    } catch (\Exception $_) {
      throw new ConnectionException("Fail to communicate with API");
    }

    if ($response->getStatusCode() !== 200) {
      throw new ConnectionException("Fail to get a response from API");
    }

    $jsonString = $response->getBody()->getContents();
    $data = \json_decode($jsonString, true);
    if (empty($data)) {
      throw new IdentifierNotFoundException("Identifier '{$identifier}' not found.");
    }

    return Metadata::fromArray($data['metadata']);
  }
}
