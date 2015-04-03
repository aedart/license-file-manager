<?php  namespace Aedart\License\File\Manager\Interfaces; 

use Aedart\License\File\Manager\Exceptions\LicenseFileDoesNotExistException;
use Aedart\License\File\Manager\Exceptions\LicenseCouldNotBeCreatedException;

/**
 * Interface File Handler
 *
 * Component handles a license file, by providing means to copy and paste it
 * from a target source (filesystem) and into a specified destination folder
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\License\File\Manager\Interfaces
 */
interface IFileHandler {

    /**
     * Copy a license file from the specified location into
     * the destination folder and renames the given file into
     * the specified filename
     *
     * @param string $sourceLicense Full path and filename of the target license file
     * @param string $destination The folder where to place the given license file
     * @param string $destinationFilename [Optional][Default 'LICENSE'] New name of the
     *
     * @throws LicenseFileDoesNotExistException If the source license-file could not be found, read or copied
     * @throws LicenseCouldNotBeCreatedException If license-file could not be created at the given destination
     */
    public function copy($sourceLicense, $destination, $destinationFilename = 'LICENSE');

}