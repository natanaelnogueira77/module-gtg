<?php 

namespace Controllers;

use GTG\MVC\Request;
use Views\Pages\UserPage;

class UserController extends Controller
{
    public function index(Request $request): void
    {
        echo new UserPage(request: $request);
    }
}