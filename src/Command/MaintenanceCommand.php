<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Maintenance command.
 */
class MaintenanceCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addArgument('action', [
            'help' => 'Enable or disable maintenance mode',
            'required' => true
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $action = $args->getArgument('action');

        if ($action === 'enable') {
            file_put_contents(TMP . 'maintenance.flag', 'enabled');
            $io->out('Maintenance mode enabled.');
        } elseif ($action === 'disable') {
            unlink(TMP . 'maintenance.flag');
            $io->out('Maintenance mode disabled.');
        } else {
            $io->out('Invalid action. Use "enable" or "disable".');
        }
    }
}
