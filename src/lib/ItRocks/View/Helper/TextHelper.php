<?php

class ItRocks_View_Helper_TextHelper extends Zend_Controller_Action_Helper_Abstract {
    public function textHelper($count) {
        $variants = [
            '%d комментарий',
            '%d комментария',
            '%d комментариев'
        ];

        $units = $count % 10;
        $scores = $count / 10 % 10;

        if ($scores == 1)
            return sprintf($variants[2], $count);

        if ($units == 1)
            return sprintf($variants[0], $count);

        if ($units > 1 && $units < 5)
            return sprintf($variants[1], $count);

        return sprintf($variants[2], $count);
    }
}