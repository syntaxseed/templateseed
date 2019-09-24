<?php
namespace App\Command\TemplateSeed;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Syntaxseed\Templateseed\TemplateSeed;

class CachePathCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'templateseed:cache-path';
    private $cachePath;

    public function __construct(TemplateSeed $tpl)
    {
        $this->cachePath = $tpl->getCachePath();
        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Display the TemplateSeed cache directory path.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command will display the path to the TemplateSeed cache directory.')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (is_null($this->cachePath)) {
            $output->writeln('<error>Cache directory not set.</error>');
            return;
        }

        $output->writeln('<comment>TemplateSeed cache path: '.$this->cachePath.'</comment>');
    }
}
