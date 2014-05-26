<?php

use Phinx\Migration\AbstractMigration;

class ChannelDataTableSeeder extends AbstractMigration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = $this->adapter->getConnection()->prepare('INSERT INTO channel_data (`entry_id`, `site_id`, `channel_id`, `field_id_1`, `field_ft_1`, `field_id_2`, `field_ft_2`, `field_id_3`, `field_dt_3`, `field_ft_3`, `field_id_4`, `field_ft_4`, `field_id_5`, `field_ft_5`, `field_id_6`, `field_ft_6`, `field_id_7`, `field_ft_7`, `field_id_8`, `field_ft_8`, `field_id_9`, `field_ft_9`, `field_id_10`, `field_ft_10`, `field_id_11`, `field_ft_11`, `field_id_12`, `field_ft_12`, `field_id_13`, `field_ft_13`, `field_id_14`, `field_ft_14`, `field_id_15`, `field_ft_15`, `field_id_16`, `field_ft_16`, `field_id_17`, `field_ft_17`, `field_id_18`, `field_ft_18`, `field_id_19`, `field_ft_19`, `field_id_20`, `field_ft_20`, `field_id_21`, `field_ft_21`, `field_id_22`, `field_ft_22`) VALUES (:entry_id, :site_id, :channel_id, :field_id_1, :field_ft_1, :field_id_2, :field_ft_2, :field_id_3, :field_dt_3, :field_ft_3, :field_id_4, :field_ft_4, :field_id_5, :field_ft_5, :field_id_6, :field_ft_6, :field_id_7, :field_ft_7, :field_id_8, :field_ft_8, :field_id_9, :field_ft_9, :field_id_10, :field_ft_10, :field_id_11, :field_ft_11, :field_id_12, :field_ft_12, :field_id_13, :field_ft_13, :field_id_14, :field_ft_14, :field_id_15, :field_ft_15, :field_id_16, :field_ft_16, :field_id_17, :field_ft_17, :field_id_18, :field_ft_18, :field_id_19, :field_ft_19, :field_id_20, :field_ft_20, :field_id_21, :field_ft_21, :field_id_22, :field_ft_22)');

        $query->execute([
            'entry_id' => 1,
            'site_id' => 1,
            'channel_id' => 2,
            'field_id_1' => '',
            'field_ft_1' => null,
            'field_id_2' => '',
            'field_ft_2' => null,
            'field_id_3' => 0,
            'field_dt_3' => null,
            'field_ft_3' => null,
            'field_id_4' => '',
            'field_ft_4' => null,
            'field_id_5' => '',
            'field_ft_5' => null,
            'field_id_6' => '',
            'field_ft_6' => null,
            'field_id_7' => '',
            'field_ft_7' => null,
            'field_id_8' => '',
            'field_ft_8' => null,
            'field_id_9' => '',
            'field_ft_9' => null,
            'field_id_10' => '',
            'field_ft_10' => null,
            'field_id_11' => '',
            'field_ft_11' => null,
            'field_id_12' => '',
            'field_ft_12' => null,
            'field_id_13' => '',
            'field_ft_13' => null,
            'field_id_14' => '',
            'field_ft_14' => null,
            'field_id_15' => '',
            'field_ft_15' => null,
            'field_id_16' => '',
            'field_ft_16' => null,
            'field_id_17' => null,
            'field_ft_17' => null,
            'field_id_18' => '',
            'field_ft_18' => null,
            'field_id_19' => '',
            'field_ft_19' => null,
            'field_id_20' => '',
            'field_ft_20' => null,
            'field_id_21' => '',
            'field_ft_21' => null,
            'field_id_22' => '',
            'field_ft_22' => null,
        ]);

        $query->execute([
            'entry_id' => 2,
            'site_id' => 1,
            'channel_id' => 2,
            'field_id_1' => '',
            'field_ft_1' => null,
            'field_id_2' => '',
            'field_ft_2' => null,
            'field_id_3' => 0,
            'field_dt_3' => null,
            'field_ft_3' => null,
            'field_id_4' => '',
            'field_ft_4' => null,
            'field_id_5' => '',
            'field_ft_5' => null,
            'field_id_6' => '',
            'field_ft_6' => null,
            'field_id_7' => '',
            'field_ft_7' => null,
            'field_id_8' => '',
            'field_ft_8' => null,
            'field_id_9' => '',
            'field_ft_9' => null,
            'field_id_10' => '',
            'field_ft_10' => null,
            'field_id_11' => '',
            'field_ft_11' => null,
            'field_id_12' => '',
            'field_ft_12' => null,
            'field_id_13' => '',
            'field_ft_13' => null,
            'field_id_14' => '',
            'field_ft_14' => null,
            'field_id_15' => '',
            'field_ft_15' => null,
            'field_id_16' => '',
            'field_ft_16' => null,
            'field_id_17' => null,
            'field_ft_17' => null,
            'field_id_18' => '',
            'field_ft_18' => null,
            'field_id_19' => '',
            'field_ft_19' => null,
            'field_id_20' => '',
            'field_ft_20' => null,
            'field_id_21' => '',
            'field_ft_21' => null,
            'field_id_22' => '',
            'field_ft_22' => null,
        ]);

        $query->execute([
            'entry_id' => 3,
            'site_id' => 1,
            'channel_id' => 2,
            'field_id_1' => '',
            'field_ft_1' => null,
            'field_id_2' => '',
            'field_ft_2' => null,
            'field_id_3' => 0,
            'field_dt_3' => null,
            'field_ft_3' => null,
            'field_id_4' => '',
            'field_ft_4' => null,
            'field_id_5' => '',
            'field_ft_5' => null,
            'field_id_6' => '',
            'field_ft_6' => null,
            'field_id_7' => '',
            'field_ft_7' => null,
            'field_id_8' => '',
            'field_ft_8' => null,
            'field_id_9' => '',
            'field_ft_9' => null,
            'field_id_10' => '',
            'field_ft_10' => null,
            'field_id_11' => '',
            'field_ft_11' => null,
            'field_id_12' => '',
            'field_ft_12' => null,
            'field_id_13' => '',
            'field_ft_13' => null,
            'field_id_14' => '',
            'field_ft_14' => null,
            'field_id_15' => '',
            'field_ft_15' => null,
            'field_id_16' => '',
            'field_ft_16' => null,
            'field_id_17' => null,
            'field_ft_17' => null,
            'field_id_18' => '',
            'field_ft_18' => null,
            'field_id_19' => '',
            'field_ft_19' => null,
            'field_id_20' => '',
            'field_ft_20' => null,
            'field_id_21' => '',
            'field_ft_21' => null,
            'field_id_22' => '',
            'field_ft_22' => null,
        ]);

        $query->execute([
            'entry_id' => 4,
            'site_id' => 1,
            'channel_id' => 2,
            'field_id_1' => '',
            'field_ft_1' => null,
            'field_id_2' => '',
            'field_ft_2' => null,
            'field_id_3' => 0,
            'field_dt_3' => null,
            'field_ft_3' => null,
            'field_id_4' => '',
            'field_ft_4' => null,
            'field_id_5' => '',
            'field_ft_5' => null,
            'field_id_6' => '',
            'field_ft_6' => null,
            'field_id_7' => '',
            'field_ft_7' => null,
            'field_id_8' => '',
            'field_ft_8' => null,
            'field_id_9' => '',
            'field_ft_9' => null,
            'field_id_10' => '',
            'field_ft_10' => null,
            'field_id_11' => '',
            'field_ft_11' => null,
            'field_id_12' => '',
            'field_ft_12' => null,
            'field_id_13' => '',
            'field_ft_13' => null,
            'field_id_14' => '',
            'field_ft_14' => null,
            'field_id_15' => '',
            'field_ft_15' => null,
            'field_id_16' => '',
            'field_ft_16' => null,
            'field_id_17' => null,
            'field_ft_17' => null,
            'field_id_18' => '',
            'field_ft_18' => null,
            'field_id_19' => '',
            'field_ft_19' => null,
            'field_id_20' => '',
            'field_ft_20' => null,
            'field_id_21' => '',
            'field_ft_21' => null,
            'field_id_22' => '',
            'field_ft_22' => null,
        ]);

        $query->execute([
            'entry_id' => 5,
            'site_id' => 1,
            'channel_id' => 2,
            'field_id_1' => '',
            'field_ft_1' => null,
            'field_id_2' => '',
            'field_ft_2' => null,
            'field_id_3' => 0,
            'field_dt_3' => null,
            'field_ft_3' => null,
            'field_id_4' => '',
            'field_ft_4' => null,
            'field_id_5' => '',
            'field_ft_5' => null,
            'field_id_6' => '',
            'field_ft_6' => null,
            'field_id_7' => '',
            'field_ft_7' => null,
            'field_id_8' => '',
            'field_ft_8' => null,
            'field_id_9' => '',
            'field_ft_9' => null,
            'field_id_10' => '',
            'field_ft_10' => null,
            'field_id_11' => '',
            'field_ft_11' => null,
            'field_id_12' => '',
            'field_ft_12' => null,
            'field_id_13' => '',
            'field_ft_13' => null,
            'field_id_14' => '',
            'field_ft_14' => null,
            'field_id_15' => '',
            'field_ft_15' => null,
            'field_id_16' => '',
            'field_ft_16' => null,
            'field_id_17' => null,
            'field_ft_17' => null,
            'field_id_18' => '',
            'field_ft_18' => null,
            'field_id_19' => '',
            'field_ft_19' => null,
            'field_id_20' => '',
            'field_ft_20' => null,
            'field_id_21' => '',
            'field_ft_21' => null,
            'field_id_22' => '',
            'field_ft_22' => null,
        ]);

        $query->execute([
            'entry_id' => 6,
            'site_id' => 1,
            'channel_id' => 2,
            'field_id_1' => '',
            'field_ft_1' => null,
            'field_id_2' => '',
            'field_ft_2' => null,
            'field_id_3' => 0,
            'field_dt_3' => null,
            'field_ft_3' => null,
            'field_id_4' => '',
            'field_ft_4' => null,
            'field_id_5' => '',
            'field_ft_5' => null,
            'field_id_6' => '',
            'field_ft_6' => null,
            'field_id_7' => '',
            'field_ft_7' => null,
            'field_id_8' => '',
            'field_ft_8' => null,
            'field_id_9' => '',
            'field_ft_9' => null,
            'field_id_10' => '',
            'field_ft_10' => null,
            'field_id_11' => '',
            'field_ft_11' => null,
            'field_id_12' => '',
            'field_ft_12' => null,
            'field_id_13' => '',
            'field_ft_13' => null,
            'field_id_14' => '',
            'field_ft_14' => null,
            'field_id_15' => '',
            'field_ft_15' => null,
            'field_id_16' => '',
            'field_ft_16' => null,
            'field_id_17' => null,
            'field_ft_17' => null,
            'field_id_18' => '',
            'field_ft_18' => null,
            'field_id_19' => '',
            'field_ft_19' => null,
            'field_id_20' => '',
            'field_ft_20' => null,
            'field_id_21' => '',
            'field_ft_21' => null,
            'field_id_22' => '',
            'field_ft_22' => null,
        ]);

        $query->execute([
            'entry_id' => 7,
            'site_id' => 1,
            'channel_id' => 1,
            'field_id_1' => '1eecbed0063a0253.jpg
22bb4d5d6211e00f.jpg',
            'field_ft_1' => 'none',
            'field_id_2' => 'A|B',
            'field_ft_2' => 'none',
            'field_id_3' => 1399602000,
            'field_dt_3' => '',
            'field_ft_3' => 'none',
            'field_id_4' => 'A
B',
            'field_ft_4' => 'xhtml',
            'field_id_5' => 'A',
            'field_ft_5' => 'xhtml',
            'field_id_6' => 'A
B',
            'field_ft_6' => 'xhtml',
            'field_id_7' => 'A
B',
            'field_ft_7' => 'xhtml',
            'field_id_8' => 'A',
            'field_ft_8' => 'xhtml',
            'field_id_9' => 'A',
            'field_ft_9' => 'xhtml',
            'field_id_10' => 'y',
            'field_ft_10' => 'none',
            'field_id_11' => '{filedir_1}1eecbed0063a0253.jpg',
            'field_ft_11' => 'none',
            'field_id_12' => ' ',
            'field_ft_12' => 'xhtml',
            'field_id_13' => '1',
            'field_ft_13' => 'none',
            'field_id_14' => '',
            'field_ft_14' => 'none',
            'field_id_15' => '[2] [related-2] Related 2
[4] [related-4] Related 4',
            'field_ft_15' => 'none',
            'field_id_16' => 'A',
            'field_ft_16' => 'none',
            'field_id_17' => '',
            'field_ft_17' => 'xhtml',
            'field_id_18' => 'A',
            'field_ft_18' => 'none',
            'field_id_19' => 'Text',
            'field_ft_19' => 'none',
            'field_id_20' => 'Textarea',
            'field_ft_20' => 'none',
            'field_id_21' => 'Page

​',
            'field_ft_21' => 'xhtml',
            'field_id_22' => '<p><a href="{page_1}">Page</a></p>

<p><img alt="" src="{filedir_1}492f2c6f0795b583.jpg" style="height:173px; width:353px" /></p>

<p><img alt="" src="{assets_6:{filedir_1}fbc59e86a565f8a3.jpg}" style="height:582px; width:481px" /></p>',
            'field_ft_22' => 'none',
        ]);

        $query->execute([
            'entry_id' => 8,
            'site_id' => 1,
            'channel_id' => 1,
            'field_id_1' => 'b87ba69c47a83184.jpg
c07cd109414fa275.jpg
fbc59e86a565f8a3.jpg',
            'field_ft_1' => 'none',
            'field_id_2' => 'C',
            'field_ft_2' => 'none',
            'field_id_3' => 1388633460,
            'field_dt_3' => '',
            'field_ft_3' => 'none',
            'field_id_4' => 'C',
            'field_ft_4' => 'xhtml',
            'field_id_5' => 'C',
            'field_ft_5' => 'xhtml',
            'field_id_6' => 'C',
            'field_ft_6' => 'xhtml',
            'field_id_7' => 'C',
            'field_ft_7' => 'xhtml',
            'field_id_8' => 'C',
            'field_ft_8' => 'xhtml',
            'field_id_9' => 'C',
            'field_ft_9' => 'xhtml',
            'field_id_10' => '',
            'field_ft_10' => 'none',
            'field_id_11' => '{filedir_1}b87ba69c47a83184.jpg',
            'field_ft_11' => 'none',
            'field_id_12' => ' ',
            'field_ft_12' => 'xhtml',
            'field_id_13' => '1',
            'field_ft_13' => 'none',
            'field_id_14' => 'C',
            'field_ft_14' => 'none',
            'field_id_15' => '[4] [related-4] Related 4
[6] [related-6] Related 6',
            'field_ft_15' => 'none',
            'field_id_16' => 'C',
            'field_ft_16' => 'none',
            'field_id_17' => '',
            'field_ft_17' => 'xhtml',
            'field_id_18' => 'C',
            'field_ft_18' => 'none',
            'field_id_19' => 'Text',
            'field_ft_19' => 'none',
            'field_id_20' => 'Textarea',
            'field_ft_20' => 'none',
            'field_id_21' => '​RTE',
            'field_ft_21' => 'xhtml',
            'field_id_22' => '<p>Wygwam</p>',
            'field_ft_22' => 'none',
        ]);

        $query->execute([
            'entry_id' => 9,
            'site_id' => 1,
            'channel_id' => 1,
            'field_id_1' => '',
            'field_ft_1' => 'none',
            'field_id_2' => '',
            'field_ft_2' => 'none',
            'field_id_3' => 0,
            'field_dt_3' => null,
            'field_ft_3' => 'none',
            'field_id_4' => '',
            'field_ft_4' => 'xhtml',
            'field_id_5' => 'A',
            'field_ft_5' => 'xhtml',
            'field_id_6' => '',
            'field_ft_6' => 'xhtml',
            'field_id_7' => '',
            'field_ft_7' => 'xhtml',
            'field_id_8' => 'A',
            'field_ft_8' => 'xhtml',
            'field_id_9' => '',
            'field_ft_9' => 'xhtml',
            'field_id_10' => '',
            'field_ft_10' => 'none',
            'field_id_11' => '',
            'field_ft_11' => 'none',
            'field_id_12' => ' ',
            'field_ft_12' => 'xhtml',
            'field_id_13' => '',
            'field_ft_13' => 'none',
            'field_id_14' => '',
            'field_ft_14' => 'none',
            'field_id_15' => '',
            'field_ft_15' => 'none',
            'field_id_16' => '',
            'field_ft_16' => 'none',
            'field_id_17' => '',
            'field_ft_17' => 'xhtml',
            'field_id_18' => 'A',
            'field_ft_18' => 'none',
            'field_id_19' => '',
            'field_ft_19' => 'none',
            'field_id_20' => '',
            'field_ft_20' => 'none',
            'field_id_21' => '',
            'field_ft_21' => 'xhtml',
            'field_id_22' => '',
            'field_ft_22' => 'none',
        ]);

        $query->execute([
            'entry_id' => 10,
            'site_id' => 1,
            'channel_id' => 1,
            'field_id_1' => '',
            'field_ft_1' => 'none',
            'field_id_2' => '',
            'field_ft_2' => 'none',
            'field_id_3' => 0,
            'field_dt_3' => null,
            'field_ft_3' => 'none',
            'field_id_4' => '',
            'field_ft_4' => 'xhtml',
            'field_id_5' => 'A',
            'field_ft_5' => 'xhtml',
            'field_id_6' => '',
            'field_ft_6' => 'xhtml',
            'field_id_7' => '',
            'field_ft_7' => 'xhtml',
            'field_id_8' => 'A',
            'field_ft_8' => 'xhtml',
            'field_id_9' => '',
            'field_ft_9' => 'xhtml',
            'field_id_10' => '',
            'field_ft_10' => 'none',
            'field_id_11' => '',
            'field_ft_11' => 'none',
            'field_id_12' => ' ',
            'field_ft_12' => 'xhtml',
            'field_id_13' => '',
            'field_ft_13' => 'none',
            'field_id_14' => '',
            'field_ft_14' => 'none',
            'field_id_15' => '',
            'field_ft_15' => 'none',
            'field_id_16' => '',
            'field_ft_16' => 'none',
            'field_id_17' => '',
            'field_ft_17' => 'xhtml',
            'field_id_18' => 'A',
            'field_ft_18' => 'none',
            'field_id_19' => '',
            'field_ft_19' => 'none',
            'field_id_20' => '',
            'field_ft_20' => 'none',
            'field_id_21' => '',
            'field_ft_21' => 'xhtml',
            'field_id_22' => '',
            'field_ft_22' => 'none',
        ]);

        $query->execute([
            'entry_id' => 11,
            'site_id' => 1,
            'channel_id' => 1,
            'field_id_1' => '',
            'field_ft_1' => 'none',
            'field_id_2' => '',
            'field_ft_2' => 'none',
            'field_id_3' => 0,
            'field_dt_3' => null,
            'field_ft_3' => 'none',
            'field_id_4' => '',
            'field_ft_4' => 'xhtml',
            'field_id_5' => 'A',
            'field_ft_5' => 'xhtml',
            'field_id_6' => '',
            'field_ft_6' => 'xhtml',
            'field_id_7' => '',
            'field_ft_7' => 'xhtml',
            'field_id_8' => 'A',
            'field_ft_8' => 'xhtml',
            'field_id_9' => '',
            'field_ft_9' => 'xhtml',
            'field_id_10' => '',
            'field_ft_10' => 'none',
            'field_id_11' => '',
            'field_ft_11' => 'none',
            'field_id_12' => ' ',
            'field_ft_12' => 'xhtml',
            'field_id_13' => '',
            'field_ft_13' => 'none',
            'field_id_14' => '',
            'field_ft_14' => 'none',
            'field_id_15' => '',
            'field_ft_15' => 'none',
            'field_id_16' => '',
            'field_ft_16' => 'none',
            'field_id_17' => '',
            'field_ft_17' => 'xhtml',
            'field_id_18' => 'A',
            'field_ft_18' => 'none',
            'field_id_19' => '',
            'field_ft_19' => 'none',
            'field_id_20' => '',
            'field_ft_20' => 'none',
            'field_id_21' => '',
            'field_ft_21' => 'xhtml',
            'field_id_22' => '',
            'field_ft_22' => 'none',
        ]);


    }

}
