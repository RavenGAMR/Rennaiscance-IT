from pathlib import Path
import re

files = {
    'Index.php': '.',
    'login-DB/login.php': '..',
    'login-DB/register.php': '..',
    'pages/Admin.php': '..',
    'pages/Contact.php': '..',
    'pages/Privacypolicy.php': '..',
    'pages/Verwerkingsovereenkomst.php': '..',
    'pages/diensten.php': '..',
    'pages/Helpdesk.php': '..',
    'pages/Weblog.php': '..',
    'pages/sub-pages/article.php': '../..',
    'pages/sub-pages/Weblogartikel.php': '../..',
    'pages/sub-pages/Helpdeskartikel.php': '../..',
    'pages/sub-pages/purchase.php': '../..',
}

app_url_pattern = re.compile(r"app_url\(([^)]+)\)")
asset_pattern = re.compile(r"asset_url\(\s*([^\)]+?)\s*\)")

for rel_path, base in files.items():
    path = Path(rel_path)
    text = path.read_text(encoding='utf-8')
    orig = text
    if '$basePath' not in text:
        if text.startswith('<?php'):
            lines = text.splitlines(True)
            if lines[0].strip() == '<?php':
                lines.insert(1, f"$basePath = '{base}';\n")
            else:
                lines.insert(0, f"<?php $basePath = '{base}'; ?>\n")
            text = ''.join(lines)
        else:
            text = f"<?php $basePath = '{base}'; ?>\n" + text
    def replace_app_url(match):
        arg = match.group(1).strip()
        m = re.match(r"^'([^']*)'\s*(.*)$", arg, re.S)
        if m:
            path_part = m.group(1)
            rest = m.group(2)
            if rest.strip() == '':
                return f"$basePath . '/{path_part}'"
            return f"$basePath . '/{path_part}'{rest}"
        return f"$basePath . '/' . ({arg})"
    text = app_url_pattern.sub(replace_app_url, text)
    def replace_asset(match):
        arg = match.group(1).strip()
        return f"(({arg}) && preg_match('#^(https?:)?//#i', {arg}) ? {arg} : $basePath . '/' . ltrim({arg}, '/'))"
    text = asset_pattern.sub(replace_asset, text)
    text = text.replace("header('Location: /Index.php'", "header('Location: ../Index.php'")
    text = text.replace('header("Location: /Index.php"', 'header("Location: ../Index.php"')
    text = text.replace("header('Location: /pages/Weblog.php'", "header('Location: ../Weblog.php'")
    text = text.replace("header('Location: /pages/Helpdesk.php'", "header('Location: ../Helpdesk.php'")
    text = text.replace("header('Location: /pages/diensten.php'", "header('Location: ../diensten.php'")
    text = text.replace("header('Location: /pages/sub-pages/purchase.php'", "header('Location: purchase.php'" )
    text = text.replace("header('Location: /pages/Admin.php'", "header('Location: Admin.php'" )
    if text != orig:
        path.write_text(text, encoding='utf-8')
        print('patched', rel_path)

for rel_path in ['components/navbar.php','components/footer.php']:
    path = Path(rel_path)
    text = path.read_text(encoding='utf-8')
    orig = text
    if '$bp' not in text:
        text = text.replace('<?php', "<?php\n$bp = isset($basePath) ? rtrim($basePath, '/') : '.';\n", 1)
    text = re.sub(r"app_url\('([^']*)'\)", r"$bp . '/\1'", text)
    if text != orig:
        path.write_text(text, encoding='utf-8')
        print('patched', rel_path)

path = Path('login-DB/auth.php')
text = path.read_text(encoding='utf-8')
orig = text
text = re.sub(r"function getAppBaseUrl\(\)[\s\S]*?function registerUser\(", "function registerUser(", text, count=1)
text = re.sub(r"function requireLogin\(\)[\s\S]*?^}\n","function requireLogin(){\n    if(!currentUser()){\n        $ret = $_SERVER['REQUEST_URI'] ?? '/';\n        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '/');\n        $segments = array_filter(explode('/', trim($scriptDir, '/')));\n        $levels = max(0, count($segments) - 1);\n        $redirectBase = str_repeat('../', $levels);\n        header('Location: ' . $redirectBase . 'login-DB/login.php?return=' . urlencode($ret));\n        exit;\n    }\n}\n", text, count=1, flags=re.M)
text = re.sub(r"function requireAdmin\(\)[\s\S]*?^}\n","function requireAdmin(){\n    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '/');\n    $segments = array_filter(explode('/', trim($scriptDir, '/')));\n    $levels = max(0, count($segments) - 1);\n    $redirectBase = str_repeat('../', $levels);\n    $u = currentUser();\n    if(!$u || $u['role'] !== 'admin'){\n        header('Location: ' . $redirectBase . 'Index.php');\n        exit;\n    }\n}\n", text, count=1, flags=re.M)
if text != orig:
    path.write_text(text, encoding='utf-8')
    print('patched auth.php')
