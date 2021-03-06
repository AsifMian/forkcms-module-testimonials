<?php

namespace Backend\Modules\TestiMonials\Engine;

use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;

/**
 * This action will add a post to the blog module.
 *
 * @author Stef Bastiaansen <stef.bastiaansen@netlash.com>
 * @author Jelmer Snoeck <jelmer.snoeck@netlash.com>
 * @author Lander Vanderstraeten <lander.vanderstraeten@wijs.be>
 */
class Model
{
    const QRY_DATAGRID_BROWSE =
        'SELECT i.id, i.title, UNIX_TIMESTAMP(i.created) AS created, i.user_id
         FROM testimonials AS i
         WHERE i.publish = ? AND i.language = ?';

    /**
     * Deletes one or more items.
     *
     * @param int $id
     */
    public static function delete($id)
    {
        $id = (int) $id;
        $db = BackendModel::get('database');

        // delete records
        $db->delete('testimonials', 'id = ?', $id);

        // get used meta ids
        $metaId = (int) $db->getVar(
            'SELECT meta_id
             FROM testimonials AS i
             WHERE id = ?',
            array($id)
        );

        // delete meta
        $db->delete('meta', 'id = ?', array($metaId));

        // invalidate the cache for mini blog
        BackendModel::invalidateFrontendCache('testimonials', BL::getWorkingLanguage());
    }

    /**
     * Checks if an item exists.
     *
     * @param int $id
     *
     * @return bool
     */
    public static function exists($id)
    {
        return (bool) BackendModel::getContainer()->get('database')->getVar(
            'SELECT i.id
             FROM testimonials AS i
             WHERE i.id = ? AND i.language = ?',
            array((int) $id, BL::getWorkingLanguage())
        );
    }

    /**
     * Get all data for a given id.
     *
     * @param int $id
     *
     * @return array
     */
    public static function get($id)
    {
        return (array) BackendModel::getContainer()->get('database')->getRecord(
            'SELECT i.*, UNIX_TIMESTAMP(i.created) AS created, UNIX_TIMESTAMP(i.edited) AS edited, m.url
             FROM testimonials AS i
             INNER JOIN meta AS m ON m.id = i.meta_id
             WHERE i.id = ? AND i.language = ?',
            array((int) $id, BL::getWorkingLanguage())
        );
    }

    /**
     * Get all items by a given tag id.
     *
     * @param int $tagId
     *
     * @return array
     */
    public static function getByTag($tagId)
    {
        $items = (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT i.id AS url, i.title AS name, mt.module
             FROM modules_tags AS mt
             INNER JOIN tags AS t ON mt.tag_id = t.id
             INNER JOIN testimonials AS i ON mt.other_id = i.id
             WHERE mt.module = ? AND mt.tag_id = ? AND i.language = ?',
            array('testimonials', (int) $tagId, BL::getWorkingLanguage())
        );

        foreach ($items as $key => $row) {
            $items[$key]['url'] = BackendModel::createURLForAction(
                'Edit', 'testimonials', null,
                array('id' => $row['url'])
            );
        }

        return $items;
    }

    /**
     * Get the maximum id.
     *
     * @return int
     */
    public static function getMaximumId()
    {
        return (int) BackendModel::getContainer()
            ->get('database')
            ->getVar('SELECT MAX(id) FROM testimonials LIMIT 1');
    }

    /**
     * Get all items (at least a chunk).
     *
     * @return array
     */
    public static function getTopAwesome($limit = 5, $days = 7)
    {
        $items = (array) BackendModel::getContainer()->get('database')->getRecords(
            'SELECT
                 i.id,i.language, i.title, i.introduction, i.text, i.awesomeness, m.url,
                 UNIX_TIMESTAMP(i.edited) AS edited, i.user_id
             FROM testimonials AS i
             INNER JOIN meta AS m ON i.meta_id = m.id
             WHERE i.publish = ? AND created > DATE_ADD(NOW(),INTERVAL -? DAY)
             ORDER BY i.awesomeness DESC
             LIMIT ?',
            array('Y', (int) $days, (int) $limit)
        );

        // no results?
        if (empty($items)) {
            return array();
        }

        foreach ($items as $key => $row) {
            $link = BackendModel::getURLForBlock('TestiMonials', 'Detail', $row['language']);
            $items[$key]['full_url'] = $link.'/'.$row['url'];
        }

        return $items;
    }

    /**
     * Retrieve the unique URL for an item.
     *
     * @param string         $URL
     * @param int [optional] $id
     *
     * @return string
     */
    public static function getURL($URL, $id = null)
    {
        $URL = \SpoonFilter::urlise((string) $URL);
        $db = BackendModel::getContainer()->get('database');

        if ($id === null) {
            $number = (int) $db->getVar(
                'SELECT COUNT(i.id)
                 FROM testimonials AS i
                 INNER JOIN meta AS m ON i.meta_id = m.id
                 WHERE i.language = ? AND m.url = ?',
                array(BL::getWorkingLanguage(), $URL)
            );

            // already exists
            if ($number != 0) {
                $URL = BackendModel::addNumber($URL);

                return self::getURL($URL);
            }
        } // current category should be excluded
        else {
            $number = (int) $db->getVar(
                'SELECT COUNT(i.id)
                 FROM testimonials AS i
                 INNER JOIN meta AS m ON i.meta_id = m.id
                 WHERE i.language = ? AND m.url = ? AND i.id != ?',
                array(BL::getWorkingLanguage(), $URL, $id)
            );

            // already exists
            if ($number != 0) {
                $URL = BackendModel::addNumber($URL);

                return self::getURL($URL, $id);
            }
        }

        return $URL;
    }

    /**
     * Inserts an item into the database.
     *
     * @param array $item
     *
     * @return int
     */
    public static function insert(array $item)
    {
        // insert and return the new revision id
        $item['id'] = BackendModel::getContainer()->get('database')->insert('testimonials', $item);

        // invalidate the cache for blog
        BackendModel::invalidateFrontendCache('testimonials', BL::getWorkingLanguage());

        return $item['id'];
    }

    /**
     * Update an existing item.
     *
     * @param array $item
     *
     * @return int
     */
    public static function update(array $item)
    {
        // archive all older active versions
        BackendModel::getContainer()->get('database')->update('testimonials', $item, 'id = ?', array($item['id']));

        // invalidate the cache for blog
        BackendModel::invalidateFrontendCache('testimonials', BL::getWorkingLanguage());

        return $item['id'];
    }
}
