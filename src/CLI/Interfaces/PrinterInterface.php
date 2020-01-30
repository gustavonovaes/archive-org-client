<?php

namespace GNovaes\CLI\Interfaces;

interface PrinterInterface
{
  public function display(string $message): void;
}
