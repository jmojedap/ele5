<?php
    //$link_status = base_url() . "orders/estado/?order_code={$row_order->order_code}";
    $link_status = base_url("orders/my_suscriptions");
?>


<body>
    <div style="<?php echo $style->body ?>">
        
        <table>
            <tr>
                <td style="width: 33%;">
                    <b style="<?php echo $style->text_info ?>"><?php echo $this->pml->money($row_order->amount) ?></b>
                    <span style="<?php echo $style->text_muted ?>">
                        <?php echo $this->Item_model->name(7, $row_order->status) ?>
                    </span>
                </td>
                <td style="text-align: left;">
                    <h4 style="<?php echo $style->h4 ?>"></h4>
                </td>
                <td style="text-align: right;">
                    <a href="<?php echo $link_status ?>" style="<?php echo $style->btn ?>" title="Ver compra en la página" target="_blank">
                        Ver compra
                    </a>
                </td>
            </tr>

            <tr>
                <td colspan="3" style="<?php echo $style->text_center ?>">

                    <h1 style="<?php echo $style->h1 ?>">
                        <?php echo $row_order->buyer_name ?>
                    </h1>
                </td>
            </tr>

            <tr style="<?php echo $style->text_center ?>">
                <td colspan="3">
                    <span style="<?php echo $style->text_muted?>">
                        Cód. compra:
                    </span>
                    <span style="<?php echo $style->text_danger ?>">
                        <?php echo $row_order->order_code ?>
                    </span>

                    <span style="<?php echo $style->text_muted?>">
                        |
                    </span>

                    <span style="<?php echo $style->text_muted?>">
                        Actualizado:
                    </span>
                    <span style="<?php echo $style->text_danger ?>">
                        <?php echo $this->pml->date_format($row_order->updated_at, 'Y-M-d H:i') ?>
                    </span>
                    <span style="<?php echo $style->text_muted?>">
                        |
                    </span>
                </td>
            </tr>
        </table>

        <h2 style="<?php echo $style->h2 ?>">Detalle de la compra</h2>

        <table style="<?php echo $style->table ?>">
            <thead style="<?php echo $style->thead ?>">
                <tr style="">
                    <td style="<?php echo $style->td ?>">Producto</td>
                    <td style="<?php echo $style->td ?>">Precio</td>
                    <td style="<?php echo $style->td ?>">Cantidad</td>
                    <td style="<?php echo $style->td ?>">
                        <?php echo $this->pml->money($row_order->amount) ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products->result() as $row_product) : ?>
                    <?php
                        $precio_detalle = $row_product->quantity * $row_product->price;
                    ?>
                    <tr>
                        <td style="<?php echo $style->td ?>">
                            <?php echo $row_product->description ?>
                        </td>
                        <td style="<?php echo $style->td ?>">
                            <p>
                                <?php echo $this->pml->money($row_product->price) ?>
                            </p>
                        </td>
                        <td style="<?php echo $style->td ?>">
                            <p>
                                <?php echo $row_product->quantity ?>
                            </p>
                        </td>
                        <td style="<?php echo $style->td ?>">
                            <?php echo $this->pml->money($precio_detalle) ?>
                        </td>

                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <h2 style="<?php echo $style->h2 ?>">Datos de entrega</h2>

        <p>
            <span style="<?php echo $style->text_muted ?>">
                No. documento
            </span>
            <span style="<?php echo $style->text_danger ?>">
                <?php echo $row_order->id_number ?>
            </span>

            |

            <span style="<?php echo $style->text_muted ?>">E-mail</span>
            <span style="<?php echo $style->text_danger ?>"><?php echo $row_order->email ?></span>

            |

            <span style="<?php echo $style->text_muted ?>">
                Ciudad
            </span>
            <span style="<?php echo $style->text_danger ?>">
                <?php echo $row_order->city ?>
            </span>

            |

            <span style="<?php echo $style->text_muted ?>">Dirección</span>
            <span style="<?php echo $style->text_danger ?>">
                <?php echo $row_order->address ?>
            </span>            

            |

            <span style="<?php echo $style->text_muted ?>">
                Teléfono
            </span>
            <span style="<?php echo $style->text_danger ?>">
                <?php echo $row_order->phone_number ?>
            </span>
        </p>

        <hr>

        <div style="<?php echo $style->text_center ?>">
            <h3>
                <?php echo APP_NAME ?><br>
            </h3>
            <p sytle="<?php echo $style->text_muted ?>">
                &copy; <?php echo date('Y') ?>
            </p>
        </div>

    </div>
</body>