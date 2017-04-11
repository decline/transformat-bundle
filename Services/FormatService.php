<?php

namespace Decline\TransformatBundle\Services;

use Decline\TransformatBundle\Exception\DuplicateKeyException;
use Decline\TransformatBundle\Exception\InvalidSchemaException;
use Decline\TransformatBundle\Exception\NoTransUnitsFoundException;
use Exception;
use SimpleXMLElement;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class FormatService
 * @package Decline\TransformatBundle\Services
 */
class FormatService
{

    /**
     * Bundle configuration
     *
     * @var array
     */
    private $config;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var ValidatorService
     */
    private $validator;

    /**
     * FormatService constructor.
     * @param $config
     * @param \Twig_Environment $twig
     * @param ValidatorService $validator
     */
    public function __construct($config, \Twig_Environment $twig, ValidatorService $validator)
    {
        $this->config = $config;
        $this->twig = $twig;
        $this->validator = $validator;
    }

    /**
     * Formats the configured set of translation files
     *
     * @param SymfonyStyle $io
     * @param string|null $fileName Name of single file to format
     *
     * @return array List of Errors
     */
    public function format(SymfonyStyle $io = null, $fileName = null)
    {
        $files = $this->getPreparedFileset($fileName);

        // check if directory contains files
        if (empty($files)) {
            return [
                sprintf(
                    'No supported files could be found in the configured directory %s!',
                    $this->getDirectory()
                ),
            ];
        }

        $errors = [];
        foreach ($files as $file) {
            try {
                $this->formatSingleFile($file);
                $msg = sprintf('<info>Success:</info> %s', $file->getFilename());
            } catch (Exception $e) {
                $errors[] = $file->getFilename().': '.$e->getMessage();
                $msg = sprintf('<fg=red>Failure:</fg=red> %s', $file->getFilename());
            }

            // write to formatted output
            if ($io) {
                $io->text($msg);
            }
        }

        return $errors;
    }

    /**
     * Create a set of files on which the formatting should be performed
     *
     * @param string|null $fileName
     * @return File[]
     */
    private function getPreparedFileset($fileName = null)
    {
        $filesToCheck = $fileName ? [$fileName] : scandir($this->getDirectory());
        $preparedFileset = [];
        foreach ($filesToCheck as $fileToCheck) {
            if (in_array($fileToCheck, ['.', '..'], true)) {
                continue;
            }

            try {
                $file = new File($this->getDirectory().'/'.$fileToCheck);
            } catch (FileNotFoundException $e) {
                continue;
            }

            // ignore files with unsupported extension
            if ($file->getExtension() !== $this->config['xliff']['extension']) {
                continue;
            }

            $preparedFileset[] = $file;
        }

        return $preparedFileset;
    }

    /**
     * Formats a single translation file and updates its content
     *
     * @param File $file
     * @throws DuplicateKeyException
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Decline\TransformatBundle\Exception\NoTransUnitsFoundException
     * @throws \Symfony\Component\Filesystem\Exception\FileNotFoundException
     * @throws \Decline\TransformatBundle\Exception\InvalidSchemaException
     */
    private function formatSingleFile(File $file)
    {

        if (!$file->isReadable()) {
            throw new FileException('File %s is not readable!', $file->getFilename());
        }

        if (!$file->isWritable()) {
            throw new FileException('File %s is not writable!', $file->getFilename());
        }

        // get content of xliff file
        $xliffContent = file_get_contents($file->getPathname());

        // validate xliff against schema
        $validationErrors = $this->validator->validate($xliffContent);
        if (count($validationErrors)) {
            throw new InvalidSchemaException($validationErrors[0]->message, $file->getFilename());
        }

        $xml = new SimpleXMLElement($xliffContent);
        $xml->registerXPathNamespace('x', $this->getXliffNamespace());
        $transUnits = array();
        foreach ($xml->xpath('//x:file/x:body/x:trans-unit') as $translation) {
            /** @var SimpleXMLElement $translation */
            $source = str_replace('&', '&amp;', (string) $translation->source);
            $target = str_replace('&', '&amp;', (string) $translation->target);

            if (array_key_exists($source, $transUnits)) {
                throw new DuplicateKeyException($source, $file->getFilename());
            }

            $transUnits[$source] = [
                'id' => $source,
                'source' => $source,
                'target' => $target,
            ];
        }

        if (empty($transUnits)) {
            throw new NoTransUnitsFoundException($file->getFilename());
        }

        //IMPORTANT: we need to use case-senstivie sorting, or else it will screw up when merging etc.
        ksort($transUnits);
        //ksort($translations, SORT_STRING | SORT_FLAG_CASE);
        //uksort($translations, "strnatcasecmp");

        // render template
        $twigContext = [
            'namespace' => $this->getXliffNamespace(),
            'sourceLanguage' => $this->getXliffSourceLanguage(),
            'transUnits' => $transUnits,
        ];
        $formattedOutput = $this->twig->render('@DeclineTransformat/format.xml.twig', $twigContext);

        // update file with formatted content
        file_put_contents($file->getPathname(), $formattedOutput);
    }

    /**
     * The configured directory of the translation files
     *
     * @return mixed
     */
    private function getDirectory()
    {
        return $this->config['directory'];
    }

    /**
     * The configured source language of the xliff files
     *
     * @return mixed
     */
    private function getXliffSourceLanguage()
    {
        return $this->config['xliff']['sourceLanguage'];
    }

    /**
     * The configured namespace of the xliff files
     *
     * @return mixed
     */
    private function getXliffNamespace()
    {
        return $this->config['xliff']['namespace'];
    }
}
