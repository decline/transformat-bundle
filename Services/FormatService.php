<?php
/**
 * Created by IntelliJ IDEA.
 * User: dom
 * Date: 21.01.17
 * Time: 17:27
 */

namespace Decline\TransformatBundle\Services;


use Exception;
use SimpleXMLElement;
use Symfony\Component\Console\Style\SymfonyStyle;

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
     * FormatService constructor.
     * @param $config
     * @param \Twig_Environment $twig
     */
    public function __construct($config, \Twig_Environment $twig)
    {
        $this->config = $config;
        $this->twig = $twig;
    }

    /**
     * Formats the configured set of translation files
     *
     * @param SymfonyStyle $io
     * @return array
     */
    public function format(SymfonyStyle $io)
    {

        // read files from configured directory
        $directory = $this->config['directory'];
        $files = scandir($directory);
        $errors = [];

        foreach ($files as $file) {

            try {
                $filePath = $directory . '/' . $file;

                // ignore files with unsupported extension
                if (pathinfo($filePath, PATHINFO_EXTENSION) !== $this->config['xliff']['extension']) {
                    continue;
                }

                $translations = array();
                $xml = new SimpleXMLElement(file_get_contents($filePath));
                foreach ($xml->file->body->{'trans-unit'} as $translation) {
                    /** @var SimpleXMLElement $translation */
                    $source = $translation->source->__toString();
                    $target = $translation->target->__toString();

                    if (array_key_exists($source, $translations)) {
                        throw new Exception('Duplicate translation key found: '.$source);
                    }

                    $translations[$source] = $target;
                }

                //IMPORTANT: we need to use case-senstivie sorting, or else it will screw up when merging etc.
                ksort($translations);
                //ksort($translations, SORT_STRING | SORT_FLAG_CASE);
                //uksort($translations, "strnatcasecmp");

                // collect entries of trans-units
                $transUnits = [];
                foreach ($translations as $transKey => $transValue) {
                    $transUnits[] = [
                        'id' => $transKey,
                        'source' => $transKey,
                        'target' => $transValue,
                    ];
                }

                // render template
                $twigContext = [
                    'sourceLanguage' => $this->config['xliff']['sourceLanguage'],
                    'transUnits' => $transUnits,
                ];
                $formattedOutput = $this->twig->render('@DeclineTransformat/format.xml.twig', $twigContext);

                // update file with formatted content
                file_put_contents($filePath, $formattedOutput);

                $io->text('<info>Success:</info> '.$file);
            } catch (Exception $e) {
                $io->text('<fg=red>Failure:</fg=red> '.$file);
                $errors[] = $file.': '.$e->getMessage();
            }
        }

        return $errors;
    }
}