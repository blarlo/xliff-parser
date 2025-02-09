<?php

namespace Matecat\XliffParser\Tests;

use Matecat\XliffParser\Constants\TranslationStatus;
use Matecat\XliffParser\XliffParser;
use Matecat\XliffParser\XliffReplacer\XliffReplacerCallbackInterface;

class XliffReplacerTest extends BaseTest
{
    /**
     * @test
     */
    public function can_replace_a_xliff_10_without_target_lang()
    {
        $data = [
                [
                        'sid' => 1,
                        'segment' => 'Image showing Italian Patreon creators',
                        'internal_id' => 'pendo-image-e3aaf7b7|alt',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => 'Bla bla bla',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 1,
                        'raw_word_count' => 1,
                ]
        ];

        $transUnits = [];

        foreach ($data as $i => $k) {
            //create a secondary indexing mechanism on segments' array; this will be useful
            //prepend a string so non-trans unit id ( ex: numerical ) are not overwritten
            $internalId = $k[ 'internal_id' ];

            $transUnits[ $internalId ] [] = $i;

            $data[ 'matecat|' . $internalId ] [] = $i;
        }

        $inputFile = __DIR__.'/../tests/files/no-target.xliff';
        $outputFile = __DIR__.'/../tests/files/output/no-target.xliff';

        $xliffParser = new XliffParser();
        $xliffParser->replaceTranslation($inputFile, $data, $transUnits, 'it-it', $outputFile);
        $output = $xliffParser->xliffToArray(file_get_contents($outputFile));

        $this->assertEquals($output['files'][1]['attr']['target-language'], 'it-it');
    }

    /**
     * @test
     */
    public function can_replace_a_xliff_10()
    {
        $data = [
            [
                'sid' => 1,
                'segment' => '<g id="1">&#128076;&#127995;</g>',
                'internal_id' => 'NFDBB2FA9-tu519',
                'mrk_id' => '',
                'prev_tags' => '',
                'succ_tags' => '',
                'mrk_prev_tags' => '',
                'mrk_succ_tags' => '',
                'translation' => '<g id="1">&#128076;&#127995;</g>',
                'status' => TranslationStatus::STATUS_TRANSLATED,
                'eq_word_count' => 1,
                'raw_word_count' => 1,
            ]
        ];

        $transUnits = [];

        foreach ($data as $i => $k) {
            //create a secondary indexing mechanism on segments' array; this will be useful
            //prepend a string so non-trans unit id ( ex: numerical ) are not overwritten
            $internalId = $k[ 'internal_id' ];

            $transUnits[ $internalId ] [] = $i;

            $data[ 'matecat|' . $internalId ] [] = $i;
        }

        $inputFile = __DIR__.'/../tests/files/file-with-emoji.xliff';
        $outputFile = __DIR__.'/../tests/files/output/file-with-emoji.xliff';

        $xliffParser = new XliffParser();
        $xliffParser->replaceTranslation($inputFile, $data, $transUnits, 'fr-fr', $outputFile);
        $output = $xliffParser->xliffToArray(file_get_contents($outputFile));
        $expected = '<g id="1">&#128076;&#127995;</g>';

        $this->assertEquals($expected, $output['files'][3]['trans-units'][1]['target']['raw-content']);
    }

    /**
     * @test
     */
    public function can_replace_a_xliff_20_with_no_errors()
    {
        $data = $this->getData();
        $inputFile = __DIR__.'/../tests/files/sample-20.xlf';
        $outputFile = __DIR__.'/../tests/files/output/sample-20.xlf';

        (new XliffParser())->replaceTranslation($inputFile, $data['data'], $data['transUnits'], 'fr-fr', $outputFile, false, new DummyXliffReplacerCallback());
        $output = (new XliffParser())->xliffToArray(file_get_contents($outputFile));
        $expected = '&lt;pc id="1"&gt;Buongiorno al <mrk id="m2" type="term">Mondo</mrk> !&lt;/pc&gt;';

        $this->assertEquals($expected, $output['files'][1]['trans-units'][1]['target']['raw-content'][0]);
    }

    /**
     * @test
     */
    public function can_replace_a_xliff_20_with_consistency_errors()
    {
        $data = $this->getData();
        $inputFile = __DIR__.'/../tests/files/sample-20.xlf';
        $outputFile = __DIR__.'/../tests/files/output/sample-20.xlf';

        (new XliffParser())->replaceTranslation($inputFile, $data['data'], $data['transUnits'], 'fr-fr', $outputFile, false, new DummyXliffReplacerCallbackWhichReturnTrue());
        $output = (new XliffParser())->xliffToArray(file_get_contents($outputFile));
        $expected = '|||UNTRANSLATED_CONTENT_START|||&lt;pc id="1"&gt;Hello <mrk id="m2" type="term">World</mrk> !&lt;/pc&gt;|||UNTRANSLATED_CONTENT_END|||';

        $this->assertEquals($expected, $output['files'][1]['trans-units'][1]['target']['raw-content'][0]);
    }

