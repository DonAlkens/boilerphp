<?php 

require __DIR__."/../../../Config/views.php";


require __DIR__."/../../../Config/errors.php";



function get_view_path($filename) 
{
    $view_path = "Views";

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

function json($content)
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
    return view("core/errors/unhadledPost.fish.php");
}

function error404()
{
    return view("errors/404");
}

function env($key)
{
    return $_ENV[$key];
}

function loadStatic($filesource) 
{
    if(file_exists("public/".$filesource)) 
    {
        return "/public/".$filesource;
    }
}

function validation($request, $key = "all")
{
    if($key == "all") 
    {
        foreach($request->validationMessages as $field => $message)
        {
            echo "<span class=\"text-danger\">$message</span>\n";
        }
    }
    else
    {
        if(isset($request->$key))
        {
            echo $request->validationMessages[$key];
        }
    }

    echo null;
}

function route($path, $paramters = null)
{
    if($paramters != null) {
        foreach($paramters as $param) {
            $path .= "/".$param;
        }
    }
    return $path;
}