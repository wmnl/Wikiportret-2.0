<?php
require '../common/bootstrap.php';
$session->checkLogin();
require '../common/header.php';

include 'tabs.php';

$archived = isset($_GET['archived']) && $_GET['archived'] != 0;
$archive = $archived ? $_GET['archived'] : 0;
?>
<div id="content">
    <div class="page-header">

        <?php
        if ($archived == 0) {
            echo "<h2>Inzendingen</h2>";
        }

        if ($archived == 1) {
            echo "<h2>Archief</h2>";
        }

        if (isset($_GET['personal'])) {
            $total_records = DB::queryFirstField(
                'SELECT COUNT(*) FROM images WHERE archived = %d AND owner = \'%s\'',
                $archive,
                $_SESSION['user']
            );
        } else {
            $total_records = DB::queryFirstField('SELECT COUNT(*) FROM images WHERE archived = %d', $archive);
        }

        $total_pages = ceil($total_records / 25);

        if ($total_pages > 1) :
        ?>
            <form class="navigation" method="post">
                <select class="select" name="page" onchange="loadPage()" id="page">
                    <?php
                    for ($i = 1; $i <= $total_pages; $i++) :
                    ?>
                        <option value='<?= $i ?>' <?php if (isset($_GET['page']) && $_GET['page'] == $i) {
                                                        echo 'selected';
                                                    } ?>><?= $i ?></option>
                    <?php
                    endfor;
                    ?>
                </select>
            </form>

        <?php
        endif;
        ?>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="img-container">Foto</th>
                    <th>Titel</th>
                    <th>Uploader</th>
                    <th>Datum</th>
                    <th>Eigenaar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                setlocale(LC_ALL, 'nl_NL');
                date_default_timezone_set('Europe/Amsterdam');
                if (isset($_GET['page'])) {
                    $page   = $_GET['page'];
                } else {
                    $page = 1;
                };
                $start_from = ($page - 1) * 25;
                if (isset($_GET['personal'])) {
                    $results = DB::query(
                        "SELECT images.id as image_id, images.title as title, images.filename as "
                            . "filename, images.name as name, images.timestamp as timestamp, users.otrsname "
                            . "as otrsname FROM images LEFT JOIN users ON users.id = owner WHERE archived = %d AND "
                            . "owner = '%s' ORDER BY images.id DESC LIMIT %d, 25",
                        $archive,
                        $_SESSION['user'],
                        $start_from
                    );
                } else {
                    $results = DB::query("SELECT images.id as image_id, images.title as title, images.filename as "
                        . "filename, images.name as name, images.timestamp as timestamp, "
                        . "users.otrsname as otrsname FROM images LEFT JOIN users ON users.id = owner "
                        . "WHERE archived = %d ORDER BY images.id DESC LIMIT %d, 25", $archive, $start_from);
                }

                foreach ($results as $row) :
                    $id = $row['image_id'];
                    $filename = $row['filename'];
                    $title = $row['title'];
                    $name = $row['name'];
                    $timestamp = $row['timestamp'];
                    if (empty($row['otrsname'])) {
                        $owner = "Aan niemand toegewezen";
                    } else {
                        $owner = $row['otrsname'];
                    }
                ?>
                    <tr>
                        <td data-title="&#xf03e;" class="image"><a href="single.php?id=<?php echo $id ?>"><img src="../uploads/thumbs/<?php echo $filename ?>" /></a></td>
                        <td data-title="&#xf02b;"><a href="single.php?id=<?php echo $id ?>"><?= htmlentities($title); ?></a>
                        </td>
                        <td data-title="&#xf007;"><?= htmlentities($name); ?></td>
                        <td data-title="&#xf073;"><?php echo strftime("%e %B %Y", $timestamp) ?></td>
                        <td data-title="&#xf0f0;"><?= $owner ?></td>
                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++) {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) {
                return sParameterName[1];
            }
        }
    }

    var page = getUrlParameter('page');
    var archived = getUrlParameter('archived');

    if (page == undefined) {
        page = 1;
    }

    if (archived == undefined) {
        archived = 0;
    }

    function loadPage() {
        window.location.href = 'overview.php?page=' + document.getElementById('page').value + '&archived=' + archived;
    }
</script>
<?php
include '../common/footer.php';
?>
