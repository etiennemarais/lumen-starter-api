<?php
namespace App\Http\Controllers;

use Parsedown;

class ApiController extends Controller
{
    /**
     * @param Parsedown $parsedown
     * @return \Laravel\Lumen\Http\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function welcome(Parsedown $parsedown)
    {
        $docs = "<!DOCTYPE html><html>
<head><link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css\"></head>
<body class=\"container\">";
        $docs .= $parsedown->parse(file_get_contents(base_path() . '/apiary.apib'));
        $docs .= "</body></html>";
        return response($docs);
    }
}
