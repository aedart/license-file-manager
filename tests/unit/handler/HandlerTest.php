<?php

use Aedart\License\File\Manager\Interfaces\IFileHandler;
use Aedart\License\File\Manager\Handler;
use Codeception\Configuration;
use \Mockery;

/**
 * Class HandlerTest
 *
 * @coversDefaultClass Aedart\License\File\Manager\Handler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class HandlerTest extends \Codeception\TestCase\Test
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

    protected function _before()
    {
        $this->sourceLicense = Configuration::dataDir() . 'license/MyCustomLicense';
        $this->destinationFolder = Configuration::outputDir();
    }

    protected function _after()
    {
        @unlink($this->destinationFolder . '/LICENSE');
        Mockery::close();
    }

    /********************************************************************
     * Helper methods
     *******************************************************************/

    /**
     * Get a new instance of the (license) handler mock
     *
     * @return Mockery\MockInterface|IFileHandler
     */
    protected function getHandler(){
        $m = Mockery::mock('Aedart\License\File\Manager\Handler')->makePartial();
        return $m;
    }

    /********************************************************************
     * Actual tests
     *******************************************************************/

    /**
     * @test
     * @covers ::copy
     *
     * @covers ::performCopy
     */
    public function copy(){
        $handler = $this->getHandler();

        $handler->copy($this->sourceLicense, $this->destinationFolder);

        $targetLicense = $this->destinationFolder . '/LICENSE';

        $this->assertFileExists($targetLicense);
    }

    /**
     * @test
     * @covers ::copy
     *
     * @expectedException \Aedart\License\File\Manager\Exceptions\LicenseFileDoesNotExistException
     */
    public function attemptCopyNonExistingLicenseFile(){
        $handler = $this->getHandler();
        $handler->copy('var/some/special/place/where/no/license/file/exists/LICENSE_' . rand(1, 9999), $this->destinationFolder);
    }

    /**
     * @test
     * @covers ::copy
     *
     * @covers ::performCopy
     *
     * @expectedException \Aedart\License\File\Manager\Exceptions\LicenseCouldNotBeCreatedException
     */
    public function actualCopyFails(){
        $handler = $this->getHandler();

        $handler->shouldReceive('performCopy')
            ->withAnyArgs()
            ->andReturn(false);

        $handler->copy($this->sourceLicense, $this->destinationFolder);
    }
}