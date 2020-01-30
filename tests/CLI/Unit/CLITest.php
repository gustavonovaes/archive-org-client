<?php

namespace GNovaes\CLI\Test\Unit;

use GNovaes\CLI\CLI;
use GNovaes\CLI\Exceptions\CommandNotFoundException;
use GNovaes\CLI\Interfaces\CommandInterface;
use GNovaes\CLI\Interfaces\PrinterInterface;
use PHPUnit\Framework\TestCase;

class CLITest extends TestCase
{
  public function testRunACommand()
  {
    $commandName = "command-a";

    $argv = [NULL, $commandName, "arg-1", "arg-2"];
    $argvCommand = ["arg-1", "arg-2"];

    $printerMock = $this->givenPrinterMock();
    $cli = new CLI($printerMock);

    $commandMock = $this->createMock(CommandInterface::class);
    $commandMock->expects($this->once())->method('run')
      ->with($argvCommand);

    $commands = [
      $commandName => $commandMock
    ];

    $cli->loadCommands($commands);

    $cli->run($argv);
  }

  public function testItThrowsExceptionWhenRunInexistentCommand()
  {
    $printerMock = $this->givenPrinterMock();
    $cli = new CLI($printerMock);

    $this->expectException(CommandNotFoundException::class);

    $commandName = "inexistent-command";
    $argv = [NULL, $commandName];

    $cli->run($argv);
  }

  private function givenPrinterMock()
  {
    $printerMock = $this->createMock(PrinterInterface::class);
    $printerMock->method('display');

    return $printerMock;
  }
}
