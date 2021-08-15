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
        //Session::start();


        //print_r(date('jS M Y',time()));
        //print_r(date('jS M Y',time() + (24*60*60)));
        //print_r(date('jS M Y',time() + (1 + 365 + 24*60*60)));
        //return $this->render('contact');
    }


    public function handleContact(Request $request){
        $body = $request->getPath();
        var_dump($body);
        exit;
    }
}