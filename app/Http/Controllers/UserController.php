<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
   private UserService $service;

   public function __construct(UserService $service) {
      $this->service = $service;
   }

   public function getUsers() {
       $users = $this->service->getAll();

       return response(compact ('users'));
   }

}
