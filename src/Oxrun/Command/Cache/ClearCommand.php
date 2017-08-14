<?php

namespace Oxrun\Command\Cache;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class ClearCommand
 * @package Oxrun\Command\Cache
 */
class ClearCommand extends Command
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('cache:clear')
            ->setDescription('Clears the cache');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $compileDir = $this->getCompileDir();
        foreach (glob($compileDir . DIRECTORY_SEPARATOR . '*') as $filename) {
            if (!is_dir($filename)) {
                unlink($filename);
            }
        }
        foreach (glob($compileDir . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . '*') as $filename) {
            if (!is_dir($filename)) {
                unlink($filename);
            }
        }
        $output->writeln('<info>Cache cleared.</info>');
    }



    /**
     * Find sCompileDir path without connect to DB.
     *
     * @return string
     */
    protected function getCompileDir()
    {
        $oxidPath = $this->getApplication()->getShopDir();
        $configfile = $oxidPath . DIRECTORY_SEPARATOR . 'config.inc.php';

        if ($oxidPath && file_exists($configfile)) {
            $oxConfigFile = new \OxConfigFile($configfile);
            return $oxConfigFile->getVar('sCompileDir');
        }

        throw new FileNotFoundException("$configfile");
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getApplication()->bootstrapOxid();
    }

}
