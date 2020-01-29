<?php

namespace GNovaes\Test\ArchiveOrg\Unit;

use GNovaes\ArchiveOrg\Item\Metadata;
use PHPUnit\Framework\TestCase;

class MetadataTest extends TestCase
{
  public function testFromArray()
  {
    $arraySingleCollection = [
      'identifier' => 'Foo',
      'publicdate' => date('Y-m-d H:i:s'),
      'collection' => 'Bar'
    ];

    $metadata = Metadata::fromArray($arraySingleCollection);

    $this->assertSame($arraySingleCollection['identifier'], $metadata->identifier());
    $this->assertSame($arraySingleCollection['publicdate'], $metadata->publicDate()->format('Y-m-d H:i:s'));
    $this->assertContains($arraySingleCollection['collection'], $metadata->collections());
  }

  public function testFromArrayMultipleCollections()
  {
    $arrayMultipleCollections = [
      'identifier' => 'Foo',
      'publicdate' => date('Y-m-d H:i:s'),
      'collection' => ['Bar', 'Baz']
    ];

    $metadata = Metadata::fromArray($arrayMultipleCollections);

    $this->assertSame($arrayMultipleCollections['identifier'], $metadata->identifier());
    $this->assertSame($arrayMultipleCollections['publicdate'], $metadata->publicDate()->format('Y-m-d H:i:s'));
    $this->assertSame($arrayMultipleCollections['collection'], $metadata->collections());
  }

  public function testFromArrayWithInvalidDate()
  {
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessageMatches('/is not a valid date format/');

    $arrayInvalidDate = [
      'identifier' => 'Foo',
      'publicdate' => 'invalid',
      'collection' => ['Bar', 'Baz']
    ];

    Metadata::fromArray($arrayInvalidDate);
  }
}
