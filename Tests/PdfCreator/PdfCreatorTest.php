<?php

namespace Ticketpark\PdfBundle\Tests\PdfCreator;

use Ticketpark\PdfBundle\PdfCreator\PdfCreator;

class PdfCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateNewPdf()
    {
        $pdfCreator = new PdfCreator($this->getPdfClientMock(), $this->getFileHandlerMock());

        $result = $pdfCreator
            ->setContent('dummy')
            ->setIdentifier('id')
            ->create();

        $this->assertEquals($result, 'dummy');
    }

    public function testCreatePdfAlreadyInCache()
    {
        $pdfCreator = new PdfCreator($this->getPdfClientMock(), $this->getFileHandlerMock(true));

        $result = $pdfCreator
            ->setContent('dummy')
            ->setIdentifier('id')
            ->create();

        $this->assertEquals('oldFileFromCache', $result);
    }

    /**
 * @expectedException Ticketpark\PdfBundle\Exception\InvalidArgumentException
 */
    public function testNoContent()
    {
        $pdfCreator = new PdfCreator($this->getPdfClientMock(), $this->getFileHandlerMock());

        $pdfCreator
            ->setIdentifier('id')
            ->create();
    }

    /**
     * @expectedException Ticketpark\PdfBundle\Exception\InvalidArgumentException
     */
    public function testNoIdentifier()
    {
        $pdfCreator = new PdfCreator($this->getPdfClientMock(), $this->getFileHandlerMock());

        $pdfCreator
            ->setContent('dummy')
            ->create();
    }

    public function getPdfClientMock()
    {
        $pdfClient = $this->getMockBuilder('Bytes\Docraptor\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('convert'))
            ->getMock();

        $pdfClient->expects($this->any())
            ->method('convert')
            ->will($this->returnCallback(array($this, 'getFileContent')));

        return $pdfClient;
    }

    public function getFileHandlerMock($fileInCache=false)
    {
        $fileHandler = $this->getMockBuilder('Ticketpark\FileBundle\FileHandler\FileHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('fromCache', 'cache'))
            ->getMock();

        $fileHandler->expects($this->any())
            ->method('fromCache')
            ->will($this->returnValue(call_user_func(array($this, 'fromCache'), $fileInCache)));

        $fileHandler->expects($this->any())
            ->method('cache')
            ->will($this->returnCallback(array($this, 'cache')));

        return $fileHandler;
    }

    public function fromCache($fileInCache)
    {
        if ($fileInCache) {
            return 'oldFileFromCache';
        }

        return false;
    }

    public function cache()
    {
        $args = func_get_args();
        return $args[0];
    }

    public function getFileContent()
    {
        $args = func_get_args();
        return $args[0]->getContent();
    }
}