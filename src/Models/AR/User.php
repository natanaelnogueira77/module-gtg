<?php 

namespace Models\AR;

use DateTime, stdClass;
use Config\FileSystem;
use Database\Statement;
use Exceptions\ApplicationException;
use Models\Lists\UsersList;

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
            'slug',
            'avatar_image'
        ];
    }
        
    public static function hasTimestamps(): bool 
    {
        return true;
    }

    public function rules(): array 
    {
        return [
            $this->createRule()->required('user_type')->setMessage(_('O nível de permissão é obrigatório!')),
            $this->createRule()->in('user_type', array_keys(self::getUserTypes()))->setMessage(_('O nível de permissão é inválido!')),
            $this->createRule()->required('name')->setMessage(_('O nome é obrigatório!')),
            $this->createRule()->maxLength('name', 100)->setMessage(sprintf(_('O nome deve ter %s caractéres ou menos!'), 100)),
            $this->createRule()->required('email')->setMessage(_('O email é obrigatório!')),
            $this->createRule()->email('email')->setMessage(_('O email é inválido!')),
            $this->createRule()->maxLength('email', 100)->setMessage(sprintf(_('O email deve ter %s caractéres ou menos!'), 100)),
            $this->createRule()->required('password')->setMessage(_('A senha é obrigatória!')),
            $this->createRule()->minLength('password', 5)->setMessage(sprintf(_('A senha deve ter %s caractéres ou menos!'), 5)), 
            $this->createRule()->raw(function($model) {
                if($model->avatar_image && !in_array(pathinfo($model->avatar_image, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $model->addError('avatar_image', _('Essa imagem é inválida!'));
                }
            })
        ];
    }

    public static function getUserTypes(): array 
    {
        return [
            self::UT_ADMIN => _('Administrador'),
            self::UT_STANDARD => _('Usuário')
        ];
    }

    public function getUserType(): ?string 
    {
        return self::getUserTypes()[$this->user_type] ?: null;
    }

    protected function transformBeforeSaving(): static 
    {
        $this->slug = slugify($this->name);
        $this->email = strtolower($this->email);
        $this->token = is_string($this->email) ? md5($this->email) : null;

        if(!password_get_info($this->password)['algo']) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
        $this->avatar_image = $this->avatar_image ?: null;

        return parent::transformBeforeSaving();
    }

    public function destroy(): void 
    {
        if($this->isAdmin()) {
            throw new ApplicationException(_('Você não pode excluir o administrador!'), 403);
        } elseif(UserMeta::get()->filters(fn($where) => $where->equal('usu_id')->assignment($this->id))->count()) {
            throw new ApplicationException(_('Você não pode excluir um usuário com dados armazenados!'), 403);
        }

        parent::destroy();
    }

    public function isAdmin(): bool
    {
        return $this->user_type == self::UT_ADMIN;
    }

    public function isStandard(): bool
    {
        return $this->user_type == self::UT_STANDARD;
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

    public function getAvatarImageURI(): ?string 
    {
        return $this->avatar_image;
    }

    public function getAvatarImageURL(): ?string 
    {
        return $this->avatar_image ? FileSystem::getLink($this->avatar_image) : null;
    }

    public function getAvatarURL(): string 
    {
        return $this->getAvatarImageURL() ?? ('https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))));
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
            $userMeta = (new UserMeta())->fillAttributes([
                'usu_id' => $userId,
                'meta' => UserMeta::KEY_LAST_PASS_REQUEST
            ]);
        }

        $userMeta->value = $time;
        $userMeta->save();
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