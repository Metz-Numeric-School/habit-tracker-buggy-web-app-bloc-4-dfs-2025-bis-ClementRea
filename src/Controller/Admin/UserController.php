<?php
namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Mns\Buggy\Core\AbstractController;
use OpenApi\Attributes as OA;


#[OA\Info(title: "API du projet DFS Training", version: "1.0.0")]
class UserController extends AbstractController
{
    #[OA\Get(
    path: "/user",
    summary: "Create a new user.",
    responses: [
      new OA\Response(
        response: 200,
        description: "List of users",
        content: new OA\MediaType(
          mediaType: "text/html",
          schema: new OA\Schema(type: "string")
        )
      )
    ]
  )]
    private UserRepository $userRepository;

    public function __construct()
    {   
        $this->userRepository = new UserRepository();
    }


    public function index()
    {
        $users = $this->userRepository->findAll();
        return $this->render('admin/user/index.html.php', [
            'users' => $users,
        ]);
    }

    public function new()
    {
        $errors = [];

        if(!empty($_POST['user']))
        {
            $user = $_POST['user'];
            
            if(empty($user['lastname']))
                $errors['lastname'] = 'Le Nom est obligatoire';

            if(empty($user['firstname']))
                $errors['firstname'] = 'Le Prénom est obligatoire';

            if(empty($user['email']))
                $errors['email'] = 'L\'email est obligatoire';

            if(empty($user['password']))
                $errors['password'] = 'Le mot de passe est obligatoire';

            
            if(count($errors) == 0)
            {
                $id = $this->userRepository->insert($user);
                header('Location: /admin/user');
                exit;
            }
        }

        return $this->render('admin/user/new.html.php', [
            'errors' => $errors,
        ]);
    }
}