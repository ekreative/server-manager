<?php
/**
 * Created by mcfedr on 9/29/15 11:26
 */

namespace AppBundle\AuthorEditor;

use AppBundle\Entity\User;

interface AuthorEditorable
{
    public function setEditor(User $user);

    public function setAuthor(User $user);
}
