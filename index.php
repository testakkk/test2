<?
error_reporting(0);

$version = '1.1';

$login = $_GET['l'];
$password = $_GET['p'];

$auth = curl('http://tiwar.ru/', 0, "login=$login&pass=$password");

preg_match_all("/Set-Cookie: (.*?);/", $auth, $cookie);
preg_match_all("/<div class='exp_bar'>(.*?)<\/div><\/div>/", $auth, $progress);
preg_match("/<div class='center'>(.*?)<\/div>/", $auth, $foot);
?>
<html>
<head>
  <title>Hello world!</title>
  <meta name='viewport' content='width=device-width; minimum-scale=1; maximum-scale=1'/>
  <link rel='stylesheet' type='text/css' href='main.css?2'/>
</head>
<body>
<?php
if ($cookie[1][1]) {
  if (isset($_GET['act'])) {
    if ($_GET['act']=='farm') {
      $farm = curl('http://tiwar.ru/farm/', $cookie[1][0], 0);

      $farm = curl('http://tiwar.ru/farm/', $cookie[1][0], 0);
      preg_match("/action='\/farm\?r=([0-9]+)' method='post'>/", $farm, $go);
      $farm = curl('http://tiwar.ru/farm?r='.$go[1], $cookie[1][0], 'time=10');

      exit();
    }

    if ($_GET['act']=='inv') {
      $inv = curl('http://tiwar.ru/inv/bag/sellAll/1/', $cookie[1][0], 0);

      exit();
    }

    if ($_GET['act']=='undying') {
      $undying = curl('http://tiwar.ru/undying/enterGame/', $cookie[1][0], 0);

      exit();
    }

    if ($_GET['act']=='gold') {
      $gold = curl('http://tiwar.ru/trade/', $cookie[1][0], 0);
      preg_match("/<a href='\/trade\/exchange\?r=([0-9]+)'>/", $gold, $go);

      $gold = curl('http://tiwar.ru/trade/exchange?r='.$go[1], $cookie[1][0], 0);
      preg_match("/trade\/exchange\/silver\/(.*?)'>/", $gold, $go);

      $gold = curl('http://tiwar.ru/trade/exchange/silver/'.$go[1], $cookie[1][0], 0);

      echo $go[1];

      exit();
    }

    if ($_GET['act']=='quest') {
      $quest = curl('http://tiwar.ru/quest/', $cookie[1][0], 0);

      if (preg_match("/Завершить задание/i", $quest)) {
        preg_match("/<div class='center'><a class='btn' href='(.*?)'><span class='end'>/", $quest, $go);
        $end = curl('http://tiwar.ru'.$go[1], $cookie[1][0], 0);
      }

      exit();
    }

    if ($_GET['act']=='relic') {
      $relic = curl('http://tiwar.ru/relic/', $cookie[1][0], 0);

      if (preg_match("/Получить награду/i", $relic)) {
        preg_match("/<a class='btn' href='(.*?)'><span class='end'>/", $relic, $go);
        $end = curl('http://tiwar.ru'.$go[1], $cookie[1][0], 0);
      }

      exit();
    }

    if ($_GET['act']=='hellworld') {
      $user_cookie = $cookie[1][0];

      function go($lvl, $user_cookie) { 
        $hellworld = curl('http://tiwar.ru/hellworld/enter/'.$lvl.'/', $user_cookie, 0);

        for ($i=0; $i < 6; $i++) { 
          $att = curl('http://tiwar.ru/hellworld', $user_cookie, 0);
          preg_match("/attack\/([0-9]+)\//", $att, $go);
          $att = curl('http://tiwar.ru/hellworld/attack/'.$go[1].'/', $user_cookie, 0);
        }
      }

      $status = go(1, $user_cookie);
      $status = go(2, $user_cookie);
      $status = go(3, $user_cookie);

      exit();
    }

    if ($_GET['act']=='life') {
      $start = curl('http://tiwar.ru/coliseum/enterFight/', $cookie[1][0], 0);
      echo 'Вошли в колизей';
      while (1) {
        echo '.';
        $quest = curl('http://tiwar.ru/coliseum/', $cookie[1][0], 0);
        if (preg_match("/Вы были убиты во время сражения/i", $quest)) {
          echo '<br>Вы были убиты во время сражения, ожидайте окончания боя';
          exit;
        }
      	if (preg_match("/Настойка/i", $quest)) {
          echo '<br>Настойка';
          break;
        }
        sleep(1);
      }

      preg_match("/healmepls\/([0-9]+)\//", $quest, $go);
      echo '<br>Выпили настойку ('.$go[1].')';
      $end = curl('http://tiwar.ru/coliseum/healmepls/'.$go[1].'/', $cookie[1][0], 0);
      $end = curl('http://tiwar.ru/'.$go[1].'/', $cookie[1][0], 0);
      echo '<br>Перешли на ломаную страницу';
      $end = curl('http://tiwar.ru/', $cookie[1][0], 0);
      echo '<br>Перешли на главную';
      exit();
    }

    if ($_GET['act']=='takeReward') {
      $quest = curl('http://tiwar.ru/league/takeReward/', $cookie[1][0], 0);

      exit();
    }
  }

  echo '<div id="auth">Авторизация успешна!<font style="float: right;">'.$version.'</font></div>'."\n";
  echo str_replace("/images/", "http://tiwar.ru/images/", $foot[0]).$progress[0][0]."\n";
  echo '<div id="progress">'."\n";

  if (isset($_GET['dolina'])) {
    $code = curl('http://tiwar.ru/undying/', $cookie[1][0], 0);
    preg_match("/undying\/mana\/([0-9]+)\//", $code, $url);
    $code = curl('http://tiwar.ru/undying/mana/'.$url[1].'/', $cookie[1][0], 0);
  }

  while (1) {
    $code = curl('http://tiwar.ru/arena/', $cookie[1][0], 0);
    
    if ($_GET['att']==3) preg_match_all("/arena\/attack\/3\/([0-9]+)\//", $code, $url);
      elseif ($_GET['att']==2) preg_match_all("/arena\/attack\/2\/([0-9]+)\//", $code, $url);
      else preg_match_all("/arena\/attack\/1\/([0-9]+)\//", $code, $url);

    $arena = curl('http://tiwar.ru/'.$url[0][0], $cookie[1][0], 0);

    preg_match("/<img src='\/images\/icon\/2hit.png' alt=''\/> (.*?) <img src='\/images\/icon\/2hit.png' alt=''\/>/", $arena, $info);
  
    preg_match("/'hp'\/> ([0-9]+) \|/", $arena, $hp);
    preg_match("/'mp'\/> ([0-9]+)</", $arena, $mp);
  
    if(preg_match("/Для нападения нужно/i", $arena)) {
      $dred = ($mp[1]<50) ? '<font color="DarkOrchid">маны</font>.<div id="mp">'.$mp[1].'</div>' : '<font color="red">жизни</font>.<div id="hp">'.$hp[1].'</div>';
      echo '<div id="error">Слишком мало '.$dred."\n</div>";
      break;
    }
  
    $udar = '<div id="response">';
    $udar .= str_replace("/images/", "http://tiwar.ru/images/", $info[0])."\n";
  
    $udar .= '<div id="mp">'.$mp[1].'</div>';
    $udar .= '<div id="hp">'.$hp[1].'</div>';
    
    $udar .= '</div>'."\n";
  
    echo $udar;
  }

  preg_match_all("/<div class='exp_bar'>(.*?)<\/div><\/div>/", $arena, $progress_end);
  preg_match("/<div class='center'><img src='\/images\/icon\/level.png' alt='lvl'\/>(.*?)<\/div>/", $arena, $foot_end);
  
  echo '</div>'."\n";

  echo '<div id="foot">'."\n";
  echo $progress_end[0][0].str_replace("/images/", "http://tiwar.ru/images/", $foot_end[0])."\n";
  echo '</div>';
} else echo '<div id="progress"><div id="error">Что-то не так, можно паниковать.</div></div>';

function curl($link, $cookie=null, $post=null){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $link);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 0);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
  if($cookie)
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
  if($post) {
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  }
  $otvet = curl_exec($ch);
  curl_close($ch);
  return $otvet;
}
?>
</body>
</html>