    /**
     * In this case the replacer must do not replace original target
     *
     * @test
     */
    public function can_replace_a_xliff_12_with__translate_no()
    {
        $data = [
                [
                    'sid' => 1,
                    'segment' => 'Tools:Review',
                    'internal_id' => '1',
                    'mrk_id' => '',
                    'prev_tags' => '',
                    'succ_tags' => '',
                    'mrk_prev_tags' => '',
                    'mrk_succ_tags' => '',
                    'translation' => 'Tools:Recensione',
                    'status' => TranslationStatus::STATUS_TRANSLATED,
                    'eq_word_count' => 1,
                    'raw_word_count' => 1,
                ]
        ];

        $transUnits = [];

        foreach ($data as $i => $k) {
            //create a secondary indexing mechanism on segments' array; this will be useful
            //prepend a string so non-trans unit id ( ex: numerical ) are not overwritten
            $internalId = $k[ 'internal_id' ];

            $transUnits[ $internalId ] [] = $i;

            $data[ 'matecat|' . $internalId ] [] = $i;
        }

        $inputFile = __DIR__.'/../tests/files/Working_with_the_Review_tool_single_tu.xlf';
        $outputFile = __DIR__.'/../tests/files/output/Working_with_the_Review_tool_single_tu.xlf';

        (new XliffParser())->replaceTranslation($inputFile, $data, $transUnits, 'it-it', $outputFile, false);
        $output = (new XliffParser())->xliffToArray(file_get_contents($outputFile));
        $expected = '<mrk mtype="seg" mid="1" MadCap:segmentStatus="Untranslated" MadCap:matchPercent="0"/>';

        $this->assertEquals($expected, $output['files'][1]['trans-units'][1]['target']['raw-content']);
    }

    /**
     * @return array
     */
    private function getData()
    {
        $data = [
            [
                'sid' => 1,
                'segment' => '<pc id="1">Hello <mrk id="m2" type="term">World</mrk> !</pc>',
                'internal_id' => 'u1',
                'mrk_id' => '',
                'prev_tags' => '',
                'succ_tags' => '',
                'mrk_prev_tags' => '',
                'mrk_succ_tags' => '',
                'translation' => '<pc id="1">Buongiorno al <mrk id="m2" type="term">Mondo</mrk> !</pc>',
                'status' => TranslationStatus::STATUS_TRANSLATED,
                'eq_word_count' => 100,
                'raw_word_count' => 200,
            ],
            [
                'sid' => 2,
                'segment' => '<pc id="1">Hello <mrk id="m2" type="term">World2</mrk> !</pc>',
                'internal_id' => 'u2',
                'mrk_id' => '',
                'prev_tags' => '',
                'succ_tags' => '',
                'mrk_prev_tags' => '',
                'mrk_succ_tags' => '',
                'translation' => '<pc id="2">Buongiorno al <mrk id="m2" type="term">Mondo2</mrk> !</pc>',
                'status' => TranslationStatus::STATUS_TRANSLATED,
                'eq_word_count' => 200,
                'raw_word_count' => 300,
            ],
        ];

        $transUnits = [];

        foreach ($data as $i => $k) {
            //create a secondary indexing mechanism on segments' array; this will be useful
            //prepend a string so non-trans unit id ( ex: numerical ) are not overwritten
            $internalId = $k[ 'internal_id' ];

            $transUnits[ $internalId ] [] = $i;

            $data[ 'matecat|' . $internalId ] [] = $i;
        }

        return [
            'data' => $data,
            'transUnits' => $transUnits,
        ];
    }
}

class RealXliffReplacerCallback implements XliffReplacerCallbackInterface
{
    /**
     * @inheritDoc
     */
    public function thereAreErrors($segment, $translation, array $dataRefMap = [])
    {
        return false;
    }
}

class DummyXliffReplacerCallback implements XliffReplacerCallbackInterface
{
    /**
     * @inheritDoc
     */
    public function thereAreErrors($segment, $translation, array $dataRefMap = [])
    {
        return false;
    }
}

class DummyXliffReplacerCallbackWhichReturnTrue implements XliffReplacerCallbackInterface
{
    /**
     * @inheritDoc
     */
    public function thereAreErrors($segment, $translation, array $dataRefMap = [])
    {
        return true;
    }
}
