<?php
namespace Core\Model;

class ArrayToTextParser extends AbstractModel
{
    private $_lastContent;

    public function prepare($file)
    {
        $content = file_get_contents($file);
        $search = array(
            'require',
            'require_once',
            'include',
            'include_once',
            '__NAMESPACE__',
            '__DIR__',
            '__FILE__'
        );
        $replace = array(
            '\'$$require\' . ',
            '\'$$require_once\' . ',
            '\'$$include\' . ',
            '\'$$include_once\' . ',
            '\'$$__NAMESPACE__\'',
            '\'$$__DIR__\'',
            '\'$$__FILE__\''
        );

        foreach ($search as &$s) {
            $s = '/(?!\')' . $s . '(?!\')/';
        }

        $content = preg_replace($search, $replace, $content);
        file_put_contents($file, $content);
    }

    public function refuse($file)
    {
        $search = array(
            '\'$$require',
            '\'$$require_once',
            '\'$$include',
            '\'$$include_once',
        );
        $replace = array(
            'require \'',
            'require_once \'',
            'include \'',
            'include_once \'',
        );

        $this->_setTextElements('__NAMESPACE__', $search, $replace);
        $this->_setTextElements('__DIR__', $search, $replace);
        $this->_setTextElements('__FILE__', $search, $replace);

        $content = file_get_contents($file);
        $content = str_replace($search, $replace, $content);
        file_put_contents($file, $content);
    }

    /**
     * @param string $element
     * @param array $search
     * @param array $replace
     */
    private function _setTextElements($element, &$search, &$replace)
    {
        $search[] = '\'$$' . $element;
        $search[] = '$$' . $element . '\'';
        $search[] = '$$' . $element;
        $replace[] = $element . '. \'';
        $replace[] = '\' . ' . $element;
        $replace[] = '\' . ' . $element . ' . \'';
    }

    /**
     * @param array $array
     */
    public function parse($array, $preContent = null)
    {
        $content = '';
        $content .= '<?php' . PHP_EOL;
        if ($preContent !== null) {
            $content .= $preContent . PHP_EOL;
        }
        $content .= 'return ';
        $content .= $this->_parseArray($array, 1);
        $this->_lastContent = $content;
        return $content;
    }

    public function parseAndSave($array, $file)
    {
        $this->parse($array);
        $this->save($file);
    }

    public function save($file)
    {
        file_put_contents($file, $this->_lastContent);
    }

    private function _parseArray($array, $depth)
    {
        if (!is_array($array)) {
            return "'" . $array . '\',' . PHP_EOL;
        }
        $content = 'array(' . PHP_EOL;
        foreach ($array as $key => $val) {
            $content .= $this->_getTabs($depth);
            if (is_array($array) && array_values($array) === $array) {
                $content .= $this->_parseArray($val, $depth + 1);
            } else {
                $content .= "'" . $key . "'" . ' => ' . $this->_parseArray($val, $depth + 1);
            }
        }
        $content .=  $this->_getTabs($depth - 1);
        if ($depth === 1) {
            $content .= ');';
        } else {
            $content .= '),' . PHP_EOL;
        }
        return $content;
    }

    private function _getTabs($count)
    {
        $content = '';
        for($i = 0; $i < $count; $i++) {
            $content .= "\t";
        }
        return $content;
    }
}