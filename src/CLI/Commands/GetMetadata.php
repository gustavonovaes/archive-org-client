<?php

namespace GNovaes\CLI\Commands;

use GNovaes\ArchiveOrg\Client;
use GNovaes\CLI\Interfaces\CommandInterface;
use GNovaes\CLI\Interfaces\PrinterInterface;

class GetMetadata implements CommandInterface
{
  private Client $client;
  private PrinterInterface $printer;

  public function __construct(Client $client, PrinterInterface $printer)
  {
    $this->client = $client;
    $this->printer = $printer;
  }

  /**
   * Print 
   * 
   * $args[]
   *  [0] string Metadata identifier.
   * 
   * @param array $args (see above)
   *
   * @return void
   */
  public function run(array $args = []): void
  {
    if (empty($args)) {
      throw new \InvalidArgumentException("Metadata identifier not passed.");
    }

    $metadataIdentifier = $args[0];

    $metadata = $this->client->fetchMetadata($metadataIdentifier);

    $publicDate = $metadata->publicDate()->format('Y-m-d H:i:s');
    $collections = implode(', ', $metadata->collections());

    $message = <<<STR
Metadata: \t{$metadataIdentifier}
Public Date: \t{$publicDate}
Collections: \t{$collections}

STR;
    $this->printer->display($message);
  }
}
