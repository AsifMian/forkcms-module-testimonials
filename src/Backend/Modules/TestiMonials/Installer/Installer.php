<?php
namespace Backend\Modules\TestiMonials\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * This action will install the mini blog module.
 *
 * @author Lander Vanderstraeten <lander.vanderstraeten@wijs.be>
 */
class Installer extends ModuleInstaller
{
    /**
     * Install the module.
     */
    public function install()
    {
        $this->importSQL(dirname(__FILE__).'/Data/install.sql');

        $this->addModule('TestiMonials');

        $this->importLocale(dirname(__FILE__).'/Data/locale.xml');

        $this->makeSearchable($this->getModule());
        $this->setModuleRights(1, $this->getModule());

        $this->setActionRights(1, $this->getModule(), 'Index');
        $this->setActionRights(1, $this->getModule(), 'Add');
        $this->setActionRights(1, $this->getModule(), 'Edit');
        $this->setActionRights(1, $this->getModule(), 'Delete');

        $this->insertExtra($this->getModule(), 'block', 'TestiMonials');
        $this->insertExtra($this->getModule(), 'widget', 'RecentPosts', 'recentposts');

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $this->setNavigation($navigationModulesId, $this->getModule(), 'TestiMonials/Index', array('TestiMonials/Add', 'TestiMonials/Edit'));
    }
}
