<?php
// controllers/menu.php
// Dynamic Bootstrap 5.3 menu system (mysqli version)
// Supports menu.table (FK to cms_table.id) and menu.extend ('Yes'/'No').
// `products`.`showonmenu` = 'Yes' triggers hosres to e listed o the menu

function getMenuData($conn, $device = 'desktop') {
    $field = ($device == 'mobile') ? 'ismobile' : 'isdesktop';
    $sql = "SELECT * FROM `menu`
            WHERE `showonweb` = 'Yes' 
            AND `archived` = 0 
            AND `$field` = 'Yes'
            ORDER BY `menu1`, `menu2`, `menu3`, `id`";
    return mysqli_query($conn, $sql);
}

function buildMenuTree($rows) {
    $tree = [];
    while ($row = mysqli_fetch_assoc($rows)) {
        $m1 = (int)$row['menu1'];
        $m2 = (int)$row['menu2'];
        $m3 = (int)$row['menu3'];

        if (!isset($tree[$m1])) $tree[$m1] = ['item'=>null,'children'=>[]];
        if ($m2 > 0 && !isset($tree[$m1]['children'][$m2])) $tree[$m1]['children'][$m2] = ['item'=>null,'children'=>[]];

        if ($m1>0 && $m2==0 && $m3==0)        $tree[$m1]['item'] = $row;                               // Level 1 node
        elseif ($m1>0 && $m2>0 && $m3==0)     $tree[$m1]['children'][$m2]['item'] = $row;              // Level 2 node
        elseif ($m1>0 && $m2>0 && $m3>0)      $tree[$m1]['children'][$m2]['children'][$m3]['item'] = $row; // Level 3 node
    }
    return $tree;
}


function buildLink($conn, $item, $baseURL) {
    // External link override
    if (!empty($item['externalurl'])) return $item['externalurl'];

    // Must have an associated page
    if (empty($item['page'])) return '#';

    // Get the clean page slug
    $pageSlug = getPageSlug($conn, $item['page']);
    $url = rtrim($baseURL, '/') . '/' . $pageSlug;

    // 🔹 CASE 1: If menu.section is set → append /sectionId/sections.slug
    if (!empty($item['section']) && intval($item['section']) > 0) {
        $secId = intval($item['section']);
        $secRow = mysqli_fetch_assoc(mysqli_query(
            $conn,
            "SELECT `slug` FROM `sections` WHERE `id` = " . $secId . " LIMIT 1"
        ));
        $secSlug = $secRow ? $secRow['slug'] : 'section';
        $url .= '/' . $secId . '/' . $secSlug;
    }

    // 🔹 CASE 2: If var1 is set → append ?var1 (after any section path)
    elseif (!empty($item['var1'])) {
        $v = trim($item['var1']);
        // add ? if not already provided
        if ($v[0] != '?') $v = '?' . $v;
        $url .= $v;
    }

    return $url;
}



function getPageSlug($conn, $pageId) {
    $res = mysqli_query($conn, "SELECT `slug` FROM `pages` WHERE `id`=".(int)$pageId);
    $r = mysqli_fetch_assoc($res);
    return $r ? trim($r['slug']) : 'page';
}


/**
 * Fetch dynamic dropdown items for a menu row that points to a table.
 * Always enforces: showonweb='Yes' AND archived=0
 *
 * Returns:
 * [
 *   'label' => '<Table Label for heading if needed>',
 *   'items' => [ ['name'=>'..','href'=>'..'], ... ]
 * ]
 */
