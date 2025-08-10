<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// 新增访问频率限制（60秒内最多60次请求）
session_start();
if (!isset($_SESSION['api_calls'])) {
    $_SESSION['api_calls'] = ['count' => 1, 'time' => time()];
} else {
    if (time() - $_SESSION['api_calls']['time'] > 60) {
        $_SESSION['api_calls'] = ['count' => 1, 'time' => time()];
    } else {
        $_SESSION['api_calls']['count']++;
        if ($_SESSION['api_calls']['count'] > 60) {
            http_response_code(429);
            die(json_encode(['error' => '请求过于频繁']));
        }
    }
}

function listSubfolders($dir, &$results = []) {
    if (!is_dir($dir)) {
        throw new InvalidArgumentException("$dir is not a directory");
    }

    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            $results[] = $file;
        }
    }
    return $results;
}

try {
    // 修改路径获取方式
    $path = isset($_GET['path']) ? rawurldecode($_GET['path']) : '';
    $path = ltrim($path, '/');  // 去除开头的斜杠
    // 修正路径处理逻辑

    // 调整安全验证逻辑
    // 检查非法路径模式：..、./和//
    if (strpos($path, '..') !== false || strpos($path, './') !== false || strpos($path, '//') !== false) {
        throw new Exception('非法路径请求Illegal path requests');
    }

    $rootPath = realpath(__DIR__);// 根目录路径-
    $fullPath = realpath($rootPath . DIRECTORY_SEPARATOR . $path);

    if (!$fullPath || strpos($fullPath, $rootPath) !== 0) {
        throw new Exception('访问路径越界The access path is out of bounds');
    }

    //文件过滤列表
    $filteredFiles = ['style.css', 'api.php', 'index.html'];

    // 获取目录列表（保持不变）
    $folders = listSubfolders($fullPath);
    
    // 获取文件列表（简化过滤逻辑）
    $filesWithTime = [];
    foreach(scandir($fullPath) as $item) {
        $itemPath = $fullPath . '/' . $item;
        if(is_file($itemPath) && !($fullPath === $rootPath && in_array($item, $filteredFiles))) {
            $filesWithTime[] = [
                'name' => $item,
                'mtime' => date('Y-m-d', filemtime($itemPath))
            ];
        }
    }

    echo json_encode([
        'path' => $path ? '/'.$path : '/',
        'folders' => $folders,
        'files' => $filesWithTime,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    // 新增延迟
    usleep(500000);

} catch (Exception $e) {
    // 错误处理添加延迟
    usleep(500000); // 0.5秒延迟
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}