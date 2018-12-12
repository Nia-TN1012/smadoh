<?php

if( !function_exists( "h" ) ) {
    function h( $html ) {
        return htmlspecialchars( $html, ENT_QUOTES, 'UTF-8' );
    }
}

?>