function fetchDynamicMenuItems($conn, $item, $baseURL) {
    $out = ['label' => '', 'items' => []];

    if (empty($item['table']) || (int)$item['table'] === 0) return $out;

    // Resolve actual table name
    $tableRow = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT `name` FROM `cms_table` WHERE `id` = " . intval($item['table'])
    ));
    if (!$tableRow) return $out;

    $tableName = $tableRow['name'];
    $out['label'] = $tableName;

    // Base WHERE (always enforced)


    // --- detect special case: forsaleonly ---
    $isForSaleOnly = false;
    if (!empty($item['var1']) && stripos($item['var1'], 'forsaleonly') !== false) {
        $isForSaleOnly = true;
    } elseif (!empty($item['var']) && stripos($item['var'], 'forsaleonly') !== false) {
        $isForSaleOnly = true;
    }

    // --- build WHERE clause ---
    $where = "showonweb='Yes' AND archived=0 AND showonmenu='Yes'";     // horses listed on dropdown

    // ✅ normal case = section is applied
    if (!$isForSaleOnly && !empty($item['section']) && intval($item['section']) > 0) {
        $where .= " AND section = " . intval($item['section']);
    }

    // ✅ if it's our forsaleonly mode, we *ignore* section
    if ($isForSaleOnly) {
        $where .= " AND forsale='Yes'";
    }

    // ✅ otherwise apply any additional var filter
    elseif (!empty($item['var'])) {
        $where .= " AND (" . $item['var'] . ")";
    }


    /*
        // Optional section filter
        if (!empty($item['section']) && intval($item['section']) > 0) {
            $where .= " AND section = " . intval($item['section']);
        }

        // Extra filters from var if provided
        if (!empty($item['var'])) {
            $where .= " AND (" . $item['var'] . ")";
        }
    */



        // --- check if table has a 'forsale' column before using it ---
        $hasForSale = false;
        $check = mysqli_query($conn, "SHOW COLUMNS FROM `{$tableName}` LIKE 'forsale'");
        if ($check && mysqli_num_rows($check) > 0) $hasForSale = true;

    //$sql = "SELECT `id`, `name`, `slug`,`forsale` FROM `{$tableName}` WHERE {$where} ORDER BY `forsale`,`name`";
      // --- build SQL dynamically ---
      $sql = "SELECT `id`, `name`, `slug`";
      
      if ($hasForSale) $sql .= ", `forsale`";

      $sql .= " FROM `{$tableName}` WHERE {$where}";
      if ($hasForSale) {
          $sql .= " ORDER BY (forsale='Yes') DESC, `name` ASC"; // Yes first, then alphabetical
      } else {
          $sql .= " ORDER BY `name` ASC";
      }


    $result = mysqli_query($conn, $sql);
    if (!$result) return $out;

    $pageSlug = getPageSlug($conn, $item['page']); // parent page
    while ($row = mysqli_fetch_assoc($result)) {
        $id   = $row['id'];
        $slug = $row['slug'];
        $label = htmlspecialchars($row['name']);
        $forsale = $hasForSale ? ($row['forsale'] ?? 'No') : 'No';

        // 🔸 Build URL path
    // ✅ Always link as /page-slug/product-id/product-slug
    $url = rtrim($baseURL,'/') . '/' . $pageSlug . '/' . intval($id) . '/' . $slug;

            $out['items'][] = [
                'name' => $label,
                 'href' => $url,
                 'forsale' => $forsale
            ];
        }

        return $out;
}



/* ===========================
   Rendering
   =========================== */
/* ===========================
   Rendering – FINAL version
   =========================== */
function renderMenu($conn, $baseURL, $placement = 'below') {
    $tree = buildMenuTree(getMenuData($conn, 'desktop'));
    ?>
    <div class="main-navbar-wrapper main-navbar--<?= $placement ?>">
      <nav class="navbar navbar-expand-lg main-navbar" data-bs-auto-close="outside">
        <div class="container">

          <!-- Brand (mobile only) -->
          <a class="navbar-brand text-white fw-semibold d-lg-none" href="<?= $baseURL ?>/">
            <i class="fa-solid fa-horse"></i>
            <?= htmlspecialchars($GLOBALS['prefs']['prefSiteName'] ?? 'Site') ?>
          </a>

          <!-- 🔹 Burger toggler -->
          <button class="navbar-toggler d-lg-none" type="button"
                  data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                  aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <!-- 🔹 Collapsible menu -->
          <div class="collapse navbar-collapse justify-content-center" id="mainNavbar">
            <ul class="navbar-nav">
              <?php foreach ($tree as $node1) renderLevel1($conn, $baseURL, $node1); ?>
            </ul>
          </div>

        </div>
      </nav>
    </div>
    <?php
}

/* ---------- LEVEL 1 ---------- */
function renderLevel1($conn, $baseURL, $node1) {
    $item = $node1['item'];
    if (!$item) return;

    // Search box item
    if (isset($item['issearch']) && strcasecmp($item['issearch'], 'Yes') === 0) {
        echo '<li class="nav-item menu-search-item">';
        renderMenuSearch($conn, $baseURL, $item);
        echo '</li>';
        return;
    }

    $children     = $node1['children'];
    $name         = htmlspecialchars($item['name'] ?? '');
    $extendYes    = (isset($item['extend']) && strcasecmp($item['extend'], 'Yes') === 0);
    $dynPayload   = fetchDynamicMenuItems($conn, $item, $baseURL);
    $hasDyn       = !empty($dynPayload['items']);
    $hasChildren  = !empty($children) || $hasDyn;

    $skipSelf = ($hasDyn && !$extendYes && empty($children)
        && (empty($item['page']) && empty($item['externalurl'])));

    if ($skipSelf) {
        foreach ($dynPayload['items'] as $d)
            echo "<li><a class='dropdown-item' href='{$d['href']}'>{$d['name']}</a></li>";
        return;
    }

    $href = ($hasChildren && empty($item['page']) && empty($item['externalurl']))
        ? '#'
        : buildLink($conn, $item, $baseURL);
    ?>
    <li class="nav-item <?= $hasChildren ? 'dropdown' : '' ?>">
      <a class="nav-link <?= $hasChildren ? 'dropdown-toggle' : '' ?>"
         href="<?= $href ?>"
         <?php if ($hasChildren): ?>
            data-bs-toggle="dropdown"
            data-bs-auto-close="outside"
            data-bs-display="static"
         <?php endif; ?>>
         <?= $name ?>
      </a>

      <?php if ($hasChildren): ?>
        <ul class="dropdown-menu">
          <?php
          foreach ($children as $node2) renderLevel2($conn, $baseURL, $node2);
          if ($hasDyn) {
              if ($extendYes) {
                  $heading = htmlspecialchars($dynPayload['label']);
                  echo "<li class='dropdown-submenu'>";
                  echo   "<a class='dropdown-item dropdown-toggle' href='#'>{$heading}</a>";
                  echo   "<ul class='dropdown-menu'>";
                  foreach ($dynPayload['items'] as $d)
                      echo "<li><a class='dropdown-item' href='{$d['href']}'>{$d['name']}</a></li>";
                  echo   "</ul></li>";
              } else {
                  foreach ($dynPayload['items'] as $d)
                      echo "<li><a class='dropdown-item' href='{$d['href']}'>{$d['name']}</a></li>";
              }
          }
          ?>
        </ul>
      <?php endif; ?>
    </li>
    <?php
}


