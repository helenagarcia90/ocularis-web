<?php

/**
 *  
 * @author Benoît
 * 
 * This Exception is raised when an invalid URL is created.
 * E.G.: We try to create an url with a page id that does not exists. 
 * This is meant to avoid urls like /page/index?id=9 that will anyway lead to nothing.
 *
 */

class NoTranslationException extends Exception{
	
}