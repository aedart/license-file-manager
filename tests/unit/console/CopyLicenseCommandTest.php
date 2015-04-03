<?php

use Aedart\License\File\Manager\Console\CopyLicenseCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Codeception\Configuration;

/**
 * Class CopyLicenseCommandTest
 *
 * @coversDefaultClass Aedart\License\File\Manager\Console\CopyLicenseCommand
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class CopyLicenseCommandTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * Source dummy license file
     *
     * @var string
     */
    protected $sourceLicense = null;

    /**
     * Destination folder
     *
     * @var string
     */
    protected $destinationFolder = null;

    /**
     * License filename
     *
     * @var string
     */
    protected $licenseFilename = 'CommandCopiedLicense';

    protected function _before()
    {
        $this->sourceLicense = Configuration::dataDir() . 'license/MyCustomLicense';
        $this->destinationFolder = Configuration::outputDir();
    }

    protected function _after()
    {
        @unlink($this->destinationFolder . '/' . $this->licenseFilename);
    }

    /********************************************************************
     * Helper methods
     *******************************************************************/

    /**
     * Get a new copy command
     *
     * @return CopyLicenseCommand
     */
    protected function getCopyCommand(){
        return new CopyLicenseCommand();
    }

    /********************************************************************
     * Actual tests
     *******************************************************************/

    /**
     * @test
     *
     * @covers ::configure
     * @covers ::execute
     */
    public function copy(){
        $command = $this->getCopyCommand();

        $tester = new CommandTester($command);
        $tester->execute([
            'source' => $this->sourceLicense,
            '--' . $command::DESTINATION_OPTION => $this->destinationFolder,
            '--' . $command::LICENSE_FILENAME_OPTION => $this->licenseFilename
        ]);
        $outputDisplay = $tester->getDisplay();

        $targetLicense = $this->destinationFolder . $this->licenseFilename;

        $this->assertFileExists($targetLicense);
        $this->assertContains('was successfully copied into', $outputDisplay);
    }

}