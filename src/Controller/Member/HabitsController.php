<?php
namespace App\Controller\Member;

use App\Repository\HabitRepository;
use App\Repository\HabitLogRepository;
use Mns\Buggy\Core\AbstractController;
use OpenApi\Attributes as OA;

#[OA\Info(title: "API du projet DFS Training", version: "1.0.0")]
class HabitsController extends AbstractController
{
    #[OA\Get(
        path: "/habits",
        summary: "Get the list of habits for the authenticated user.",
        responses: [
            new OA\Response(
                response: 200,
                description: "List of habits",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(type: "array", items: new OA\Schema(ref: "#/components/schemas/Habit"))
                )
            )
        ]
    )]
    private HabitRepository $habitRepository;
    private HabitLogRepository $habitLogRepository;

    public function __construct()
    {
        $this->habitRepository = new HabitRepository();
        $this->habitLogRepository = new HabitLogRepository();
    }

    /**
     * Liste les habitudes de l'utilisateur
     */
    public function index()
    {

        $userId = $_SESSION['user']['id'];
        $habits = $this->habitRepository->findByUser($userId);

        return $this->render('member/habits/index.html.php', [
            'habits' => $habits,
        ]);
    }

    /**
     * Crée une nouvelle habitude
     */
    public function new()
    {

        $errors = [];

        if (!empty($_POST['habit'])) {
            $habit = $_POST['habit'];

            if (empty($habit['name'])) {
                $errors['name'] = 'Le nom de l’habitude est obligatoire';
            }

            if (count($errors) === 0) {
                $this->habitRepository->insert([
                    'user_id' => $_SESSION['user']['id'],
                    'name' => $habit['name'],
                    'description' => $habit['description'] ?? null
                ]);

                header('Location: /habits');
                exit;
            }
        }

        return $this->render('member/habits/new.html.php', [
            'errors' => $errors
        ]);
    }

    /**
     * Marque ou décoche une habitude pour aujourd'hui
     */
    public function toggle()
    {

        if (!empty($_POST['habit_id'])) {
            $habitId = (int)$_POST['habit_id'];
            $this->habitLogRepository->toggleToday($habitId);
        }

        header('Location: /dashboard');
        exit;
    }
}
