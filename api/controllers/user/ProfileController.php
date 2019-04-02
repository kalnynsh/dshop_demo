<?php

namespace api\controllers\user;

use yii\rest\Controller;
use shop\repositories\UserRepository;
use shop\helpers\UserHelper;
use shop\entities\User\User;
use api\helpers\DateHelper;

/**
 * ProfileController - serve users data
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
     * @SWG\Get(
     *     path="/user/profile",
     *     tags={"Profile"},
     *     description="Returns profile info",
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Profile")
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     *
     * @return array
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

    /**
     * @return User|null
     */
    public function findCurrentUser(): ?User
    {
        $id = $this->yiiApp->user->id;

        return $this->repository->findOne($id);
    }

    /**
     * @return array
     */
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

/**
 *  @SWG\Definition(
 *     definition="Profile",
 *     type="object",
 *     required={"id"},
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="email", type="string"),
 *     @SWG\Property(property="city", type="string"),
 *     @SWG\Property(property="role", type="string")
 * )
 */
