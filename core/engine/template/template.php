<?php

class TemplateEngine {

    public static $content = [];

    public function __construct($ext){
        $this->ext = $ext;
    }

    public function render($fileContent, $content=null) {

        self::$content = $content;

        $fcontent = TemplateEngine::editFile($fileContent, $content);
        $fcontent = eval("?>".$fcontent);
        return $fcontent;
    }


    public function basic($fcontent) {
        
        
        $fcontent = preg_replace("/@\{\{(.*)\}\}(.*)\@\{/", '<?php echo $1; ?>$2@{',$fcontent);
        $fcontent = preg_replace("/@\{\{(.*)\"(.*)\"(.*)\}\}(.*)\@\{/", '<?php echo $2; ?>$4@{',$fcontent);
        $fcontent = preg_replace("/@\{\{\"(.*)\"\}\}/", '<?php echo "$1"; ?>',$fcontent);
        $fcontent = preg_replace("/@\{\{(.*)\}\}/", '<?php echo $1; ?>',$fcontent);
        $fcontent = preg_replace("/@\{\{(.*)\"(.*)\"(.*)\}\}/", '<?php echo $2; ?>',$fcontent);
        $fcontent = preg_replace("/\{\{(.*)\}\}/", '<?php $1; ?>',$fcontent);
        
        return $fcontent;
    }


    public function ConditionalStatement($fcontent){
        
        $fcontent = preg_replace("/\@if\((.*)\)/", '<?php if($1){ ?>',$fcontent);
        $fcontent = preg_replace("/\@else/", '<?php } else { ?>',$fcontent);
        $fcontent = preg_replace("/\@endif/", '<?php } ?>',$fcontent);

        return $fcontent;
    }

    public function ToLoopContents($fcontent){
        
        $fcontent = preg_replace("/\@foreach\((.*)\)/", '<?php foreach($1){ ?>',$fcontent);
        $fcontent = preg_replace("/\@endforeach/", '<?php } ?>',$fcontent);

        $fcontent = preg_replace("/\@for\((.*)\)/", '<?php for($1){ ?>',$fcontent);
        $fcontent = preg_replace("/\@endfor/", '<?php } ?>',$fcontent);

        return $fcontent;
    }


    public function extendLayout($fcontent, $viewPath, $ext) {

        if(preg_match("/@{extends/", $fcontent)) {
            $layout = substr($fcontent, strpos($fcontent,"@{extends "), strpos($fcontent, "}"));
            $layout = str_replace("@{extends \"","",$layout);
            $cleaned = str_replace("\"","",trim($layout," "));
            $layoutPath = $viewPath."/".$cleaned."."."$ext";

            $layout = file_get_contents($layoutPath);
            $fcontent = preg_replace("/@\{extends (.*)\}/","",$fcontent);
            $layout = preg_replace("/@\{\{(.*)content(.*)\}\}/", $fcontent, $layout);
            return $layout;
        }
        return $fcontent;
    }

  
    public function editFile($fileContent, $content=null){
        
        $fcontent = $fileContent;
        $fcontent = preg_replace("/@\{\{(.*)load (.*)\}\}/",'<?php echo TemplateEngine::loadFile("views/".($2).".".$this->ext, $content); ?>',$fcontent);
        
        $fcontent = TemplateEngine::sessions($fcontent);
        $fcontent = TemplateEngine::keys($fcontent, $content);
        $fcontent = TemplateEngine::ConditionalStatement($fcontent);
        $fcontent = TemplateEngine::ToLoopContents($fcontent);        
        $fcontent = TemplateEngine::basic($fcontent);
        $fcontent = TemplateEngine::emptyParameter($fcontent);  

        return $fcontent;
    }


    public function loadFile($filename, $content){
        $fcontent = file_get_contents($filename);

        $fcontent = preg_replace("/@\{\{(.*)load (.*)\}\}/",'<?php echo TemplateEngine::loadFile("views/".($2).".".$this->ext, $content); ?>',$fcontent);
        
        $fcontent = TemplateEngine::sessions($fcontent);
        $fcontent = TemplateEngine::keys($fcontent, $content);
        $fcontent = TemplateEngine::ConditionalStatement($fcontent);
        $fcontent = TemplateEngine::ToLoopContents($fcontent);        
        $fcontent = TemplateEngine::basic($fcontent);
        $fcontent = TemplateEngine::emptyParameter($fcontent);

        $fcontent = eval("?>". $fcontent ."");
        return $fcontent;
    }

    static function emptyParameter($fcontent){
        $fcontent = preg_replace("/(.*)if\((.*)\~(.*)\~(.*)\)/", '$1 if(false) ' ,$fcontent);
        $fcontent = preg_replace("/(.*)for\((.*)\~(.*)\~(.*)\)/", '$1 function() ' ,$fcontent);
        $fcontent = preg_replace("/(.*)foreach\((.*)\~(.*)\~(.*)\)/", '$1 function() ' ,$fcontent);
        $fcontent = preg_replace("/(.*)\~(.*)\~(.*)/", "$1"."false"."$3",$fcontent);
        return $fcontent;
    }

    static function keys($fcontent, $content=null) {
        if(!is_null($content)){
            foreach ($content as $key => $value) {
                if(!is_array($value)){
                    $fcontent = preg_replace("/(.*)\~".$key."\~(.*)/", '$1$content["'.$key.'"]$2' ,$fcontent);
                } 
                
                if(is_array($value)) {
                    $fcontent = preg_replace("/\~".$key."\~/", '$content["'.$key.'"]',$fcontent);
                } 
            }
        }

        return $fcontent;
    }

    static function sessions($fcontent){        
        foreach($_SESSION as $key => $value) {
            if(!is_array($value)){
                $fcontent = preg_replace("/(.*)\~".$key."\~(.*)/", '$1$_SESSION["'.$key.'"]$2' ,$fcontent);
            } 
            
            if(is_array($value)) {
                $fcontent = preg_replace("/\~".$key."\~/", '$_SESSION["'.$key.'"]',$fcontent);
            } 
        } 
        return $fcontent;
    }

}