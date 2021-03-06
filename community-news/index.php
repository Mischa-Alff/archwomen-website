<?php
  $title = "Community News";
  include $_SERVER['DOCUMENT_ROOT']."/inc/header.php";
  include_once $_SERVER['DOCUMENT_ROOT'] . '/inc/autoloader.php';

  $urls = file('urls.txt');
  $feed = new SimplePie();
  $feed->set_feed_url($urls);
  $feed->set_cache_location($_SERVER['DOCUMENT_ROOT'] . '/community-news/cache');
  $feed->set_cache_duration(14400);
  $feed->enable_cache();
  $feed->init();

  $max = $feed->get_item_quantity();
  $entries = 6; //entries per page
  $numPages = ($max / $entries);
  $page = (isset($_GET['page']) && !empty($_GET['page'])) ? $_GET['page'] : 1;
  if (!$page || $page > $numPages) $page = 0;
  $start = ($page - 1) * $entries;
?>
    <div id="content">
      <h2 role="heading">Community News</h2>
      <?php foreach ($feed->get_items($start, $entries) as $item): ?>
        <article class="feed">
        <?php
          if ($item->get_permalink()) echo '<a href="' . $item->get_permalink() . '">';
          echo '<h3>' . $item->get_title(true) . '</h3>';
          if ($item->get_permalink()) echo '</a>';
        ?>
          <h4>
          <?php
            if ($author = $item->get_author()) echo $author->get_name() . ', ';
            if ($item->get_date('F j, Y')) echo $item->get_date('F j, Y');
          ?>
          </h4>
          <p><?php print $item->get_content(false); ?></p>
        </article>
      <?php endforeach; ?>
      <?php
        $next = (int) $page + 1;
        $prev = (int) $page - 1;
        $nextlink = '<a href="?page=' . $next . '"> ' . $next . ' </a> <a href="?page=' . $next . '">Next &raquo;</a>';
        if ($next > $max) {
          $nextlink = ' Next &raquo; ';
        }
        $prevlink = '<a href="?page=' . $prev . '"> &laquo; Previous </a> <a href="?page=' . $prev . '">' . $prev . ' </a>';
        if ($prev <= 1 && (int) $page > 1) {
          $prevlink = '<a href="?page=1">&laquo; Previous </a><a href="?page=1">1 </a>';
        }
        else if ($prev <= 0) {
          $prevlink = '&laquo; Previous ';
        }
        echo '<p class="center">' . $prevlink . '<span id="page">' . $page . '</span>' . $nextlink . '</p>';
      ?>
    </div>
<?php include $_SERVER['DOCUMENT_ROOT']."/inc/footer.php"; ?>
