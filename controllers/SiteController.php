<?php


namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Session;


class SiteController extends Controller {
    public function home()
    {
        $params = [
            'name' => "Ijaware"
        ];

        return $this->render('home', $params);
    }



    public function contact()
    {
        Session::start();
        Session::set('name', 'Tobi');
        print_r(Session::all());

        //return $this->render('contact');
    }


    public function handleContact(Request $request){
        $body = $request->getBody();
        var_dump($body);
        exit;
    }
}