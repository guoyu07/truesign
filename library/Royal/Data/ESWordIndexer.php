<?php

namespace Royal\Data;

class ESWordIndexer {
    public static function edgeNgramTokenizer($minGram = 1, $maxGram = 20, $tokenChars = array('digit', 'letter')) {
        $tokenizer = self::nGramTokenizer($minGram, $maxGram, $tokenChars);
        $tokenizer['type'] = 'edgeNGram';
        return $tokenizer;
    }

    public static function nGramTokenizer($minGram = 1, $maxGram = 20) {
        $tokenizer = array('type' => 'nGram');
        if ($minGram) {
            $tokenizer['min_gram'] = $minGram;
        }
        if ($maxGram) {
            $tokenizer['max_gram'] = $maxGram;
        }
        if ($tokenChars) {
            $tokenizer['token_chars'] = (is_array($tokenChars)) ? $tokenChars : array($tokenChars);
        }
        return $tokenizer;
    }

    public static function text($type = 'string') {
        return array(
            'indexAnalyzer' => 'mmseg',
            'searchAnalyzer' => 'mmseg',
            'type' => $type,
        );
    }

    public static function name($type = 'string') {
        return self::nGramMatch();
    }

    public static function pinyin($type = 'string') {
        $tokenizerName = 'e_pinyin_tokenizer';
        $analyzerName = 'e_pinyin_analyzer';
        $filterName = 'e_pinyin_ngram';
        return array(
            'name' => $analyzerName,
            'type' => $type,
            'indexAnalyzer' => $analyzerName,
            //'searchAnalyzer' => $analyzerName,
            'analyzer' => array(
                $analyzerName => array(
                    'tokenizer' => $tokenizerName,
                    'filter' => array($filterName, 'unique'),
                ),
            ),
            'tokenizer' => array(
                $tokenizerName => array(
                    'type' => 'PinyinSuggest',
                ),
            ),
            'filter' => array(
                $filterName => array(
                    'type' => 'edgeNGram',
                    'min_gram' => 1,
                    'max_gram' => 12,
                ),
            ),
        );
    }

    public static function letter($type = 'string') {
        $tokenizerName = 'e_letter_tokenizer';
        $analyzerName = 'e_letter_analyzer';
        return array(
            'name' => $analyzerName,
            'type' => $type,
            'indexAnalyzer' => $analyzerName,
            'searchAnalyzer' => $analyzerName,
            'analyzer' => array(
                $analyzerName => array(
                    'tokenizer' => $tokenizerName,
                    'filter' => array('unique'),
                ),
            ),
            'tokenizer' => array(
                $tokenizerName => array(
                    'type' => 'letter',
                ),
            ),
        );
    }


    public static function whitespace($type = 'string') {
        $tokenizerName = 'e_whitespace_tokenizer';
        $analyzerName = 'e_whitespace_analyzer';
        return array(
            'name' => $analyzerName,
            'type' => $type,
            'indexAnalyzer' => $analyzerName,
            'searchAnalyzer' => $analyzerName,
            'analyzer' => array(
                $analyzerName => array(
                    'tokenizer' => $tokenizerName,
                    'filter' => array('unique'),
                ),
            ),
            'tokenizer' => array(
                $tokenizerName => array(
                    'type' => 'whitespace',
                ),
            ),
        );
    }

    public static function nGramMatch($minGram = 1, $maxGram = 12) {
        $tokenizerName = "ngram_match_${minGram}_${maxGram}";
        $analyzerName = strtolower(__FUNCTION__) . "_${minGram}_${maxGram}";
        return array(
            'name' => $analyzerName,
            'type' => 'string',
            'indexAnalyzer' => $analyzerName,
            'analyzer' => array(
                $analyzerName => array(
                    'tokenizer' => $tokenizerName,
                    'filter' => array('unique'),
                ),
            ),
            'tokenizer' => array(
                $tokenizerName => self::nGramTokenizer($minGram, $maxGram),
            ),
        );
    }

    public static function suffixMatch($minGram = 1, $maxGram = 20) {
        $filterName = "suffix_match_${minGram}_${maxGram}";
        $analyzerName = strtolower(__FUNCTION__) . "_${minGram}_${maxGram}";
        return array(
            'name' => $analyzerName,
            'type' => 'string',
            'analyzer' => array(
                $analyzerName => array(
                    'tokenizer' => 'keyword',
                    'filter' => array('word_delimiter', 'reverse', $filterName, 'reverse', 'unique'),
                ),
            ),
            'filter' => array(
                $filterName => self::edgeNgramTokenizer($minGram, $maxGram),
            ),
        );
    }

    public static function suffixPhone() {
        return self::suffixMatch(2, 12);
    }

    public static function phone($type = 'string') {
        return self::nGramMatch(2, 11);
    }

    public static function normal($type = 'string') {
        return array(
            'index' => 'not_analyzed',
            'type' => $type,
        );
    }

    public static function contractCode($type = 'string') {
        // nGram, min_gram = 2, max_gram = 20
    }

    public static function number($type = 'string') {
        return array(
            'index' => 'not_analyzed',
            'type' => $type,
        );
    }

    // less than 12 words
    public static function chineseShort($type = 'string') {
        // nGram, min_gram = 1, max_gram = 12
    }
}
