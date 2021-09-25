<?php 

use App\Config\ViewsConfig;


function get_view_path($filename) 
{

    $view_path = ViewsConfig::$views_path;

    $extension = "fish.html";
    $full_path = $view_path."/".$filename.".".$extension;

    return [ 
        "fullpath" => $full_path, 
        "viewpath" => $view_path,
        "extension" => $extension
    ];
}

function view($view_file, $content=null) 
{

    $path = get_view_path($view_file);

    $full_path = $path["fullpath"]; 
    $view_path = $path["viewpath"];
    $extension = $path["extension"];

    if(file_exists($full_path)) 
    {
        $fcontent = file_get_contents($full_path);

        $template = new TemplateEngine($extension);
        $fcontent = $template->extendLayout($fcontent, $view_path, $extension);
        $fcontent = $template->render($fcontent, $content);      
        return $template;
    } 
    else 
    {
        throw new Error($view_file." does not exists");
    }

}

function content($text)
{
    echo $text;
}

function Json($content)
{
    echo json_encode($content);
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
    return view("core/errors/unhadledPost");
}

function error404()
{
    header("HTTP/1.1 404 Not Found");
    return view("errors/404");
}


function mailPage($view_file, $data = null) 
{

    $path = get_view_path($view_file);

    $full_path = $path["fullpath"]; 
    $view_path = $path["viewpath"];
    $extension = $path["extension"];

    if(file_exists($full_path)) 
    {
        $fcontent = file_get_contents($full_path);

        $template = new TemplateEngine($extension);
        $fcontent = $template->extendLayout($fcontent, $view_path, $extension);
        $fcontent = $template->content($fcontent, $data);      
        return $fcontent;
    } 
    else 
    {
        throw new Error($view_file." does not exists");
    }

}
