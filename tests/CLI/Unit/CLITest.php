<?php

namespace GNovaes\CLI\Test\Unit;

use GNovaes\CLI\CLI;
use GNovaes\CLI\Interfaces\CommandInterface;
use GNovaes\CLI\Interfaces\PrinterInterface;
use PHPUnit\Framework\TestCase;

class CliTest extends TestCase
{
  public function testRunACommand()
  {
    $commandName = "command-a";

    $argv = [NULL, $commandName, "arg-1", "arg-2"];
    $argvCommand = ["arg-1", "arg-2"];

    $printerMock = $this->createMock(PrinterInterface::class);
    $printerMock->method('display');

    $cli = new Cli($printerMock);

    $commandMock = $this->createMock(CommandInterface::class);
    $commandMock->expects($this->once())->method('run')
      ->with($argvCommand);

    $commands = [
      $commandName => $commandMock
    ];

    $cli->loadCommands($commands);

    $cli->run($argv);
  }
}
