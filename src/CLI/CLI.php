<?php

namespace GNovaes\CLI;

use GNovaes\CLI\Exceptions\CommandNotFoundException;
use GNovaes\CLI\Interfaces\CommandInterface;

class CLI
{
  /** @var CommandInterface[] $commands */
  private array $commands = [];

  /**
   * Load commands
   * @param CommandInterface[] $commands Array of commands
   *
   * @return void
   */
  public function loadCommands(array $commands): void
  {
    foreach ($commands as $commandName => $command) {
      if ($command instanceof CommandInterface) {
        $this->commands[$commandName] = $command;
      }
    }
  }

  public function run(array $args = []): void
  {
    $command = $this->getCommand($args[1]);

    $commandArgs = \array_slice($args, 2);

    $command->run($commandArgs);
  }

  /**
   * Return a command.
   * 
   * @param string $commandName
   * 
   * @throws CommandNotFoundException When command was not loaded.
   *
   * @return \GNovaes\CLI\Interfaces\CommandInterface
   */
  private function getCommand(string $commandName): CommandInterface
  {
    if (!isset($this->commands[$commandName])) {
      throw new CommandNotFoundException("Command \"{$commandName}\" does not exists.");
    }

    return $this->commands[$commandName];
  }
}
