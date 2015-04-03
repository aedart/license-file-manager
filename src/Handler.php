<?php  namespace Aedart\License\File\Manager;

use Aedart\License\File\Manager\Exceptions\LicenseCouldNotBeCreatedException;
use Aedart\License\File\Manager\Exceptions\LicenseFileDoesNotExistException;
use Aedart\License\File\Manager\Interfaces\IFileHandler;

/**
 * Class Handler
 *
 * @see IFileHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\License\File\Manager
 */
class Handler implements IFileHandler{

    public function copy($sourceLicense, $destination, $destinationFilename = 'LICENSE') {
        // Check if the source license file exists
        if(!file_exists($sourceLicense)){
            throw new LicenseFileDoesNotExistException(sprintf('%s cannot be read or does not exist', $sourceLicense));
        }

        // Create the full destination
        $fullDestination = $destination . '/' . $destinationFilename;

        // Perform the copy-file
        $result = copy($sourceLicense, $fullDestination);

        // Post copy check
        if(!$result){
            throw new LicenseCouldNotBeCreatedException(sprintf('%s could not be created, from source: %s', $fullDestination, $sourceLicense));
        }
    }
}