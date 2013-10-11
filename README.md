<p><b>Crawler</b> - PHP Library, part of wi-framework.</p>
<p>Library allow to parse site bodies, find forms from sources, autocomplete html forms and send these forms to server for save.</p>
<p>So, it is spam bot =)</p>
<br />
<p>
	Пример запуска парсера из каталога с библиотекой:
</p>
<pre>
APPLICATION_ENV=local ../../bin/cli.php command-crawl --url=devaka.ru
APPLICATION_ENV=local ../../bin/cli.php command-crawl --url=freetonik.com/blog/all/vagrant/
</pre>
<p>
	Запуск тестов phpunit, находясь в каталоге с либой:
</p>
<pre>
phpunit --bootstrap=../../tests/bootstrap.php phpunit/ParserTestNoQuery.php
phpunit --bootstrap=../../tests/bootstrap.php phpunit     # запуск всех тестов сразу
</pre>

<p>Список сайтов для тестирования засылки форм:</p>
<ul>
<li>http://freetonik.com/blog/all/vagrant/</li>
<li>http://jeka.by/ask/add</li>
</ul>

<p>Текущий принцип работы (для одного url):</p>
<ol>
<li>Crawler_Parser делает http запрос по урлу и получает тело страницы. Парсит тело, находит теги form и содержимое input, textarea внутри формы;
Возвращает массив с описание формы (action, method, fields), где fields - информация о всех input и textarea формы;</li>
<li>Crawler_Generator::createFormData получает на вход массив от Crawler_Parser, генерирует данные для заполнения формы. Возвращает массив готовых для вставки данных.</li>
<li>Crawler_Sender получает массив, создаёт http запрос и методом POST засылает сгенерированные данные серверу.</li>
</ol>

<p>Новый принцип работы:</p>