<?php

namespace api\controllers\user;

use yii\rest\Controller;
use shop\repositories\UserRepository;
use shop\helpers\UserHelper;
use shop\entities\User\User;
use api\helpers\DateHelper;

/**
 * @OA\PathItem(
 *  path="/profile"
 * )
 *
 * @OA\Schema(
 *  schema="user_profile",
 *  description="Current user profile information"
 * )
 */
class ProfileController extends Controller
{
    /** @property UserRepository $repository */
    private $repository;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        UserRepository $repository,
        $config = []
    ) {
        parent::__construct($id, $module, $config);

        $this->repository = $repository;
        $this->yiiApp = \Yii::$app;
    }

    /**
     * @OA\Get(
     *  tags={"Profile"},
     *  path="/user/profile",
     *  description="Returns information about current user",
     *  @OA\Response(
     *      response=200,
     *      description="Success response",
     *      @OA\JsonContent(ref="#/components/schemas/user_profile")
     *  ),
     *  security={
     *      {"Bearer": {}, "OAuth2": {}}
     *  }
     * )
     */
    public function actionIndex(): array
    {
        return $this->serializeUser($this->findCurrentUser());
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET'],
        ];
    }

    public function findCurrentUser(): ?User
    {
        $id = $this->yiiApp->user->id;

        return $this->repository->findOne($id);
    }

    private function serializeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'date' => [
                'created' => $this->apiDateFormatter($user->created_at),
                'updated' => $this->apiDateFormatter($user->updated_at),
            ],
            'status' => [
                'code' => $user->status,
                'name' => $this->getStatusName($user->status),
            ],
        ];
    }

    private function apiDateFormatter($timestamp)
    {
        return DateHelper::formatApi($timestamp);
    }

    private function getStatusName($statusCode)
    {
        return UserHelper::statusName($statusCode);
    }
}
