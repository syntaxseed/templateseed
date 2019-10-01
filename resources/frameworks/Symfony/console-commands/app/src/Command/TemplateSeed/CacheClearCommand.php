<?php
namespace App\Command\TemplateSeed;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Syntaxseed\Templateseed\TemplateSeed;

class CacheClearCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'templateseed:cache-clear';
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
        ->setDescription('Clear the TemplateSeed cache directory.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command will empty all cached templates from the cache directory.')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (is_null($this->cachePath)) {
            $output->writeln('<error>Cache directory not set.</error>');
            return;
        }

        if (!is_readable($this->cachePath) || !is_writeable($this->cachePath) || !is_dir($this->cachePath)) {
            $output->writeln('<error>Cache directory not accessible. ('.$this->cachePath.')</error>');
            return;
        }

        $files = array_filter((array) glob($this->cachePath."*"));
        array_map('unlink', $files);
        $output->writeln('<info>Cleared '.sizeof($files).' cached template(s).</info>');
    }
}
