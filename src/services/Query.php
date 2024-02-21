<?php
/**
 * View Count plugin for Craft CMS
 *
 * Count the number of times an element has been viewed.
 *
 * @author    Double Secret Agency
 * @link      https://www.doublesecretagency.com/
 * @copyright Copyright (c) 2019 Double Secret Agency
 */

namespace doublesecretagency\viewcount\services;

use craft\base\Component;
use craft\db\Query as CraftQuery;
use craft\elements\db\ElementQuery;
use craft\helpers\Json;
use doublesecretagency\viewcount\ViewCount;
use doublesecretagency\viewcount\records\ElementTotal;
use doublesecretagency\viewcount\records\UserHistory;
use yii\db\Expression;

/**
 * Class Query
 * @since 1.0.0
 */
class Query extends Component
{

    /**
     * Get the total number of views for a given Element.
     *
     * @param null|int $elementId
     * @param null|string $key
     * @return int
     */
    public function total(?int $elementId, ?string $key = null): int
    {
        // No ID for newly created elements
        if (!$elementId) {
            return 0;
        }
        // Get existing record
        $record = ElementTotal::findOne([
            'elementId' => $elementId,
            'viewKey'   => $key,
        ]);
        // Return total number of views
        return ($record ? $record->viewTotal : 0);
    }

    // ========================================================================= //

    /**
     * Get complete view history of a given User.
     *
     * @param null|int $userId
     * @return array
     */
    public function userHistory(?int $userId = null): array
    {
        if (!$userId) {
            return [];
        }
        $record = UserHistory::findOne([
            'id' => $userId,
        ]);
        if (!$record) {
            return [];
        }
        return Json::decode($record->history);
    }

    /**
     * Sort the query by most viewed elements.
     *
     * @param ElementQuery $query
     * @param null|string $key
     */
    public function orderByViews(ElementQuery $query, ?string $key = null): void
    {
        // Collect and sort elementIds
        $elementIds = $this->_elementIdsByViews($query, $key);

        // If no element IDs, bail
        if (!$elementIds) {
            return;
        }

        // Match order to elementIds
        $ids = implode(', ', $elementIds);
        $query->orderBy = [new Expression("field([[elements.id]], {$ids}) desc")];
    }

    /**
     * Collect and sort the element IDs.
     *
     * @param ElementQuery $query
     * @param null|string $key
     * @return null|array
     */
    private function _elementIdsByViews(ElementQuery $query, ?string $key): ?array
    {
        // If key isn't valid, bail
        if (!ViewCount::$plugin->viewCount->validKey($key)) {
            return null;
        }

        // Adjust conditions based on whether a key was provided
        if (null === $key) {
            $conditions = '[[totals.viewKey]] is null';
        } else {
            $conditions = ['[[totals.viewKey]]' => $key];
        }

        // Construct order SQL
        $total = 'ifnull([[totals.viewTotal]], 0)';
        $order = "{$total} desc, [[elements.id]] desc";

        // Join with elements table to sort by total
        $elementIds = (new CraftQuery())
            ->select('[[elements.id]]')
            ->from('{{%elements}} elements')
            ->where($conditions)
            ->andWhere(['[[elements.type]]' => $query->elementType])
            ->leftJoin('{{%viewcount_elementtotals}} totals', '[[elements.id]] = [[totals.elementId]]')
            ->orderBy([new Expression($order)])
            ->column();

        // Return elementIds
        return array_reverse($elementIds);
    }

}
