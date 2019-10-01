<?php
namespace App\Command\TemplateSeed;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Syntaxseed\Templateseed\TemplateSeed;

class CachePruneCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'templateseed:cache-prune';
    private $cachePath;
    private $cacheExpiry;

    public function __construct(TemplateSeed $tpl)
    {
        $this->cachePath = $tpl->getCachePath();
        $this->cacheExpiry = $tpl->getCacheExpiry();
        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Prune expired TemplateSeed cache files.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command will prune all cached templates that are older than the configured TTL from the cache directory.')
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
        $filesExpired = array_filter($files, function($cacheFile){
            if (file_exists($cacheFile) && filemtime($cacheFile) > (time() - $this->cacheExpiry)) {
                return(false);
            } else {
                return(true);
            }
        });
        array_map('unlink', $filesExpired);
        $output->writeln('<info>Pruned '.sizeof($filesExpired).' expired cached template(s).</info>');
    }
}
