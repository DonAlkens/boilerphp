<?php 


function get_view_path($filename) 
{
    
    $path = "./Views/";
    $ext = "fish.html";
    $fullpath = $path.$filename.".".$ext;

    return [ "fullpath" => $fullpath, "viewpath" => "Views", "extension" => $ext];
}

function view($viewfile, $content=null) 
{

    $path = get_view_path($viewfile);
    $fullpath = $path["fullpath"]; 
    $viewPath = $path["viewpath"];
    $ext = $path["extension"];

    if(file_exists($fullpath)) {
        
        $fcontent = file_get_contents($fullpath);

        $template = new TemplateEngine($ext);
        $fcontent = $template->extendLayout($fcontent, $viewPath, $ext);
        $fcontent = $template->render($fcontent, $content);      
        return $fcontent;
    } 
    else {
        throw new Error($viewfile." does not exists");
    }

}

function mail_view($filename, $data) 
{

    if(file_exists($filename)) {
        $fcontent = file_get_contents($filename);

        $template = new TemplateEngine;
        $fcontent = $template->editFile($fcontent, $data);      
        return $fcontent;
    } 
    else {
        throw new Error($filename." does not exists");
    }
}

function content($text)
{
    return $text;
}

function json($content)
{
    return json_encode($content);
}

function redirect($location) 
{
    $location = trim($location,"/");
    $location = "/".$location;
    return header("location:".$location);
}

function redirectToHost($location) 
{
    return header("location:".$location);
}

function unhandledPost() 
{
    return file_get_contents("core/errors/unhadledPost.fish.php");
}

function Error404()
{
    return file_get_contents("views/errors/404.fish.html");
}

function env($key)
{
    return $_ENV[$key];
}

function loadStatic($filesource) {
    $fullpath = "public/".$filesource;
    if(file_exists($fullpath)) {
        return "/public/".$fullpath;
    }

    
}