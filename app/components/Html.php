<?php
class Html
{
    /**
     * Generates dropdown list tag <select> filled by items from array
     * @param $params
     * @return bool
     *
     * Params array structure example:
     *
     * $params = {
     *      'source' => $itemsArray,
     *      'name' => 'selectName',
     *      'active' => 'IDofActiveElement',
     * }
     */
    public static function dropList($params)
    {
        if(!is_array($params['source']))
            return false;

        $dropList = '<select name="'.$params['name'].'">';
        foreach($params['source'] as $value => $title)
        {
            $dropList .= '<option value="'.$value.'"'.($value==$params['active'] ? ' selected' : '').'>'.$title.'</option>';
        }
        $dropList .= '</select>';
        return $dropList;
    }

    public static function pagination($params)
    {
        $baseUrl = isset($params['baseUrl']) ? $params['baseUrl'] : '/';

        $postsCount = isset($params['postsCount']) ? $params['postsCount'] : 1;
        $currentPage = isset($params['currentPage']) ? $params['currentPage'] : 1;
        $perPage = isset($params['perPage']) ? $params['perPage'] : 10;
        $totalLinks = isset($params['totalLinks']) ? $params['totalLinks'] : 9;

        $firstText = isset($params['firstText']) ? $params['firstText'] : 'First';
        $lastText = isset($params['lastText']) ? $params['lastText'] : 'Last';
        $nextText = isset($params['nextText']) ? $params['nextText'] : 'Next';
        $prevText = isset($params['prevText']) ? $params['prevText'] : 'Previous';

        $showInfo = isset($params['showInfo']) ? $params['showInfo'] : true;
        $linkCss = isset($params['linkCss']) ? $params['linkCss'] : 'paginate-link';
        $firstCss = isset($params['firstCss']) ? $params['firstCss'] : 'paginate-first';
        $lastCss = isset($params['lastCss']) ? $params['lastCss'] : 'paginate-last';
        $nextCss = isset($params['nextCss']) ? $params['nextCss'] : 'paginate-next';
        $prevCss = isset($params['prevCss']) ? $params['prevCss'] : 'paginate-prev';
        $currentCss = isset($params['currentCss']) ? $params['currentCss'] : 'paginate-current';
        $infoCss = isset($params['infoCss']) ? $params['infoCss'] : 'paginate-info';

        $totalPages = ceil($postsCount / $perPage);

        // If only one page do nothing
        if($totalPages == 1) return false;

        $pager = "<span>";

        // Link First
        if($currentPage != 1)
        {
            $pager .= self::link(array(
                'url' => $baseUrl.'1',
                'class' => $firstCss,
                'title' => $firstText
            ));
        }

        // Link Prev
        if($totalPages > 2 && ($currentPage != 1))
        {
            $pager .= self::link(array(
                'url' => $baseUrl.($currentPage - 1),
                'class' => $prevCss,
                'title' => $prevText
            ));
        }

        // Link 1
        $pager .= self::link(array(
            'url' => $baseUrl.'1',
            'class' => ($currentPage == 1) ? $currentCss: $linkCss,
            'title' => 1
        ));

        $startPage = 2;
        $endPage = $totalPages;

        $showLeftReplacer = false;
        $showRightReplacer = false;

        if($totalPages > $totalLinks)
        {
            $leftRight = floor($totalLinks / 2);
            $addToLeft = 0;
            $addToRight = 0;

            if($currentPage - $leftRight < $startPage)
                $addToRight = $leftRight - $currentPage + 1;

            if($currentPage + $leftRight > $endPage)
                $addToLeft = $currentPage + $leftRight - $endPage;

            $leftSide = $leftRight + $addToLeft - $addToRight;
            $rightSide = $leftRight + $addToRight - $addToLeft;

            echo 'aTr '.$leftSide.' atL '.$rightSide;

            $startPage = $currentPage - $leftSide + 1;
            $endPage = $currentPage + $rightSide;

            if($startPage  == 3)
                $startPage-- ;

            if($totalPages - $endPage == 1)
                $endPage++;
        }

        if($startPage > 2)
            $showLeftReplacer = true;

        if($endPage < $totalPages - 1)
            $showRightReplacer = true;

        if($showLeftReplacer)
            $pager .= '<span>...</span>';

        for($i = $startPage; $i < $endPage; $i++)
        {
            $pager .= self::link(array(
                'url' => $baseUrl.$i,
                'class' => ($currentPage == $i) ? $currentCss : $linkCss,
                'title' => $i
            ));
        }

        if($showRightReplacer)
            $pager .= '<span>...</span>';

        // Link last (numerical)
        $pager .= self::link(array(
            'url' => $baseUrl.$totalPages,
            'class' => ($currentPage == $totalPages) ? $currentCss : $linkCss,
            'title' => $totalPages
        ));

        // Link Next
        if($totalPages > 2 && ($currentPage != $totalPages))
        {
            $pager .= self::link(array(
                'url' => $baseUrl.($currentPage + 1),
                'class' => $nextCss,
                'title' => $nextText
            ));
        }

        // Link Last
        if($currentPage != $totalPages)
        {
            $pager .= self::link(array(
                'url' => $baseUrl.$totalPages,
                'class' => $lastCss,
                'title' => $lastText
            ));
        }

        if($showInfo)
        {
            $pager .= '<div class="'.$infoCss.'">';
//            $pager .= 'Shown '.$perPage.' from '.$postsCount.' records.';
            $pager .= '</div>';
        }

        $pager .= "</span>";

        return $pager;
    }

    public static function link($params)
    {
        $url = isset($params['url']) ? $params['url'] : '#';
        $class = isset($params['class']) ? $params['class'] : '';
        $title = isset($params['title']) ? $params['title'] : 'Link';

        /*
        $template = '<a href="{URL}" class="{CLASS}">{TITLE}</a>';

        $link = str_replace('{URL}', $url, $template);
        $link = str_replace('{CLASS}', $class, $link);
        $link = str_replace('{TITLE}', $title, $link);
        */
        $link = '<a href="'.$url.'" class="'.$class.'">'.$title.'</a>';

        return $link;
    }
}
