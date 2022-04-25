<?php

namespace Api\Handler;

use Phalcon\Acl\Adapter\Memory;


class Acl
{
    public function buildAcl()
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

        file_put_contents(
            $aclFile,
            serialize($acl)
        );
        echo "Build Successfully";
        // echo  $acl->isallowed('manager', 'index', 'index');
    }
}
