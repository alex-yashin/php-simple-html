<?php

namespace SimpleHtml;

class Html extends BaseHtml
{

    public static function br()
    {
        return static::tag('br');
    }

    public static function li($content = '', $options = [])
    {
        return static::tag('li', $content, $options);
    }

    public static function p($content = '', $options = [])
    {
        return static::tag('p', $content, $options);
    }

    public static function nest($path, $content = '', $rootOptions = [])
    {
        $pathParts = explode('/', $path);
        while ($p = array_pop($pathParts)) {
            $siblings = explode('+', $p);
            $siblingContent = '';
            while ($s = array_shift($siblings)) {
                $options = [];
                $left = strlen($s);
                if (preg_match_all('/(([#.])([\w\-_ =]+))|(\[([#\w\-_ =]+)\])/si', $s, $matches)) {
                    foreach ($matches[0] as $k => $full) {
                        $left = min($left, strpos($s, $full));
                        $prefix = !empty($matches[2][$k]) ? $matches[2][$k] : '[';
                        $value = $matches[3][$k];
                        $prop = '';
                        switch ($prefix) {
                            case '#':
                                $prop = 'id';
                                break;
                            case '.':
                                $prop = 'class';
                                break;
                            case '[':
                                $parts = explode('=', $matches[5][$k]);
                                $prop = $parts[0];
                                $value = $parts[1] ?? $prop;
                                break;
                        }
                        if ($prop) {
                            $options[$prop] = (isset($options[$prop]) ? $options[$prop] . ' ' : '') . $value;
                        }
                    }
                    $s = substr($s, 0, $left);
                    if (empty($s)) {
                        $s = 'div';
                    }
                }

                if (empty($pathParts) && empty($siblings)) {
                    if (!empty($options['class']) && !empty($rootOptions['class'])) {
                        $rootOptions['class'] = $options['class'] . ' ' . $rootOptions['class'];
                    }
                    $options = array_merge($options, $rootOptions);
                }

                if (empty($siblings)) {
                    $content = $siblingContent . ($s == '%' ? $content : Html::tag($s, $content, $options));
                } else {
                    $siblingContent .= Html::tag($s, '', $options);
                }
            }
        }
        return $content;
    }

    /**
     * @param string $template
     * @param $item
     * @return string
     * @throws \Exception
     */
    public static function zz($template, ...$items)
    {
        $zz = new ZZ($template);
        return $zz->run($items);
    }

}