/* ---------- LEVEL 2 ---------- */
function renderLevel2($conn,$baseURL,$node2){
   $item      = $node2['item'];

   if (!$item) return;

   // 🔍 Search item in dropdown
   if (isset($item['issearch']) && strcasecmp($item['issearch'], 'Yes') === 0) {
       echo '<li class="dropdown-item p-2">';
       renderMenuSearch($conn, $baseURL, $item);
       echo '</li>';
       return;
   }

   $children  = $node2['children'];

   if (!empty($item['isdivider']) && $item['isdivider'] === 'Yes') {
       echo '<li><hr class="dropdown-divider"></li>';
       return;
   }

   $name       = htmlspecialchars($item['name'] ?? '');



   $extendYes  = (isset($item['extend']) && strcasecmp($item['extend'],'Yes') === 0);
   $dynPayload = fetchDynamicMenuItems($conn, $item, $baseURL);
   $hasDyn     = !empty($dynPayload['items']);

   // ✅ If extend=No and there are dynamic items, we skip rendering this parent line completely
    if ($hasDyn && !$extendYes) {
        foreach ($dynPayload['items'] as $d) {
            $forsale = '';
            if (!empty($d['forsale']) && $d['forsale'] === 'Yes') {
                $forsale = " <i class='fa-solid fa-gavel text-warning'></i>";
            }
            echo "<li><a class='dropdown-item' href='{$d['href']}'>{$d['name']}{$forsale}</a></li>";
        }
        return;
    }

   // If we reach here, either extend=Yes or no dynamic content
   $hasSubmenu = !empty($children) || ($hasDyn && $extendYes);
   $href = ($hasSubmenu && empty($item['page']) && empty($item['externalurl']))
       ? '#'
       : buildLink($conn,$item,$baseURL);
   ?>
   <li class="<?= $hasSubmenu ? 'dropdown-submenu' : '' ?>">
     <a class="dropdown-item <?= $hasSubmenu ? 'dropdown-toggle' : '' ?>" href="<?= $href ?>">
       <?= $name ?> 
     </a>

     <?php if ($hasSubmenu): ?>
       <ul class="dropdown-menu">
         <?php
         // static level-3 items
         foreach ($children as $node3) renderLevel3($conn,$baseURL,$node3);

         // dynamic list only when extend=Yes
         if ($hasDyn && $extendYes) {
             foreach ($dynPayload['items'] as $d) {
                 echo "<li><a class='dropdown-item' href='{$d['href']}'>{$d['name']}</a></li>";
             }
         }
         ?>
       </ul>
     <?php endif; ?>
   </li>
   <?php
}





/* ---------- LEVEL 3 ---------- */
function renderLevel3($conn,$baseURL,$node3){
    $item = $node3['item']; if (!$item) return;

    if (!empty($item['isdivider']) && $item['isdivider'] === 'Yes') {
        echo '<li><hr class="dropdown-divider"></li>';
        return;
    }

    $name = htmlspecialchars($item['name']);
    $href = buildLink($conn,$item,$baseURL);
    echo "<li><a class='dropdown-item' href='$href'>$name</a></li>";
}


/**
 * Render a Bootstrap 5 search box inside the menu.
 * $item['page'] determines the target page for the form action.
 */
function renderMenuSearch($conn, $baseURL, $item) {
    $action = buildLink($conn, $item, $baseURL);
    $placeholder = !empty($item['name']) ? htmlspecialchars($item['name']) : 'Search...';
    ?>
    <form class="d-flex menu-search-form" role="search" method="get" action="<?= $action ?>">
        <input class="form-control form-control-sm me-2" 
               type="search" 
               name="q" 
               placeholder="<?= $placeholder ?>" 
               aria-label="Search">
        <button class="btn btn-outline-secondary btn-sm" type="submit">
            <i class="fa fa-search"></i>
        </button>
    </form>
    <?php
}




?>
