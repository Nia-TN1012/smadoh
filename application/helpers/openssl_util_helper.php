<?php

if( !function_exists( "der_to_pem" ) ) {
    function der_to_pem( $der ) {
        $pem = chunk_split( base64_encode( $der ), 64, "\n" );
        $pem = "-----BEGIN CERTIFICATE-----\n{$pem}-----END CERTIFICATE-----\n";
        return $pem;
    }
}

?>