<?php

namespace GNovaes\ArchiveOrg\Item;

use Webmozart\Assert\Assert;

class Metadata
{
  private string $identifier;
  private \DateTimeInterface $publicDate;
  private array $collections;

  public function __construct(
    string $identifier,
    \DateTimeInterface $publicDate,
    array $collections
  ) {
    $this->identifier = $identifier;
    $this->publicDate = $publicDate;
    $this->collections = $collections;
  }

  public function identifier(): string
  {
    return $this->identifier;
  }

  public function publicDate(): \DateTimeInterface
  {
    return $this->publicDate;
  }

  public function collections(): array
  {
    return $this->collections;
  }

  /**
   * Create a instance of Client from array.
   * 
   * array
   *  ['identifier'] string The identifier.
   *  ['publicdate'] string Date in the format "Y-m-d H:i:s".
   *  ['collection'] string|array The collection or array of collections.
   * 
   * @param array $array (see above)
   *
   * @return self
   */
  public static function fromArray(array $array): self
  {
    [
      'identifier' => $identifier,
      'publicdate' => $publicDateRaw,
      'collection' => $collection
    ] = $array;

    $arrCollections = is_array($collection) ? $collection : [$collection];

    $publicDate = \DateTime::createFromFormat('Y-m-d H:i:s', $publicDateRaw);
    if (false === $publicDate) {
      throw new \InvalidArgumentException("The value \"{$publicDateRaw}\" is not a valid date format.");
    }

    return new self($identifier, $publicDate, $arrCollections);
  }
}
