<?php

namespace Api\Handler;

use Phalcon\Acl\Adapter\Memory;

class Acl
{
    public function buildAcl(): void
    {
        $aclFile = BASE_PATH . '/security/acl.cache';
        $acl = new Memory();
        $acl->addRole('admin');
        $acl->addRole('manager');
        $acl->addRole('user');
        $acl->addRole('guest');
        $acl->addComponent(
            'index',
            [
                'index',
            ]
        );

        $acl->allow('admin', '*', '*');
        $acl->allow('user', 'index', 'index');


        file_put_contents(
            $aclFile,
            serialize($acl)
        );
        echo "Build Successfully";
    }
}
