<?php

namespace Engine\Html;

class Html {
    
    /**
     * @param string $text
     * @param string $url
     * @return string
     */
    public static function link($text, $url = '#') {
        $htmlOptions['href'] = $url;
        return self::tag('a', $htmlOptions, $text);
    }
    
    /**
     * @param string $tag
     * @param array $htmlOptions
     * @param string $content
     * @return string
     */
    public static function tag($tag, $htmlOptions = [], $content = null) {
        return '<' . $tag . self::renderAttributes($htmlOptions) . '>' . $content . '</' . $tag . '>';
    }
    
    /**
     * @param array $htmlOptions
     * @return string
     */
    public static function renderAttributes($htmlOptions) {
        $html = '';

        foreach ($htmlOptions as $name => $value) {
            $html .= ' ' . $name . '="' . self::encode($value) . '"';
        }
        
        return $html;
    }
    
    /**
     * @param string $text
     * @return string
     */
    public static function encode($text) {
        return htmlspecialchars($text, ENT_QUOTES);
    }

}
