<?php

namespace Ticketpark\PdfBundle\PdfCreator;

interface PdfCreatorInterface
{
    /**
     * Set file contents
     *
     * @param string $content
     * @return PdfCreator
     */
    public function setContent($content);

    /**
     * Set identifier
     *
     * This is used to identify the file, e.q. for caching
     *
     * @param string $identifier
     * @return PdfCreator
     */
    public function setIdentifier($identifier);

    /**
     * Create pdf
     *
     * @return string Path to created pdf file
     */
    public function create();
}