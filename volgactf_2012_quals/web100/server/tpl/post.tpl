<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>WEB 100</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<body>
<div id="wrapper">
<div id="header"></div>
<div id="main">
    <div id="inner">
		<a href="index.php">Назад</a>
        <div class="date">${time}</div>
        <h1>${title}</h1>
			${text}
        <h2 class="h2_comment">Комментарии</h2>
			${comments}
        <h2>Добавить комментарий</h2>
        <form method="POST">
            <input type="hidden" name="id" value="${id}"/>
            <div class="nameline"><label>Имя:</label><input type="text" name="author"></div>
            <div class="area">
                <label>Сообщение:</label>
                <textarea class="comm_textarea" name="text"></textarea>
            </div>
            <div class="subm">  <input type="submit"/></div>
        </form>
    </div>
</div>
<div id="footer"><p>© Все права защищены и принадлежат пиратам.</p></div>
</div>
</body>
</html>