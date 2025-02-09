<?php

namespace Matecat\XliffParser;

use Matecat\XliffParser\Utils\Strings;
use Matecat\XliffParser\XliffParser\XliffParserFactory;
use Matecat\XliffParser\XliffReplacer\XliffReplacerCallbackInterface;
use Matecat\XliffParser\XliffReplacer\XliffReplacerFactory;
use Matecat\XliffParser\XliffUtils\XliffVersionDetector;
use Matecat\XliffParser\XliffUtils\XmlParser;
use Psr\Log\LoggerInterface;

class XliffParser
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * XliffParser constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Replace the translation in a xliff file
     *
     * @param string $originalXliffPath
     * @param array $data
     * @param array $transUnits
     * @param string $targetLang
     * @param string $outputFile
     * @param bool $setSourceInTarget
     * @param XliffReplacerCallbackInterface|null $callback
     */
    public function replaceTranslation($originalXliffPath, &$data, &$transUnits, $targetLang, $outputFile, $setSourceInTarget = false, XliffReplacerCallbackInterface $callback = null)
    {
        try {
            $parser = XliffReplacerFactory::getInstance($originalXliffPath, $data, $transUnits, $targetLang, $outputFile, $setSourceInTarget, $this->logger, $callback);
            $parser->replaceTranslation();
        } catch (\Exception $exception) {
            // do nothing
        }
    }

    /**
     * Parse a xliff file to array
     * In case of some failure an empty array will be returned
     *
     * @param string $xliffContent
     *
     * @return array
     */
    public function xliffToArray($xliffContent)
    {
        try {
            $xliff = [];
            $xliffContent = self::forceUft8Encoding($xliffContent, $xliff);
            $version = XliffVersionDetector::detect($xliffContent);

            if($version === 1){
                $xliffContent = self::removeInternalFileTagFromContent($xliffContent, $xliff);
            }

            $parser = XliffParserFactory::getInstance($version, $this->logger);
            $dom = XmlParser::parse($xliffContent);

            return $parser->parse($dom, $xliff);
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * Pre-Processing.
     * Fixing non UTF-8 encoding (often I get Unicode UTF-16)
     *
     * @param $xliffContent
     * @param $xliff
     *
     * @return false|string
     */
    private static function forceUft8Encoding($xliffContent, &$xliff)
    {
        $enc = mb_detect_encoding($xliffContent);

        if ($enc !== 'UTF-8') {
            $xliff[ 'parser-warnings' ][] = "Input identified as $enc ans converted UTF-8. May not be a problem if the content is English only";

            return iconv($enc, 'UTF-8', $xliffContent);
        }

        return $xliffContent;
    }

    /**
     * Remove <internal-file> heading tag from xliff content
     * This allows to parse xliff files with a very large <internal-file>
     *
     * @param $xliffContent
     * @param $xliff
     *
     * @return mixed|string
     */
    private static function removeInternalFileTagFromContent($xliffContent, &$xliff)
    {
        $index = 1;
        $a = Strings::preg_split( '|<internal-file[\s>]|si', $xliffContent );

        // no match, return original string
        if(count($a) === 1){
            return $a[0];
        }

        $b = Strings::preg_split( '|</internal-file>|si', $a[1] );
        $strippedContent = $a[0].$b[1];
        $xliff['files'][$index][ 'reference' ][] = self::extractBase64($b[0]);
        $index++;

        if(isset($a[2])){
            $c = Strings::preg_split( '|</internal-file[\s>]|si', $a[2] );
            $strippedContent .= $c[1];
            $xliff['files'][$index][ 'reference' ][] = self::extractBase64($c[0]);
        }

        return $strippedContent;
    }

    /**
     * @param $base64
     *
     * @return array
     */
    private static function extractBase64($base64)
    {
        return [
            'form-type' => 'base64',
            'base64' => trim(str_replace('form="base64">', '', $base64)),
        ];
    }
}
