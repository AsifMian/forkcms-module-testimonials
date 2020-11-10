<?php

namespace Frontend\Modules\TestiMonials\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Tags\Engine\Model as FrontendTagsModel;
use Frontend\Modules\TestiMonials\Engine\Model as FrontendTestiMonialsModel;

/**
 * This is the detail-action.
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 * @author Davy Hellemans <davy.hellemans@netlash.com>
 * @author Dieter Vanden Eynde <dieter.vandeneynde@netlash.com>
 * @author Stef Bastiaansen <stef.bastiaansen@netlash.com>
 * @author Jelmer Snoeck <jelmer.snoeck@netlash.com>
 * @author Lander Vanderstraeten <lander.vanderstraeten@wijs.be>
 */
class Detail extends FrontendBaseBlock
{
    /**
     * The blogpost.
     *
     * @var array
     */
    private $record;

    /**
     * Execute the extra.
     */
    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->getData();
        $this->parse();
    }

    /**
     * Load the data, don't forget to validate the incoming data.
     */
    private function getData()
    {
        if ($this->URL->getParameter(1) === null) {
            $this->redirect(FrontendNavigation::getURL(404));
        }

        // get by URL
        $this->record = FrontendTestiMonialsModel::get($this->URL->getParameter(1));
        if (empty($this->record)) {
            $this->redirect(FrontendNavigation::getURL(404));
        }

        $this->record['full_url'] = FrontendNavigation::getURLForBlock('TestiMonials', 'Detail').'/'.$this->record['url'];
        $this->record['tags'] = FrontendTagsModel::getForItem('TestiMonials', $this->record['id']);
    }

    /**
     * Parse the data into the template.
     */
    private function parse()
    {
        $this->header->addJS('/src/Frontend/Modules/TestiMonials/Js/ThisIsAwesome.js', 'TestiMonials');

        $this->breadcrumb->addElement($this->record['title']);

        // set meta
        $this->header->setPageTitle($this->record['title']);
        $this->header->addMetaDescription($this->record['meta_description'], ($this->record['meta_description_overwrite'] == 'Y'));
        $this->header->addMetaKeywords($this->record['meta_keywords'], ($this->record['meta_keywords_overwrite'] == 'Y'));

        $this->tpl->assign('hideContentTitle', true);
        $this->tpl->assign('item', $this->record);

        $navigation = FrontendTestiMonialsModel::getNavigation($this->record['id']);
        if ($navigation['previous'] === null && $navigation['next'] === null) {
            $navigation = false;
        }
        $this->tpl->assign('navigation', $navigation);
    }
}
