<?php
/* @var $this PenjualanController */
/* @var $model Penjualan */

$this->breadcrumbs = array(
    'Penjualan' => array('index'),
    $model->id,
);

$this->boxHeader['small'] = 'View';
$this->boxHeader['normal'] = '<i class="fa fa-shopping-cart fa-lg"></i> Penjualan: ' . $model->nomor;

// Agar total terformat
$model->scenario = 'tampil';
?>
<div class="row">
    <div class="small-12 columns">   
        <ul class="button-group">	
            <li><a href="<?php echo $this->createUrl('exportcsv', array('id' => $model->id)); ?>" class="tiny bigfont success button">Export <span class="ak">C</span>SV</a></li>
            <li>
                <button href="#" accesskey="p" data-dropdown="printinvoice" aria-controls="printinvoice" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-file-text fa-fw"></i> <span class="ak">P</span>rint Invoice (rrp)</button><br>
                <ul id="printinvoice" data-dropdown-content class="f-dropdown" aria-hidden="true">
                    <?php
                    foreach ($printerInvoiceRrp as $printer) {
                        ?>
                        <li>
                            <a href="<?php echo $this->createUrl('printinvoice', array('id' => $model->id, 'printId' => $printer['id'])) ?>">
                                <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
            <li>
                <button href="#" accesskey="t" data-dropdown="printnota" aria-controls="printnota" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-file-text-o fa-fw"></i> Print No<span class="ak">t</span>a</button><br>
                <ul id="printnota" data-dropdown-content class="f-dropdown" aria-hidden="true">
                    <?php
                    foreach ($printerNota as $printer) {
                        ?>
                        <li>
                            <a href="<?php echo $this->createUrl('printnota', array('id' => $model->id, 'printId' => $printer['id'])) ?>">
                                <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
            <li>
                <button href="#" accesskey="k" data-dropdown="printstruk" aria-controls="printstruk" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-file-text-o fa-fw"></i> Print Stru<span class="ak">k</span></button><br>
                <ul id="printstruk" data-dropdown-content class="f-dropdown" aria-hidden="true">
                    <?php
                    foreach ($printerStruk as $printer) {
                        ?>
                        <li>
                            <a href="<?php echo $this->createUrl('printstruk', array('id' => $model->id, 'printId' => $printer['id'])) ?>">
                                <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </div>
    <div class="small-12 columns header">
        <?php
        if ($model->transfer_mode) {
            ?>
            <span class="warning label">Transfer Barang</span>
            <?php
        }
        ?>
        <span class="secondary label">Customer</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
        <span class="secondary label">Total</span><span class="alert label"><?php echo $model->total; ?></span>
        <span class="secondary label">Status</span><span class="warning label"><?php echo $model->getNamaStatus(); ?></span>
    </div>
</div>
<div class="row">
    <div class="small-12  columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'penjualan-detail-grid',
            'dataProvider' => $penjualanDetail->search(),
            'filter' => $penjualanDetail,
            'columns' => array(
                array(
                    'name' => 'barcode',
                    'value' => '$data->barang->barcode',
                ),
                array(
                    'name' => 'namaBarang',
                    'value' => '$data->barang->nama',
                ),
                array(
                    //'name' => 'harga_jual',
                    'header' => 'HPP',
                    'type'=>'raw',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'filter' => false,
                    'value' => array($this, 'tampilkanHargaBeli')
                ),
                array(
                    'name' => 'qty',
                    'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'filter' => false
                ),
                /*
                  array(
                  'name' => 'harga_beli',
                  'htmlOptions' => array('class' => 'rata-kanan'),
                  'value' => function($data) {
                  return number_format($data->harga_beli, 0, ',', '.');
                  }
                  ),
                 */
                array(
                    'name' => 'harga_jual',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'filter' => false,
                    'value' => array($this, 'formatHargaJual')
                ),
                array(
                    'name' => 'harga_jual_rekomendasi',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'filter' => false,
                    'value' => array($this, 'formatHargaJualRekomendasi')
                ),
                array(
                    'name' => 'subTotal',
                    'value' => '$data->total',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'filter' => false
                ),
            ),
        ));
        ?>
    </div>
</div>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-download"></i> I<span class="ak">m</span>port', 'url' => $this->createUrl('import'), 'linkOptions' => array(
                    'class' => 'warning button',
                    'accesskey' => 'm'
                )),
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-download"></i>', 'url' => $this->createUrl('import'), 'linkOptions' => array(
                    'class' => 'warning button',
                )),
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
