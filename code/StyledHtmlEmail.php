<?php
use Pelago\Emogrifier\CssInliner;
use Symfony\Component\CssSelector\Exception\ParseException;

/**
 * Same as the normal system email class, but runs the content through
 * Emogrifier to merge css style inline before sending.
 * @author Mark Guinn
 */
class StyledHtmlEmail extends Email
{
    /**
     * Replaces the default mail handling with a check for inline styles. If found
     * we run the email through emogrifier to inline the styles.
     * @param bool $isPlain
     * @return $this
     * @throws ParseException
     */
    protected function parseVariables($isPlain = false)
    {
        parent::parseVariables($isPlain);

        // if it's an html email, filter it through emogrifier
        if (!$isPlain && preg_match('/<style[^>]*>(?:<\!--)?(.*)(?:-->)?<\/style>/ims', $this->body, $match)) {
            $css = $match[1];
            $html = str_replace(
                array(
                    "<p>\n<table>",
                    "</table>\n</p>",
                    '&copy ',
                    $match[0],
                ),
                array(
                    "<table>",
                    "</table>",
                    '',
                    '',
                ),
                $this->body
            );

            $emog = CssInliner::fromHtml($html);
            $emog->inlineCss($css);
            $this->body = $emog->render();
        }

        return $this;
    }
}
