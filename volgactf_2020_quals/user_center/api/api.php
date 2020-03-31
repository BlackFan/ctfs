<?php
  require '/var/www/vendor/autoload.php';
  use Aws\S3\S3Client;
  use Aws\S3\Exception\S3Exception;

  session_start();

  $mysqli = new mysqli("127.0.0.1", "task", "", "task");
  function createUser($username, $password) {
    global $mysqli;
    $stmt = $mysqli->prepare("INSERT INTO user (`username`, `password`, `guid`) VALUES (?, ?, ?)");
    $password = password_hash($password, PASSWORD_BCRYPT);
    $guid = bin2hex(random_bytes(16));
    $stmt->bind_param('sss', $username, $password, $guid);
    if ($stmt->execute()) {
      $stmt->close();
      $_SESSION['guid'] = $guid;
      return $guid;
    } else {
      $stmt->close();
      return FALSE;
    }
  }

  function userExists($username) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT id FROM user WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return ($row === NULL) ? FALSE : TRUE;
  }

  function login($username, $password) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT guid, password FROM user WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    if($row == NULL) 
      return FALSE;
    if(!password_verify($password, $row['password'])) {
      return FALSE;
    }
    $_SESSION['guid'] = $row['guid'];
    return $row['guid'];
  }

  function getUser($guid) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT username, bio, avatar FROM user WHERE guid = ?");
    $stmt->bind_param('s', $guid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    if($row == NULL) 
      return FALSE;
    return $row;
  }

  function getCurrentGuid() {
    if(!isset($_SESSION['guid']) or !is_string($_SESSION['guid'])) {
      return FALSE;
    } else {
      return $_SESSION['guid'];
    }
  }

  function updateBio($guid, $bio) {
    global $mysqli;
    $stmt = $mysqli->prepare("UPDATE user SET bio = ? WHERE guid = ?");
    $stmt->bind_param('ss', $bio, $guid);
    $stmt->execute();
    $stmt->close();
    return TRUE;
  }

  function updateAvatar($guid, $file, $type) {
    global $mysqli;
    $s3 = new S3Client([
      'version' => 'latest',
      'region'  => 'us-west-1',
      'credentials' => [
        'key'    => '',
        'secret' => '',
      ],
    ]);

    $check = explode(";", $type);
    $check = $check[0];
    if(stripos($check, '/') === FALSE) {
      return 'Incorrect MIME type';
    }

    if(
      (stripos($check, 'xml')  !== FALSE) or 
      (stripos($check, 'xsl')  !== FALSE) or 
      (stripos($check, 'html') !== FALSE)
    ) {
      return 'Forbidden MIME type';
    }

    try {
      $key = bin2hex(random_bytes(16));
      $result = $s3->putObject([
        'Bucket'      => 'bucket',
        'Key'         => $key,
        'Body'        => base64_decode($file),
        'ACL'         => 'public-read',
        'ContentType' => $type
      ]);
    } catch (S3Exception $e) {
      return 'Upload error';
    }

    $stmt = $mysqli->prepare("UPDATE user SET avatar = ? WHERE guid = ?");
    $stmt->bind_param('ss', $key, $guid);
    $stmt->execute();
    $stmt->close();
    return TRUE;
  }

  function bodyParam($name) {
    if($_SERVER["CONTENT_TYPE"] === 'application/json') {
      $post = json_decode(file_get_contents('php://input'), true);
      if($post !== NULL) {
        if(isset($post[$name]) and is_string($post[$name])) {
          return $post[$name];
        }
      } else {
        die(error('JSON decode error'));
      }
    }
    return '';
  }

  function getParam($name) {
    if(isset($_GET[$name]) and is_string($_GET[$name])) {
      return $_GET[$name];
    }
    return '';
  }

  function error($s) {
    print(json_encode(['success' => FALSE, 'error' => $s]));
  }

  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Allow-Origin: https://volgactf-task.ru');

  if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Headers: content-type');
    die();
  }

  header('Content-Type: application/json');
  switch ($_SERVER['PATH_INFO']) {
    case '/user':
      $guid = (!empty(getParam('guid'))) ? getParam('guid') : getCurrentGuid();
      if($guid === FALSE) {
        error('User is not authorized');
      } else {
        $user = getUser($guid);
        if($user !== FALSE) {
          print(json_encode(['success' => TRUE, 'user' => $user]));
        } else {
          error('User is not found');
        }
      }
      break;
    case '/login':
      if(!empty(bodyParam('username')) and !empty(bodyParam('password'))) {
        $guid = login(bodyParam('username'), bodyParam('password'));
        if($guid !== FALSE) {
          print(json_encode(['success' => TRUE, 'guid' => $guid]));
        } else {
          error('Incorrect username or password');
        }
      } else {
        error('Required parameters not specified');
      }
      break;
    case '/register':
      if(!empty(bodyParam('username')) and !empty(bodyParam('password'))) {
        if(strlen(bodyParam('password')) < 8) {
          error('Password must be at least 8 characters long');
        } else {
          if(!userExists(bodyParam('username'))) {
            $guid = createUser(bodyParam('username'), bodyParam('password'));
            if($guid === FALSE) {
              error('Unknown error');
            } else {
              print(json_encode(['success' => TRUE, 'guid' => $guid]));
            }
          } else {
            error('User already exists');
          }
        }
      } else {
        error('Required parameters not specified');
      }
      break;
    case '/user-update':
      if(getCurrentGuid() === FALSE) {
        error('User is not authorized');
      } else {
        $return = TRUE;
        if(!empty(bodyParam('bio'))) {
          $return = updateBio(getCurrentGuid(), bodyParam('bio'));
        }
        if(!empty(bodyParam('avatar')) and !empty(bodyParam('type'))) {
          $return = updateAvatar(getCurrentGuid(), bodyParam('avatar'), bodyParam('type'));
        }
        if($return === TRUE) {
          print(json_encode(['success' => TRUE]));
        } else {
          error($return);
        }        
      }
      break;
    case '/logout':
      unset($_SESSION['guid']);
      print(json_encode(['success' => TRUE]));
      break;
    default:
      error('Unknown action');
      break;
  }
?>