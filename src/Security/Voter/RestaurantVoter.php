<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RestaurantVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT_RESTAURANT', 'DELETE_RESTAURANT'])
            && $subject instanceof \App\Entity\Restaurant;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT_RESTAURANT':
                if($subject->getUser() == $token->getUser()){
                    return true;
                }

                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'DELETE_RESTAURANT':
                if( in_array("ROLE_SUPER_ADMIN", $token->getUser()->getRoles())){
                    return true;
                }

                break;
        }

        return false;
    }
}
