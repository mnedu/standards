<?php

if (class_exists('PEAR_Sniffs_Commenting_FunctionCommentSniff', true) === false) {
    $error = 'Class PEAR_Sniffs_Commenting_FunctionCommentSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Symfony2 standard customization to PEARs FunctionCommentSniff. - Shamelessly borrowed...
 *
 * Verifies that :
 * <ul>
 *  <li>There is a @return tag iff a return statement exists inside the method</li>
 * </ul>
 *
 * @package   PHP_CodeSniffer
 * @author    Felix Brandt <mail@felixbrandt.de>
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class MState_Sniffs_Commenting_FunctionCommentSniff extends PEAR_Sniffs_Commenting_FunctionCommentSniff
{

    /**
     * Process the return comment of this function comment.
     *
     * @param int $commentStart The position in the stack where the comment started.
     * @param int $commentEnd   The position in the stack where the comment ended.
     *
     * @return void
     */
    protected function processReturn($commentStart, $commentEnd)
    {
        if ($this->isInheritDoc()) {
            return;
        }
        
        parent::processReturn($commentStart, $commentEnd);
        

    } /* end processReturn() */

    /**
     * Is the comment an inheritdoc?
     *
     * @return boolean True if the comment is an inheritdoc
     */
    protected function isInheritDoc ()
    {
        $content = $this->commentParser->getComment()->getContent();

        return preg_match('#{@inheritdoc}#i', $content) === 1;
    } // end isInheritDoc()

    /**
     * Process the function parameter comments.
     *
     * @param int $commentStart The position in the stack where
     *                          the comment started.
     *
     * @return void
     */
    protected function processParams($commentStart)
    {
        if ($this->isInheritDoc()) {
            return;
        }

        parent::processParams($commentStart);
    } // end processParams()

}//end class

?>