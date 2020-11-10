<?php

namespace Frontend\Modules\TestiMonials\Widgets;

/*
 * This is a widget with recent blogarticles
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 * @author Stef Bastiaansen <stef.bastiaansen@netlash.com>
 * @author Lander Vanderstraeten <lander.vanderstraeten@wijs.be>
 */

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\TestiMonials\Engine\Model as FrontendTestiMonialsModel;

class RecentPosts extends FrontendBaseWidget
{
    /**
     * Execute the extra.
     */
    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    /**
     * Parse.
     */
    private function parse()
    {
        $this->tpl->assign('widgetTestiMonialsRecentPosts', FrontendTestiMonialsModel::getAll(5));
    }
}
