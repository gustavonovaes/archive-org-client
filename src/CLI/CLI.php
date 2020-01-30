<?php

namespace GNovaes\CLI;

use GNovaes\CLI\Exceptions\CommandNotFoundException;
use GNovaes\CLI\Exceptions\CommandNotPassedException;
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

  /**
   * Run the CLI
   * 
   * $args[]
   *  [0] string Executable name
   *  [1] string Command name
   *  [...] string (rest of args)
   * 
   * @param array $args (see above)
   * 
   * @throws CommandNotPassedException When a command is not passed.
   *
   * @return void
   */
  public function run(array $args = []): void
  {
    if (!isset($args[1])) {
      throw new CommandNotPassedException("The command name was not passed in args.");
    }

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
