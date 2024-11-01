<?php
/**
 * PDF Template.
 * 
 * @global array $count     Product counts. 
 * @global array $items     Order IDs.
 * @global array $columns   Column data.
 * 
 * @package Dkjensen\WCOPC
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<table style="width: 100%; table-layout: fixed; font-size: 12px;">
    <thead>
        <tr>
            <?php foreach ( $columns as $key => $column ) : ?>

                <th style="text-align: left; width: <?php echo esc_attr( $column['size'] ); ?>; padding-bottom: 20px;"><?php echo esc_html( $column['label'] ); ?></th>

            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ( $count as $product_id => $count ) :  
            $product = wc_get_product( $product_id );
        ?>

        <tr>
            <?php 
                foreach ( $columns as $column ) {
                    $content = '';

                    if ( is_callable( $column['callback'] ) ) {
                        $content = call_user_func_array( $column['callback'], array( $product_id, $count, $items, $product ) );
                    } else {
                        $content = sprintf( esc_html__( 'Uncallable function: %s', 'wc-order-product-count' ), esc_html( $column['callback'] ) );
                    }

                    printf( '<td style="padding: 8px 0;">%s</td>', $content );
                }
            ?>
        </tr>

        <?php endforeach; ?>
    </tbody>
</table>

<?php