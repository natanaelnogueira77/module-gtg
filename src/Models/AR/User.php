<?php 

namespace Src\Models\AR;

use DateTime, stdClass;
use Src\DB\Statement;
use Src\Exceptions\ApplicationException;
use Src\Models\Lists\UsersList;

class User extends UserModel 
{
    public const UT_ADMIN = 1;
    public const UT_STANDARD = 2;
    
    public static function tableName(): string 
    {
        return 'usuario';
    }

    public static function primaryKey(): string 
    {
        return 'id';
    }

    public static function attributes(): array 
    {
        return [
            'user_type', 
            'name', 
            'email', 
            'password', 
            'token', 
            'slug'
        ];
    }
        
    public static function hasTimestamps(): bool 
    {
        return true;
    }

    public function rules(): array 
    {
        return [
            $this->createRule()->required('user_type')->setMessage(_('The user permission is required!')),
            $this->createRule()->in('user_type', array_keys(self::getUserTypes()))->setMessage(_('The user permission is invalid!')),
            $this->createRule()->required('name')->setMessage(_('The name is required!')),
            $this->createRule()->maxLength('name', 100)->setMessage(sprintf(_('The name must have %s characters or less!'), 100)),
            $this->createRule()->required('email')->setMessage(_('The email is required!')),
            $this->createRule()->email('email')->setMessage(_('The email is invalid!')),
            $this->createRule()->maxLength('email', 100)->setMessage(sprintf(_('The email must have %s characters or less!'), 100)),
            $this->createRule()->required('password')->setMessage(_('The password is required!')),
            $this->createRule()->minLength('password', 5)->setMessage(sprintf(_('The password must have %s characters or less!'), 5))
        ];
    }

    public static function getUserTypes(): array 
    {
        return [
            self::UT_ADMIN => _('Admin'),
            self::UT_STANDARD => _('User')
        ];
    }

    public function getUserType(): ?string 
    {
        return isset(self::getUserTypes()[$this->user_type]) 
            ? self::getUserTypes()[$this->user_type] 
            : null;
    }

    public function save(): void 
    {
        $this->transformBeforeSaving();
        parent::save();
    }

    private function transformBeforeSaving(): self 
    {
        $this->slug = slugify($this->name);
        $this->email = strtolower($this->email);
        $this->token = is_string($this->email) ? md5($this->email) : null;

        if(!password_get_info($this->password)['algo']) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
        return $this;
    }

    public static function saveMany(array $models): array 
    {
        foreach($models as $model) {
            $model->transformBeforeSaving();
        }
        return parent::saveMany($models);
    }

    public function destroy(): void 
    {
        if($this->isAdmin()) {
            throw new ApplicationException(
                _('You cannot delete the Admin!'), 
                403
            );
        } elseif(UserMeta::get()->filters(fn($where) => $where->equal('usu_id')->assignment($this->id))->count()) {
            throw new ApplicationException(
                _('You cannot delete an user with stored data!'), 
                403
            );
        }

        parent::destroy();
    }

    public function isAdmin(): bool
    {
        return $this->user_type == self::UT_ADMIN;
    }

    public static function getByEmail(string $email, string $columns = '*'): ?self 
    {
        return self::get($columns)->filters(fn($where) => $where->equal('email')->assignment($email))->fetch(false);
    }

    public static function getByToken(string $token, string $columns = '*'): ?self 
    {
        return self::get($columns)->filters(fn($where) => $where->equal('token')->assignment($token))->fetch(false);
    }

    public static function getUsersCountGroupedByUserType(): array 
    {
        $usersCount = [];
        foreach(self::getUserTypes() as $userTypeId => $userType) {
            $userCounts = (new Statement(stdClass::class, self::tableName(), [
                'user_type', 
                'COUNT(*)' => 'users_count'
            ]))->group('user_type')->fetch('count');

            foreach($userCounts as $userCount) {
                $usersCount[$userCount->user_type] = $userCount->users_count;
            }
        }
        return $usersCount;
    }

    public function verifyPassword(string $password): bool 
    {
        return $this->password ? password_verify($password, $this->password) : false;
    }

    public static function getSlugByName(string $name): string 
    {
        return slugify($name . (new DateTime())->getTimestamp());
    }

    public function getCreatedAtDateTime(): DateTime 
    {
        return new DateTime($this->created_at);
    }

    public function getUpdatedAtDateTime(): DateTime 
    {
        return new DateTime($this->updated_at);
    }

    public function getAvatarURL(): string 
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
    }

    public function getLastResetPasswordRequest(): ?string 
    {
        return UserMeta::get()->filters(
            fn($where) => $where->equal('meta')->assignment(UserMeta::KEY_LAST_PASS_REQUEST)
        )->fetch(false)?->value;
    }

    public function setLastResetPasswordRequest(string $time): bool 
    {
        $userId = $this->id;
        $userMeta = UserMeta::get()->filters(function($where) use ($userId) {
            $where->equal('usu_id')->assignment($this->id);
            $where->equal('meta')->assignment(UserMeta::KEY_LAST_PASS_REQUEST);
        })->fetch(false);

        if(!$userMeta) {
            $userMeta = (new UserMeta())->loadData([
                'usu_id' => $userId,
                'meta' => UserMeta::KEY_LAST_PASS_REQUEST
            ]);
        }

        $userMeta->value = $time;
        return $userMeta->save();
    }

    public static function getList(UsersList $list): ?array
    {
        return self::get()->filters(self::getListFilters($list))->paginate(
            $list->getLimit(), 
            $list->getPageToShow()
        )->order("{$list->getOrderBy()} {$list->getOrderType()}")->fetch(true);
    }

    private static function getListFilters(UsersList $list): callable 
    {
        return function($where) use ($list) {
            if($list->getSearchTerm()) {
                $where->groupWithOr(function($where) use ($list) {
                    $where->like('name')->pattern("%{$list->getSearchTerm()}%");
                    $where->like('email')->pattern("%{$list->getSearchTerm()}%");
                    $where->like('slug')->pattern("%{$list->getSearchTerm()}%");
                });
            }

            if($list->getUserType()) {
                $where->equal('user_type')->assignment($list->getUserType());
            }
        };
    }

    public static function getListResultsCount(UsersList $list): int
    {
        return self::get()->filters(self::getListFilters($list))->count();
    }
}