<?php  namespace Aedart\License\File\Manager;

use Aedart\License\File\Manager\Exceptions\LicenseCouldNotBeCreatedException;
use Aedart\License\File\Manager\Exceptions\LicenseFileDoesNotExistException;
use Aedart\License\File\Manager\Interfaces\IFileHandler;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Handler
 *
 * @see IFileHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\License\File\Manager
 */
class Handler implements IFileHandler{

    /**
     * The output
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Handler constructor.
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function copy($sourceLicense, $destination, $destinationFilename = 'LICENSE') {
        // Check if the source license file exists
        if(!file_exists($sourceLicense)){
            throw new LicenseFileDoesNotExistException(sprintf('%s cannot be read or does not exist', $sourceLicense));
        }

        // Create the full destination
        $fullDestination = $destination . '/' . $destinationFilename;

        // Get the checksum value for each file
        $sourceChecksum = $this->checksum($sourceLicense);
        $destinationChecksum = $this->checksum($fullDestination);

        if($sourceChecksum == $destinationChecksum){
            $this->output->writeln('<info>No license changes detected!</info>');
            return;
        }

        // Perform the copy-file
        try {
            $this->performCopy($sourceLicense, $fullDestination);

            // Output trace msg
            $msg = sprintf('%s was successfully copied into %s', $sourceLicense, $fullDestination);
            $this->output->writeln('<info>'.$msg.'</info>');
        } catch(\Exception $e){
            throw new LicenseCouldNotBeCreatedException(sprintf('%s could not be created, from source: %s; %s', $fullDestination, $sourceLicense, $e->getMessage()), 0, $e);
        }
    }

    /**
     * Perform a copy-file
     *
     * @param string $sourceFile The source file
     * @param string $targetDestination The target location
     *
     * @return bool True if the source file was successfully copied to the target destination, false if not
     */
    public function performCopy($sourceFile, $targetDestination){
        return copy($sourceFile, $targetDestination);
    }

    /**
     * Returns the SHA1 checksum of the given file
     *
     * @param string $file Path to file
     *
     * @return string Checksum or empty string if file doesn't exist
     */
    public function checksum($file)
    {
        if(file_exists($file)){
            return sha1_file($file);
        }

        return '';
    }
}