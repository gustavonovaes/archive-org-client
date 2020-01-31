<?php

namespace GNovaes\Test\CLI\Unit;

use GNovaes\CLI\CLI;
use GNovaes\CLI\Exceptions\CommandNotFoundException;
use GNovaes\CLI\Exceptions\CommandNotPassedException;
use GNovaes\CLI\Interfaces\CommandInterface;
use PHPUnit\Framework\TestCase;

class CLITest extends TestCase
{
  private CLI $cli;

  public function setUp(): void
  {
    $this->cli = new CLI();
  }

  public function testRunACommand()
  {
    $commandName = "command-a";

    $argv = [NULL, $commandName, "arg-1", "arg-2"];
    $argvCommand = ["arg-1", "arg-2"];

    $commandMock = $this->createMock(CommandInterface::class);
    $commandMock->expects($this->once())->method('run')
      ->with($argvCommand);

    $commands = [
      $commandName => $commandMock
    ];

    $this->cli->loadCommands($commands);

    $this->cli->run($argv);
  }

  public function testItThrowsExceptionWhenRunInexistentCommand()
  {
    $this->expectException(CommandNotFoundException::class);

    $commandName = "inexistent-command";
    $argv = [NULL, $commandName];

    $this->cli->run($argv);
  }

  public function testItThorwsExceptionWhenNotPassedACommand()
  {
    $this->expectException(CommandNotPassedException::class);
    $this->cli->run([]);
  }
}
