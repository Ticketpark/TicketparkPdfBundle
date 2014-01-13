<?php

namespace Ticketpark\PdfBundle\PdfCreator;

use Bytes\Docraptor\Document\PdfDocument;
use Bytes\Docraptor\Client as PdfClient;
use Ticketpark\PdfBundle\Exception\InvalidArgumentException;
use Ticketpark\FileBundle\FileHandler\FileHandlerInterface;

class PdfCreator implements PdfCreatorInterface
{
    protected $pdfClient;
    protected $fileHandler;
    protected $content;
    protected $identifier;
    protected $useCache = true;

    public function __construct(PdfClient $pdfClient, FileHandlerInterface $fileHandler)
    {
        $this->pdfClient   = $pdfClient;
        $this->fileHandler = $fileHandler;
    }

    /**
     * @inheritDoc
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function create()
    {
        if (null === $this->identifier) {
            throw new InvalidArgumentException('You must define a file identifier with setIdentifier() to create a pdf');
        }

        if (null === $this->content) {
            throw new InvalidArgumentException('You must define a content with setContent() to create a pdf');
        }

        $contentHash = hash('sha256', $this->content);
        $identifier = 'ticketpark_pdf_'.$this->identifier;
        if (!$file = $this->fileHandler->fromCache($identifier, array($contentHash))) {

            $document = new PdfDocument($this->identifier);
            $document->setContent($this->content);

            $fileContents = $this->pdfClient->convert($document);
            $file = $this->fileHandler->cache($fileContents, $identifier, array($contentHash));
        }

        return $file;
    }
}