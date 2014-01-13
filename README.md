# TicketparkPdfBundle

This Symfony2 bundle ads functionalities to create pdf files with [Docraptor](https://docraptor.com).

Implempents [BytesDocraptorBundle](https://github.com/MartijnDwars/docraptor-bundle), adds caching (don't hit Docraptor API if the same file has been created before) and simplifies the creation api.

## Functionalities
* PdfCreator (Service)
    * Create pdf files from html templates

## Installation

Add TicketparkPdfBundle in your composer.json:

```js
{
    "require": {
        "ticketpark/pdf-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update ticketpark/pdf-bundle
```

Enable the bundles in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ticketpark\FileBundle\TicketparkPdfBundle(),
        new Bytes\Bundle\DocraptorBundle\BytesDocraptorBundle(),
    );
}
```

Update the config:

```yml
// app/config.yml

bytes_docraptor:
    apikey: YOU_DOCRAPTOR_API_KEY
    testmode: false
```

## Usage of PdfCreator
Use the pdf creator service in a controller to create a pdf:

``` php
$file = $this->get('ticketpark.pdf.creator')
    ->setIdentifier('someIdentifier') // the identifier is used for caching purposes
    ->setContent('<h1>My first document</h1>')
    ->create();
    
// Output the file (example, this is not connected to the bundle)
$headers = array(
 'Content-Type' => 'application/pdf',
 'Content-Disposition' => 'attachment; filename="filename.pdf"'
);

return new Symfony\Component\HttpFoundation\Response(file_get_contents($file), 200, $headers);
```

## License

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
