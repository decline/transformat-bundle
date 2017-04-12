# transformat-bundle

[![Build Status](https://travis-ci.org/decline/transformat-bundle.svg?branch=master)](https://travis-ci.org/decline/transformat-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/decline/transformat-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/decline/transformat-bundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/decline/transformat-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/decline/transformat-bundle/?branch=master)

Symfony Bundle to format and sort translation files

Have you ever been fed up about keeping the content of your Symfony translation files organized, well structured and sorted? Yeah, me too. That's why I developed this little bundle to make things easier.

What it does:
* structures your translation files by a given template (which you can override of course)
* alphabetically sorts your entries by the *source* key
* checks for duplicate trans-unit id's in your xlf files
* optionally validates your xlf files against a xsd schema

Limitations:
* only works for xlf/xliff files at the moment

## Get the bundle using composer

To install the bundle, require it using composer by running the following command at the root of your application:

```bash
composer require decline/transformat-bundle
```

## Enable the bundle
Register the bundle in your application's kernel class:

```php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Decline\TransformatBundle\DeclineTransformatBundle(),
            // ...
        ];
    }
}
```

## Configure the bundle
This could be a possible configuration for the bundle:

```yaml
decline_transformat:
    directory: "%kernel.root_dir%/Resources/translations"   # The directory where the translation files are located.
    xliff:
        extension: xlf                                      # The extension of the translation files which should be formatted. Default value is 'xlf', can be one of ['xlf', 'xliff']
        sourceLanguage: en                                  # The source language of the translation files. Default value is 'en'
        namespace: urn:oasis:names:tc:xliff:document:1.2    # The xml namespace for translation files. Default value is 'urn:oasis:names:tc:xliff:document:1.2'
        validation: false                                   # If set, your xlf files will be validated against either a transitional or strict xsd schema. Default value is 'false', can be one of [false, 'transitional', 'strict']
```

## Usage
Format all files in the directory which was configured in the settings:

```bash
bin/console transformat:format
```

Format a single file in the directory which was configured in the settings:

```bash
bin/console transformat:format foobar.en.xlf
```

## Override template
If you wish to override the twig template which is used for generating the formatted xlf-files, no problem! Just copy the twig template from

```bash
vendor/decline/transformat-bundle/Resources/views/format.xml.twig
```

to this location (for example) in your application

```bash
app/Resources/DeclineTransformatBundle/views/format.xml.twig
```

and make your desired adjustments.
