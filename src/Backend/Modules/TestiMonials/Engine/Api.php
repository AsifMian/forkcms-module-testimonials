<?php
namespace Backend\Modules\TestiMonials\Engine;

use Api\V1\Engine\Api as BaseAPI;
use Backend\Modules\TestiMonials\Engine\Model as BackendTestiMonialsModel;

/**
 * This action will add a post to the blog module.
 *
 * @author Stef Bastiaansen <stef.bastiaansen@netlash.com>
 * @author Lander Vanderstraetren <lander.vanderstraeten@wijs.be>
 */
class Api
{
    public static function showTopAwesome()
    {
        $number = (int) \SpoonFilter::getGetValue('number', null, 10);
        $days = (int) \SpoonFilter::getGetValue('days', null, 7);
        BaseAPI::output('200', BackendTestiMonialsModel::getTopAwesome($number, $days));
    }
}
