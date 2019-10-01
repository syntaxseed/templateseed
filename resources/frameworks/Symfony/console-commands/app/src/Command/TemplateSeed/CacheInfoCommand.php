<?php
namespace App\Command\TemplateSeed;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Syntaxseed\Templateseed\TemplateSeed;

class CacheInfoCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'templateseed:cache-info';
    private $cacheSetting;
    private $cachePath;
    private $cacheExpiry;

    public function __construct(TemplateSeed $tpl)
    {
        $this->cacheSetting = $tpl->isCacheEnabled() ? '<info>true</info>' : '<comment>false</comment>';
        $this->cachePath = $tpl->getCachePath();
        $this->cacheExpiry = $tpl->getCacheExpiry();
        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Display TemplateSeed cache information.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command will display information about the TemplateSeed cache.')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("");
        $output->writeln("<fg=blue;options=bold>TemplateSeed cache enabled:</> ".$this->cacheSetting);

        $output->writeln("<fg=blue;options=bold>TemplateSeed cache TTL:</> ".$this->cacheExpiry.' seconds');

        if (is_null($this->cachePath)) {
            $output->writeln('<error>Cache directory not set.</error>');
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
        $numCache = sizeof($files);
        $numExpired = sizeof($filesExpired);
        $numValid = $numCache - $numExpired;
        $output->writeln("<fg=blue;options=bold>TemplateSeed cache counts:</> {$numValid} valid, {$numExpired} expired, {$numCache} total");

        $output->writeln("<fg=blue;options=bold>TemplateSeed cache path:</> ".$this->cachePath.'');
        $output->writeln("");
    }
}
