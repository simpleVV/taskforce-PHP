<?php

namespace app\helpers;

use yii\helpers\HTML;
use yii\helpers\Url;
use taskforce\logic\actions\CancelAction;
use taskforce\logic\actions\CompleteAction;
use taskforce\logic\actions\RefusalAction;
use taskforce\logic\actions\RespondAction;

/**
 * generates an array of button elements depending on the user's role and the
 * status of the task
 * 
 * @param array $availableActions array of available actions for task
 * @param int $id current task id
 * @return array button elements
 */
class HtmlHelper
{
    public static function getActionButtons(array $availableActions, int $id): array
    {
        $buttons = [];
        $colorsMap = [
            RespondAction::getInnerName() => 'blue',
            RefusalAction::getInnerName() => 'orange',
            CompleteAction::getInnerName() => 'pink',
            CancelAction::getInnerName() => 'yellow'
        ];

        foreach ($availableActions as $action) {
            $label = $action::getName();
            $buttonColor = $action::getInnerName();

            $option = [
                'data-action' => $action::getInnerName(),
                'class' => "button button--$colorsMap[$buttonColor] action-btn",
            ];

            if ($action::getInnerName() === 'cancel') {
                $option['href'] = Url::to(['tasks/cancel', 'id' => $id]);
            }

            $buttons[] = HTML::tag('a', $label, $option);;
        }

        return $buttons;
    }

    /**
     * generates an array of button elements depending on the user's role and the
     * status of the task
     * 
     * @param int $value initial stars number  
     * @param bool $active the flag at which the active class is added
     * @param string $size class-name. Set element size 
     * @param int $starsCount total number of stars 
     * @return array button elements
     */
    public static function getStarElements($value, $active = false, $size = 'small', $starsCount = 5): string
    {
        $stars = [];

        for ($i = 1; $i <= $starsCount; $i++) {
            $className = $i <= $value ? 'fill-star' : '';
            $stars[] = Html::tag('span', '&nbsp;', ['class' => $className]);
        }

        $activeClass = $active ? 'active-stars' : '';
        $className = "stars-rating $size $activeClass";

        $result = Html::tag('div', implode($stars), ['class' => $className]);

        return $result;
    }
}
