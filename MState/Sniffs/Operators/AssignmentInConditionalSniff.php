<?php
/**
 * MState_Sniffs_Operators_AssignmentInConditionalSniff.
 * 
 * PHP Version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Justin Rovang <justin.rovang@minnesota.edu>
 * @copyright Whatever works
 * @license   Whatever works
 * @version   Release: 1.3
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * MState_Sniffs_Operators_AssignmentInConditionalSniff.
 * 
 * Ensure no assignment in a condition happens (unless explicit brackets), e.g.:
 *
 * <code>
 * if ($foo = functionCall()) { }   // BAD
 * if (($foo = functionCall()) { }  //BAD
 * 
 * if (($foo = functionCall()) == TRUE) { } //O.K.
 * </code>
 * 
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Justin Rovang <justin.rovang@minnesota.edu>
 * @copyright Whatever works
 * @license   Whatever works
 * @version   Release: 1.3
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class MState_Sniffs_Operators_AssignmentInConditionalSniff implements PHP_CodeSniffer_Sniff
{
	
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
        	T_IF,
        	T_WHILE
        );

    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
    	
    	$err = 'Assignment inside a conditional, try wrapping logic in parenthesis, followed by a comparison operator';
    	
   		$tokens = $phpcsFile->getTokens();
   		
   		$opener = $tokens[$stackPtr]['parenthesis_opener'];
   		$closer = $tokens[$stackPtr]['parenthesis_closer'];
   		
   		$eq = $phpcsFile->findNext(T_EQUAL, $opener, $closer);
   		
   		/* Ensure at least one equal sign is found*/
   		while ($eq !== FALSE) {
   			

   			
   			$container = array_reverse($tokens[$eq]['nested_parenthesis'], TRUE);
   			
   			foreach ($container as $lowerBound => $upperBound) {
   				
	   			/* Look to the left or right of the boundary
	   			 * Should see:
	   			 * 	(+/-)1 T_WHITESPACE
	   			 *  (+/-)2 PHP_CodeSniffer_Tokens::$comparisonTokens;
	   			 */
   				/*
   				var_dump(
   					$tokens[$upperBound],
   					$tokens[$upperBound+1],
   					$tokens[$upperBound+2],
   					'==============================',
   					$tokens[$lowerBound],
   					$tokens[$lowerBound-1],
   					$tokens[$lowerBound-2]
   				);
   				*/
   				if (
   					$tokens[($upperBound+1)]['code'] !== T_WHITESPACE
   					&&
   					$tokens[($lowerBound-1)]['code'] !== T_WHITESPACE
   				) {
   					$phpcsFile->addError($err.' (No wrapper)', $stackPtr, 'CondAssign');
   					return;
   				}
   				
   				if (
   					in_array($tokens[$upperBound+2]['code'], PHP_CodeSniffer_Tokens::$comparisonTokens) == FALSE
   					&&
   					in_array($tokens[$lowerBound-2]['code'], PHP_CodeSniffer_Tokens::$comparisonTokens) == FALSE
   				) {
   					$phpcsFile->addError($err.' (No comparison)', $stackPtr, 'CondAssign'
   					);
   					return;
   				}
   			
   				break;
   			}
   			$eq = $phpcsFile->findNext(T_EQUAL, ($eq+1), $closer);
   		}
   		return;  		
    }
}

?>