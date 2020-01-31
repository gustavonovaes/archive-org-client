<?php

namespace GNovaes\Test\CLI\Unit\Commands;

use GNovaes\ArchiveOrg\Client;
use GNovaes\ArchiveOrg\Item\Metadata;
use GNovaes\CLI\Commands\GetMetadata as CommandsGetMetadata;
use GNovaes\CLI\Interfaces\PrinterInterface;
use PHPUnit\Framework\TestCase;

class GetMetadata extends TestCase
{
  public function testItWorks()
  {
    $identifier = 'popeye_taxi-turvey';
    $publicDate = '2020-01-31 11:12:00';
    $arrColections = ["foo", "bar"];
    $collections = implode(', ', $arrColections);

    $metadata = new Metadata($identifier, new \DateTimeImmutable($publicDate), $arrColections);

    $clientMock = $this->createMock(Client::class);
    $clientMock->expects($this->once())
      ->method('fetchMetadata')
      ->with($identifier)
      ->willReturn($metadata);

    $message = <<<STR
Metadata: \t{$identifier}
Public Date: \t{$publicDate}
Collections: \t{$collections}

STR;
    $printerMock = $this->createMock(PrinterInterface::class);
    $printerMock->expects($this->once())
      ->method('display')
      ->with($message);

    $getMetadataCommand = new CommandsGetMetadata($clientMock, $printerMock);

    $getMetadataCommand->run([$identifier]);
  }


  public function testItThrowExceptionWhenHasNotMetadataIdentifier()
  {
    $clientMock = $this->createMock(Client::class);
    $printerMock = $this->createMock(PrinterInterface::class);
    $getMetadataCommand = new CommandsGetMetadata($clientMock, $printerMock);

    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage("Metadata identifier not passed.");

    $getMetadataCommand->run([]);
  }
  
}
