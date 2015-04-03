<?php  namespace Aedart\License\File\Manager\Console;

use Aedart\License\File\Manager\Handler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Copy License Command
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\License\File\Manager\Console
 */
class CopyLicenseCommand extends Command{

    /**
     * Source argument name
     */
    const SOURCE_ARGUMENT = 'source';

    /**
     * License filename option name
     */
    const LICENSE_FILENAME_OPTION = 'licenseFilename';

    protected function configure(){
        $this
            ->setName('license:copy')
            ->setDescription('Copies the target license file into the root of your project. Eventual already existing license file will be overwritten!')
            ->addArgument(
                self::SOURCE_ARGUMENT,
                InputArgument::REQUIRED,
                'Source license file to be copied'
            )
            ->addOption(
                self::LICENSE_FILENAME_OPTION,
                'name',
                InputOption::VALUE_OPTIONAL,
                'The filename to give your copied license file',
                'LICENSE'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // Get the arguments and options
        $source = $input->getArgument(self::SOURCE_ARGUMENT);
        $filename = $input->getOption(self::LICENSE_FILENAME_OPTION);

        // Create a new (license) file handler
        $handler = new Handler();

        // Perform the copy
        $handler->copy($source, realpath(getcwd()), $filename);

        // Output trace msg
        $msg = sprintf('%s was successfully copied into %s', $source, $filename);
        $output->writeln('<info>'.$msg.'</info>');
    }
}