<?php

namespace App\Security\Voter;

use App\Entity\Item;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ItemVoter extends Voter
{

    const DELETE = 'delete';
    const EDIT   = 'edit';
    const CREATE = 'create';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::DELETE, self::EDIT, self::CREATE])) {
            return false;
        }

        if (!$subject instanceof Item) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        /** @var Item */
        $item = $subject; // $subject must be a Post instance, thanks to the supports method

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            // if the user is an admin, allow them to create new posts
            case self::DELETE:
                if ($user === $item->getAuthor()||$this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
                    return true;
                }

                break;

            case self::CREATE:
                if ($this->decisionManager->decide($token, ['ROLE_USER'])) {
                    return true;
                }

                break;

            // if the user is the author of the post, allow them to edit the posts
            case self::EDIT:
                if ($user === $item->getAuthor()||$this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
                    return true;
                }

                break;
        }

        return false;
    }
}
