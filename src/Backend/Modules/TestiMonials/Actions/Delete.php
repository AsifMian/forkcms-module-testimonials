<?php

namespace Backend\Modules\TestiMonials\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;
use Backend\Modules\TestiMonials\Engine\Model as BackendTestiMonialsModel;

/**
 * This action will delete a mini blog article.
 *
 * @author Dave Lens <dave.lens@netlash.com>
 * @author Davy Hellemans <davy.hellemans@netlash.com>
 * @author Stef Bastiaansen <stef.bastiaansen@netlash.com>
 * @author Lander Vanderstraeten <lander.vanderstraeten@wijs.be>
 */
class Delete extends BackendBaseActionDelete
{
    /**
     * Execute the action.
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        // does the item exist
        if ($this->id !== null && BackendTestiMonialsModel::exists($this->id)) {
            // call parent, this will probably add some general CSS/JS or other required files
            parent::execute();

            // get data
            $this->record = (array) BackendTestiMonialsModel::get($this->id);

            // delete item
            BackendTestiMonialsModel::delete($this->id);

            // delete search indexes
            if (is_callable(array('BackendSearchModel', 'removeIndex'))) {
                BackendSearchModel::removeIndex('testimonials', $this->id);
            }

            // trigger an event
            BackendModel::triggerEvent('testimonials', 'after_delete', $this->record);

            // item was deleted, so redirect
            $this->redirect(BackendModel::createURLForAction('Index').'&report=deleted&var='.urlencode($this->record['title']));
        } // something went wrong
        else {
            $this->redirect(BackendModel::createURLForAction('Index').'&error=non-existing');
        }
    }
}
