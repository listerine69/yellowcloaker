<?php
global $startdate, $enddate, $config, $stats_timezone;
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/initialization.php';
require_once __DIR__ . '/tablecolumns.php';
require_once __DIR__ . '/../settings.php';

$db = new Db();
?>
<!doctype html>
<html lang="en">
<?php include "head.php" ?>

<body>
    <?php include "menu.php" ?>
    <div class="all-content-wrapper">
        <?php include "header.php" ?>
        <?php
$sTables = json_decode(file_get_contents(__DIR__ . '/settings.json'), true);
foreach ($sTables['tables'] as $tSettings) {
    $dataset = $db->getStatisticsData($tSettings['columns'], $tSettings['groupby'], $config);
    $tName = $tSettings['name'];
    ?>
        <div id="t<?=$tName?>"></div>

        <script>
            let t<?=$tName?>Data = <?= json_encode($dataset) ?>;
            let t<?=$tName?>Columns = <?= get_stats_columns($tSettings['columns'], $tSettings['groupby'], $stats_timezone) ?>;
            let t<?=$tName?>GroupBy = ["<?= implode('","', array_slice($tSettings['groupby'], 0, -1)) ?>"];
            let t<?=$tName?>Table = new Tabulator('#t<?= $tSettings['name'] ?>', {
                layout: "fitColumns",
                columns: t<?=$tName?>Columns,
                columnCalcs: "both",
                pagination: "local",
                paginationSize: 500,
                paginationSizeSelector: [25, 50, 100, 200, 500, 1000, 2000, 5000],
                paginationCounter: "rows",
                height: "100%",
                data: t<?=$tName?>Data,
                groupBy: t<?=$tName?>GroupBy,
                groupStartOpen: false
            });
        </script>
        <?php } ?>

    </div>
</body>

</html>
