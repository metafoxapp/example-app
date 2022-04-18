<?php

namespace Company\Note\Support\Browse\Scopes\Note;

use MetaFox\Platform\Contracts\User;
use MetaFox\Platform\Support\Browse\Browse;
use MetaFox\Platform\Support\Browse\Scopes\BaseScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Company\Note\Models\Note;

/**
 * Class ViewScope.
 */
class ViewScope extends BaseScope
{
    public const VIEW_DEFAULT = Browse::VIEW_ALL;
    public const VIEW_DRAFT = 'draft';

    /**
     * @return array<int, string>
     */
    public static function getAllowView(): array
    {
        return [
            Browse::VIEW_ALL,
            Browse::VIEW_MY,
            Browse::VIEW_FRIEND,
            Browse::VIEW_PENDING,
            Browse::VIEW_FEATURE,
            Browse::VIEW_SPONSOR,
            self::VIEW_DRAFT,
        ];
    }

    /**
     * @var string
     */
    protected string $view = self::VIEW_DEFAULT;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @var bool
     */
    protected bool $isViewOwner = false;

    /**
     * @var int
     */
    protected int $profileId = 0;

    /**
     * @return int
     */
    public function getProfileId(): int
    {
        return $this->profileId;
    }

    /**
     * @param int $profileId
     *
     * @return ViewScope
     */
    public function setProfileId(int $profileId): self
    {
        $this->profileId = $profileId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isViewOwner(): bool
    {
        return $this->isViewOwner;
    }

    /**
     * @param bool $isViewOwner
     *
     * @return ViewScope
     */
    public function setIsViewOwner(bool $isViewOwner): self
    {
        $this->isViewOwner = $isViewOwner;

        return $this;
    }

    /**
     * @return User
     */
    public function getUserContext(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return ViewScope
     */
    public function setUserContext(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * @param string $view
     *
     * @return ViewScope
     */
    public function setView(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function apply(Builder $builder, Model $model)
    {
        if ($this->isViewOwner()) {
            return;
        }

        $view = $this->getView();
        $userContext = $this->getUserContext();

        switch ($view) {
            case Browse::VIEW_MY:
                $builder->where([
                    ['notes.owner_id', '=', $userContext->entityId(), 'or'],
                    ['notes.user_id', '=', $userContext->entityId(), 'or'],
                ]);
                break;
            case Browse::VIEW_FRIEND:
                $builder->join('friends AS f', function (JoinClause $join) use ($userContext) {
                    $join->on('f.user_id', '=', 'notes.owner_id')
                        ->where([
                            ['f.owner_id', '=', $userContext->entityId()],
                            ['notes.is_draft', '!=', Note::IS_DRAFT],
                            ['notes.is_approved', '=', Note::IS_APPROVED],
                        ]);
                });
                break;
            case Browse::VIEW_PENDING:
                $builder->where('notes.is_approved', '!=', Note::IS_APPROVED);
                if ($this->getProfileId() == $userContext->entityId()) {
                    $builder->where('notes.user_id', $this->getProfileId());
                }

                if ($this->getProfileId() == 0) {
                    $builder->whereColumn('notes.user_id', '=', 'notes.owner_id');
                }
                break;
            case self::VIEW_DRAFT:
                $builder->where([
                    ['notes.is_draft', '=', Note::IS_DRAFT],
                    ['notes.user_id', '=', $userContext->entityId()],
                ]);
                break;
            default:
                $builder->where([
                    ['notes.is_draft', '!=', Note::IS_DRAFT],
                    ['notes.is_approved', '=', Note::IS_APPROVED],
                ]);
        }
    }
}
