<?php
//文件夹
function listSubfolders($dir, &$results = []) {
  // 确保提供的路径是一个目录
  if (!is_dir($dir)) {
    throw new InvalidArgumentException("$dir is not a directory");
  }

  // 打开目录，并读取其内容
  $files = scandir($dir);
  foreach ($files as $file) {
    // 忽略 "." 和 ".."
    if ($file === '.' || $file === '..') {
      continue;
    }
    $path = $dir . DIRECTORY_SEPARATOR . $file;
    // 检查当前项是否是一个目录
    if (is_dir($path)) {
      // 添加到结果数组中，只添加文件夹名称
      $results[] = $file;
    }
  }
  return $results;
}

if (isset($_GET['path'])) {
  $pathget = isset($_GET['path']) ? htmlspecialchars($_GET['path']) : '';
}else{
  $pathget = '/dav';
}

$rootPath = "/storage/emulated/0/Documents/"; // 根路径
$fullPath = rtrim($rootPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($pathget, DIRECTORY_SEPARATOR);

try {
  // 检查并列出子目录
  $subfolderNames = listSubfolders($fullPath);
  $subfoldercount = count($subfolderNames);
} catch (InvalidArgumentException $e) {
  echo "Error: " . $e->getMessage() . "\n";
}

//文件
// 初始化文件名和扩展名的列表
$fileNames = [];
$extensions = [];
// 使用scandir函数列出文件夹中的所有项
if ($files = scandir($fullPath)) {
  foreach ($files as $file) {
    // 排除目录中的'.'和'..'
    if ($file != "." && $file != "..") {
      $filePath = $fullPath . '/' . $file;
      if (is_file($filePath)) {
      // 使用pathinfo函数获取文件名和扩展名
      $info = pathinfo($file);
      // 检查是否有扩展名（有些文件可能没有扩展名）
      if (isset($info['extension'])) {
        // 将文件名（不含扩展名）和扩展名分别添加到列表中
        $fileNames[] = $info['filename'];
        $extensions[] = $info['extension'];
      } else {
        // 如果没有扩展名，则文件名就是整个文件名，扩展名为空字符串
        $fileNames[] = $file;
        $extensions[] = '';
      }}
    }
  }
  $fileListcount = count($fileNames);
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Explorer</title>
  <link rel="icon" type="images/png" sizes="16*16" href="icon.svg" />
  <link rel="stylesheet" href="style.css" />
  <script src="script.js"></script>
</head>
<body id="body" style>
  <section class="main">
    <div class="row">
      <div class="nav unselectable">
        <a href="/" class="disk" title="disk.prcooker.asia">
          <img class="icon" src="title.svg">
          <h2 class="nav-title">压力锅网盘</h2>
        </a>
        <a href="https://www.prcooker.asia" target="_blank" class="home" title="www.prcooker.asia">
          <img class="home-icon" src="home.svg">
          <h3 class="home-text">压力锅主页</h2>
        </a>
      </div>
    </div>
    <div class="row">
      <div class="list">
        <div class="type">
          <div class="name"><h4 class="type-text">名称</h4></div>
          <div class="dac"><h4 class="type-text">类型</h4></div>
          <div class="dac"><h4 class="type-text">日期</h4></div>
        </div>
        <?php
        $i = 0;
        if($subfolderNames != ''){
        for ($i = 0; $i < $subfoldercount; $i++) {
          echo'
          <a id="/'.$subfolderNames[$i].'" href="" class="list-file unselectable type-folder">
            <div class="name">
              <img class="f-icon" src="folder.svg">
              <h4 class="f-name">'.$subfolderNames[$i].'</h4>
            </div>
            <div class="dac"><h4 class="dac-text">文件夹</h4></div>
            <div class="dac"><h4 class="dac-text">---</h4></div>
          </a>
          ';
        }}
        $i = 0;
        $icon = array('unknown','svg','png','html','htm','php','js','mp4','mp3','jpg','css','jfif','avi','exe','zip','mov','gif','json','rar','pdf','txt','jar','doc','aac','sql','ppt','csv','dll','flv','iso','xls','ttf','xml','docx','woff','sh','pptx','py','mp4','mkv','mhtml','jpeg','flc','cmd','c','bmp','7z');
        if($fileNames != ''){
        for ($i = 0; $i < $fileListcount; $i++) {
          if(in_array($extensions[$i], $icon)){
            $iconnum = array_search($extensions[$i], $icon);
          }else{
            $iconnum = 0;
          }

          if($pathget === ''){
            $file = $fileNames[$i] . '.' . $extensions[$i];
          }else{
            $file = $pathget . '/' . $fileNames[$i] . '.' . $extensions[$i];
          }
          if (file_exists($file)) {
            $date = date("Y-m-d H:i:s.", filemtime($file));
          } else {
            $date = "---";
          }

          echo'
          <a id="'.$fileNames[$i].'" href="" class="list-file unselectable type-files">
            <div class="name">
              <img class="f-icon" src="'.$icon[$iconnum].'.svg">
              <h4 class="f-name">'.$fileNames[$i].'</h4>
            </div>
            <div class="dac"><h4 class="dac-text js-ex">'.$extensions[$i].'</h4></div>
            <div class="dac"><h4 class="dac-text">'.$date.'</h4></div>
          </a>
          ';
        }}
        ?>
      </div>
    </div>
  </section>
</body>
</html>