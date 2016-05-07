<?php

use Aedart\License\File\Manager\Handler;
use Codeception\Configuration;
use \Mockery as m;
use Symfony\Component\Console\Output\OutputInterface;
use Faker\Factory;

/**
 * Class HandlerTest
 *
 * @group handler
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
     * @var Faker\Generator
     */
    protected $faker;

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
        $this->faker = Factory::create();
        $this->sourceLicense = Configuration::dataDir() . 'license/MyCustomLicense';
        $this->destinationFolder = Configuration::outputDir();
    }

    protected function _after()
    {
        @unlink($this->destinationFolder . 'LICENSE');
        m::close();
    }

    /********************************************************************
     * Helper methods
     *******************************************************************/

    /**
     * Get a new instance of the (license) handler mock
     *
     * @param OutputInterface $output
     *
     * @return Handler
     */
    protected function getHandler($output){
        return new Handler($output);
    }

    /**
     * Returns a handler mock
     *
     * @param OutputInterface $output
     *
     * @return m\Mock|Handler
     */
    protected function makeHandlerMock($output)
    {
        return m::mock(Handler::class, [$output])->makePartial();
    }

    /**
     * Returns a mock of the output interface
     *
     * @return m\MockInterface|OutputInterface
     */
    protected function makeOutputMock()
    {
        return m::mock(OutputInterface::class);
    }

    /********************************************************************
     * Actual tests
     *******************************************************************/

    /**
     * @test
     *
     * @covers ::__construct
     */
    public function canObtainHandler()
    {
        $handler = $this->getHandler($this->makeOutputMock());

        $this->assertNotNull($handler);
    }

    /**
     * @test
     *
     * @covers ::performCopy
     */
    public function canCopyFile(){
        $handler = $this->getHandler($this->makeOutputMock());

        $targetLicense = $this->destinationFolder . 'LICENSE';

        $handler->performCopy($this->sourceLicense, $targetLicense);

        $this->assertFileExists($targetLicense);
    }

    /**
     * @test
     *
     * @covers ::checksum
     */
    public function canObtainChecksumOfExistingFile()
    {
        $handler = $this->getHandler($this->makeOutputMock());

        $this->assertNotEmpty($handler->checksum($this->sourceLicense));
    }

    /**
     * @test
     *
     * @covers ::checksum
     */
    public function returnsEmptyChecksumWhenFileDoesNotExist()
    {
        $handler = $this->getHandler($this->makeOutputMock());

        $this->assertEmpty($handler->checksum($this->faker->word));
    }

    /**
     * @test
     * @covers ::copy
     *
     * @expectedException \Aedart\License\File\Manager\Exceptions\LicenseFileDoesNotExistException
     */
    public function itFailsWhenSourceLicenseFileDoesNotExist()
    {
        $handler = $this->getHandler($this->makeOutputMock());

        $handler->copy($this->faker->word, $this->destinationFolder);
    }

    /**
     * @test
     *
     * @covers :copy
     * @covers :performCopy
     * @covers :checksum
     */
    public function itCanCopySourceLicenseFile()
    {
        $output = $this->makeOutputMock();
        $output->shouldReceive('writeln')
            ->withAnyArgs();

        $handler = $this->getHandler($output);

        $handler->copy($this->sourceLicense, $this->destinationFolder);

        $targetLicense = $this->destinationFolder . 'LICENSE';

        $this->assertFileExists($targetLicense);
    }

    /**
     * @test
     *
     * @covers ::copy
     */
    public function doesNotCopyLicenseIfChecksumIsTheSame()
    {
        $output = $this->makeOutputMock();
        $output->shouldReceive('writeln')
            ->withAnyArgs();

        $handler = $this->makeHandlerMock($output);
        $handler->shouldReceive('checksum')
            ->withAnyArgs()
            ->twice()
            ->andReturn($this->getHandler($output)->checksum($this->sourceLicense));

        $handler->shouldNotReceive('performCopy');

        $handler->copy($this->sourceLicense, $this->destinationFolder);
    }

    /**
     * @test
     * @covers ::copy
     *
     * @covers ::performCopy
     *
     * @expectedException \Aedart\License\File\Manager\Exceptions\LicenseCouldNotBeCreatedException
     */
    public function itFailsIfLicenseCouldNotBeWrittenToDisk()
    {
        $handler = $this->makeHandlerMock($this->makeOutputMock());
        $handler->shouldReceive('performCopy')
            ->withAnyArgs()
            ->andThrow(Exception::class);

        $handler->copy($this->sourceLicense, $this->destinationFolder);
    }
}