<?php

namespace Woodlands;

class Routes implements \CSY2088\Routes{

    public function getPage($route){
        require '../functions/database.php';

        //define tables here
        //$adminTable = new \CSY2088\DatabaseTable($pdo, 'adminlogin', 'AdminId');
        //$uniImageGalleryTable = new \CSY2088\DatabaseTable($pdo, 'uniimage', "imageId");
    
        //define controller here
        $controllers = [];
        // $controllers['public'] = new \Woodlands\Controllers\People($uniImageGalleryTable, $courseImageGalleryTable, $studentFacilities, $studentExperience);
        // $controllers['admin'] = new \Woodlands\Controllers\Admin($adminTable);



        try{
            if ($route == '') {
                // $page = $controllers['public']->home(); A.K.A route to home page
            } else {
    
                $pattern = '/^\w+\/\w+$/';  //should be a better way to do it. research
    
                if (!preg_match($pattern, $route)) {
                    // $page = $controllers['public']->$route(); A.K.A route to home page
                } else {
                    list($controllerName, $functionName) = explode('/', $route);
    
                    if (!isset($controllers[$controllerName])) {
                        $page = "error";
                    } else {
                        $controller = $controllers[$controllerName];
                        $page = $controller->$functionName();
                    }
                }
    
    
    
    
            }
    
            return $page;
        } catch(\Throwable $e){
            return [
                //TODO make the file
                'title' => 'Error',
                'template' => 'error404',
                'templateVars' => [
                    
                ]
            ];
        }
    }
}