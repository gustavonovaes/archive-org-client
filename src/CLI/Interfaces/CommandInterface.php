<?php

namespace GNovaes\CLI\Interfaces;

interface CommandInterface
{
  public function run(array $argv = []): void;
}
