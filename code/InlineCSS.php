<?php
use Pelago\Emogrifier;
use Symfony\Component\CssSelector\Exception\ParseException;

/**
 * Inline CSS
 */
class InlineCSS
{
    /**
     * Inline both the embedded css, and css from an external file, into html
     *
     * @param string $htmlContent
     * @param string $cssFile path and filename
     * @return string with inlined CSS
     * @throws ParseException
     */
    public static function convert($htmlContent, $cssFile)
    {
        $emog = Emogrifier\CssInliner::fromHtml($htmlContent);

        // Apply the css file to Emogrifier
        if ($cssFile) {
            $cssFileLocation = implode(DIRECTORY_SEPARATOR, array(Director::baseFolder(), $cssFile));
            $cssFileHandler = fopen($cssFileLocation, 'rb');
            $css = fread($cssFileHandler, filesize($cssFileLocation));
            fclose($cssFileHandler);

            $emog->inlineCss($css);
        }

        return $emog->render();
    }
}
