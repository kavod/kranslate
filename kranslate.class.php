<?php

define('KRANSLATE_ROOT_DIR','..');
mb_detect_order("ISO-8859-1,UTF-8");

class kranslate
{
  protected $_plugin;
  protected $_dir;

  public function __construct($plugin,$dir=null)
  {
    $this->_plugin = $plugin;
    if (is_null($dir))
    {
      $this->_dir = KRANSLATE_ROOT_DIR.DIRECTORY_SEPARATOR.$plugin;
    } else {
      $this->_dir = $dir.DIRECTORY_SEPARATOR;
    }
  }

  public function globalSearch()
  {
    $files = $this->scanfiles();
    $result = array();
    foreach($files as $file)
    {
      $sentences = $this->getSentences($file);
      if (!is_null($sentences))
        $result[str_replace($this->_dir,'plugins'.DIRECTORY_SEPARATOR.$this->_plugin,$file)] = $sentences;
    }
    return $result;
  }

  public function scanfiles($dir=null)
  {
    $dir = (is_null($dir)) ? $this->_dir : $dir;
    $files = scandir($dir);
    // print_r($files);
    $result = array();
    foreach($files as $file)
    {
      if (substr($file,0,1)=='.')
        continue;
      $file = $dir.DIRECTORY_SEPARATOR.$file;
      if (is_dir($file))
      {
        $result = array_merge($result,$this->scanfiles($file));
      }
      if (substr($file,-4)=='.php' || substr($file,-3)=='.js')
      {
        $result[] = $file;
      }
    }
    return $result;
  }

  public function getSentences($file_path)
  {
    $content = file_get_contents($this->_dir . DIRECTORY_SEPARATOR . $file_path);
    // print_r($content);
    if (preg_match_all('/\{\{([^\}]+)\}\}/',$content,$matches)!=0)
    {
      $result = array();
      foreach($matches[1] as $match)
      {
        $match = utf8_encode(str_replace('\\','',$match));
        $result[$match] = $match;
      }
      return $result;
    }
    return null;
  }

  public function generateLangFile($lang)
  {
    $result = $this->globalSearch();
    $json = json_encode($result,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
    // echo mb_detect_encoding($json);
    // $json = mb_convert_encoding($json,'ISO-8859-1');
    $content = utf8_decode($json);
    // //$content = mb_convert_encoding($json,'ISO-8859-1',mb_detect_encoding($json,"UTF-8, ISO-8859-1, ISO-8859-15",true));
    // echo mb_detect_encoding($content);
    file_put_contents($lang.'.json',$content);
  }
}

$k = new kranslate('kring');
//$matches = $k->getSentences('desktop/php/kring.php');

//echo utf8_decode(json_encode($result,JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE));
$k->generateLangFile('fr_FR');
?>
