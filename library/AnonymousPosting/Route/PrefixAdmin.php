<?php

class AnonymousPosting_Route_PrefixAdmin implements XenForo_Route_Interface
{
    public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
    {
        return $router->getRouteMatch('AnonymousPosting_ControllerAdmin', $routePath, 'tools');
    }

}
