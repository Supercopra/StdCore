<?php
namespace Core\Model;

use Core\Model\AbstractModel;

class TmpToPhpParser extends AbstractModel
{
    /**
     * @param string $file
     * @param array $vars
     */
    public function parse($file, $vars)
    {
        $content = file_get_contents($file);
        $search  = $replace = array();
        foreach ($vars as $s => $r) {
            $search[]  = '{{' . $s . '}}';
            $replace[] = $r;
        }
        $content = str_replace($search, $replace, $content);
        $content = '<?php' . PHP_EOL . $content;
        return $content;
    }
}